<?php

namespace App\Providers;

use App\Models\Setting;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Paginator::defaultView('partials.pagination');
        Paginator::defaultSimpleView('partials.pagination');

        // Shared at render time (not boot time) so the active locale is applied.
        View::composer('*', function ($view) {
            try {
                $settings = Schema::hasTable('settings') ? Setting::localizedAll() : [];
            } catch (\Throwable $e) {
                $settings = [];
            }

            $view->with('settings', $settings);
        });
    }
}
