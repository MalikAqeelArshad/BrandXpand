<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SubCategory extends Model
{
    protected $table = 'sub_categories';
    protected $fillable = ['user_id', 'category_id', 'name', 'description'];

    public function category()
    {
        return $this->belongsTo('App\Category');
    }
    
    public function products($publish = null)
    {
        return $publish ? $this->hasMany('App\Product')->wherePublish($publish) : $this->hasMany('App\Product');
    }
}
