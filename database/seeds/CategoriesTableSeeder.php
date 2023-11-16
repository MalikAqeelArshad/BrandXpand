<?php

use Illuminate\Database\Seeder;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	factory(App\Category::class, 5)->create()
        ->each(function ($category) {
            $category->subCategories()->saveMany(factory(App\SubCategory::class, rand(1,5))->make());
        });
    }
}
