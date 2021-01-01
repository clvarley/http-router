<?php

namespace Routing;

/**
 * Simple class used to represent a single route
 *
 * @author C Varley <clvarley>
 */
Class Route
{

    /**
     * The regex used to match this route
     *
     * @var string $regex Route regex
     */
    public $regex = '';

    /**
     * Allowed HTTP methods for this route
     *
     * @var string[] $methods HTTP methods
     */
    public $methods = [];

    /**
     * Arguments to be passed to the controller
     *
     * @var array $args Route arguments
     */
    public $args = [];

    /**
     * The controller used to handle this route
     *
     * @var callable|null $callable Route controller
     */
    public $callable = null;

}
