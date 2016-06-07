<?php

namespace Tests\Features\Middleware;


use App\Entities\Role;
use Symfony\Component\HttpFoundation\Request;

class ApiUserTest extends \TestCase
{

    public function testSetCompanyAsNonAdmin()
    {
        $user = $this->beLoggedInAsUser(['role' => Role::USER]);
        \Route::group(['prefix' => 'api', 'middleware' => ['apiUser']], function () use (&$requestResult){
            \Route::post('test', function (Request $request) use (&$requestResult) {
                $requestResult = $request;
            });
        });
        $this->apiCall('POST', 'test', ['companies_name' => 'other company']);
        $this->assertEquals($requestResult->get('companies_name'), $user->company->name);
    }

    public function testSetCompanyAsAdminAllowsPostingCompanyName()
    {
        $user = $this->beLoggedInAsAdmin();
        \Route::group(['prefix' => 'api', 'middleware' => ['apiUser']], function () use (&$requestResult){
            \Route::post('test', function (Request $request) use (&$requestResult) {
                $requestResult = $request;
            });
        });
        $this->apiCall('POST', 'test', ['companies_name' => 'other company']);
        $this->assertEquals($requestResult->get('companies_name'), 'other company');
    }

}
