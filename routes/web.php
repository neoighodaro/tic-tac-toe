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

$router->group(['prefix' => '/api'], function ($router) {
    $router->post('/game/{id}/autoplay', 'Api\GameController@autoplay');
    $router->get('/game/{id}/status', 'Api\GameController@status');
    $router->get('/game/{id}', 'Api\GameController@show');
    $router->post('/game/{id}', 'Api\GameController@update');
});

$router->get('/game/new', 'GameController@create');
$router->get('/game/{id}', 'GameController@show');

$router->get('/', function () use ($router) {
    return $router->app->version();
});
