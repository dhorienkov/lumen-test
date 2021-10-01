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
$router->get('/api/v1/product/{id}', 'ProductController@getById');

$router->get('/api/v1/products', 'ProductController@getAll');

$router->delete('/api/v1/product/{id}', 'ProductController@delete');

$router->post('/api/v1/product', 'ProductController@create');

$router->put('/api/v1/product/{id}', 'ProductController@update');

$router->post('/api/v1/product/{id}/stock', 'ProductController@addStock');
