<?php

namespace App\Providers;

use App\Models\Cart;
use App\Models\ProductType;
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
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
        view()->composer('header',function($view){
            $product_types = ProductType::all();
            $view->with('product_types', $product_types);
        });

        view()->composer('header', function($view){
            $oldCart = session('cart');
            $cart = new Cart($oldCart);
            $view->with('cart', $cart);
        });
    }
}
