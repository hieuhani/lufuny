<?php

$app->group(['prefix' => 'v1', 'namespace' => 'App\Http\Controllers'], function () use ($app) {
    /**
     * @api {post} /auth/sign_in Sign in with username and password
     * @apiName SignIn
     * @apiGroup User
     *
     * @apiParam {String} email Email
     * @apiParam {String} password Password
     *
     * @apiSuccess {String} token   Access token
     * @apiSuccess {String} ttl     Time to live of the access token
     */
    $app->post('/auth/sign_in', 'AuthController@signIn');

    /**
     * @api {get} /categories Get the list of categories
     * @apiName CategoryList
     * @apiGroup Common
     *
     * @apiSuccess {Array} data Array of categories
     */
    $app->get('/categories', 'CategoriesController@index');

    /**
     * @api {post} /medias Create a media
     * @apiName CreateMedia
     * @apiGroup Media
     *
     * @apiParam {String} description   Short description about this media
     * @apiParam {String} nickname      Anonymous user can manually enter nickname
     * @apiParam {Integer} category_id  Category that the media belongs to
     *
     * @apiSuccess {String} data Media information
     */
    $app->post('/medias', 'MediasController@store');

    /**
     * @api {post} /medias/:id/files Add file to media
     * @apiName AddFileToMedia
     * @apiGroup Media
     *
     * @apiParam {String}   [photo]     Image file (jpeg,bmp,png,gif allowed)
     * @apiParam {Integer}  type        1: Normal photo, 2: YouTube video
     * @apiParam {String}   [video_url] YouTube video url
     * @apiParam {String}   media_token Media token that got from creating a media
     *
     * @apiSuccess {Object} data Media information
     */
    $app->post('/medias/{id:[0-9]+}/files', 'MediasController@addFile');

    /**
     * @api {get} /medias Get the list of active medias
     * @apiName MediaList
     * @apiGroup Media
     *
     * @apiSuccess {Array} data Array of medias
     */
    $app->get('/medias', 'MediasController@index');

    $app->group(['middleware' => 'auth', 'namespace' => 'App\Http\Controllers'], function () use ($app) {
        $app->get('/users/me', 'UsersController@me');

        /**
         * @api {put} /medias/:id/toggleVoteMedia Vote or remove vote media
         * @apiPermission User authenticated
         * @apiName VoteMedia
         * @apiGroup Media
         *
         * @apiSuccess {Array} data Update media information
         */
        $app->put('/medias/{id}/toggleVoteMedia', 'MediasController@toggleVoteMedia');
    });

    $app->group(['middleware' => 'auth|moderator', 'prefix' => 'v1/moderator', 'namespace' => 'App\Http\Controllers'], function () use ($app) {
        $app->get('/', 'ModeratorController@mod');

        /**
         * @api {get} /moderator/medias/inactive Inactive medias
         * @apiPermission User is moderator
         * @apiName ModeratorGetMediaList
         * @apiGroup Moderator
         *
         * @apiSuccess {Array} data Array of inactive medias
         */
        $app->get('/medias/inactive', 'ModeratorController@inActiveMedias');

        /**
         * @api {put} /moderator/medias/:id/toggleActiveMedia Toggle update active status of media
         * @apiPermission User is moderator
         * @apiName ModeratorToggleMediaStatus
         * @apiGroup Moderator
         *
         * @apiSuccess {Object} data Updated media information
         */
        $app->put('/medias/{id}/toggleActiveMedia', 'ModeratorController@toggleActiveMedia');
    });
});