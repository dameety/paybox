<?php

namespace Dameety\Paybox;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Cviebrock\EloquentSluggable\ServiceProvider as SluggableServiceProvider;

class PayboxServiceProvider extends ServiceProvider
{
    protected $defer = false;

    public function boot()
    {
        $this->configure();
        $this->registerRoutes();
        $this->assetPublishing();
        $this->registerHelpers();
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        $this->registerViews();
    }

    public function configure()
    {
        $this->publishes([__DIR__ . '/../config/paybox.php' => config_path('paybox.php')], 'config');
    }

    public function assetPublishing()
    {
        //this publishes the paybox.min.css and paybox.min.js files
        $this->publishes([
            __DIR__ . '/../public' => public_path('vendor/paybox')
        ], 'assets');

        //publish vue assets files
        $this->publishes([
            __DIR__ . '/../resources/assets' => resource_path('assets/vendor/paybox')
        ], 'vue-components');

        //this is a blade file contain some keys needed in the components
        $this->publishes([
            __DIR__ . '/../resources/views/scripts' => resource_path('views/vendor/paybox/scripts')
        ], 'global-scripts');
    }

    public function registerViews()
    {
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'paybox');
        $this->publishes([
            __DIR__ . '/../resources/views' => resource_path('views/vendor/paybox')
        ], 'views');
    }

    public function registerRoutes()
    {
        Route::group([
            'middleware' => ['web', 'auth'],
            'namespace' => 'Dameety\Paybox\Http\Controllers'
        ], function () {
            $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');
        });
    }

    public function register()
    {
        $this->app->register(SluggableServiceProvider::class);
    }

    public function registerHelpers()
    {
        require_once( __DIR__.'/Helpers/ActiveMenu.php');
    }
}
