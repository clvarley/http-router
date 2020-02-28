<?php

require_once __DIR__ . '/parser/ParserInterface.php';
require_once __DIR__ . '/parser/JsonParser.php';
require_once __DIR__ . '/Dispatcher.php';


$parser = new \Routing\Parser\JsonParser();
$dispatcher = new \Routing\Dispatcher( $parser );

$dispatcher->loadFile( __DIR__ . '/test.routes.json' );

// Should return the 'home' route
$dispatcher->dispatch( "GET", '/' );

// Should return the 'test_number' route
$dispatcher->dispatch( "GET", '/test/145' );

// Should return the 'test_string' route
$dispatcher->dispatch( "GET", '/test/a-string' );