<?php

namespace TypiCMS\Modules\Settings\Providers;

use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use Settings;
use TypiCMS\Modules\Core\Shells\Services\Cache\LaravelCache;
use TypiCMS\Modules\Settings\Shells\Models\Setting;
use TypiCMS\Modules\Settings\Shells\Repositories\CacheDecorator;
use TypiCMS\Modules\Settings\Shells\Repositories\EloquentSetting;

class ModuleProvider extends ServiceProvider
{
    public function boot()
    {
        /*
         * Get configuration from DB and store it in the container
         */
        $TypiCMSConfig = $this->app->make('TypiCMS\Modules\Settings\Shells\Repositories\SettingInterface')
            ->allToArray();

        // merge config
        $config = $this->app['config']->get('typicms', []);
        $this->app['config']->set('typicms', array_merge_recursive($TypiCMSConfig, $config));

        $this->loadViewsFrom(__DIR__.'/../resources/views/', 'settings');
        $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'settings');

        $this->publishes([
            __DIR__.'/../resources/views' => base_path('resources/views/vendor/settings'),
        ], 'views');
        $this->publishes([
            __DIR__.'/../database' => base_path('database'),
        ], 'migrations');
    }

    public function register()
    {
        $app = $this->app;

        /*
         * Register route service provider
         */
        $app->register('TypiCMS\Modules\Settings\Shells\Providers\RouteServiceProvider');

        $app->bind('TypiCMS\Modules\Settings\Shells\Repositories\SettingInterface', function (Application $app) {
            $repository = new EloquentSetting(new Setting());
            if (!config('typicms.cache')) {
                return $repository;
            }
            $laravelCache = new LaravelCache($app['cache'], 'settings', 10);

            return new CacheDecorator($repository, $laravelCache);
        });
    }
}
