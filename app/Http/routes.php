<?php

Route::group(['middleware' => ['web']], function () {
    Route::get('login', 'Auth\AuthController@getLogin')->name('login.get');
    Route::post('login', 'Auth\AuthController@postLogin')->name('login.post');

    // Password Reset Routes...
    Route::get('password/reset/{token?}', 'Auth\PasswordController@showResetForm')->name('password.reset.get');
    Route::post('password/email', 'Auth\PasswordController@sendResetLinkEmail')->name('password.email.post');
    Route::post('password/reset', 'Auth\PasswordController@reset')->name('password.reset.post');

    Route::post('create-user', 'Users\UserController@store')->name('user.store');

    Route::group(['middleware' => ['auth']], function () {
        Route::get('logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index');

        Route::get('/', function () {
            return view('layouts.default');
        })->name('admin.landing');

        Route::group(['middleware' => ['adminAccess']], function () {
            Route::get('create-user', 'Users\AdminUserController@get')->name('adminUser.get');
            Route::post('create-user', 'Users\AdminUserController@store')->name('adminUser.store');

            Route::get('create-company', 'Company\CompanyUIController@createIndex')->name('companyCreate.get');

            Route::get('instance', 'Instance\QueryController@getInstances')->name('instance.get');
            Route::get('riskScore', 'Instance\QueryController@getRiskScore')->name('instance.getRiskScore');
            Route::get('instanceCsv', 'Instance\QueryController@getInstancesCsv')->name('instance.getCsv');
            Route::get('instanceQuery', 'Instance\InstanceUIController@index')->name('instance.index');
            Route::get('sentimentQuery', 'Instance\InstanceUIController@sentimentIndex')
                ->name('instance.sentiment.index');
            Route::post('toggleInstance', 'Instance\InstanceController@toggleDelete')->name('instance.toggleDelete');

            Route::post('addCompetitor', 'Company\CompanyController@addCompetitor')->name('instance.addCompetitor');
            Route::post('removeCompetitor', 'Company\CompanyController@removeCompetitor')
                ->name('instance.removeCompetitor');
        });
    });

    Route::get('logout', 'Auth\AuthController@logout')->name('logout');
});
