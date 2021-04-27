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

$router->group(['prefix' => 'api/v1'], function() use ($router) {
    $router->get('show-all', ['uses' => 'CustomerController@showAll']);
    $router->post('user/add',['uses' => 'CustomerController@addCustomer']);
    $router->post('update-name/{id}', ['uses' => 'CustomerController@updateName']);
    $router->post('ib/updateTotalBalance', ['uses' => 'NABController@updateTotalBalance']);
    $router->post('ib/topup', ['uses' => 'CustomerController@storeBalance']);
    $router->get('ib/listNAB', ['uses' => 'NABController@list']);
    $router->post('ib/withdraw', ['uses' => 'CustomerController@withdrawBalance']);
    $router->get('ib/member', ['uses' => 'CustomerController@showAll']);
});

// $router->get('/key', function() {
//     return Str::random(32);
// });
