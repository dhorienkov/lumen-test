<?php
namespace App\Providers;

use App\Entities\Product;
use App\Repository\ProductRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register Product Repository
     */
    public function register()
    {
        $this->app->bind(ProductRepository::class, function(){
            return new ProductRepository(
                \EntityManager::getRepository(Product::class)
            );
        });
    }
}
