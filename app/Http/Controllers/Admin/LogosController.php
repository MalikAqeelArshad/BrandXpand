<?php

namespace App\Http\Controllers\Admin;

use App\Logo;
use Illuminate\Http\Request;
use App\Traits\ImageUploadTrait;
use App\Http\Controllers\Controller;

class LogosController extends Controller
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
        return view('admin.logos.index', [
            'logos' => Logo::latest()->paginate(10)
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
            'logo' => 'required|image|dimensions:ratio=3/3|mimes:jpeg,jpg,png,bmp|max:10000', // max 10000KB = 10MB
            // 'logo' => 'required|image|dimensions:width=150,height=150|mimes:jpeg,jpg,png,bmp|max:10000', // max 10000KB = 10MB
        ]);

        if (request()->hasFile('logo')) {
            $this->uploadImage(request()->file('logo'), 'logos');
        }
        auth()->user()->logos()->create($request->all())->galleries()->create(request()->all());
        flash('success', "New logo added successfully.");
        return back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Logo $logo)
    {
        return view('admin.logos.ajax.show', compact('logo'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Logo $logo)
    {
        $logo->update(request()->all());
        return 'Logo has been updated successfully.';
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Logo $logo)
    {
        $this->validate($request, [
            'logo' => 'image|dimensions:ratio=3/3|mimes:jpeg,jpg,png,bmp|max:10000', // max 10000KB = 10MB
            // 'logo' => 'image|dimensions:width=150,height=150|mimes:jpeg,jpg,png,bmp|max:10000', // max 10000KB = 10MB
        ]);

        if (request()->hasFile('logo')) {
            $this->uploadImage(request()->file('logo'), 'logos');
            $this->deleteImage(@$logo->gallery->filename, 'logos');
            @$logo->gallery()->delete();
            $logo->gallery()->create(request()->all());
        }
        $logo->update($request->all());
        flash('success', "Logo has been updated successfully.");
        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Logo $logo)
    {
        if ($logo->delete()) {
            $this->deleteImage(@$logo->gallery->filename, 'logos');
            @$logo->gallery()->delete();
        }
        flash('success', "Logo has been deleted successfully.");
        return back();
    }
}
