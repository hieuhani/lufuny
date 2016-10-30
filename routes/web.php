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

$app->post('/auth/sign_in', 'AuthController@signIn');
$app->get('/categories', 'CategoriesController@index');
$app->post('/medias', 'MediasController@store');
$app->get('/medias', 'MediasController@index');

$app->group(['middleware' => 'auth', 'namespace' => 'App\Http\Controllers'], function () use ($app) {
    $app->get('/users/me', 'UsersController@me');
    $app->put('/medias/{id}/toggleVoteMedia', 'MediasController@toggleVoteMedia');
});

$app->group(['middleware' => 'auth|moderator', 'namespace' => 'App\Http\Controllers'], function () use ($app) {
    $app->get('/moderator', 'ModeratorController@mod');
    $app->get('/moderator/medias/inactive', 'ModeratorController@inActiveMedias');
    $app->put('/moderator/medias/{id}/toggleActiveMedia', 'ModeratorController@toggleActiveMedia');
});