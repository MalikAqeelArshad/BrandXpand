<?php

namespace App;

use App\Traits\MorphTrait;
use App\Traits\ImageUploadTrait;
use Illuminate\Database\Eloquent\Model;

class Slide extends Model
{
    use MorphTrait, ImageUploadTrait;
	protected $table = 'slides';
    protected $fillable = ['user_id', 'slider_id', 'url', 'name', 'description', 'publish'];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function slider()
    {
        return $this->belongsTo('App\Slider');
    }
}
