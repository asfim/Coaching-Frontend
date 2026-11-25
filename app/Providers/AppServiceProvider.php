<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Schema;
use Illuminate\Pagination\Paginator;
use App\Models\Setting;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::defaultView('vendor.pagination.default');

        View::composer('*', function ($view) {
            try {
                if (Schema::hasTable('settings')) {
                    $siteSettings = Setting::all()->pluck('value', 'key')->toArray();
                } else {
                    $siteSettings = [];
                }
            } catch (\Throwable $e) {
                $siteSettings = [];
            }
            $view->with('siteSettings', $siteSettings);
        });
    }
}
