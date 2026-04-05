<?php

namespace Modules\User\Providers;

use Nwidart\Modules\Support\ModuleServiceProvider;
use Illuminate\Console\Scheduling\Schedule;
use Modules\User\Repositories\Interfaces\UserRepositoryInterface;
use Modules\User\Repositories\UserRepository;

class UserServiceProvider extends ModuleServiceProvider
{
    /**
     * The name of the module.
     */
    protected string $name = 'User';

    /**
     * The lowercase version of the module name.
     */
    protected string $nameLower = 'user';

    /**
     * Command classes to register.
     *
     * @var string[]
     */
    // protected array $commands = [];

    /**
     * Provider classes to register.
     *
     * @var string[]
     */
    protected array $providers = [
        EventServiceProvider::class,
        RouteServiceProvider::class,
    ];


    public function boot(): void{
        $this->loadViewsFrom(module_path($this->name). '/Resources/views', 'users');
    }

    /**
     * Register any module services.
     */
    public function register(): void
    {
        parent::register();

        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
    }

    /**
     * Define module schedules.
     * 
     * @param $schedule
     */
    // protected function configureSchedules(Schedule $schedule): void
    // {
    //     $schedule->command('inspire')->hourly();
    // }
}
