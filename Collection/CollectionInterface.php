<?php

namespace Routing\Collection;

use Routing\Route;

/**
 * Common interface for managing a collection of routes
 *
 * @author C Varley <clvarley>
 */
Interface CollectionInterface Extends \Iterator, \Countable
{

    /**
     * Adds a new route to the collection
     *
     * @param string $name Route identifier
     * @param Route $route Route definition
     * @return void        N/a
     */
    public function add( string $name, Route $route ) : void;

    /**
     * Gets the named route from the collection
     *
     * @param string $name Route identifier
     * @return Route|null  Route definition
     */
    public function get( string $name ) : ?Route;

    /**
     * Removes the named route from the collection
     *
     * @param string $name Route identifier
     * @return void        N/a
     */
    public function remove( string $name ) : void;

}
