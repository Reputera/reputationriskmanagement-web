<?php

namespace Tests\Unit\Repositories;


use App\Entities\Company;
use App\Entities\Instance;
use App\Entities\User;
use App\Repositories\UserRepository;

class UserRepositoryTest extends \TestCase
{

    /**
     * @var UserRepository
     */
    protected $userRepository;

    public function setUp()
    {
        parent::setUp();
        $this->userRepository = app(UserRepository::class);
    }

    public function testGetAlertedUserIdsForInstanceId()
    {
        //Noise record that should not be returned from function.
        factory(User::class, 'user')->create();

        $company = factory(Company::class)->create();
        $instance = factory(Instance::class)->create([
            'company_id' => $company->id,
            'risk_score' => 95
        ]);
        \DB::table('company_alert_parameters')->insert([
            'company_id' => $company->id,
            'min_threshold' => 90,
            'max_threshold' => 100
        ]);
        $matchedUsers = factory(User::class)->times(3)->create(['company_id' => $company->id]);

        $result = $this->userRepository->getAlertedUserIdsForInstanceId($instance->id);
        $this->assertCount(3, $result);
        $this->assertArraySubset($result, $matchedUsers->lists('id')->toArray());
    }

    public function testGetAlertedUserIdsForInstanceIdNoAlertsMatch()
    {
        $company = factory(Company::class)->create();
        $instance = factory(Instance::class)->create([
            'company_id' => $company->id,
            'risk_score' => 50
        ]);
        \DB::table('company_alert_parameters')->insert([
            'company_id' => $company->id,
            'min_threshold' => 90,
            'max_threshold' => 100
        ]);
        factory(User::class)->times(3)->create(['company_id' => $company->id]);

        $result = $this->userRepository->getAlertedUserIdsForInstanceId($instance->id);
        $this->assertCount(0, $result);
    }

}