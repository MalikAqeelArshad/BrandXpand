<?php

namespace App\Http\Controllers;

use App\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function productDetail($id)
    {
        $product = Product::wherePublish(true)->findOrFail($id);

        $relatedProducts = Product::whereHas('user', function($q){
            $q->whereNull('deleted_at');
        })->whereCategoryId($product->category_id)->wherePublish(true)->orderBy('id','desc')->take(8)->get()->except($product->id);

        return view('product.detail', compact('product', 'relatedProducts'));
    }
}
