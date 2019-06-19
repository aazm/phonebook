<?php

namespace App\Providers;

use App\Services\ExchangeServiceInterface;
use App\Services\Impl\ExchangeService;
use App\Services\Impl\MetaService;
use App\Services\MetaServiceInterface;
use App\Services\Impl\RecordsService;
use App\Services\RecordsServiceInterface;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(RecordsServiceInterface::class, function($app){
            return new RecordsService(config('phonebook.max_page_size'));
        });

        $this->app->bind(MetaServiceInterface::class, function($app){
            return new MetaService(
                config('phonebook.max_page_size'),
                config('phonebook.filedir'),
                config('phonebook.filename')
            );
        });

        $this->app->bind(ExchangeServiceInterface::class, function($app){

            $path = config('phonebook.filedir') . '/' . config('phonebook.filename');

            return new ExchangeService($path);
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
