<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Pagination\Paginator;
use App\AutoOrder;
use App\Observers\AutoOrderObserver;
use App\Support\Brand;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Paginator::defaultView('pagination::bootstrap-4');

        AutoOrder::observe(AutoOrderObserver::class);

        // Share the resolved brand with EVERY view so logo/name/footer render per-brand
        // everywhere (chrome, invoices, footer, chat, emails). On the florida portal
        // PORTAL_BRAND=crazyrays forces CrazyRays for all users; on the Hello landing it
        // falls back to per-user branding. Views that pass an explicit $brand keep theirs.
        View::composer('*', function ($view) {
            $data = $view->getData();
            if (!array_key_exists('brand', $data)) {
                $view->with('brand', Brand::current());
            }
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
