<?php

use Infrastructure\Models\Routing\RouteCollectionBuilder;


define('HOST', getenv('HOST'));

/**
 * @Info(title="Title", version="1")
 * @Server(url=HOST)
 */

$routesCollectionBuilder = new RouteCollectionBuilder();

/**
 * @Get(
 *     path="/",
 *     @Response(
 *          response="200",
 *          description="Generate and return documentation"
 *      )
 * )
 */
$routesCollectionBuilder->addGET('/', ApiDocumentation::class, 'generate');

return $routesCollectionBuilder->build();