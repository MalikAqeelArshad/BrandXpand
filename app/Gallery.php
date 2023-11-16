<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
    protected $table = 'galleries';
    protected $fillable = ['user_id', 'galleryable_type', 'galleryable_id', 'filename', 'filetype', 'filesize', 'publish'];

    public static function boot()
    {
        parent::boot();
        static::creating(function ($gallery) {
            $gallery->user_id = auth()->id();
        });
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

	/**
     * Get all of the owning gallery models.
     */
    public function galleryable()
    {
    	return $this->morphTo('galleryable');
    }
}
