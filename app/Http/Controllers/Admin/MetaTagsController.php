<?php

namespace App\Http\Controllers\Admin;

use App\MetaTag;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MetaTagsController extends Controller
{
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
        return view('admin.meta-tags.index', [
            'metatags' => MetaTag::latest()->paginate(10)
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
        $attributes = $this->validate($request, [
            'slug' => 'required|string|min:1|unique:meta_tags',
            'title' => 'required|string|min:3',
            'description' => 'required|string|min:3',
            'keywords' => 'required|string|min:3',
        ]);

        MetaTag::create($attributes);

        flash('success', "New meta tag added successfully.");
        return back();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\MetaTag  $metaTag
     * @return \Illuminate\Http\Response
     */
    public function show(MetaTag $metaTag)
    {
        return view('admin.meta-tags.ajax.show', compact('metaTag'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\MetaTag  $metaTag
     * @return \Illuminate\Http\Response
     */
    public function edit(MetaTag $metaTag)
    {
        return 'edit';
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\MetaTag  $metaTag
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, MetaTag $metaTag)
    {
        $attributes = $this->validate($request, [
            'slug' => 'required|string|min:1|unique:meta_tags,slug,'.$metaTag->id,
            'title' => 'required|string|min:3',
            'description' => 'required|string|min:3',
            'keywords' => 'required|string|min:3',
        ]);

        $metaTag->update($attributes);

        flash('success', "Meta tag has been updated successfully.");
        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\MetaTag  $metaTag
     * @return \Illuminate\Http\Response
     */
    public function destroy(MetaTag $metaTag)
    {
        $metaTag->delete();
        flash('success', "MetaTag has been deleted successfully.");
        return back();
    }
}
