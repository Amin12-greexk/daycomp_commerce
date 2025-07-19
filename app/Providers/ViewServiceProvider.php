<?php

namespace App\Providers;

use App\Models\Category;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
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
        View::composer('*', function ($view) {
            $cartCount = count(session('cart', []));

            // Use a unique name for the categories meant for the navigation
            $navCategories = Category::all();

            // Share both variables with the view
            $view->with('cartCount', $cartCount)
                ->with('navCategories', $navCategories); // Use the new variable name here
        });
    }
}