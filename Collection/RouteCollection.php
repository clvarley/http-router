<?php

namespace Routing\Collection;

use Routing\Route;

/**
 * Class used to store and manage a collection of routes
 *
 * @author C Varley <clvarley>
 */
Class RouteCollection Implements CollectionInterface
{

    /**
     * The contents of this collection
     *
     * @var Route[] $routes Route collection
     */
    protected $routes;

    /**
     * Create a new collection around the given routes
     *
     * @param Route[] $routes (Optional) Route definitions
     */
    public function __construct( array $routes = [] )
    {
        $this->routes = $routes;
    }

    /**
     * @inheritdoc
     */
    public function compile( string $method = 'GET' ) : string
    {
        $regexes = [];

        foreach ( $this->routes as $name => $route ) {
            if ( \in_array( $method, $route->methods ) ) {
                $regexes[] = '(?>' . $route->regex . '(*:' . $name . '))';
            }
        }

        return '~^(?|' . \implode( '|', $regexes ) . ')$~ixX';
    }
}
