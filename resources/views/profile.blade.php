@extends('layouts.app')

@section('title', $user->profile->full_name. ' - Profile')

@section('content')
<!-- Page Content -->
<section class="content container-fluid">
    <!-- Breadcrumb -->
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <a href="{{ url('profile') }}"><i class="far fa-user fa-fw"></i> Profile</a>
        </li>
        <li class="breadcrumb-item active">{{ $user->profile->full_name }}</li>
    </ol>
    <!-- Profile -->
    <div class="row mt-4">
        <div class="col-sm-4 col-md-4 col-lg-3 mb-3">
            <div class="card">
                <img class="card-img-top" src="{{ asset('images/default.png') }}" alt="Profile Photo">
                <div class="card-body">
                    <h5 class="card-title">{{ $user->profile->full_name }}</h5>
                    <p class="card-text">{{ $user->profile->about }}</p>
                </div>
                <nav class="list-group list-group-flush">
                    <a href="javascript:;" class="list-group-item list-group-item-action">
                        <i class="float-right text-primary fab fa-facebook-square fa-lg"></i> Facebook
                    </a>
                    <a href="javascript:;" class="list-group-item list-group-item-action">
                        <i class="float-right text-danger fab fa-google-plus-square fa-lg"></i> Google +
                    </a>
                    <a href="javascript:;" class="list-group-item list-group-item-action">
                        <i class="float-right text-info fab fa-linkedin fa-lg"></i> LinkedIn
                    </a>
                </nav>
            </div>
        </div>
        <div class="col-sm-8 col-md-8 col-lg-9">
            <ul class="nav nav-tabs">
                <li class="nav-item">
                    <a class="nav-link active" data-toggle="tab" href="#tab1" role="tab" aria-controls="tab1" aria-selected="true">Profile</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">Account</a>
                    <div class="dropdown-menu">
                        <a class="dropdown-item" data-toggle="tab" href="#tab2" role="tab" aria-controls="tab2" aria-selected="false">Account Detail</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" data-toggle="tab" href="#tab3" role="tab" aria-controls="tab3" aria-selected="false">Change Password</a>
                    </div>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#tab4" role="tab" aria-controls="tab4" aria-selected="false">Address</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#tab5" role="tab" aria-controls="tab5" aria-selected="false">Socials</a>
                </li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane fade show active" id="tab1" role="tabpanel">
                    <form method="POST" action="{{ url('') }}">
                        <div class="card rounded-0 border-top-0 pt-3">
                            <div class="card-body">
                                <div class="form-group row">
                                    <label class="col-md-4 col-lg-3 col-xl-2 col-form-label">First Name</label>
                                    <div class="col-md-8 col-lg-9 col-xl-10">
                                        <input type="text" name="first_name" value="{{ $user->profile->first_name }}" class="form-control" placeholder="Aqeel">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-4 col-lg-3 col-xl-2 col-form-label">Last Name</label>
                                    <div class="col-md-8 col-lg-9 col-xl-10">
                                        <input type="text" name="last_name" value="{{ $user->profile->last_name }}" class="form-control" placeholder="Malik">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-4 col-lg-3 col-xl-2 col-form-label">Gender</label>
                                    <div class="col-md-8 col-lg-9 col-xl-10">
                                        <div class="form-check-inline pt-2">
                                            <label class="form-check-label">
                                                <input type="radio" class="form-check-input" name="gender" {{ checked(@$user->profile->gender,1) }}>Male
                                            </label>
                                        </div>
                                        <div class="form-check-inline pt-2">
                                            <label class="form-check-label">
                                                <input type="radio" class="form-check-input" name="gender" {{ checked(@$user->profile->gender,0) }}>Female
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-4 col-lg-3 col-xl-2 col-form-label">Date of Birth</label>
                                    <div class="col-md-8 col-lg-9 col-xl-10">
                                        <input type="text" class="form-control bg-transparent" data-provide="datepicker" data-date-format="yyyy-mm-dd" value="{{ $user->profile->dob }}" placeholder="yyyy-mm-dd" readonly>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-4 col-lg-3 col-xl-2 col-form-label">Phone</label>
                                    <div class="col-md-8 col-lg-9 col-xl-10">
                                        <input type="phone" name="phone" value="{{ @$user->meta->phone }}" class="form-control" placeholder="00923026262164">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-4 col-lg-3 col-xl-2 col-form-label">Alt. Phone</label>
                                    <div class="col-md-8 col-lg-9 col-xl-10">
                                        <input type="phone" name="alt_phone" value="{{ @$user->meta->alt_phone }}" class="form-control" placeholder="Optional">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-4 col-lg-3 col-xl-2 col-form-label">About</label>
                                    <div class="col-md-8 col-lg-9 col-xl-10">
                                        <textarea name="about" class="form-control" placeholder="Write a short info about yourself...">{{ $user->profile->about }}</textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <button type="submit" class="btn btn-info mr-1">Submit</button>
                                <button type="reset" class="btn btn-secondary">Reset</button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="tab-pane fade" id="tab2" role="tabpanel">
                    <form>
                        <div class="card rounded-0 border-top-0 pt-3">
                            <div class="card-body">
                                <div class="form-group row">
                                    <label class="col-md-4 col-lg-3 col-xl-2 col-form-label">Username</label>
                                    <div class="col-md-8 col-lg-9 col-xl-10">
                                        <input type="text" readonly class="form-control-plaintext" value="abc_xyz">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-4 col-lg-3 col-xl-2 col-form-label">Email</label>
                                    <div class="col-md-8 col-lg-9 col-xl-10">
                                        <input type="email" class="form-control" placeholder="abc@xyz.com">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-4 col-lg-3 col-xl-2 col-form-label">Role</label>
                                    <div class="col-md-8 col-lg-9 col-xl-10">
                                        <select class="custom-select">
                                            <option>please choose</option>
                                            <option>Role 1</option>
                                            <option>Role 2</option>
                                            <option>Role 3</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-4 col-lg-3 col-xl-2 col-form-label">Status</label>
                                    <div class="col-md-8 col-lg-9 col-xl-10">
                                        <select class="custom-select">
                                            <option>please choose</option>
                                            <option>Status 1</option>
                                            <option>Status 2</option>
                                            <option>Status 3</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <button type="submit" class="btn btn-info mr-1">Submit</button>
                                <button type="reset" class="btn btn-secondary">Reset</button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="tab-pane fade" id="tab3" role="tabpanel">
                    <form>
                        <div class="card rounded-0 border-top-0 pt-3">
                            <div class="card-body small">
                                <div class="form-group row">
                                    <label class="col-md-4 col-lg-3 col-xl-2 col-form-label">Old Password</label>
                                    <div class="col-md-8 col-lg-9 col-xl-10">
                                        <input type="password" class="form-control" placeholder="Current Password">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-4 col-lg-3 col-xl-2 col-form-label">New Password</label>
                                    <div class="col-md-8 col-lg-9 col-xl-10">
                                        <input type="password" class="form-control" placeholder="New Password">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-4 col-lg-3 col-xl-2 col-form-label">Confirm Password</label>
                                    <div class="col-md-8 col-lg-9 col-xl-10">
                                        <input type="password" class="form-control" placeholder="Confirm Password">
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <button type="submit" class="btn btn-info mr-1">Submit</button>
                                <button type="reset" class="btn btn-secondary">Reset</button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="tab-pane fade" id="tab4" role="tabpanel">
                    <form>
                        <div class="card rounded-0 border-top-0 pt-3">
                            <div class="card-body">
                                <div class="form-group row">
                                    <label class="col-md-4 col-lg-3 col-xl-2 col-form-label">Street</label>
                                    <div class="col-md-8 col-lg-9 col-xl-10">
                                        <input type="text" class="form-control" placeholder="Street 4, Defence">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-4 col-lg-3 col-xl-2 col-form-label">City</label>
                                    <div class="col-md-8 col-lg-9 col-xl-10">
                                        <input type="text" class="form-control" placeholder="Islamabad">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-4 col-lg-3 col-xl-2 col-form-label">State</label>
                                    <div class="col-md-8 col-lg-9 col-xl-10">
                                        <input type="text" class="form-control" placeholder="Punjab">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-4 col-lg-3 col-xl-2 col-form-label">Zip/Postal</label>
                                    <div class="col-md-8 col-lg-9 col-xl-10">
                                        <input type="text" class="form-control" placeholder="54000">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-4 col-lg-3 col-xl-2 col-form-label">Country</label>
                                    <div class="col-md-8 col-lg-9 col-xl-10">
                                        <select class="custom-select">
                                            <option>please choose</option>
                                            <option selected>Pakistan</option>
                                            <option>Country 1</option>
                                            <option>Country 2</option>
                                            <option>Country 3</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <button type="submit" class="btn btn-info mr-1">Submit</button>
                                <button type="reset" class="btn btn-secondary">Reset</button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="tab-pane fade" id="tab5" role="tabpanel">
                    <form>
                        <div class="card rounded-0 border-top-0 pt-3">
                            <div class="card-body">
                                <div class="form-group row">
                                    <label class="col-md-4 col-lg-3 col-xl-2 col-form-label">Facebook</label>
                                    <div class="col-md-8 col-lg-9 col-xl-10">
                                        <input type="text" class="form-control" placeholder="https://">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-4 col-lg-3 col-xl-2 col-form-label">Google +</label>
                                    <div class="col-md-8 col-lg-9 col-xl-10">
                                        <input type="text" class="form-control" placeholder="https://">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-4 col-lg-3 col-xl-2 col-form-label">LinkedIn</label>
                                    <div class="col-md-8 col-lg-9 col-xl-10">
                                        <input type="text" class="form-control" placeholder="https://">
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <button type="submit" class="btn btn-info mr-1">Submit</button>
                                <button type="reset" class="btn btn-secondary">Reset</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection