@extends('layouts.app')

@section('title', 'Reset Password')

@section('content')
    <div class="container-fluid py-5" style="background:url(https://www.bird-wittenbergdental.com/wp-content/uploads/2017/01/top-line-management-login-background-1.jpg) center / cover no-repeat">
        <form action="{{ route('password.update') }}" method="POST" class="form-horizontal signup-form bg-white rounded-lg shadow p-4 mx-auto col-xl-5 col-md-7">
            @csrf <h5 class="mb-5"><b class="pb-3 pr-4" style="border-bottom: 3px solid var(--brand)">RESET PASSWORD</b></h5>
            <input type="hidden" name="token" value="{{ $token }}">
            <div class="form-group">
                <label class="small">Email Address</label>
                <input type="email" name="email" value="{{ $email ?? old('email') }}" class="form-control focus-brand {{ $errors->has('email') ? ' is-invalid' : '' }}" required autofocus>
                @if ($errors->has('email'))
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $errors->first('email') }}</strong>
                </span>
                @endif
            </div>
            <div class="form-group">
                <label class="small">Password</label>
                <input type="password" name="password" class="form-control focus-brand {{ $errors->has('password') ? ' is-invalid' : '' }}" required>
                @if ($errors->has('password'))
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $errors->first('password') }}</strong>
                </span>
                @endif
            </div>
            <div class="form-group">
                <label class="small">Confirm Password</label>
                <input type="password" name="password_confirmation" class="form-control focus-brand {{ $errors->has('password_confirmation') ? ' is-invalid' : '' }}" required>
                @if ($errors->has('password_confirmation'))
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $errors->first('password_confirmation') }}</strong>
                </span>
                @endif
            </div>
            <div class="form-group small d-sm-flex align-items-center text-center mb-0">
                <button type="submit" class="btn btn-brand rounded-pill col-12 col-sm-auto px-5 my-3">Reset Password</button>
                <a class="text-brand ml-sm-3" href="{{ route('register') }}">New to BrandXpend?</a>
            </div>
        </form>
    </div>
@endsection
