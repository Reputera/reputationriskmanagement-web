<?php

use App\Entities\Role;
use App\Entities\Status;

Route::group(['prefix' => 'api', 'middleware' => ['api']], function () {
    Route::post('login', 'Auth\ApiAuthController@authenticate')->name('api.login.post');
    Route::get('myInstances', 'Api\Instance\ApiQueryController@getMyInstances')->name('api.myInstances');
    Route::get('vector-risk-scores-by-month', 'Api\Instance\MonthlyRiskScoreController@byCompany')->name('api.monthly.risk');
});

Route::group(['middleware' => ['web']], function () {
    Route::get('login', 'Auth\AuthController@getLogin')->name('login.get');
    Route::post('login', 'Auth\AuthController@postLogin')->name('login.post');
    Route::get('logout', 'Auth\AuthController@logout')->name('logout');

    // Password Reset Routes...
    Route::get('password/reset/{token?}', 'Auth\PasswordController@showResetForm')->name('password.reset.get');
    Route::post('password/email', 'Auth\PasswordController@sendResetLinkEmail')->name('password.email.post');
    Route::post('password/reset', 'Auth\PasswordController@reset')->name('password.reset.post');

    Route::group(['middleware' => ['auth', 'status:'.Status::EMAIL_NOT_CHANGED]], function () {
        Route::get('password/force-reset', 'Auth\PasswordController@forceReset')->name('password.force-reset.get');
    });

    Route::group(['middleware' => ['auth', 'status:'.Status::ENABLED]], function () {
        Route::get('vectors', 'Vector\VectorController@get');

        Route::get('/', function () {
            return view('layouts.default');
        })->name('admin.landing');

        Route::group(['middleware' => ['role:'.Role::ADMIN]], function () {
            Route::get('logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index');

            Route::get('users', 'Admin\Users\UserUIController@listAll')->name('admin.users.all.get');
            Route::post('users/toggle', 'Admin\Users\UserController@toggle')->name('admin.users.toggle.post');

            Route::get('create-user', 'Admin\Users\UserUIController@createUser')->name('admin.users.create.get');
            Route::post('create-user', 'Admin\Users\UserController@createUser')->name('admin.users.create.store');

            Route::get('create-company', 'Admin\Company\CompanyUIController@createIndex')->name('admin.company.create.get');
            Route::post('create-company', 'Admin\Company\CompanyController@createPost')->name('admin.company.create.store');

            Route::get('edit-company', 'Admin\Company\CompanyUIController@editIndex')->name('admin.company.edit');

            Route::post('adminVectorColor', 'Vector\VectorController@adminSaveVectorColor')->name('admin.vector.color');

            Route::post('vectorColor', 'Vector\VectorController@saveVectorColor')->name('vector.color');
            Route::get('vectorColors', 'Vector\VectorController@getCompanyVectorColors')->name('get.vector.color');

            Route::post('industry', 'Admin\Industry\IndustryController@store')->name('admin.industry.create.store');

            Route::get('instance', 'Instance\QueryController@getInstances')->name('instance.get');
            Route::get('riskScore', 'Instance\QueryController@getRiskScore')->name('instance.getRiskScore');
            Route::get('instanceCsv', 'Instance\QueryController@getInstancesCsv')->name('instance.getCsv');
            Route::get('instanceQuery', 'Instance\InstanceUIController@index')->name('instance.index');
            Route::get('sentimentQuery', 'Instance\InstanceUIController@sentimentIndex')
                ->name('instance.sentiment.index');
            Route::post('toggleInstance', 'Instance\InstanceController@toggleDelete')->name('instance.toggleDelete');

            Route::post('addCompetitor', 'Admin\Company\CompanyController@addCompetitor')->name('instance.addCompetitor');
            Route::post('removeCompetitor', 'Admin\Company\CompanyController@removeCompetitor')
                ->name('instance.removeCompetitor');
        });
    });
});
