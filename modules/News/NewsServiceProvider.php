<?php

namespace Modules\News;

use Illuminate\Support\ServiceProvider;

class NewsServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->loadRoutesFrom(__DIR__.'/routes/web.php');
        $this->loadViewsFrom(__DIR__.'/Views', 'news');
    }

    public function boot()
    {
        // Можно добавить миграции, публикацию ресурсов и т.д.
    }
}
