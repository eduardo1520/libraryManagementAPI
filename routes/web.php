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

    $router->get('books', 'BookController@index');
    $router->get("books/{id}", 'BookController@show');
    $router->post("books", 'BookController@store');
    $router->put("books/{id}", 'BookController@update');
    $router->delete("books/{id}", 'BookController@destroy');

    $router->get('loans', 'LoanController@index');
    $router->get("loans/{id}", 'LoanController@show');
    $router->post("loans", 'LoanController@store');
    $router->put("loans/{id}", 'LoanController@update');
    $router->delete("loans/{id}", 'LoanController@destroy');

});
