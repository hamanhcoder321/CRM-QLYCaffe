<?php

namespace Modules\NhapHang\Providers;

use Nwidart\Modules\Support\ModuleServiceProvider;
use Modules\NhapHang\Repositories\Interfaces\NhapHangRepositoryInterface;
use Modules\NhapHang\Repositories\NhapHangRepository;

class NhapHangServiceProvider extends ModuleServiceProvider
{
    protected string $name = 'NhapHang';
    protected string $nameLower = 'nhaphang';

    protected array $providers = [
        EventServiceProvider::class,
        RouteServiceProvider::class,
    ];

    public function boot(): void
    {
        $this->loadViewsFrom(module_path($this->name) . '/resources/views', 'nhaphang');
    }

    public function register(): void
    {
        parent::register();
        $this->app->bind(NhapHangRepositoryInterface::class, NhapHangRepository::class);
    }
}
