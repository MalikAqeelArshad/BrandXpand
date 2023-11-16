@extends('layouts.app')
@section('title', @Auth::user()->profile->full_name. ' - Profile')

@push('styles')
<link rel="stylesheet" href="{{ asset('plugins/bootstrap-datepicker/bootstrap-datepicker.min.css') }}">
@endpush

@section('content')
<!-- Page Content -->
<div class="container">
    <section class="content">
    <!-- Breadcrumb -->
    <ol class="breadcrumb shadow-sm">
        <li class="breadcrumb-item">
            <a href=""><i class="far fa-user fa-fw"></i>Edit Profile</a>
        </li>
        <li class="breadcrumb-item active">{{ @$user->profile->full_name }}
    </li>
    </ol>

 

    <!-- Profile -->
    <div class="row profile small mt-4">
        <div class="col-sm-4 col-md-4 col-lg-3 mb-3">
            <div class="card shadow border-0">
                <img src="{{ @$user->gallery->filename ? asset('uploads/avatars/'.@$user->gallery->filename) : asset('images/default.png') }}" class="card-img-top" alt="Profile Photo">
                <a href="javascript:;" data-toggle="modal" data-target="#editModal" class="btn btn-warning rounded-0">Change Avatar</a>
                <div class="card-body">
                    <h5 class="card-title">{{ @$user->profile->full_name }}</h5>
                    @if (@$user->profile->about)
                    <p class="card-text small">
                        {{ substr(@$user->profile->about, 0, 75) }}
                        @if (strlen(@$user->profile->about) > 75)
                        <span id="more" class="w2a-moreless-text collapse">{{ substr(@$user->profile->about, 75) }}</span>
                        <a href="javascript:;" data-hover="tooltip" title="About in Detail" class="w2a-moreless-btn collapsed text-primary link" data-toggle="collapse" data-target="#more" w2a-before="...More" w2a-after="Less"></a>
                        @endif
                    </p>
                    @else
                    <small class="text-muted">About info hasn't been yet.</small>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-sm-8 col-md-8 col-lg-9">
            <ul class="nav nav-tabs border-0">
                <li class="nav-item">
                    <a class="nav-link @if(old('tab') == 'profile' || !old('tab')) active @endif" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="true">Profile</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle @if(old('tab') == 'account' || old('tab') == 'password') active @endif" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">Account</a>
                    <div class="dropdown-menu shadow border-0">
                        <a class="dropdown-item small @if(old('tab') == 'account') active @endif" data-toggle="tab" href="#account" role="tab" aria-controls="account" aria-selected="false">Account Detail</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item small @if(old('tab') == 'password') active @endif" data-toggle="tab" href="#password" role="tab" aria-controls="password" aria-selected="false">Change Password</a>
                    </div>
                </li>
                <li class="nav-item">
                    <a class="nav-link @if(old('tab') == 'address') active @endif" data-toggle="tab" href="#address" role="tab" aria-controls="address" aria-selected="false">Address</a>
                </li>
            </ul>
            <div class="tab-content shadow-sm bg-light">
                <div class="tab-pane fade @if(old('tab') == 'profile' || !old('tab')) show active @endif" id="profile" role="tabpanel">
                    <form method="POST" action="{{ route('user.update', $user->id) }}">
                        @csrf @method('PUT') <input type="hidden" name="tab" value="profile">
                        <div class="card bg-transparent rounded-0 border-0 pt-3">
                            <div class="card-body">
                                <div class="form-group row">
                                    <label class="col-md-4 col-lg-3 col-xl-2 col-form-label">First Name</label>
                                    <div class="col-md-8 col-lg-9 col-xl-10">
                                        <input type="text" name="first_name" value="{{ old('first_name') ?: @$user->profile->first_name }}" class="form-control {{ !$errors->first('first_name') ?: 'is-invalid' }}" placeholder="First Name" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-4 col-lg-3 col-xl-2 col-form-label">Last Name</label>
                                    <div class="col-md-8 col-lg-9 col-xl-10">
                                        <input type="text" name="last_name" value="{{ old('last_name') ?: @$user->profile->last_name }}" class="form-control {{ !$errors->first('last_name') ?: 'is-invalid' }}" placeholder="Last Name" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-4 col-lg-3 col-xl-2 col-form-label">Gender</label>
                                    <div class="col-md-8 col-lg-9 col-xl-10 pt-2">
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" id="pr1" name="gender" value="1" {{ checked(@$user->profile->gender,1) }} class="custom-control-input">
                                            <label class="custom-control-label" for="pr1">Male</label>
                                        </div>
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" id="pr2" name="gender" value="0" {{ checked(@$user->profile->gender,0) }} class="custom-control-input">
                                            <label class="custom-control-label" for="pr2">Female</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-4 col-lg-3 col-xl-2 col-form-label">Date of Birth</label>
                                    <div class="col-md-8 col-lg-9 col-xl-10">
                                        <input type="text" name="dob" value="{{ old('dob') ?: @$user->profile->dob }}" class="form-control bg-white" data-provide="datepicker" data-date-format="yyyy-mm-dd" placeholder="yyyy-mm-dd" readonly>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-4 col-lg-3 col-xl-2 col-form-label">Phone</label>
                                    <div class="col-md-8 col-lg-9 col-xl-10">
                                        <input type="phone" name="phone" value="{{ old('phone') ?: @$user->profile->phone }}" class="form-control" placeholder="00923026262164">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-4 col-lg-3 col-xl-2 col-form-label">About</label>
                                    <div class="col-md-8 col-lg-9 col-xl-10">
                                        <textarea name="about" class="form-control" placeholder="Write a short info about yourself...">{{ old('about') ?: @$user->profile->about }}</textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <button type="submit" class="btn btn-info mr-1"><small>Submit</small></button>
                                <button type="reset" class="btn btn-secondary"><small>Reset</small></button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="tab-pane fade @if(old('tab') == 'account') show active @endif" id="account" role="tabpanel">
                    <form method="POST" action="{{ route('user.update', $user->id) }}">
                        @csrf @method('PUT') <input type="hidden" name="tab" value="account">
                        <div class="card bg-transparent rounded-0 border-0 pt-3">
                            <div class="card-body">
                                <div class="form-group row">
                                    <label class="col-md-4 col-lg-3 col-xl-2 col-form-label">Email</label>
                                    <div class="col-md-8 col-lg-9 col-xl-10">
                                        <input type="email" name="email" value="{{ old('email') ?: $user->email }}" class="form-control" placeholder="abc@xyz.com">
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <button type="submit" class="btn btn-info mr-1"><small>Submit</small></button>
                                <button type="reset" class="btn btn-secondary"><small>Reset</small></button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="tab-pane fade @if(old('tab') == 'password') show active @endif" id="password" role="tabpanel">
                    <form method="POST" action="{{ route('user.update', $user->id) }}">
                        @csrf @method('PUT') <input type="hidden" name="tab" value="password">
                        <div class="card bg-transparent rounded-0 border-0 pt-3">
                            <div class="card-body small">
                                <div class="form-group row">
                                    <label class="col-md-4 col-lg-3 col-xl-2 col-form-label">Old Password</label>
                                    <div class="col-md-8 col-lg-9 col-xl-10">
                                        <input type="password" name="current_password" value="{{ old('current_password') ?: '' }}" class="form-control" placeholder="Current Password" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-4 col-lg-3 col-xl-2 col-form-label">New Password</label>
                                    <div class="col-md-8 col-lg-9 col-xl-10">
                                        <input type="password" name="password" value="{{ old('password') ?: '' }}" class="form-control" placeholder="New Password" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-4 col-lg-3 col-xl-2 col-form-label">Confirm Password</label>
                                    <div class="col-md-8 col-lg-9 col-xl-10">
                                        <input type="password" name="password_confirmation" value="{{ old('password_confirmation') ?: '' }}" class="form-control" placeholder="Confirm Password" required>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <button type="submit" class="btn btn-info mr-1"><small>Submit</small></button>
                                <button type="reset" class="btn btn-secondary"><small>Reset</small></button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="tab-pane fade @if(old('tab') == 'address') show active @endif" id="address" role="tabpanel">
                    <form method="POST" action="{{ route('user.update', $user->id) }}">
                        @csrf @method('PUT') <input type="hidden" name="tab" value="address">
                        <div class="card bg-transparent rounded-0 border-0 pt-3">
                            <div class="card-body">
                                <div class="form-group row">
                                    <label class="col-md-4 col-lg-3 col-xl-2 col-form-label">Address</label>
                                    <div class="col-md-8 col-lg-9 col-xl-10">
                                        <input type="text" name="address" value="{{ old('address') ?: $user->address['address'] }}" class="form-control" placeholder="Street #4, Defence">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-4 col-lg-3 col-xl-2 col-form-label">City</label>
                                    <div class="col-md-8 col-lg-9 col-xl-10">
                                        <input type="text" name="city" value="{{ old('city') ?: $user->address['city'] }}" class="form-control" placeholder="Islamabad">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-4 col-lg-3 col-xl-2 col-form-label">State</label>
                                    <div class="col-md-8 col-lg-9 col-xl-10">
                                        <input type="text" name="state" value="{{ old('state') ?: $user->address['state'] }}" class="form-control" placeholder="Punjab">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-4 col-lg-3 col-xl-2 col-form-label">Zip/Postal</label>
                                    <div class="col-md-8 col-lg-9 col-xl-10">
                                        <input type="text" name="postcode" value="{{ old('postcode') ?: $user->address['postcode'] }}" class="form-control" placeholder="54000">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-4 col-lg-3 col-xl-2 col-form-label">Country</label>
                                    <div class="col-md-8 col-lg-9 col-xl-10">
                                        <select name="country" class="custom-select">
                                            <option>please choose</option>
                                            @foreach (get_countries_list() as $key => $name)
                                                <option value="{{ $key }}" @if ($key == $user->address['country']) selected @endif>{{ $name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <button type="submit" class="btn btn-info mr-1"><small>Submit</small></button>
                                <button type="reset" class="btn btn-secondary"><small>Reset</small></button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>    
</div>


<!--begin:: Edit Modal-->
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Profile Avatar</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div id="dynamic-content"></div>
            <form method="POST" action="{{ route('avatar.update', $user->id) }}" enctype="multipart/form-data">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <div class="modal-body text-center">
                    <figure class="d-inline-block">
                        <img src="{{ @$user->gallery->filename ? asset('uploads/avatars/'.@$user->gallery->filename) : asset('images/default.png') }}" class="img-thumbnail" wcs-file-image="avatar" style="max-height: 17rem">
                        <figcaption class="mt-2">
                            <a href="javascript:;" class="btn btn-block btn-outline-danger btn-sm position-relative rounded-pill mt-3">
                                <small>Change Picture</small>
                                <input type="file" name="avatar" wcs-file-input="avatar" class="w-100 h-100" style="opacity: 0; position: absolute; left: 0; top: 0; cursor: pointer">
                            </a>
                        </figcaption>
                    </figure>
                </div>
                <div class="modal-footer">
                    <button type="reset" class="btn btn-light border" data-dismiss="modal"><small>Close</small></button>
                    <button type="submit" class="btn btn-info"><small>Save changes</small></button>
                </div>
            </form>
        </div>
    </div>
</div>
<!--end:: Edit Modal-->
@endsection

@section('scripts')
<script type="text/javascript" src="{{ asset('plugins/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
@endsection