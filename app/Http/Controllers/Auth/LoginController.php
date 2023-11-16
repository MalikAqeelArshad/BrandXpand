<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\User;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;


class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo="/";

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('guest')->except('logout');
        $this->middleware('guest', ['except' => 'logout']);
        // for redirect to previous page after login
        $this->redirectTo = url()->previous();
    }

    protected function authenticated(Request $request, User $user) {
        \Artisan::call('optimize:clear');
        
        Cart::restore(Auth::id());
        foreach(Cart::content() as $cart)
        {
            if(empty($cart->model->id))
            {
                Cart::remove($cart->rowId);
            }
        }
        // return redirect(auth()->user()->hasAnyRole() ? '/admin' : '/');
    }
}
