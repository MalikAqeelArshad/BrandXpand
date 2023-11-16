@extends('layouts.app')

@section('title', 'Reset Password Email')

@section('content')
    <div class="container-fluid py-5" style="background:url(https://www.bird-wittenbergdental.com/wp-content/uploads/2017/01/top-line-management-login-background-1.jpg) center / cover no-repeat">
        <form action="{{ route('password.email') }}" method="POST" class="form-horizontal signup-form bg-white rounded-lg shadow p-4 mx-auto col-xl-5 col-md-7">
            @csrf <h5 class="mb-5"><b class="pb-3 pr-4" style="border-bottom: 3px solid var(--brand)">RESET PASSWORD</b></h5>
            @if (session('status'))
            <div class="alert alert-success small" role="alert">
                {{ session('status') }}
            </div>
            @endif
            <div class="form-group">
                <label class="small">Email Address</label>
                <input type="email" name="email" value="{{ old('email') }}" class="form-control focus-brand {{ $errors->has('email') ? ' is-invalid' : '' }}" required>
                @if ($errors->has('email'))
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $errors->first('email') }}</strong>
                </span>
                @endif
            </div>
            <div class="form-group small d-flex justify-content-between">
                <a class="text-brand" href="{{ route('login') }}"><i class="fa fa-arrow-left fa-fw"></i> Go Back</a>
                <a class="text-brand ml-sm-5 d-inline-block" href="{{ route('register') }}">New to BrandXpend?</a>
            </div>
            <div class="form-group pt-3">
                <button type="submit" class="btn btn-block btn-brand rounded-pill"><small>Send Password Reset Link</small></button>
            </div>
        </form>
    </div>
@endsection
