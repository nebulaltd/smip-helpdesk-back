<?php

namespace App\Providers;

use App\Models\tmp\Club;
use App\Observers\ClubObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind('path.public', function () {
            return base_path('../public_html');
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Club::observe(ClubObserver::class);
    }
}
