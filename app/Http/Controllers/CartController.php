<?php

namespace App\Http\Controllers;

use App\Coupon;
use App\Product;
use App\ShippingCost;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //Cart::destroy();
        // dd(session()->all());
        $items = Cart::content();

        // dd($items);

        foreach (Cart::content() as $cart) {
            if (empty($cart->model->id)) {
                Cart::remove($cart->rowId);
            }
        }

        // dd(Cart::total());
        return view('product.cart-items', compact('items'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function storeCart(Request $request)
    {
        $product = Product::FindOrFail($request->product_id);
        $lastStock = $product->lastStock('unsold', request('attrs'));
        $productStocks = $product->stocks('unsold')->whereAttrs(request('attrs'));

        if($productStocks->count() < $request->qty) { return "error"; }

        // $shoppingCart = Cart::get($request->rowId);
        $productPrice = $lastStock->discount > 0 ? $lastStock->discountPrice : $lastStock->sale;
        $cartItem = Cart::add($product->id, $product->name, $request->qty, $productPrice, ['attrs'=>request('attrs')]);

        Cart::associate($cartItem->rowId, Product::class);

        if (Auth::check()) { Cart::store(Auth::id()); }
        return Cart::content()->count();
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Cart $cart
     * @return \Illuminate\Http\Response
     */
    public function show()
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Cart $cart
     * @return \Illuminate\Http\Response
     */
    public function edit(Cart $cart)
    {
        return 'edit';
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Cart $cart
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $shoppingCart = Cart::get($request->rowId);
        // $lastStock = $shoppingCart->model->lastStock('unsold', $shoppingCart->options->attrs);
        $productStocks = $shoppingCart->model->stocks('unsold')->whereAttrs($shoppingCart->options->attrs);

        if($productStocks->count() < $request->qty) { return "error"; }

        if ($request->qty > 0) {
            if ($shoppingCart->qty == $request->qty) { return false; }
            $cart = Cart::update($request->rowId, $request->qty);
        } else {
            Cart::remove($request->rowId);
            return Cart::content()->count();
        }

        if (Auth::check()) { Cart::store(Auth::id()); }

        session(['discount' => '', 'coupon_id' => '']);
        return [
            'cart' => json_encode($cart),
            'total' => round(Cart::total(), 2),
            'subtotal' => round(Cart::subtotal(), 2)
            // 'total' => (int) str_replace(',', '', Cart::total()),
            // 'subtotal' => (int) str_replace(',', '', Cart::subtotal())
        ];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Cart $cart
     * @return \Illuminate\Http\Response
     */
    public function destroy($rowId)
    {
        Cart::remove($rowId);
        if (Auth::check()) { Cart::store(Auth::id()); }

        session(['discount' => '', 'coupon_id' => '']);
        return [
            'cartcount' => Cart::content()->count(),
            'total' => round(Cart::total(), 2),
            'subtotal' => round(Cart::subtotal(), 2)
            // 'total' => (int) str_replace(',', '', Cart::total()),
            // 'subtotal' => (int) str_replace(',', '', Cart::subtotal())
        ];
    }

    public function applyCoupon(Request $request)
    {
        $coupon = Coupon::where('code', $request->coupon)->first();

        if (empty($coupon)) {
            return ['not_found' => 'Sorry! Coupon Not Valid'];
        } else {
            $c = Coupon::where('expiry_date', '>=', Carbon::now())->where('status', 1)->first();
            if (empty($c)) {
                return ['expired' => 'Sorry! This Coupon is expired'];
            }
            session(['discount' => $c->discount, 'coupon_id' => $c->id]);
            return ['discount' => $c->discount, 'subtotal' => round(Cart::subtotal(), 2)];
        }
    }

    public function setShipCost(Request $request)
    {
        if ($request->ajax()) {
            session(['shipping_cost' => '']);
            session(['shipping_state' => '']);
            session(['shipcost' => 1]);
        }
    }

    public function checkout()
    {
        if (session('shipcost') >= 0) {
            $total = round(Cart::subtotal(), 2);
            $discount = "";
            if (session('discount')) {
                if (session('shipping_cost')) {
                    $total -= ($total / 100) * session('discount');
                    $total += session('shipping_cost');
                } else {
                    $total -= ($total / 100) * session('discount');
                }
            } else if (session('shipping_cost')) {
                $total += session('shipping_cost');
            }
            $states = ShippingCost::all();
            return view('product.checkout', compact('total', 'discount', 'states'));
        } else {
            return redirect()->back();
        }
    }
}
