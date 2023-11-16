<?php

namespace App\Http\Controllers\Admin;

use App\ShippingCost;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ShippingCostsController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(ShippingCost::class);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('viewAny', ShippingCost::class);
        return view('admin.shipping-costs.index', [
            'shippingCosts' => ShippingCost::latest()->paginate(10)
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
            'state' => 'required|string|unique:shipping_costs,state,'.auth()->id(),
            /*'charges' => 'required|numeric',*/
        ]);
        auth()->user()->shippingCosts()->create($request->all());
        flash('success', "New shipping cost added successfully.");
        return back();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\ShippingCost  $shippingCost
     * @return \Illuminate\Http\Response
     */
    public function show(ShippingCost $shippingCost)
    {
        return view('admin.shipping-costs.ajax.show', compact('shippingCost'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\ShippingCost  $shippingCost
     * @return \Illuminate\Http\Response
     */
    public function edit(ShippingCost $shippingCost)
    {
        return 'edit';
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\ShippingCost  $shippingCost
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ShippingCost $shippingCost)
    {
        $this->validate($request, [
            'state' => 'required|string',
            'charges' => 'required|numeric',
        ]);
        $shippingCost->update($request->all());
        flash('success', "Shipping cost has been updated successfully.");
        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\ShippingCost  $shippingCost
     * @return \Illuminate\Http\Response
     */
    public function destroy(ShippingCost $shippingCost)
    {
        $shippingCost->delete();
        flash('success', "Shipping cost has been deleted successfully.");
        return back();
    }
}
