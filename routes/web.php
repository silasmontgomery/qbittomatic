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

use App\Http\Controllers\TorrentController;

$router->group(['prefix' => 'api'], function ($router) {
    $router->group(['prefix' => 'v1'], function ($router) {
        $router->get('/torrent', 'TorrentController@index');
        $router->post('/torrent/{hash}', 'TorrentController@update');
        $router->post('/torrent', 'TorrentController@add');
        $router->delete('/torrent/{hash}', 'TorrentController@delete');
        $router->get('/search', 'TorrentController@search');
        $router->get('/search_apis', 'TorrentController@searchApis');
        $router->get('/paths', 'TorrentController@paths');
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
