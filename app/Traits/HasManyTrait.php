<?php

namespace App\Traits;

trait HasManyTrait {

    public function industries()
    {
        return $this->hasMany('App\Industry');
    }

    public function tags()
    {
        return $this->hasMany('App\Tag');
    }

    public function locationtags()
    {
        return $this->hasMany('App\LocationTag');
    }

    public function d3searches()
    {
        return $this->hasMany('App\D3Search');
    }

}