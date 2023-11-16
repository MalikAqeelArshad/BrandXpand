<?php

namespace App;

use App\Traits\MorphTrait;
use App\Traits\ImageUploadTrait;
use App\Traits\VideoUploadTrait;
use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    use MorphTrait, ImageUploadTrait, VideoUploadTrait;
	protected $table = 'videos';
    protected $fillable = ['user_id', 'url', 'title', 'description', 'publish'];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function getFile($filetype = null)
    {
        switch ($filetype) {
            case 'image':
            return $this->galleries()->whereIn('filetype', ['jpeg','jpg','png','bmp'])->first();
            break;
            case 'video':
            return $this->galleries()->whereIn('filetype', ['mp4','ogg','webm','avi'])->first();
            break;
            default:
            return $this->galleries()->first();
            break;
        }
    }

    public function getVideo()
    {
        return @$this->getFile('video')->filename;
    }

    public function getPoster()
    {
        return @$this->getFile('image')->filename;
    }
}
