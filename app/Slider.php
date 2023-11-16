<?php

namespace App;

use App\Traits\ImageUploadTrait;
use Illuminate\Database\Eloquent\Model;

class Slider extends Model
{
    use ImageUploadTrait;
	protected $table = 'sliders';
    protected $fillable = ['user_id', 'type', 'name', 'description', 'publish'];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function slides()
    {
        return $this->hasMany('App\Slide');
    }
}
