<?php

namespace App\Helpers;

class RouteHelper
{

    public static function resourceRoute(&$router, $uri, $controller): void
    {
        $router->get($uri, "{$controller}@index");
        $router->post($uri, "{$controller}@store");
        $router->get("{$uri}/{id}", "{$controller}@show");
        $router->put("{$uri}/{id}", "{$controller}@update");
        $router->delete("{$uri}/{id}", "{$controller}@destroy");
    }

}
