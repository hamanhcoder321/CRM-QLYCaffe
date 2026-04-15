<?php

namespace Modules\BanHang\Providers;

use Nwidart\Modules\Support\ModuleServiceProvider;
use Modules\BanHang\Repositories\BanHangRepository;
use Modules\BanHang\Repositories\Interfaces\BanHangRepositoryInterface;

class BanHangServiceProvider extends ModuleServiceProvider
{
    protected string $name = 'BanHang';
    protected string $nameLower = 'banhang';

    protected array $providers = [
        RouteServiceProvider::class,
    ];

    public function boot(): void
    {
        $this->loadViewsFrom(module_path($this->name) . '/resources/views', 'banhang');
    }

    public function register(): void
    {
        parent::register();
        $this->app->bind(BanHangRepositoryInterface::class, BanHangRepository::class);
    }
}
