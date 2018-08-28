<?php

use Infrastructure\Models\Routing\RouteCollectionBuilder;

$routesCollectionBuilder = new RouteCollectionBuilder();

$routes = $routesCollectionBuilder->build();

return $routes;