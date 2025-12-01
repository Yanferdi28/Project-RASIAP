<?php

namespace App\Providers;

use App\Models\ArsipUnit;
use App\Models\BerkasArsip;
use App\Models\User;
use App\Observers\ArsipUnitObserver;
use App\Observers\BerkasArsipObserver;
use App\Observers\UserObserver;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\App;
use Illuminate\Database\Eloquent\Model;

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
        // Atur lokal aplikasi ke bahasa Indonesia
        App::setLocale('id');

        // Optimisasi: Prevent lazy loading in development (catches N+1 issues)
        // Model::preventLazyLoading(!app()->isProduction());
        
        // Optimisasi: Prevent silently discarding attributes
        Model::preventSilentlyDiscardingAttributes(!app()->isProduction());

        // Register model observers
        User::observe(UserObserver::class);
        ArsipUnit::observe(ArsipUnitObserver::class);
        BerkasArsip::observe(BerkasArsipObserver::class);
    }
}
