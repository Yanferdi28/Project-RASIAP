<?php

namespace App\Providers;

use App\Models\BerkasArsip;
use App\Policies\BerkasArsipPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        BerkasArsip::class => BerkasArsipPolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();
    }
}