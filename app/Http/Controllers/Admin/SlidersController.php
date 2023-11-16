<?php

namespace App\Http\Controllers\Admin;

use App\Slider;
use Illuminate\Http\Request;
use App\Traits\ImageUploadTrait;
use App\Http\Controllers\Controller;

class SlidersController extends Controller
{
    use ImageUploadTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.sliders.index', [
            'sliders' => Slider::latest()->paginate(10)
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
            'type' => 'required|integer|unique:sliders',
            'name' => 'required|string|min:3',
        ]);
        auth()->user()->sliders()->create($request->all());
        flash('success', "New slider added successfully.");
        return back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Slider $slider)
    {
        return view('admin.sliders.ajax.show', compact('slider'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Slider $slider)
    {
        $slider->update(request()->all());
        return 'Slider has been updated successfully.';
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Slider $slider)
    {
        $this->validate($request, [
            'type' => 'required|integer|unique:sliders,type,'.$slider->type,
            'name' => 'required|string|min:3',
        ]);
        $slider->update($request->all());
        flash('success', "Slider has been updated successfully.");
        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Slider $slider)
    {
        foreach ($slider->slides()->get() as $slide) {
            $this->deleteImage($slide->gallery->filename, 'sliders');
            $slide->gallery()->delete();
        }
        $slider->delete();
        flash('success', "Slider has been deleted successfully.");
        return back();
    }

    public function sliderSlides(Slider $slider)
    {
        return view('admin.sliders.slides', [
            'slider' => $slider,
            'slides' => $slider->slides()->paginate(10)
        ]);
    }
}
