<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useTailwind();

        // Gate untuk Admin
        Gate::define('admin', function ($user) {
            return $user->is_admin == true;
        });

        Gate::define('kasir', function ($user) {
            return $user->is_admin == false;
        });
    }
}
