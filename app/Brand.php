<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    protected $table = 'brands';
    protected $fillable = ['user_id', 'name', 'description'];

    // custom query scope
    public function scopeAllByRole($query)
    {
        return auth()->user()->hasRole(['administrator', 'admin']) ? $query : $query->whereUserId(auth()->id());
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function products()
    {
        return $this->hasMany('App\Product');
    }
}
