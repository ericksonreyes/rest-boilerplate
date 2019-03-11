<?php

use Laravel\Lumen\Routing\Router;

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

/**
 * @var $router Router
 */

$router->get('/', function () use ($router) {
    $routes = [
        'appName' => 'Sales API',
        'version' => env('APP_VERSION')
    ];

    foreach ($router->getRoutes() as $route) {
        $routes['_links'][] = [
            'type' => $route['method'],
            'href' =>  $route['uri']
        ];
    }
    return $routes;
});

/**
 * Security
 */
$router->group(['middleware' => 'auth'], function () use ($router) {

    $router->group(['prefix' => '/' . env('APP_VERSION'). '/sales/api/'], function () use ($router) {
        $router->get('/events/{model}', 'EventsController@all');


        $router->get('/users/{userId}', 'UsersController@getUser');
    });
});

/**
 * Leads
 */
$router->post(
    '/' . env('APP_VERSION'). '/sales/api/leads',
    'LeadsController@create'
);

$router->get(
    '/' . env('APP_VERSION'). '/sales/api/leads/{leadId}',
    'LeadsController@getLead'
);

$router->get(
    '/' . env('APP_VERSION'). '/sales/api/leads/{leadId}/signupTokens',
    'LeadsController@getLeadTokens'
);

$router->get(
    '/' . env('APP_VERSION'). '/sales/api/leads/{leadId}/signupTokens/{token}/code/{verificationCode}',
    'LeadsController@getLeadToken'
);

$router->put(
    '/' . env('APP_VERSION'). '/sales/api/leads/{leadId}/signup',
    'LeadsController@signup'
);

/**
 * Accounts
 */
$router->get('/' . env('APP_VERSION'). '/sales/api/accounts/{accountId}', 'AccountsController@get');
