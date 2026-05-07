<?php

namespace Modules\TaiChinh\Providers;

use Illuminate\Support\ServiceProvider;

class TaiChinhServiceProvider extends ServiceProvider
{
    protected string $moduleName = 'TaiChinh';
    protected string $moduleNameLower = 'taichinh';

    public function register(): void
    {
        $this->app->register(RouteServiceProvider::class);
    }

    public function boot(): void
    {
        $this->loadViewsFrom(module_path($this->moduleName, 'resources/views'), $this->moduleNameLower);
    }
}
