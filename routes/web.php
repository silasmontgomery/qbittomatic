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

// API Routes

$router->group(['prefix' => 'api'], function ($router) {
    $router->group(['prefix' => 'v1'], function ($router) {
        // Auth routes
        $router->post('/auth/login', 'AuthController@authenticate');

        // Auth required routes
        $router->group(['middleware' => ['jwt.auth']], function ($router) {
            // Torrent routes
            $router->get('/torrent', 'TorrentController@index');
            $router->post('/torrent/{hash}', 'TorrentController@update');
            $router->post('/torrent', 'TorrentController@add');
            $router->delete('/torrent/{hash}', 'TorrentController@delete');
            $router->get('/search', 'TorrentController@search');
            $router->get('/search_apis', 'TorrentController@searchApis');
            $router->get('/paths', 'TorrentController@paths');
        });

        // Helper routes
        $router->get('/key', function () {
            $bytes = random_bytes(16);
            return bin2hex($bytes);
        });
    });
});

// Catch all other requests and route to Vue
$router->get('/{route:.*}/', function () {
    return view('app');
});
