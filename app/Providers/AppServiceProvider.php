<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Cache;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // fixes an issue with MySQL 5.6 that prevents migrations
        Schema::defaultStringLength(191);
        
        // Share cached total number of bookmarks
        View::share('totalBookmarks', Cache::get('totalBookmarks', 0));
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
