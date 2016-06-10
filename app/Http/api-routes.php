<?php

Route::group(['prefix' => 'api', 'middleware' => ['api']], function () {
    Route::post('login', 'Auth\ApiAuthController@authenticate')->name('api.login.post');
    Route::post('password/reset', 'Auth\ApiPasswordController@sendResetLinkEmail')->name('api.password.resetToken.post');

    Route::group(['middleware' => ['auth', 'apiUser']], function () {
        Route::get('company/logo', 'Admin\Company\CompanyController@getCompanyLogo')->name('logo.get');
        Route::get('instance/alerts', 'Instance\AlertController@getAlertedInstances')->name('instance.alerts');
        Route::get('instance/alerts/dismiss/{instanceId}', 'Instance\AlertController@dismissAlert')->name('instance.dismissAlert');

        Route::post('vectors', 'Vector\VectorController@get');
        Route::post('myRiskScore', 'Instance\QueryController@getRiskScore')->name('api.getRiskScore');
        Route::post('riskScoreMapData', 'Api\Instance\RiskScoreMapController@getRiskScoreMapData');
        Route::get('instance', 'Instance\QueryController@getInstances')->name('instance.get');
        Route::get('riskScore', 'Instance\QueryController@getRiskScore')->name('instance.getRiskScore');

        Route::get('vector-risk-scores-by-month', 'Vector\MonthlyRiskScoreController@byCompany')
            ->name('instance.getMonthlyVectorComparison');

        Route::get('competitors-average-risk-score', 'Instance\QueryController@competitorsAverageRiskScore')
            ->name('instance.competitorAverageRiskScore');

        Route::group(['middleware' => ['role:'.\App\Entities\Role::ADMIN]], function () {
            Route::get('instanceCsv', 'Instance\QueryController@getInstancesCsv')->name('instance.getCsv');
        });
    });
});
