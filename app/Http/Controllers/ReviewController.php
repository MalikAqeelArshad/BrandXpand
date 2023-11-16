<?php

namespace App\Http\Controllers;

use App\Order;
use App\Review;
use Illuminate\Http\Request;
use Auth;

class ReviewController extends Controller
{
    public function show($id)
    {
        $orders  = Order::where('reference_number',$id)->get();
        return view('orders.ajax.review',compact('orders'));
    }

    public function save(Request $request)
    {
       $this->validate($request,[
           'rating' => 'required|numeric',
           'order_id' => 'required',
       ]);
       $order = Order::findOrFail($request->order_id);

       $review = Review::create(array_merge($request->all() + ['user_id' => Auth::id(),'product_id' => $order->product_id]));

       return json_encode($review);

    }
}
