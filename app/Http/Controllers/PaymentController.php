<?php

namespace App\Http\Controllers;

use App\Notifications\OrderNotification;
use App\Order;
use App\Product;
use App\User;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Http\Request;
use Auth;
use Illuminate\Support\Facades\Session;
use App\ShippingCost;
use Notification;


class PaymentController extends Controller
{

    public function showPayTab(Request $request)
    {
        $this->validate($request, [
            'address' => 'required',
            'mobile' => 'required|max:255',
            'state' => 'required',
            'city' => 'required|string|max:255',
            'country' => 'required',
        ]);


        $address = $request->address;
        $city = $request->city;
        $mobile = $request->mobile;
        $getstate = ShippingCost::find($request->state);
        $state = $getstate->state;
        $country = $getstate->country;
        $postcode = $request->postcode;

        $productNames = [];
        foreach (Cart::content() as $cart) {
            $productNames[] = $cart->model->name;
        }
        $products = implode(',', $productNames);

        // $total = (int) str_replace( ',', '',Cart::subtotal());
        $total = round(Cart::subtotal(), 2);
        if (session('discount')) {
            if (session('shipping_cost')) {
                $total -= ($total / 100) * session('discount');
                $total += $getstate->charges;
            } else {
                $total -= ($total / 100) * session('discount');
            }
        } else {

            $total += $getstate->charges;
        }
        return view('ajax.paytab', compact('address', 'total', 'city','country','mobile', 'state', 'postcode', 'products'));
    }

    public function payment(Request $request)
    {

        $result = json_encode($_REQUEST);
        $getResult = json_decode($result, true);
        $getResult['order_id'];

        // $total = (int) str_replace( ',', '',Cart::subtotal());
        $total = round(Cart::subtotal(), 2);
        $state = ShippingCost::where('state', session('shipping_state'))->first();

        if (session('discount')) {
            if (session('shipping_cost')) {
                $total -= ($total / 100) * session('discount');
                $total += $state->charges;
            } else {
                $total -= ($total / 100) * session('discount');
            }
        } else {

            $total += $state->charges;
        }

        $i = 0;

        foreach (Cart::content() as $cart) {
            $i++;
            $order = Order::create([
                'user_id' => Auth::id(),
                'product_id' => $cart->model->id,
                'coupon_id' => session('coupon_id') ?: null,
                'product_stock_ids' => $cart->model->stockIds($cart->options->attrs, $cart->qty),
                'payment_method' => $getResult['card_brand'],
                'shipping_charges' => session('shipping_cost'),
                'total' => $cart->price * $cart->qty,
                'grand_total' => $total,
                'status' => 'pending',
                'reference_number' => $getResult['order_id']
            ]);
            if ($i == 1) {
                $order->address()->create([
                    'address' => $getResult['address_shipping'],
                    'mobile' => $getResult['customer_phone'],
                    'type' => 'shipping',
                    'state' => $state->state,
                    'postcode' => $getResult['postal_code_shipping'],
                    'city' => $getResult['city_shipping'],
                    'country' => $getResult['country_shipping']
                ]);

                session(['order_id' => $order->id]);
            }

            $users = User::whereIn('id',[$cart->model->user_id])->get();

            $details = [
                'reference_number' => $order->reference_number,
            ];

            Notification::send($users, new OrderNotification($details));

            $stocks = explode(',', $cart->model->stockIds($cart->options->attrs, $cart->qty));

            for($j=0; $j < sizeof($stocks); $j++)
            {
                // __changeStockStatus($stocks[$j],"booked");
                ProductStock::findOrFail($stocks[$j])->update(['status' => "booked"]);
            }
        }

        $allUserGetByRole = User::where(['role_id' => 1 , 'role_id' => 2])->get();

        Notification::send($allUserGetByRole, new OrderNotification(['reference_number' => $ref]));


        Cart::destroy();

        Cart::store(Auth::id());

        session(['discount' => '', 'shipcost' => '', 'coupon_id' => '', 'shipping_cost' => '', 'shipping_state' => '']);

        return redirect()->route('order.success');
    }

}
