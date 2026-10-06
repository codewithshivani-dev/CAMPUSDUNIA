<?php

namespace App\Providers;
use App\Helpers\MenuHelper;
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
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Share menu with all views
        view()->composer('*', function ($view) {
            if (auth()->check()) {
                $view->with('menuItems', MenuHelper::getUserMenu());
            }
        });

        // Register menu helper
        $this->app->singleton('menu.helper', function ($app) {
            return new MenuHelper();
        });
    }
}
