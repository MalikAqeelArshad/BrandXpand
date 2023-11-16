<?php

namespace App\Traits;

trait MorphTrait {

    public function address()
    {
        return $this->morphOne('App\Address', 'addressable');
    }

    public function addresses()
    {
        return $this->morphMany('App\Address', 'addressable');
    }

    public function gallery()
    {
        return $this->morphOne('App\Gallery', 'galleryable');
    }

    public function galleries()
    {
        return $this->morphMany('App\Gallery', 'galleryable');
    }
}