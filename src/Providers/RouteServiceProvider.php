<?php

namespace TypiCMS\Modules\Settings\Providers;

use Illuminate\Routing\Router;
use TypiCMS\Modules\Core\Shells\Providers\BaseRouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to the controller routes in your routes file.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'TypiCMS\Modules\Settings\Shells\Http\Controllers';

    /**
     * Define the routes for the application.
     *
     * @param \Illuminate\Routing\Router $router
     *
     * @return void
     */
    public function map(Router $router)
    {
        $router->group(['namespace' => $this->namespace], function (Router $router) {
            /*
             * Admin routes
             */

            $router->get('admin/settings', 'AdminController@index')->name('admin::index-settings');
            $router->post('admin/settings', 'AdminController@store')->name('admin::store-setting');
            $router->get('admin/cache/clear', 'AdminController@clearCache')->name('admin::clear-cache');

            $router->get('admin/artisan/configCache', 'AdminController@configCache')->name('admin::artisan-configCache');
            $router->get('admin/artisan/dbMigrate', 'AdminController@dbMigrate')->name('admin::artisan-dbMigrate');

            /*
             * API routes
             */
            $router->put('api/settings', 'AdminController@deleteImage');
        });
    }
}
