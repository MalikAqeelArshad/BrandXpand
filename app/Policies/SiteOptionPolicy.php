<?php

namespace App\Policies;

use App\User;
use App\SiteOption;
use Illuminate\Auth\Access\HandlesAuthorization;

class SiteOptionPolicy
{
    use HandlesAuthorization;
    
    /**
     * Determine whether the user can view any site options.
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
     * Determine whether the user can view the site option.
     *
     * @param  \App\User  $user
     * @param  \App\SiteOption  $siteOption
     * @return mixed
     */
    public function view(User $user, SiteOption $siteOption)
    {
        dd('view policy');
    }

    /**
     * Determine whether the user can create site options.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        dd('create policy');
    }

    /**
     * Determine whether the user can update the site option.
     *
     * @param  \App\User  $user
     * @param  \App\SiteOption  $siteOption
     * @return mixed
     */
    public function update(User $user, SiteOption $siteOption)
    {
        dd('update policy');
    }

    /**
     * Determine whether the user can delete the site option.
     *
     * @param  \App\User  $user
     * @param  \App\SiteOption  $siteOption
     * @return mixed
     */
    public function delete(User $user, SiteOption $siteOption)
    {
        dd('delete policy');
    }

    /**
     * Determine whether the user can restore the site option.
     *
     * @param  \App\User  $user
     * @param  \App\SiteOption  $siteOption
     * @return mixed
     */
    public function restore(User $user, SiteOption $siteOption)
    {
        dd('restore policy');
    }

    /**
     * Determine whether the user can permanently delete the site option.
     *
     * @param  \App\User  $user
     * @param  \App\SiteOption  $siteOption
     * @return mixed
     */
    public function forceDelete(User $user, SiteOption $siteOption)
    {
        dd('forceDelete policy');
    }
}
