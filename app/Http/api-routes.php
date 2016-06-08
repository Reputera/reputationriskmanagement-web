<?php

Route::group(['prefix' => 'api', 'middleware' => ['api']], function () {
    Route::post('login', 'Auth\ApiAuthController@authenticate')->name('api.login.post');
    Route::post('password/reset', 'Auth\ApiPasswordController@sendResetLinkEmail')->name('api.password.resetToken.post');

    Route::post('vectors', 'Vector\VectorController@get');
    Route::post('myRiskScore', 'Instance\QueryController@getRiskScore')->name('api.getRiskScore');
    Route::post('myRiskScoreByMonth', 'Api\Instance\MonthlyRiskScoreController@byCompany')->name('api.monthly.risk');
    Route::post('myInstances', 'Api\Instance\ApiQueryController@getMyInstances')->name('api.myInstances');
    Route::post('riskScoreMapData', 'Api\Instance\RiskScoreMapController@getRiskScoreMapData');

    Route::group(['middleware' => ['auth', 'role:'.\App\Entities\Role::ADMIN]], function () {
        Route::get('vector-risk-scores-by-month', 'Vector\MonthlyRiskScoreController@byCompany')
            ->name('instance.getMonthlyVectorComparison');
        \Route::group(['middleware' => ['apiUser', 'api']], function() {
//        Instance routes
            Route::get('instance', 'Instance\QueryController@getInstances')->name('instance.get');
            Route::get('riskScore', 'Instance\QueryController@getRiskScore')->name('instance.getRiskScore');
            Route::get('instanceCsv', 'Instance\QueryController@getInstancesCsv')->name('instance.getCsv');
            Route::get('competitors-average-risk-score', 'Instance\QueryController@competitorsAverageRiskScore')
                ->name('instance.competitorAverageRiskScore');
        });
    });
});
