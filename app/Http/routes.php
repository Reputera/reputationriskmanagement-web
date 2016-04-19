<?php

Route::group(['middleware' => ['web']], function () {
    Route::get('login', 'Auth\AuthController@getLogin')->name('login.get');
    Route::post('login', 'Auth\AuthController@postLogin')->name('login.post');

    Route::post('create-user', 'Users\UserController@store')->name('user.store');

    Route::group(['middleware' => ['auth']], function () {
        Route::get('logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index');

        Route::get('/', function () {
            return view('layouts.default');
        })->name('admin.landing');

        Route::group(['middleware' => ['adminAccess']], function () {
            Route::get('create-user', 'Users\AdminUserController@get')->name('adminUser.get');
            Route::post('create-user', 'Users\AdminUserController@store')->name('adminUser.store');

            Route::get('create-company', 'Company\CompanyController@createIndex')->name('companyCreate.get');

            Route::get('instance', 'Instance\QueryController@getInstances')->name('instance.get');
            Route::get('riskScore', 'Instance\QueryController@getRiskScore')->name('instance.getRiskScore');
            Route::get('instanceCsv', 'Instance\QueryController@getInstancesCsv')->name('instance.getCsv');
            Route::get('instanceQuery', 'Instance\InstanceUIController@index')->name('instance.index');
            Route::get('sentimentQuery', 'Instance\InstanceUIController@sentimentIndex')
                ->name('instance.sentiment.index');
            Route::post('flagInstance', 'Instance\InstanceController@flag')->name('instance.flag');

            Route::post('addCompetitor', 'Company\CompanyController@addCompetitor')->name('instance.addCompetitor');
            Route::post('removeCompetitor', 'Company\CompanyController@removeCompetitor')
                ->name('instance.removeCompetitor');
        });
    });

    Route::get('logout', 'Auth\AuthController@logout')->name('logout');
});

Route::group(['prefix' => 'api', 'middleware' => ['api']], function () {
    Route::post('login', 'Auth\ApiAuthController@authenticate')->name('api.login.post');
    Route::group(['middleware' => 'auth'], function () {
        Route::get('vector-risk-scores-by-month', 'Vector\MonthlyRiskScoreController@byCompany')
            ->name('instance.getMonthlyVectorComparison');
//        Instance routes
        Route::get('instance', 'Instance\QueryController@getInstances')->name('instance.get');
        Route::get('riskScore', 'Instance\QueryController@getRiskScore')->name('instance.getRiskScore');
        Route::get('instanceCsv', 'Instance\QueryController@getInstancesCsv')->name('instance.getCsv');
        Route::get('competitors-average-risk-score', 'Instance\QueryController@competitorsAverageRiskScore')
            ->name('instance.competitorAverageRiskScore');

        Route::post('industry', 'Industry\IndustryController@store')->name('industry.post');
    });
});
