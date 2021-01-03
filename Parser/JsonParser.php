<?php

namespace Routing\Parser;

use Routing\{
    Route,
    Collection\RouteCollection
};

/**
 * Class responsible for loading routes from .json files
 *
 * @author <clvarley>
 */
Class JsonParser Implements ParserInterface
{

    /**
     * Allowed HTTP methods
     *
     * @var string[] HTTP_METHODS HTTP verbs
     */
    const HTTP_METHODS = [
        'GET',
        'POST',
        'PUT',
        'DELETE',
        'UPDATE'
    ];

    /**
     * The regex used to strip out URL args
     *
     * @var string ROUTE_REGEX Regular expression
     */
    private const ROUTE_REGEX = '~\[(?:(?P<type>i|s):)?(?P<name>\w+)\]|(?:[^\[]+)~ix';

    /**
     * Parse the given json routes file
     *
     * @param string $filename Path to file
     * @return RouteCollection Route collection
     */
    public function parse( string $filename ) : RouteCollection
    {
        $routes = new RouteCollection();

        $json = $this->loadJson( $filename );

        // Nothing to do
        if ( empty( $json ) ) {
            return $routes;
        }

        foreach ( $json as $name => $contents ) {
            if ( empty( $contents['path'] ) || empty( $contents['handler'] ) ) {
                continue;
            }

            $routes->add( $name, $this->convert( $contents ) );
        }

        return $routes;
    }

    /**
     * Attempts to load the given JSON file
     *
     * @param string $filename JSON file
     * @return array           JSON data
     */
    private function loadJson( string $filename ) : array
    {
        if ( !\file_exists( $filename ) ) {
            return [];
        }

        $raw = \file_get_contents( $filename );

        // Nothing here, exit out
        if ( empty( $raw ) ) {
            return [];
        }

        $json = \json_decode( $raw, true, 3 );

        return \json_last_error() === \JSON_ERROR_NONE ? $json : [];
    }

    /**
     * Converts the JSON array into a Route object
     *
     * @param  array $route Route JSON
     * @return Route        Route definition
     */
    private function convert( array $json ) : Route
    {
        $route = new Route;

        // Parse regex
        // TODO: Parse regular expression

        // Allowed HTTP verbs only
        if ( !empty( $json['methods'] ) && \is_array( $json['methods'] ) ) {
            $route->methods = \array_intersect( $json['methods'], self::HTTP_METHODS );
        } else {
            $route->methods = [ 'GET' ];
        }

        // Controller/handler function
        $route->callable = \explode( '::', $json['handler'] );

        return $route;
    }

    /**
     * Parse the given route and build the neccessary regex
     *
     * The args parameter will be filled with the details of any arguments
     * this route takes
     *
     * @param  string $route  Route path
     * @param  array  $args   Route arguments
     * @return string         Regular expression
     */
    private function parseRoute( string $route, &$args = [] ) : string
    {
        $route = \rtrim( $route, " \n\r\t\0\x0B\\/" );

        // No route, assume root
        if ( empty( $route ) ) {
            return '/';
        }

        // Route placeholders?
        if ( !\preg_match_all( self::ROUTE_REGEX, $route, $matches, \PREG_SET_ORDER | \PREG_OFFSET_CAPTURE ) ) {
            return $route;
        }

        $route = '';

        /**
         * Reference:
         *
         * $match[0]      - The entire match, ie: '[i:name]'
         * $match['name'] - The name of the placeholder
         * $match['type'] - The type of the placeholder
         */
        foreach ( $matches as $match ) {

            if ( empty( $match['name'] ) ) {
                $route .= \preg_quote( $match[0][0] );
                continue;
            }

            // Atomic groups were messing with names :(
            $regex = '(';

            // Use appropriate regex
            switch ( $match['type'][0] ) {
                case 'i':
                    $regex .= '\d+';
                break;

                case 's':
                default:
                    $regex .= '[a-zA-Z0-9-_]+';
                break;
            }

            $regex .= ')';

            // Register the param
            $args[] = [
                'name'    => $match['name'][0],
                'offset'  => \strlen( $route ),
                'length'  => \strlen( $regex ),
                'type'    => $match['type'][0] ?: 's'
            ];

            $route .= $regex;
        }

        return $route;
    }
}
