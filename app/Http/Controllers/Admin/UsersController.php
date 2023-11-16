<?php

namespace App\Http\Controllers\Admin;

use App\User;
use App\Traits\MorphTrait;
use Illuminate\Http\Request;
use App\Traits\ImageUploadTrait;
use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Registered;

class UsersController extends Controller
{
    use MorphTrait, ImageUploadTrait;
    public function __construct()
    {
        $this->authorizeResource(User::class);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // __filter('User');
        $this->authorize('viewAny', User::class);
        return view('admin.users.index', [
            'users' => User::latest()->status(request('status'))->paginate(10)
            // 'users' => ModelFilter('User')->latest()->status(request('status'))->paginate(10)
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
        $this->validate($request, [
            'first_name' => 'required|string|min:3',
            'last_name' => 'required|string|min:3',
            'email' => 'required|email|unique:users',
        ]);
        $request['password'] = 'secret';
        $user = User::create($request->all());
        $user->profile()->updateOrCreate([], $request->all());
        !$request->email_verified_at ?: $user->markEmailAsVerified();
        event(new Registered($user));
        flash('success', "New user added successfully.");
        flash('info', "New user password will be [secret].");
        return redirect()->route('users.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        return view('admin.users.ajax.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        return view('admin.profile', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        if ($request->tab == 'profile') {
            $this->validate($request, [
                'first_name' => 'required|string|min:3',
                'last_name' => 'required|string|min:3',
            ]);
        }

        if ($request->tab == 'account') {
            $this->validate($request, [
                'email' => 'required|email|unique:users,email,'.$user->id,
            ]);
            !$request->email_verified_at ?: $user->markEmailAsVerified();
        }

        if ($request->tab == 'password') {
            $this->validate($request, [
                'current_password' => 'required|string|min:4|different:password',
                'password' => 'required|string|min:4|confirmed',
            ]);
            if (!(\Hash::check($request->current_password, $user->password))) {
                return back()->with("error","Your current password does not matched.")->withInput($request->only('tab'));
            }
        }
        
        // update the user or eloquent
        $user->update($request->all());
        $user->profile()->updateOrCreate(['user_id' => $user->id],$request->all());
        $user->address()->updateOrCreate(['addressable_id' => $user->id, 'type' => request('type') ?: 'office'], $request->all());
        flash('success', "Your profile has been updated successfully.");
        // redirect to profile page with form input
        return back()->withInput($request->only('tab'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        dd('destroy');
        User::onlyTrashed()->findOrFail($user->id)->forceDelete();
        flash('success', "User has been deleted successfully.");
        return back();
    }

    public function trashed($id)
    {
        User::findOrFail($id)->delete();
        flash('success', "User has been moved to trashed successfully.");
        return back();
    }

    public function restore($id)
    {
        User::onlyTrashed()->findOrFail($id)->restore();
        flash('success', "User has been restored successfully.");
        return back();
    }

    public function dropped($id)
    {
        $this->authorize('dropped', User::class);
        User::onlyTrashed()->findOrFail($id)->forceDelete();
        flash('success', "User has been deleted successfully.");
        return back();
    }
}
