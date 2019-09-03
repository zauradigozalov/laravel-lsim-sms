<?php

namespace samirmhdev\LaravelLsimSms;

use Illuminate\Support\ServiceProvider;
use samirmhdev\LaravelLsimSms\Controllers\LsimController;

class LsimServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadTranslationsFrom(__DIR__ . '/../resources/lang', 'LaravelLsimSms');

        $this->publishes([
            __DIR__ . '/../config/laravel-lsim-sms.php' => config_path('laravel-lsim-sms.php'),
        ],'laravel-lsim-config');

        $this->publishes([
            __DIR__ . '/../resources/lang' => resource_path('lang/vendor/laravel_lsim'),
        ],'laravel-lsim-translations');
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->make('samirmhdev\LaravelLsimSms\Controllers\LsimController');

        $this->mergeConfigFrom(
            __DIR__ . '/../config/laravel-lsim-sms.php', 'laravel-lsim-sms'
        );

        $this->app->singleton('laravel-lsim-sms', function ($app) {
            return new LsimController();
        });
    }
}