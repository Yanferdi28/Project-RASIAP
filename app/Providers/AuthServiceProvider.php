<?php

namespace App\Providers;

use App\Models\ArsipAktif;
use App\Policies\ArsipAktifPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        ArsipAktif::class => ArsipAktifPolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();
    }
}
