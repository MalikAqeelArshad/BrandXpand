<?php

namespace App\Http\Controllers\Admin;

use App\User;
use App\Product;
use App\ProductStock;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProductStocksController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.stocks.index', [
            'products' => Product::allByRole()->get(),
            'filterProducts' => Product::allByRole()->filters()->paginate(__take(10))
            // 'stocks' => ProductStock::whereIn('product_id', Product::allByRole()->pluck('id'))->filters()->paginate(10)
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
        return 'store';
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\ProductStock  $stock
     * @return \Illuminate\Http\Response
     */
    public function show(ProductStock $stock)
    {
        return view('admin.stocks.ajax.show', compact('stock'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\ProductStock  $stock
     * @return \Illuminate\Http\Response
     */
    public function edit(ProductStock $stock)
    {
        return 'edit';
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\ProductStock  $stock
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ProductStock $stock)
    {
        // return $stock;
        $oldAttrs = strtolower($stock->attrs);
        $request['attrs'] = strtolower(request('attrs'));
        $this->validate(request(), ['attrs' => 'required']);
        // return $stock;
        if (request('product')) {
            ProductStock::where(['product_id'=>request('product'),'attrs'=>$oldAttrs])->update(['attrs'=>request('attrs')]);
            $attrs = strtolower($stock->fresh()->attrs);
            $this->validate(request(), ['sale' => 'required|numeric|gt:0']);
            ProductStock::where(['product_id'=>request('product'),'attrs'=>$attrs])->whereStatus('unsold')->update(['sale'=>request('sale'), 'discount'=>request('discount')]);
            if (request('changeStock')) {
                $this->validate(request(), ['purchase' => 'required|numeric|gt:0','date' => 'required']);
                ProductStock::where(['product_id'=>request('product'),'attrs'=>$attrs])
                ->whereStatus('unsold')->wherePurchase(request('price'))
                ->whereDate('created_at', request('date'))->update(['purchase'=>request('purchase')]);
            }
            if (request('deleteStock')) {
                $this->validate(request(), ['stock' => 'required|numeric|gt:0','delete_date' => 'required']);
                ProductStock::where(['product_id'=>request('product'),'attrs'=>$attrs])
                ->whereStatus('unsold')->whereDate('created_at', request('delete_date'))
                ->limit(request('stock'))->delete();
            }
            if ($product = Product::where(['id'=>request('product'), 'attrs'=>$oldAttrs])) {
                $product->update(['attrs'=>$attrs, 'sale'=>$stock->fresh()->sale ?? $stock->sale, 'discount'=>$stock->fresh()->discount ?? $stock->discount]);
            }
        } else {
            $stock->update($request->all());
        }
        flash('success', "Stock has been updated successfully.");
        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\ProductStock  $stock
     * @return \Illuminate\Http\Response
     */
    public function destroy(ProductStock $stock)
    {
        if (request('product')) {
            $stock->where(['product_id'=>request('product'),'attrs'=>$stock->attrs,'status'=>'unsold'])->delete();
            $product = Product::find(request('product'));
            $lastStock = $product->lastStock('unsold', request('attrs'));
            $product->update(['attrs'=>$lastStock->attrs, 'sale'=>$lastStock->sale, 'discount'=>$lastStock->discount]);
        } else {
            $stock->delete();
        }
        flash('success', "Stock has been deleted successfully.");
        return back();
    }

    public function stocksProfit()
    {
        $proByRole = Product::allByRole();
        $productIds = ProductStock::whereIn('product_id', $proByRole->pluck('id'))->filters()->pluck('product_id');
        // return $productIds;
        return view('admin.stocks.profit', [
            'users' => User::whereHasAnyRole()->get(),
            'productsByRole' => $proByRole->get(),
            'products' => $proByRole->userId(request('user'))->whereIn('id', $productIds ?? $proByRole->pluck('id'))->paginate(__take(10)),
        ]);
        
    }
}
