<?php

namespace App\Policies;

use App\User;
use App\Brand;
use Illuminate\Auth\Access\HandlesAuthorization;

class BrandPolicy
{
    use HandlesAuthorization;
    
    /**
     * Determine whether the user can view any brands.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        // dd('viewAny policy');
        return $user->hasAnyRole();
    }

    /**
     * Determine whether the user can view the brand.
     *
     * @param  \App\User  $user
     * @param  \App\Brand  $brand
     * @return mixed
     */
    public function view(User $user, Brand $brand)
    {
        // dd('view policy');
        return $user->hasAnyRole();
    }

    /**
     * Determine whether the user can create brands.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        // dd('create policy');
        return $user->hasAnyRole();
    }

    /**
     * Determine whether the user can update the brand.
     *
     * @param  \App\User  $user
     * @param  \App\Brand  $brand
     * @return mixed
     */
    public function update(User $user, Brand $brand)
    {
        // dd('update policy');
        return $user->hasRole(['administrator', 'admin']) || $user->id == $brand->user_id;
    }

    /**
     * Determine whether the user can delete the brand.
     *
     * @param  \App\User  $user
     * @param  \App\Brand  $brand
     * @return mixed
     */
    public function delete(User $user, Brand $brand)
    {
        // dd('delete policy');
        return $user->hasRole(['administrator', 'admin']) || $user->id == $brand->user_id;
    }

    /**
     * Determine whether the user can restore the brand.
     *
     * @param  \App\User  $user
     * @param  \App\Brand  $brand
     * @return mixed
     */
    public function restore(User $user, Brand $brand)
    {
        dd('restore policy');
    }

    /**
     * Determine whether the user can permanently delete the brand.
     *
     * @param  \App\User  $user
     * @param  \App\Brand  $brand
     * @return mixed
     */
    public function forceDelete(User $user, Brand $brand)
    {
        dd('forceDelete policy');
    }
}
