<?php

namespace App\Http\Controllers\Admin;

use App\Slide;
use Illuminate\Http\Request;
use App\Traits\ImageUploadTrait;
use App\Http\Controllers\Controller;

class SlidesController extends Controller
{
    use ImageUploadTrait;
    
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
        // return 'index';
        return view('admin.slides.index', [
            'slides' => Slide::latest()->paginate(10)
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
            'slider_id' => 'required|integer',
            'slide' => 'required|image|dimensions:min_width=1366,min_height=450|mimes:jpeg,jpg,png,bmp|max:10000', // max 10000kb
        ]);

        if (request()->hasFile('slide')) {
            $this->uploadImage(request()->file('slide'), 'sliders');
        }
        auth()->user()->slides()->create($request->all())->galleries()->create(request()->all());
        flash('success', "New slide added successfully.");
        return back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Slide $slide)
    {
        return view('admin.slides.ajax.show', compact('slide'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Slide $slide)
    {
        $slide->update(request()->all());
        return 'Slide has been updated successfully.';
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Slide $slide)
    {
        $this->validate($request, [
            'slider_id' => 'required|integer',
            'slide' => 'image|dimensions:min_width=1366,min_height=450|mimes:jpeg,jpg,png,bmp|max:10000', // max 10000kb
        ]);

        if (request()->hasFile('slide')) {
            $this->uploadImage(request()->file('slide'), 'sliders');
            $this->deleteImage(@$slide->gallery->filename, 'sliders');
            @$slide->gallery()->delete();
            $slide->gallery()->create(request()->all());
        }
        $slide->update($request->all());
        flash('success', "Slide has been updated successfully.");
        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Slide $slide)
    {
        if ($slide->delete()) {
            $this->deleteImage(@$slide->gallery->filename, 'sliders');
            @$slide->gallery()->delete();
        }
        flash('success', "Slide has been deleted successfully.");
        return back();
    }
}
