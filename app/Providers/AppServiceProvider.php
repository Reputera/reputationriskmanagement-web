<?php

namespace App\Providers;

use App\Entities\User;
use App\Entities\Status;
use Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        \Validator::extendImplicit('required_for_role', function($attribute, $value, $parameters, $validator) {
//            Fail if no value is given, and user's role is in parameters
            return !(!$value && in_array(auth()->user()->role, $parameters));
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if ($this->app->environment('local')) {
            $this->app->register(IdeHelperServiceProvider::class);
        }
    }
}
