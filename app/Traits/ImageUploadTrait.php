<?php

namespace App\Traits;

use Image;

trait ImageUploadTrait {
    /**
    * Image upload trait used in controllers to upload files
    */
    public function uploadImage($file, $folder = 'images')
    {
        $path = public_path("uploads/{$folder}/");
        $small = public_path("uploads/{$folder}/small/");
        $large = public_path("uploads/{$folder}/large/");

        /*
        if(! \File::isDirectory($path) )
        {
            \File::makeDirectory($path, 0777, true, true);
            \File::makeDirectory($path .'/thumb', 0777, true, true);
        }
        */

        // request()['user_id'] = auth()->id() ?: 1;
        request()['filesize'] = $size = $file->getSize();
        request()['filetype'] = $type = strtolower($file->getClientOriginalExtension());
        request()['filename'] = $filename = uniqid(time()) .'.'. $type; // $file->getClientOriginalName();

        Image::make($file)->save($path . $filename);
        Image::make($file)->fit(300,350)->save($large . $filename);
        Image::make($file)->fit(96)->save($small . $filename);
    }

    public function deleteImage($filename = null, $folder = 'images')
    {
        $this->deleteFile($filename, $folder);
        if(file_exists(public_path("uploads/{$folder}/small/") . $filename))
        {
            @unlink(public_path("uploads/{$folder}/small/") . $filename);
        }
        if(file_exists(public_path("uploads/{$folder}/large/") . $filename))
        {
            @unlink(public_path("uploads/{$folder}/large/") . $filename);
        }
    }

    public function deleteFile($filename = null, $folder = 'images')
    {
        if(file_exists(public_path("uploads/{$folder}/") . $filename))
        {
            @unlink(public_path("uploads/{$folder}/") . $filename);
        }
    }

    public function galleryPhotos($thumbnail = false, $folder = null)
    {
        $folder = $folder ?: str_slug($this->table);
        return $this->gallery()->count() && $this->gallery->filename ? $thumbnail 
                ? asset("uploads/{$folder}/".$thumbnail."/" . $this->gallery->filename) 
                : asset("uploads/{$folder}/" . $this->gallery->filename) : false;
    }
}