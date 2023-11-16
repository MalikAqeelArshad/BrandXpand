<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MetaTag extends Model
{
    protected $table = 'meta_tags';
    protected $fillable = ['slug', 'title', 'description', 'keywords', 'author'];

    // public function getRouteKeyName() {
    //     return 'slug';
    // }
    
    // protected static function boot() {
    // 	parent::boot();

    // 	static::creating(function ($metaTag) {
    // 		$metaTag->slug = Str::slug($metaTag->title);
    // 	});
    // }
    
    public function setSlugAttribute($value){
        $this->attributes['slug'] = preg_replace('#[ -]+#', '-', strtolower($value));
        // $this->attributes['slug'] = str_slug($value);
    }
    
    // public function getSlugAttribute($value){
    //     return str_slug($value);
    // }
}
