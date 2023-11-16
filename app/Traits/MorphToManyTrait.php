<?php

namespace App\Traits;

trait MorphToManyTrait {

    public function industries()
    {
        return $this->morphToMany('App\Industry', 'industrialable')->withTimestamps();
    }

    public function tags()
    {
        return $this->morphToMany('App\Tag', 'taggable')->withTimestamps();
    }

    public function locationtags()
    {
        return $this->morphToMany('App\LocationTag', 'locationable')->withTimestamps();
    }

    public function d3searches()
    {
        return $this->morphToMany('App\D3Search', 'd3_searchable')->withTimestamps();
    }

}