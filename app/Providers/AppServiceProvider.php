<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Setting;
use App\Models\Language;

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
        View::composer('welcome', function ($view) {
            $multilang = Setting::get('multilang_enabled', 0);
            $languages = $multilang ? Language::where('active', 1)->get() : collect();
            $lang_style = Setting::get('lang_style', 'flag');
            $view->with(compact('multilang', 'languages', 'lang_style'));
        });
    }
}
