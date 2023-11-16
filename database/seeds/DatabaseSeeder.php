<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $roles = array
        (
            ['name' => 'administrator', 'description' => 'Administrator'],
            ['name' => 'admin', 'description' => 'Admin'],
            ['name' => 'vendor', 'description' => 'Vendor']
        );
        foreach ($roles as $role) {
            App\Role::create($role);
        }
        
        $users = array
        (
            ['role_id' => '1', 'email' => 'administrator@brandxpend.com', 'password' => 'secret', 'email_verified_at' => now()],
            ['role_id' => '2', 'email' => 'admin@brandxpend.com', 'password' => 'secret', 'email_verified_at' => now()],
            ['role_id' => '3', 'email' => 'vendor@brandxpend.com', 'password' => 'secret', 'email_verified_at' => now()],
            ['role_id' => null, 'email' => 'public@brandxpend.com', 'password' => 'secret', 'email_verified_at' => null]
        );
        foreach ($users as $user) {
            App\User::create($user);
        }

        $this->call([
            // UsersTableSeeder::class,
            // CategoriesTableSeeder::class,
            // CouponsTableSeeder::class,
            // ProductsTableSeeder::class,
            // AddressesTableSeeder::class,
            // GalleriesTableSeeder::class,
        ]);
    }
}
