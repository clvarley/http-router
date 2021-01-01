<?php

namespace Routing\Collection;

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
    protected $routes = [];

    /**
     *
     */
    public function __construct()
    {
        // TODO:
    }
}
