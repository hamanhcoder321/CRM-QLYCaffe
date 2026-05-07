<?php

namespace Modules\QuanLyChiTieu\Providers;

use Illuminate\Support\ServiceProvider;

class QuanLyChiTieuServiceProvider extends ServiceProvider
{
    protected string $moduleName = 'QuanLyChiTieu';
    protected string $moduleNameLower = 'quanlychitieu';

    public function register(): void
    {
        $this->app->register(RouteServiceProvider::class);
    }

    public function boot(): void
    {
        $this->loadViewsFrom(module_path($this->moduleName, 'resources/views'), $this->moduleNameLower);
    }
}
