<?php

namespace Routing\Parser;

/**
 * Interface for classes that parse route files
 *
 * @author <clvarley>
 */
Interface ParserInterface
{

    /**
     * Parse the given routes file and return an array of routes
     *
     * @throws \Routing\Exception\FileNotFoundException  File not found
     * @throws \Routing\Exception\InvalidFileException   Invalid format
     * @param  string $filename Path to file
     * @return array            Routes array
     */
    public function parseFile( string $filename ) : array;

}