<?php

Route::group(['middleware' => ['web']], function () {
    Route::get('login', 'Auth\AuthController@getLogin')->name('login.get');
    Route::post('login', 'Auth\AuthController@postLogin')->name('login.post');

    Route::get('riskScore', 'Instance\QueryController@getRiskScore')->name('instance.getRiskScore');
    Route::get('instance', 'Instance\QueryController@getInstances')->name('instance.get');
    Route::get('instanceCsv', 'Instance\QueryController@getInstancesCsv')->name('instance.getCsv');

    Route::get('instanceQuery', 'Instance\InstanceUIController@index')->name('instance.index');

    Route::post('create-user', 'Users\UserController@store')->name('user.store');

    Route::group(['middleware' => ['auth']], function () {
        Route::get('/', function () {
            return view('layouts.default');
        })->name('admin.landing');

        Route::group(['middleware' => ['adminAccess']], function () {
            Route::get('create-user', 'Users\AdminUserController@get')->name('adminUser.get');
            Route::post('create-user', 'Users\AdminUserController@store')->name('adminUser.store');
        });
    });

    Route::get('logout', 'Auth\AuthController@logout')->name('logout');
});

Route::group(['prefix' => 'api', 'middleware' => ['api']], function () {
    Route::post('login', 'Auth\ApiAuthController@authenticate')->name('api.login.post');
    Route::group(['middleware' => 'auth'], function () {
//        Instance routes
        Route::get('instance', 'Instance\QueryController@getInstances')->name('instance.get');
        Route::get('riskScore', 'Instance\QueryController@getRiskScore')->name('instance.getRiskScore');
    });
});
