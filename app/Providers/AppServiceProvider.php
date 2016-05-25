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
        User::restoring(function ($user) {
            return $user->update(['status' => Status::ENABLED]);
        });

        User::deleting(function ($user) {
            return $user->update(['status' => Status::DISABLED]);
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
