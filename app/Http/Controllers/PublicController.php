<?php

namespace App\Http\Controllers;

use Auth;
use App\User;
use App\Order;
use App\Address;
use App\Contact;
use App\Product;
use App\Category;
use Notification;
use App\SubCategory;
use App\ProductStock;
use App\ShippingCost;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use App\Notifications\OrderNotification;
use Gloudemans\Shoppingcart\Facades\Cart;

class PublicController extends Controller
{
    public function profile()
    {
        return view('user.profile', [
            'user' => auth()->user()
        ]);
    }

    public function updateProfile(Request $request, $id)
    {
        $user = User::findOrFail($id);

        if ($request->tab == 'profile') {
            $this->validate($request, [
                'first_name' => 'required|string|min:3',
                'last_name' => 'required|string|min:3',
            ]);
        }

        if ($request->tab == 'account') {
            $this->validate($request, [
                'email' => 'required|email|unique:users,email,' . $user->id,
            ]);
            !$request->email_verified_at ?: $user->markEmailAsVerified();
        }

        if ($request->tab == 'password') {
            $this->validate($request, [
                'current_password' => 'required|string|min:4|different:password',
                'password' => 'required|string|min:4|confirmed',
            ]);
            if (!(\Hash::check($request->current_password, $user->password))) {
                return back()->with("error", "Your current password does not matched.")->withInput($request->only('tab'));
            }
        }

        // update the user or eloquent
        $user->update($request->all());
        $user->profile()->updateOrCreate(['user_id' => $user->id], $request->all());
        $user->address()->updateOrCreate(['addressable_id' => $user->id, 'type' => request('type') ?: 'office'], $request->all());
        flash('success', "Your profile has been updated successfully.");
        // redirect to profile page with form input
        return back()->withInput($request->only('tab'));
    }

    public function index()
    {
        $products = Product::whereHas('user', function($q) {
            $q->whereNull('deleted_at');
        })->wherePublish(1)->latest()->limit(8)->get();

        $bestProducts = Product::whereHas('user', function($q) {
            $q->whereNull('deleted_at');
        })->wherePublish(1)->whereIn('category_id', Category::pluck('id'))->oldest()->limit(4)->get();

        return view('welcome', compact('bestProducts', 'products'));
    }

    public function products()
    {
        if ($id = request('category')) {
            $category = Category::findOrFail($id);
        } elseif ($id = request('sub-category')) {
            $category = SubCategory::findOrFail($id);
        }
        
        if (isset($category)) {
            $products = $category->products($publish=1)->whereHas('user', function ($q) {
                $q->whereNull('deleted_at');
            })->orderBy('id', 'desc')->paginate(4);

            if (request()->ajax()) {
                $view = view('category.ajax.show', compact('products'))->render();
                return response()->json(['html' => $view]);
            }
         } else {
            abort(404);
            $products = Product::wherePublish(1)->paginate(30);
        }

        return view('category.products', compact('category', 'products'));
    }

    public function search(Request $request)
    {
        // dd($request->is('search/products'));
        $orderType = request('orderType') ?? null;
        $search = $request->s ? '%' . $request->s . '%' : null;
        $min = request('min'); $max = request('max'); $price = request('price');

        $products = Product::whereHas('user', function ($q) {
            $q->whereNull('deleted_at'); // get all products where user is activated
        })->wherePublish(1);

        if ($search) { $products->whereLike(['name','code','excerpt','description'], $search); }
        if (in_array($orderType, ['arrival','promotion'])) { $products->whereType($orderType); }

        $str = 'sale * (1 - discount / 100)'; // discount price formula
        if ($min) { $products->whereRaw("{$str} >= {$min}"); }
        if ($max) { $products->whereRaw("{$str} <= {$max}"); }
        if ($price) { $products->select('*', \DB::raw("{$str} as price")); $request['orderBy'] = 'price'; }
        if ($price == 'lowtohigh' || $orderType == 'asc' && $price != 'hightolow') { $request['order'] = 'asc'; }

        $products = $products->filters()->paginate(20);

        $category = (int) $request->category ? Category::find($request->category) : null;
        return view('search-results', compact('products', 'category'));
    }

    public function orderStatus($refId)
    {
        return view('orders.ajax.track-order', [
            'order' => Order::whereReferenceNumber($refId)->first()
        ]);
    }

    public function contactUs(Request $request)
    {
        $this->validate($request,[
            'name' => 'required|string|max:255',
            'email' => 'required|string|max:255',
            'message' => 'required'
        ]);

        Contact::create($request->all());

        return redirect()->back()->with('success','Your message submit successfully!');
    }

    public function getStates($code)
    {
        $states = ShippingCost::where('country',$code)->get();
        return view('product.ajax.get-states',compact('states'));
    }

    public function addShipCost(Request $request)
    {
        $total = round(Cart::subtotal(), 2);
        $shippingCost = ShippingCost::findOrFail($request->id);

        if ($request->self) {
            if (session('discount')) {
                $total -= ($total / 100) * session('discount');
            }
            session(['shipping_cost' => '', 'shipping_state' => '']);
            return ['total' => $total, 'shippingCost' => 0];
        }

        if (session('discount')) {
            $total -= ($total / 100) * session('discount');
            $total += $shippingCost->charges;
        } else {
            $total += $shippingCost->charges;
        }

        session(['shipping_cost' => $shippingCost->charges, 'shipping_state' => $shippingCost->state]);

        return ['total' => $total, 'shippingCost' => $shippingCost->charges];
    }

    public function cashOnDelivery(Request $request)
    {
        $this->validate($request, [
            'address' => 'required',
            'mobile' => 'required|max:255',
            'state' => 'required',
            'city' => 'required|string|max:255',
            'country' => 'required',
        ]);

        $total = round(Cart::subtotal(), 2);
        $shippingCost = ShippingCost::findOrFail($request->state);

        if (session('discount')) {
            if (session('shipping_cost')) {
                $total -= ($total / 100) * session('discount');
                $total += $shippingCost->charges;
            } else {
                $total -= ($total / 100) * session('discount');
            }
        } else { $total += $shippingCost->charges; }

        // $request['type'] = "shipping";
        $request['state'] = $shippingCost->state;

        $ref = $this->saveOrder($request, $total, "cash on delivery");
        $order = Order::whereReferenceNumber($ref)->first();
        $order->address()->create($request->all());
        return redirect()->route('order.completed', ['ref'=>$ref]);
    }

    public function selfCollect(Request $request)
    {
        $total = round(Cart::subtotal(), 2);
        
        if (session('discount')) { $total -= ($total / 100) * session('discount'); }

        $ref = $this->saveOrder($request, $total, "self collect");
        return redirect()->route('order.completed', ['ref'=>$ref]);
    }

    public function saveOrder($request, $total, $paymentMethod)
    {
        $ref = str_pad(mt_rand(1, 99999999), 12, 0, STR_PAD_LEFT);
        foreach (Cart::content() as $cart) {
            $order = Order::create([
                'user_id' => auth()->id(),
                'product_id' => $cart->model->id,
                'coupon_id' => session('coupon_id') ?: null,
                'product_stock_ids' => $cart->model->stockIds($cart->options->attrs, $cart->qty),
                'reference_number' => $ref,
                'payment_method' => $paymentMethod,
                'shipping_charges' => session('shipping_cost') ?: 0,
                'total' => $cart->price * $cart->qty,
                'grand_total' => $total,
                'status' => 'pending'
            ]);

            $usersByProduct[] = $cart->model->user_id;

            $stocks = explode(',', $cart->model->stockIds($cart->options->attrs, $cart->qty));

            for ($i = 0; $i < sizeof($stocks); $i++) {
                ProductStock::findOrFail($stocks[$i])->update(['status' => "booked"]);
            }
        }

        $usersByRole = User::whereHasRole(['administrator', 'admin'])->pluck('id')->toArray();
        $users = User::whereIn('id', array_unique(array_merge($usersByRole, $usersByProduct)))->get();

        Notification::send($users, new OrderNotification(['reference_number' => $ref]));

        Cart::destroy(); Cart::store(auth()->id());

        session(['discount' => '', 'shipcost' => '', 'coupon_id' => '', 'shipping_cost' => '', 'shipping_state' => '']);

        return $ref;
    }

    public function orderCompleted()
    {
        abort_unless($ref = request('ref'), 404);
        return view('order-completed', ['order' => Order::whereReferenceNumber($ref)->first()]);
    }
}
