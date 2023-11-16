<?php

namespace App\Policies;

use App\User;
use App\Coupon;
use Illuminate\Auth\Access\HandlesAuthorization;

class CouponPolicy
{
    use HandlesAuthorization;
    
    /**
     * Determine whether the user can view any coupons.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        // dd('viewAny policy');
        return $user->hasRole('administrator');
    }

    /**
     * Determine whether the user can view the coupon.
     *
     * @param  \App\User  $user
     * @param  \App\Coupon  $coupon
     * @return mixed
     */
    public function view(User $user, Coupon $coupon)
    {
        // dd('view policy');
        return $user->hasRole('administrator');
    }

    /**
     * Determine whether the user can create coupons.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        // dd('create policy');
        return $user->hasRole('administrator');
    }

    /**
     * Determine whether the user can update the coupon.
     *
     * @param  \App\User  $user
     * @param  \App\Coupon  $coupon
     * @return mixed
     */
    public function update(User $user, Coupon $coupon)
    {
        // dd('update policy');
        return $user->hasRole('administrator') || $user->id == $coupon->user_id;
    }

    /**
     * Determine whether the user can delete the coupon.
     *
     * @param  \App\User  $user
     * @param  \App\Coupon  $coupon
     * @return mixed
     */
    public function delete(User $user, Coupon $coupon)
    {
        // dd('delete policy');
        return $user->hasRole('administrator') || $user->id == $coupon->user_id;
    }

    /**
     * Determine whether the user can restore the coupon.
     *
     * @param  \App\User  $user
     * @param  \App\Coupon  $coupon
     * @return mixed
     */
    public function restore(User $user, Coupon $coupon)
    {
        dd('restore policy');
    }

    /**
     * Determine whether the user can permanently delete the coupon.
     *
     * @param  \App\User  $user
     * @param  \App\Coupon  $coupon
     * @return mixed
     */
    public function forceDelete(User $user, Coupon $coupon)
    {
        dd('forceDelete policy');
    }
}
