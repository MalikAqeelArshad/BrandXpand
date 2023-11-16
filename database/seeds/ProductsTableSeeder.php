<?php

use Illuminate\Database\Seeder;

class ProductsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	// factory(App\Product::class, 50)->create();
    	factory(App\Product::class, 50)->create()
    	->each(function ($product) {
    		// $product->attributes()->save(factory(App\ProductAttribute::class)->make());
            $product->attributes()->saveMany(factory(App\ProductAttribute::class, rand(1,5))->make());
    	});
    }
}
