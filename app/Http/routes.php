<?php

Route::group(['prefix' => 'api', 'middleware' => ['api']], function () {
    Route::post('login', 'Auth\ApiAuthController@authenticate')->name('api.post.login');

    Route::group(['middleware' => 'auth'], function () {
        // Future routes.
    });
});
