<?php

namespace App\Providers;

use App\Models\Pages;
use App\Models\Categories;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::share('pages', Pages::where('active','Y')->take(5)->get());
        View::share('categories', Categories::where('active','Y')->take(5)->get());
    }
}
