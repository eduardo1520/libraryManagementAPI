<?php

/** @var \Laravel\Lumen\Routing\Router $router */

$router->get('/', function () use ($router) {
    return response()->json(['message' => 'Hello Lumen'. $router->app->version()]);
});

$router->group(['prefix' => 'api'], function () use ($router) {
    $router->get('authors', 'AuthorController@index');
    $router->get("authors/{id}", 'AuthorController@show');
    $router->post("authors", 'AuthorController@store');
    $router->put("authors/{id}", 'AuthorController@update');
    $router->delete("authors/{id}", 'AuthorController@destroy');

    // Helper::resourceRoute($router, 'books'  , 'App\Http\Controllers\BookController');
    // Helper::resourceRoute($router, 'loans'  , 'App\Http\Controllers\LoanController');
});
