<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\User' => 'App\Policies\UserPolicy',
        'App\Brand' => 'App\Policies\BrandPolicy',
        'App\Coupon' => 'App\Policies\CouponPolicy',
        'App\Product' => 'App\Policies\ProductPolicy',
        'App\SiteOption' => 'App\Policies\SiteOptionPolicy',
        'App\ShippingCost' => 'App\Policies\ShippingCostPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        // \Gate::define('destroy-user', 'App\Policies\UserPolicy@destroy');
    }
}
