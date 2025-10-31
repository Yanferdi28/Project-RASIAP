<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [ /* kosong → auto-discovery */ ];

    public function boot(): void
    {
        $this->registerPolicies();
    }
}
