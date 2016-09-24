<?php

namespace App\Providers;

use App\Interactions\UserLogin;
use App\Repositories\ServiceRepository;
use App\Services\TickerLocker;
use Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider;
use Illuminate\Support\ServiceProvider;
use Leo108\CAS\Contracts\Interactions\UserLogin as UserLoginInterface;
use Leo108\CAS\Contracts\TicketLocker as TicketLockerInterface;
use Leo108\CAS\Repositories\ServiceRepository as ServiceRepositoryBase;
use Leo108\CASServer\OAuth\PluginCenter;
use NinjaMutex\Lock\LockAbstract;
use NinjaMutex\Lock\MySqlLock;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if ($this->app->environment() == 'local') {
            $this->app->register(IdeHelperServiceProvider::class);
        }

        $this->app->bind(UserLoginInterface::class, UserLogin::class);
        $this->app->bind(TicketLockerInterface::class, TickerLocker::class);
        $this->app->bind(ServiceRepositoryBase::class, ServiceRepository::class);
        $this->app->bind(
            LockAbstract::class,
            function () {
                $conf = config('database.connections.mysql');

                return new MySqlLock($conf['username'], $conf['password'], $conf['host']);
            }
        );
        $this->app->singleton(
            PluginCenter::class,
            function () {
                return new PluginCenter(app()->getLocale(), config('app.fallback_locale'));
            }
        );
    }
}
