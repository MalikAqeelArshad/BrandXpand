<?php

namespace App\Http\Controllers\Admin;

use App\Role;
use App\User;
use App\Order;
use App\Review;
use App\Product;
use App\SiteOption;
use App\ProductStock;
use App\HasRolesTrait;
use App\Traits\MorphTrait;
use Illuminate\Http\Request;
use App\Traits\ImageUploadTrait;
use App\Http\Controllers\Controller;

class AdminController extends Controller
{
    use HasRolesTrait, MorphTrait, ImageUploadTrait;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // return Order::whereIn('product_id', auth()->user()->products->pluck('id'))->get()->unique('reference_number');
        return view('admin.index');
    }

    public function siteOptions()
    {
        $this->authorize('viewAny', SiteOption::class);
        if (request()->isMethod('post')) {
            foreach (request()->all() as $key => $value) {
                if (!in_array($key, ['_token', '_method'])) {
                    SiteOption::updateOrCreate(['key' => $key], ['value' => $value ?: '']);
                }
            }
            flash('success', "Site options has been updated successfully.");
            return back();
        }
        return view('admin.site-options');
    }

    public function avatarUpdate($id)
    {
        $user = User::findOrFail($id);
        $this->validate(request(), [
            'avatar' => 'required|image|mimes:jpeg,jpg,png,bmp|max:2048', // max 2048 kilobytes
        ]);
        if (request()->hasFile('avatar')) {
            $this->uploadImage(request()->file('avatar'), 'avatars');
            $this->deleteImage(@$user->gallery->filename, 'avatars');
        }

        $user->gallery()->updateOrCreate(['user_id'=>$user->id], request()->all());
        flash('success', "Your profile has been updated successfully.");
        return back();
    }

    public function productReviews()
    {
        $reviews = Review::whereIn('product_id', Product::allByRole()->pluck('id'))->orderBy('id','desc')->paginate(15);
        return view('admin.reviews',compact('reviews'));
    }

    public function editReview($id)
    {
        $review = Review::findOrFail($id);
        $review->publish = $_REQUEST['publish'];
        $review->save();
        return "Review Status Updated Successfully";
    }

    public function reviewDestroy($id)
    {
        Review::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Review Deleted Successfully');
    }

    public function orderNotifications()
    {
        return view('admin.orders.notifications', [
            'notifications' => auth()->user()->unreadNotifications()->paginate(10)
        ]);
    }
}
