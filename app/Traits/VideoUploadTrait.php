<?php

namespace App\Traits;

trait VideoUploadTrait {
    /**
    * Video upload trait used in controllers to upload files
    */
    public function uploadVideo($file, $folder = 'videos')
    {
        $path = public_path("uploads/{$folder}/");

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

        $file->move($path, request('filename'));
    }

    public function deleteVideo($filename = null, $folder = 'videos')
    {
        if(file_exists(public_path("uploads/{$folder}/") . $filename))
        {
            @unlink(public_path("uploads/{$folder}/") . $filename);
        }
    }
}