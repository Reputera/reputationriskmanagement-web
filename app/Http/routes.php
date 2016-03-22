<?php

Route::group(['middleware' => ['web']], function () {
    Route::get('login', 'Auth\AuthController@getLogin')->name('login.get');
    Route::post('login', 'Auth\AuthController@postLogin')->name('login.post');

    Route::post('create-user', 'Users\UserController@store')->name('user.store');

    Route::group(['middleware' => ['auth']], function () {
        Route::get('/', function () {
            return view('layouts.default');
        })->name('admin.landing');
    });

    Route::get('logout', 'Auth\AuthController@logout')->name('logout');
});

Route::group(['prefix' => 'api', 'middleware' => ['api']], function () {
    Route::post('login', 'Auth\ApiAuthController@authenticate')->name('api.login.post');
    Route::group(['middleware' => 'auth'], function () {
        Route::get('instance', 'Instance\QueryController@getInstances')->name('instance.get');

        Route::post('create-admin', 'Users\AdminUserController@store')->name('admin.store');
    });
});
