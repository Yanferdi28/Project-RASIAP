<?php

use Illuminate\Support\Facades\Artisan;

/*
|--------------------------------------------------------------------------
| Optimasi Proyek - Cache Configuration
|--------------------------------------------------------------------------
|
| File ini berisi konfigurasi untuk mengoptimalkan aplikasi Laravel
| melalui caching, preloading, dan optimasi resource lainnya
|
*/

// Optimasi aplikasi setelah deploy
if (!function_exists('optimizeApplication')) {
    function optimizeApplication()
    {
        try {
            Artisan::call('config:cache');
            Artisan::call('route:cache');
            Artisan::call('view:cache');
            Artisan::call('event:cache');
        } catch (\Exception $e) {
            // Jika mode debug aktif, jangan cache
            if (config('app.debug')) {
                Artisan::call('config:clear');
                Artisan::call('route:clear');
                Artisan::call('view:clear');
                Artisan::call('event:clear');
            }
        }
    }
}