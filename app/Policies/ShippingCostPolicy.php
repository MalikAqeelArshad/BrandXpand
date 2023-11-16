<?php

namespace App\Policies;

use App\User;
use App\ShippingCost;
use Illuminate\Auth\Access\HandlesAuthorization;

class ShippingCostPolicy
{
    use HandlesAuthorization;
    
    /**
     * Determine whether the user can view any shipping costs.
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
     * Determine whether the user can view the shipping cost.
     *
     * @param  \App\User  $user
     * @param  \App\ShippingCost  $shippingCost
     * @return mixed
     */
    public function view(User $user, ShippingCost $shippingCost)
    {
        // dd('view policy');
        return $user->hasRole('administrator');
    }

    /**
     * Determine whether the user can create shipping costs.
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
     * Determine whether the user can update the shipping cost.
     *
     * @param  \App\User  $user
     * @param  \App\ShippingCost  $shippingCost
     * @return mixed
     */
    public function update(User $user, ShippingCost $shippingCost)
    {
        // dd('update policy');
        return $user->hasRole('administrator');
    }

    /**
     * Determine whether the user can delete the shipping cost.
     *
     * @param  \App\User  $user
     * @param  \App\ShippingCost  $shippingCost
     * @return mixed
     */
    public function delete(User $user, ShippingCost $shippingCost)
    {
        // dd('delete policy');
        return $user->hasRole('administrator');
    }

    /**
     * Determine whether the user can restore the shipping cost.
     *
     * @param  \App\User  $user
     * @param  \App\ShippingCost  $shippingCost
     * @return mixed
     */
    public function restore(User $user, ShippingCost $shippingCost)
    {
        dd('restore policy');
    }

    /**
     * Determine whether the user can permanently delete the shipping cost.
     *
     * @param  \App\User  $user
     * @param  \App\ShippingCost  $shippingCost
     * @return mixed
     */
    public function forceDelete(User $user, ShippingCost $shippingCost)
    {
        dd('forceDelete policy');
    }
}
