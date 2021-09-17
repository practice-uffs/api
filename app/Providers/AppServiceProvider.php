<?php

namespace App\Providers;

use App\Auth\CredentialManager;
use App\Services\AuraNLP;
use Illuminate\Support\Facades\URL;
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
        $this->app->singleton(CredentialManager::class, function($app) {
            return new CredentialManager();
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Fix wrong style/mix urls when being served from reverse proxy
        URL::forceRootUrl(config('app.url'));

        $this->app->singleton(AuraNLP::class, function($app) {
            return new AuraNLP(config('auranlp'));
        });
    }
}
