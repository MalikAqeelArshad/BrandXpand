<?php

namespace App\Http\Controllers\Admin;

use App\User;
use App\Product;
use App\ProductStock;
use Illuminate\Http\Request;
use App\Traits\ImageUploadTrait;
use App\Http\Controllers\Controller;

class ProductsController extends Controller
{
    use ImageUploadTrait;
    public function __construct()
    {
        $this->authorizeResource(Product::class);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('viewAny', Product::class);
        return view('admin.products.index', [
            'productsByRole' => Product::allByRole()->get(),
            'products' => Product::allByRole()->filters()->paginate(__take(10))
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
        // return $request->all();
        $this->validate($request, [
            'category_id' => 'required|integer',
            'sub_category_id' => 'nullable|integer',
            'name' => 'required|string|min:3',
            'purchase' => 'required|numeric|gt:0',
            'sale' => 'required|numeric|gt:0',
            'stock' => 'required|integer|gt:0',
            'logo' => 'required|image|mimes:jpeg,jpg,png,bmp|max:10000', // max 10000KB = 10MB
        ]);

        if (request()->hasFile('logo')) {
            $this->uploadImage(request()->file('logo'), 'products');
            $request['image'] = request('filename');
        }

        auth()->user()->products()->create($request->all())->addStock();

        flash('success', "New product added successfully.");
        return back();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        if (request()->ajax()) {
            return view('admin.products.ajax.show', compact('product'));
        }
        if ($notification = auth()->user()->notifications()->find(request('notification_id'))) {
            $notification->markAsRead();
        }
        return view('admin.products.show', compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        $product->update(request()->all());
        return 'Product has been updated successfully.';
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        // return request()->all();
        $this->validate($request, [
            'category_id' => 'required|integer',
            'sub_category_id' => 'nullable|integer',
            'name' => 'required|string|min:3',
            'sale' => 'required|numeric|gt:0',
            // 'logo' => 'required|image|mimes:jpeg,jpg,png,bmp|max:10000', // max 10000KB = 10MB
        ]);

        if (request()->hasFile('logo')) {
            $this->uploadImage(request()->file('logo'), 'products');
            $this->deleteImage(@$product->image, 'products');
            $request['image'] = request('filename');
        }

        $request['shipping_cost'] = request('shipping_cost') ? 1 : 0;

        $product->update($request->all()); $product->updateStock();
        flash('success', "Product has been updated successfully.");
        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        if ($product->delete()) {
            foreach ($product->galleries()->get() as $image) {
                $this->deleteImage(@$image->filename, 'products');
            }
            @$product->galleries()->delete();
            $this->deleteImage(@$product->image, 'products');
        }
        flash('success', "Product has been deleted successfully.");
        return back();
    }

    public function productGalleries($id)
    {
        $product = Product::findOrFail($id);
        if (request()->isMethod('post') && request()->hasFile('file')) {
            $this->uploadImage(request()->file('file'), 'products');
            flash('success', "Product image has been added successfully.");
            $product->galleries()->create(request()->all());
            return back();
        }
        // return $product->galleries()->paginate(10);
        return request('json') ? $product->galleries : view('admin.products.galleries', [
            'product' => $product,
            'galleries' => $product->galleries()->paginate(10)
        ]);
    }

    public function productStocks(Request $request, $id)
    {
        $request['attrs'] = strtolower(request('attr') == 'attr1' ? request('attr1') : request('attr2')) ?: 'main';

        $product = Product::findOrFail($id);
        // return $product->stocks('unsold')->dd();
        // return $product->stocks('unsold')->pluck('attrs')->unique();
        if (request()->isMethod('post')) {
            $this->validate(request(), [
                'purchase' => 'required|numeric|gt:0',
                'sale' => 'required|numeric|gt:0',
                'stock' => 'required|integer|gt:0',
            ]);

            if (strtolower($product->attrs) == request('attrs')) { $product->update(request()->all()); }
            
            $product->addStock();
            flash('success', "New stock added successfully.");
            return back();
        }

        return view('admin.products.stocks', compact('product'));
    }
}
