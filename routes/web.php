<?php

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
    return $router->app->version();
});

$router->get('/led', 'LedController@getAll');

$router->post('/led', 'LedController@create');

$router->get('/led/{ledId}', 'LedController@get');

$router->put('/led/{ledId}', 'LedController@update');

$router->delete('/led/{ledId}', 'LedController@delete');

$router->get('/led/{ledId}/color', 'ColorController@get');

$router->put('/led/{ledId}/color', 'ColorController@update');
