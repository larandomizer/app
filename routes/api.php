<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

$router->group(['prefix' => 'connection'], function($router) {
    $router->get('{uuid}', 'Connection@notify');
    $router->get('{uuid}', 'Connection@dismissNotifications');
    $router->get('{uuid}', 'Connection@disconnect');
    $router->get('{type}', 'Connection@disconnectType');
    $router->get('/', 'Connection@disconnectAll');
});

$router->post('notification', 'Notification@send');

$router->group(['prefix' => 'prize'], function($router) {
    $router->post('/', 'Prize@add');
    $router->delete('/', 'Prize@reset');
    $router->get('/', 'Prize@giveaway');
});

$router->group(['prefix' => 'topic'], function($router) {
    $router->delete('{uuid}', 'Topic@destroy');
    $router->post('/', 'Topic@store');
});

$router->post('server', 'Server@restart');
