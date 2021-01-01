<?php

namespace Routing\Collection;

/**
 * Class used to store a collection of cached routes
 *
 * @author C Varley <clvarley>
 */
Class CachedCollection Implements CollectionInterface
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
