<?php

namespace Routing;

use \Routing\Parser\ParserInterface;

/**
 * Simple regex dispatcher
 *
 * @author <clvarley>
 */
Class Dispatcher
{

    /**
     * The route file parser to use
     *
     * @var ParserInterface $parser Route parser
     */
    private $parser = null;

    /**
     * The compiled regex for each method
     *
     * @var array $compiled Regular expressions
     */
    private $compiled = [];

    /**
     * The routes for this router
     *
     * @var array $routes Route definitions
     */
    private $routes = [];

    /**
     * HTTP methods supported by this router
     *
     * @var array ALLOWED_METHODS HTTP methods
     */
    private const ALLOWED_METHODS = [
        'GET',
        'POST',
        'PUT',
        'UPDATE',
        'DELETE'
    ];

    /**
     * Create a new router specifying the parser to use
     *
     * @param ParserInterface $parser [description]
     */
    public function __construct( ParserInterface $parser = null )
    {
        $this->parser = $parser;
    }

    /**
     * Sets the parser for this router
     *
     * @param ParserInterface $parser Parser implementation
     */
    public function setParser( ParserInterface $parser ) : void
    {
        $this->parser = $parser;
    }

    /**
     * Gets the parser being used by this router
     *
     * @return ParserInterface|null Parser implementation
     */
    public function getParser() : ?ParserInterface
    {
        return $this->parser;
    }

    /**
     * Checks to see if this router has routes
     *
     * @return bool Has routes?
     */
    public function hasRoutes() : bool
    {
        return !empty( $this->routes );
    }

    /**
     * Gets a route definition by name
     *
     * Returns null if the given route does not exist
     *
     * @param  string $name Route name
     * @return array|null   Route definition
     */
    public function getRoute( string $name ) : ?array
    {
        return $this->routes[ $name ] ?? null;
    }

    /**
     * Gets all the registered routes
     *
     * @return array Route definitions
     */
    public function getRoutes() : array
    {
        return $this->routes;
    }

    /**
     * Loads the given routes file
     *
     * @var string $filename  File path
     */
    public function loadFile( string $filename ) : void
    {
        // No parser?
        if ( $this->parser === null ) {
            throw new \Exception( "No parser has been set for this router!" );
        }

        $this->routes = $this->parser->parseFile( $filename );

        return;
    }

    /**
     * Returns all the routes that match the path
     *
     * @param  string $method [description]
     * @param  string $path   [description]
     * @return array          [description]
     */
    public function dispatch( string $method, string $path ) : array
    {
        if ( !\in_array( $method, self::ALLOWED_METHODS ) ) {
            $method = 'GET';
        }

        $path = \rtrim( $path, " \n\r\t\0\x0B\\/" );

        if ( empty( $path ) ) {
            $path = '/';
        }

        // Compile the regex
        if ( !isset( $this->compiled[$method] ) ) {
            $this->compile( $method );
        }

        if ( !\preg_match_all( $this->compiled[$method], $path, $matches, \PREG_SET_ORDER ) ) {
            return [];
        }

        $route = $this->routes[$matches[0]['MARK']];

        $args = [];

        // Handle params (if any)
        foreach ( $route['args'] ?? [] as $index => $param ) {
            $args[$param['name']] = $matches[0][$index + 1] ?? null;
        }

        return [ $route['handler'], $args ];
    }

    /**
     * Compiles the regex for this method
     *
     * @param string $method  HTTP method
     * @return void           N/a
     */
    private function compile( string $method ) : void
    {
        $regexes = [];

        // Only routes that support the method
        foreach ( $this->routes as $name => $route ) {
            if ( !\in_array( $method, $route['methods'] ) ) {
                continue;
            }

            $regexes[] = '(?>' . $route['path'] . '(*:' . $name . '))';
        }

        $this->compiled[$method] = '~^(?|' . \implode( '|', $regexes ) . ')$~ixX';

        return;
    }
}