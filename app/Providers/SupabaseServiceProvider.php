<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Supabase\Client;
use Supabase\Configuration;

class SupabaseServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(Client::class, function ($app) {
            $config = new Configuration(
                config('services.supabase.url'),
                config('services.supabase.key')
            );
            
            return new Client($config);
        });
    }

    public function boot()
    {
        //
    }
} 