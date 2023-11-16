<?php

namespace App;

use App\Traits\MorphTrait;
use App\Traits\ImageUploadTrait;
use Illuminate\Database\Eloquent\Model;

class Logo extends Model
{
	use MorphTrait, ImageUploadTrait;
	protected $table = 'logos';
	protected $fillable = ['user_id', 'url', 'name', 'description', 'publish'];

	public function user()
	{
		return $this->belongsTo('App\User');
	}
}
