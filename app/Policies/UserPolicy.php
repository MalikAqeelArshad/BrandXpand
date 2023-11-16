<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;
    
    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        // dd('viewAny policy');
        return $user->hasRole(['administrator', 'admin']);
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\User  $user
     * @param  \App\User  $model
     * @return mixed
     */
    public function view(User $user, User $model)
    {
        // dd('view policy');
        return $user->hasRole(['administrator', 'admin']);
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        // dd('create policy');
        return $user->hasRole(['administrator', 'admin']);
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\User  $user
     * @param  \App\User  $model
     * @return mixed
     */
    public function update(User $user, User $model)
    {
        // dd('update policy');
        if (request('role_id')) {
            if ($user->role_id == $model->role_id) {
                request()['role_id'] = $user->role_id;
            } else if ($user->hasRole('administrator') && !$model->hasRole('administrator')) {
                request()['role_id'] = request('role_id');
            } else if ($user->hasRole('admin')) {
                if ($model->hasRole(['administrator', 'admin'])) {
                    request()['role_id'] = $model->role_id;
                } else {
                    if ($roleCheck = User::whereRoleId(request('role_id'))->first()) {
                        request()['role_id'] = $roleCheck->hasRole(['administrator', 'admin']) ? $model->role_id : request('role_id');
                    }
                }
            }
        }
        return $user->hasRole(['administrator', 'admin']) || $user->id == $model->id;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\User  $model
     * @return mixed
     */
    public function delete(User $user, User $model)
    {
        dd('delete policy');
        // return $user->hasRole(['administrator', 'admin']);
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\User  $user
     * @param  \App\User  $model
     * @return mixed
     */
    public function restore(User $user, User $model)
    {
        // dd('restore policy');
        return $user->hasRole(['administrator', 'admin']);
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\User  $model
     * @return mixed
     */
    public function forceDelete(User $user, User $model)
    {
        dd('forceDelete policy');
        // return auth()->user()->hasRole('administrator');
    }

    
    public function trashed(User $user)
    {
        // dd('trashed policy');
        return $user->hasRole(['administrator', 'admin']);
    }
    public function dropped(User $user)
    {
        // dd('dropped policy');
        return $user->hasRole('administrator');
    }
}
