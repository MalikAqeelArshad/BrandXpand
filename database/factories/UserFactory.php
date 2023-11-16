<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(App\User::class, function (Faker $faker) {
    return [
        'role_id' => $faker->randomElement([null, App\Role::inRandomOrder()->first()]),
        'email' => $faker->unique()->safeEmail,
        'email_verified_at' => $faker->randomElement([$faker->dateTime(), null]),
        'password' => 'secret', // $2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm
        'remember_token' => str_random(10),
        'deleted_at' => null,
    ];
});

$factory->define(App\Profile::class, function (Faker $faker) {
    return [
        'first_name' => $faker->firstName,
        'last_name' => $faker->lastName,
        'gender' => $faker->numberBetween($min = 0, $max = 1),
        'dob' => $faker->date($format = 'Y-m-d', $max = 'now'),
        'mobile' => $faker->phoneNumber,
        'phone' => $faker->e164PhoneNumber,
        'about' => $faker->paragraph,
    ];
});

$factory->define(App\Category::class, function (Faker $faker) {
    return [
        'user_id' => $faker->randomElement(App\User::pluck('id')),
        'name' => $faker->jobTitle,
        'description' => $faker->sentence,
    ];
});

$factory->define(App\SubCategory::class, function (Faker $faker) {
    return [
        'user_id' => $faker->randomElement(App\User::pluck('id')),
        // 'category_id' => $faker->randomElement(App\Category::pluck('id')),
        'name' => $faker->jobTitle,
        'description' => $faker->sentence,
    ];
});

$factory->define(App\Coupon::class, function (Faker $faker) {
    return [
        // 'user_id' => $faker->randomElement([null, App\User::inRandomOrder()->first()]),
        'user_id' => $faker->unique()->randomElement(App\User::pluck('id')),
        'code' => str_pad(mt_rand(1,99999999), 12, 0, STR_PAD_LEFT),
        'name' => $faker->jobTitle,
        'discount' => rand(10, 100),
        'expiry_date' => $faker->dateTimeBetween($startDate = 'now', $endDate = '+'.rand(1,5).' years'),
        'status' => $faker->randomElement([true, false]),
    ];
});

$factory->define(App\Product::class, function (Faker $faker) {
    $id = $faker->randomElement(App\Category::pluck('id'));
    $category = App\Category::whereId($id)->first();
    $sub_category = $category->subCategories()->inRandomOrder()->first();

    return [
        'user_id' => $faker->randomElement(App\User::pluck('id')),
        'category_id' => $category->id,
        'sub_category_id' => $sub_category->id,
        'brand_id' => $faker->randomElement(App\Brand::pluck('id')),
        'code' => str_pad(mt_rand(1,99999999), 12, 0, STR_PAD_LEFT),
        'name' => $faker->jobTitle,
        'size' => $faker->randomElement(['Small','Medium','Large']),
        'color' => $faker->randomElement(['Red','Green','White']),
        'price' => rand(10, 100),
        'discount' => rand(10, 50),
        'stock' => rand(1, 7),
        'description' => $faker->sentence,
        'image' => null,
        'publish' => $faker->randomElement([true, false]),
    ];
});

$factory->define(App\ProductAttribute::class, function (Faker $faker) {
    return [
        'label' => $faker->randomElement(['Product Red','Product Green','Product White']),
        'size' => $faker->randomElement(['Small','Medium','Large']),
        'price' => rand(10, 100),
        'stock' => rand(1, 10),
        'discount' => rand(10, 50),
        'description' => $faker->sentence,
    ];
});

$factory->define(App\Address::class, function (Faker $faker) {
    $uid = App\User::inRandomOrder()->first()->getKey();
    $pid = App\Product::inRandomOrder()->first()->getKey();
    $addressable_type = $faker->randomElement(['App\User', 'App\Product']);
    $addressable_id = ($addressable_type === "App\User") ? $uid : $pid;

    return [
        'addressable_id' => $addressable_id,
        'addressable_type' => $addressable_type,
        'type' => $faker->randomElement(['home', 'office']),
        'address' => $faker->streetName,
        'city' => $faker->city,
        'state' => $faker->state,
        'country' => $faker->countryCode, //$faker->country
        'postcode' => $faker->postcode,
    ];
});

$factory->define(App\Gallery::class, function (Faker $faker) {
    $uid = App\User::inRandomOrder()->first()->getKey();
    $pid = App\Product::inRandomOrder()->first()->getKey();
    $galleryable_type = $faker->randomElement(['App\User', 'App\Product']);
    $galleryable_id = ($galleryable_type === "App\User") ? $uid : $pid;

    return [
        'user_id' => $uid,
        'galleryable_id' => $galleryable_id,
        'galleryable_type' => $galleryable_type,
        'filename' => $faker->imageUrl($width = 300, $height = 300),
        //'filetype' => $faker->fileExtension,
        'filetype' => $faker->randomElement(['jpg', 'png']),
        'filesize' => $faker->numberBetween($min = 100, $max = 1000),
        'publish' => $faker->randomElement([true, false]),
    ];
});
