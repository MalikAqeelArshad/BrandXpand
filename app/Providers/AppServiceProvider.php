<?php

namespace App\Providers;

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

    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        \Schema::defaultStringLength(191);

        // @user('attr') directive can access the login user like @user('id')
        \Blade::directive('user', function ($attr) {
            return "<?php echo auth()->user()[{$attr}]; ?>";
        });

        // @role('admin') directive can access the login user role(s)
        // like @role('admin') or @role(['admin','editor'])
        \Blade::if('role', function ($role) {
            return auth()->check() && auth()->user()->hasRole($role);
        });
    }
}
