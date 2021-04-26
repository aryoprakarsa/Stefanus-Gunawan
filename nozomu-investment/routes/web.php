<?php

use Illuminate\Support\Str;
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

$router->group(['prefix' => 'api'], function() use ($router) {
    $router->get('show-all', ['uses' => 'CustomerController@showAll']);
    $router->post('register',['uses' => 'CustomerController@addCustomer']);
});

// $router->get('/key', function() {
//     return Str::random(32);
// });
