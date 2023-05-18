<?php

namespace App\Providers;

use App\Repositories\EloquentClientRepository;
use App\Repositories\EloquentLeadRepository;
use App\Repositories\RepositoryInterface;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(
            RepositoryInterface::class,
            EloquentLeadRepository::class,
            EloquentClientRepository::class,
        );
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
