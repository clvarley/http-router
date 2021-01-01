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
     * Precompiled route regexes
     *
     * @var string[] $compiled Compiled regexes
     */
    protected $compiled = [];

    /**
     *
     */
    public function __construct()
    {
        // TODO:
    }

    /**
     * @inheritdoc
     */
    public function compile( string $method = 'GET' ) : string
    {
        return ( isset( $this->compiled[$method] )
            ? $this->compiled[$method]
            : ''
        );
    }
}
