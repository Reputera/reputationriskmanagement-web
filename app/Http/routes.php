<?php

Route::group(['middleware' => ['web']], function () {
    Route::get('login', 'Auth\AuthController@getLogin');
    Route::post('login', 'Auth\AuthController@postLogin');

    Route::group(['middleware' => ['auth']], function () {
        Route::get('/', function () {
            return view('layouts.default');
        })->name('admin.landing');
    });

    Route::get('logout', 'Auth\AuthController@logout')->name('logout');
});

Route::group(['prefix' => 'api', 'middleware' => ['api']], function () {
    Route::post('login', 'Auth\ApiAuthController@authenticate')->name('api.post.login');

    Route::group(['middleware' => 'auth'], function () {
        // Future routes.
    });
});
