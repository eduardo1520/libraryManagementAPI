<?php

use App\Helpers\RouteHelper as Helper;
use App\Http\Controllers\AuthorController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\LoanController;

/** @var \Laravel\Lumen\Routing\Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return response()->json(['message' => 'Hello Lumen'. $router->app->version()]);
});

$router->group(['prefix' => 'api'], function () use ($router) {
    Helper::resourceRoute($router, 'authors', AuthorController::class);
    Helper::resourceRoute($router, 'books'  , BookController::class);
    Helper::resourceRoute($router, 'loans'  , LoanController::class);
});
