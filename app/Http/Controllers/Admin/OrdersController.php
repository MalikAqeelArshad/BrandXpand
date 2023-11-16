<?php

namespace App\Http\Controllers\Admin;

use Auth;
use App\Order;
use App\Product;
use Carbon\Carbon;
use App\ProductStock;
use Illuminate\Http\Request;
use App\Exports\OrdersExport;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;


class OrdersController extends Controller
{

    public function index()
    {
        // if(Auth::user()->role_id == 3) {
        //     $orders = Order::whereIn('product_id',Product::allByRole()->pluck('id'))->latest()->status(request('status'))->paginate(15);
        // } else {
        //     $orders = Order::latest()->paginate(15);
        // }

        $orders = Order::whereIn('product_id',Product::allByRole()->pluck('id'))->latest()->status(request('status'))->paginate(15);
        return view('admin.orders.index', compact('orders'));
    }

    public function export()
    {
        return Excel::download(new OrdersExport, 'orders.xlsx');
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
     * @param  \App\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $orders = Order::whereReferenceNumber($id)->whereIn('product_id', Product::allByRole()->pluck('id'))->get();
        return view('admin.orders.ajax.show', compact('orders'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // return Order::whereReferenceNumber($id)->get();
        $order = Order::whereReferenceNumber($id);
        abort_unless($order->count(), 404);

        if ($notification = auth()->user()->unreadNotifications()->whereId(request('notification_id'))->first()) { $notification->markAsRead(); }

        $orders = optional(Auth::user()->role_id == 3 ? $order->whereIn('product_id', Product::allByRole()->pluck('id')) : $order)->get();
        // $orders = optional(auth()->user()->hasRole('vendor') ? $order->whereIn('product_id', Product::allByRole()->pluck('id')) : $order)->get();
        // $orders = Auth::user()->role_id == 3 ? $order->whereIn('product_id', Product::allByRole()->pluck('id'))->get() : $order->get();
        // return $orders;

        return view('admin.orders.edit', compact('orders'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $ref)
    {
        $order = Order::whereReferenceNumber($ref);
        if ($request->status == "pending") {
            $stockStatus = 'booked';
            $order->update(['status' => "pending", 'shipped_date' => null, 'delivered_date' => null]);
        } elseif ($request->status == "shipped") {
            $stockStatus = 'booked';
            $order->update(['status' => "shipped", 'shipped_date' => now(), 'delivered_date' => null]);
        } elseif ($request->status == "delivered") {
            $stockStatus = 'sold';
            $order->update(['status' => "delivered", 'delivered_date' => now()]);
        } else {
           $stockStatus = 'unsold';
           $order->update(['status' => $request->status, 'shipped_date' => null, 'delivered_date' => null]);
       }

       $this->updateProductStock($order->get(), $stockStatus);
       flash('success','Order status updated successfully.');
       return back();
    }
    public function updateProductStock($orders, $stockStatus)
    {
        foreach ($orders as $order) {
            $stocks = explode(',', $order->product_stock_ids);
            for($i = 0; $i < count($stocks); $i++) {
                ProductStock::find($stocks[$i])->update(['status' => $stockStatus]);
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function destroy($refId)
    {
        DB::table('notifications')->where('type', 'App\Notifications\OrderNotification')
        ->where('data', '{"message":"New order created","reference_number":"'.$refId.'"}')->delete();

        Order::whereReferenceNumber($refId)->delete();
        return redirect()->back()->with('success','Order deleted successfully.');
    }
}
