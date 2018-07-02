<?php

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

$router->post('/game/new', 'GameController@create');
$router->get('/game/{id}/status', 'GameController@status');
$router->get('/game/{id}', 'GameController@show');
$router->post('/game/{id}', 'GameController@update');

$router->get('/', function () use ($router) {
    return $router->app->version();
});
