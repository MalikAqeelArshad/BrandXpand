<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'categories';
    protected $fillable = ['user_id', 'name', 'description'];

    public function subCategories()
    {
        return $this->hasMany('App\SubCategory');
    }
    
    public function products($publish = null)
    {
        return $publish ? $this->hasMany('App\Product')->wherePublish($publish) : $this->hasMany('App\Product');
    }
}
