<?php

namespace App\Http\Controllers\Admin;

use App\Video;
use Illuminate\Http\Request;
use App\Traits\ImageUploadTrait;
use App\Traits\VideoUploadTrait;
use App\Http\Controllers\Controller;

class VideosController extends Controller
{
    use ImageUploadTrait, VideoUploadTrait;
    
    public function __construct()
    {
        $this->middleware('role:administrator');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.videos.index', [
            'videos' => Video::latest()->paginate(10)
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return 'create';
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'video' => 'required|mimes:mp4|max:10000', // max 10000KB = 10MB
            'poster' => 'image|dimensions:min_width=320,min_height=180|mimes:jpeg,jpg,png,bmp|max:10000',
        ]);

        $video = auth()->user()->videos()->create($request->all());

        if (request()->hasFile('poster')) {
            $this->uploadImage(request()->file('poster'), 'videos');
            $video->galleries()->create(request()->all());
        }

        $this->uploadVideo(request()->file('video'), 'videos');
        $video->galleries()->create(request()->all());

        flash('success', "New video added successfully.");
        return back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Video $video)
    {
        return view('admin.videos.ajax.show', compact('video'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Video $video)
    {
        $video->update(request()->all());
        return 'Video has been updated successfully.';
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Video $video)
    {
        $this->validate($request, [
            'video' => 'mimes:mp4|max:10000', // max 10000KB = 10MB
            'poster' => 'image|dimensions:min_width=320,min_height=180|mimes:jpeg,jpg,png,bmp|max:10000',
        ]);

        if (request()->hasFile('poster')) {
            $this->uploadImage(request()->file('poster'), 'videos');
            $this->deleteImage($video->getPoster(), 'videos');
            optional($video->getFile('image'))->delete();
            $video->gallery()->create(request()->all());
        }

        if (request()->hasFile('video')) {
            $this->uploadVideo(request()->file('video'), 'videos');
            $this->deleteVideo($video->getVideo(), 'videos');
            optional($video->getFile('video'))->delete();
            $video->gallery()->create(request()->all());
        }

        $video->update($request->all());
        flash('success', "Video has been updated successfully.");
        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Video $video)
    {
        if ($video->delete()) {
            foreach ($video->galleries()->get() as $file) {
                $this->deleteImage(@$file->filename, 'videos');
            }
            @$video->galleries()->delete();
        }
        flash('success', "Video has been deleted successfully.");
        return back();
    }
}
