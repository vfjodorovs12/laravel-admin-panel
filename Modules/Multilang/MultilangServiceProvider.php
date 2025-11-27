<?php
namespace Modules\Multilang;

use Illuminate\Support\ServiceProvider;

class MultilangServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__.'/routes/web.php');
        $this->loadViewsFrom(__DIR__.'/resources/views', 'multilang');
    }
}
