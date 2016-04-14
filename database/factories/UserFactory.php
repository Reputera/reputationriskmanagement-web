<?php

use App\Entities\Company;
use App\Entities\Role;

$factory->define(App\Entities\User::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->email,
        'password' => bcrypt(str_random(10)),
        'remember_token' => str_random(10),
        'role' => Role::USER,
        'company_id' => null
    ];
});

$factory->defineAs(App\Entities\User::class, 'admin', function ($faker) use ($factory) {
    $user = $factory->raw(App\Entities\User::class);

    return array_merge($user, [
        'role' => Role::ADMIN,
    ]);
});

$factory->defineAs(App\Entities\User::class, 'user', function ($faker) use ($factory) {
    $user = $factory->raw(App\Entities\User::class);

    return array_merge($user, [
        'role' => Role::ADMIN,
        'company_id' => function () {
            return factory(Company::class)->create()->id;
        },
    ]);
});

$factory->defineAs(App\Entities\User::class, 'user_admin', function ($faker) use ($factory) {
    $user = $factory->raw(App\Entities\User::class);

    return array_merge($user, [
        'role' => Role::USER_ACCOUNT_ADMIN,
        'company_id' => function () {
            return factory(Company::class)->create()->id;
        },
    ]);
});
