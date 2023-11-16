<?php

namespace App\Traits;

trait HasRolesTrait {

    public function role()
    {
        return $this->belongsTo('App\Role');
    }

    public function roles()
    {
        return $this->belongsToMany('App\Role', 'App\User', 'id', 'role_id');
    }

    public function hasAnyRole()
    {
        return $this->roles()->count();
    }

    /**
    * @param string|array|intersect $role()
    */
    public function hasRole($role)
    {
        if (is_string($role)) {
            return $this->roles->contains('name', $role);
        }

        if (is_array($role)) {
            foreach ($role as $r) {
                if ($this->hasRole($r)) { return true; }
            }
            return false;
        }
        // if we're dealing in collections
        return !! $role->intersect($this->roles)->count();
    }

    public function assignRole($role)
    {
        return $this->roles()->save(
            \App\Role::whereName($role)->firstOrFail()
        );
    }

    // user query scope
    public function scopeWhereHasRole($query, $role)
    {
        $ids = \App\Role::whereIn('name', is_array($role) ? $role : [$role])->pluck('id');
        return $query->whereIn('role_id', $ids);
        // return $query->join('roles', 'roles.id', 'users.role_id')->whereIn('roles.name', is_array($role) ? $role : [$role]);
    }

    public function scopeWhereHasAnyRole($query)
    {
        return $query->whereIn('role_id', \App\Role::pluck('id'));
    }
}