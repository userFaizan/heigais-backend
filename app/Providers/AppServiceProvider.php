<?php

namespace App\Providers;

use App\Models\Event;
use App\Models\Transection;
use App\Models\User;
use App\Observers\EventObserver;
use App\Observers\TransectionObserver;
use App\Observers\UserObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Event::observe(EventObserver::class);
        User::observe(UserObserver::class);
        Transection::observe(TransectionObserver::class);
    }
}
