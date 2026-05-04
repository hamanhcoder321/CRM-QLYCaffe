<?php

namespace Modules\TuyenDung\Providers;

use Nwidart\Modules\Support\ModuleServiceProvider;

class TuyenDungServiceProvider extends ModuleServiceProvider
{
    protected string $name = 'TuyenDung';
    protected string $nameLower = 'tuyendung';

    protected array $providers = [
        EventServiceProvider::class,
        RouteServiceProvider::class,
    ];

    public function boot(): void
    {
        $this->loadViewsFrom(module_path($this->name) . '/resources/views', 'tuyendung');
    }

    public function register(): void
    {
        parent::register();
    }
}
