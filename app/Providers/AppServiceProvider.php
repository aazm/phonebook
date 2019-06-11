<?php

namespace App\Providers;

use App\Services\RecordsService;
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
