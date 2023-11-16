<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    protected $table = 'profiles';
    protected $fillable = ['user_id', 'first_name', 'last_name', 'gender', 'dob', 'mobile', 'phone', 'about'];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function getFirstNameAttribute($value)
    {
        return ucfirst($value);
    }

    public function getFullNameAttribute()
    {
      return preg_replace('/\s+/', ' ',$this->first_name.' '.$this->last_name);
    }
}
