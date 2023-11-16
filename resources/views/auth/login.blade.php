@extends('layouts.app')

@section('title', 'Login')

@section('content')
    <div class="container-fluid py-5" style="background:url(https://www.bird-wittenbergdental.com/wp-content/uploads/2017/01/top-line-management-login-background-1.jpg) center / cover no-repeat">
        <form action="{{ route('login') }}" method="POST" class="form-horizontal signup-form bg-white rounded-lg shadow p-4 mx-auto col-xl-5 col-md-7">
            @csrf <h4 class="mb-5"><b class="pb-3 pr-4" style="border-bottom: 3px solid var(--brand)">LOGIN</b></h4>
            <div class="form-group">
                <label class="small">Email Address</label>
                <input type="email" name="email" value="{{ old('email') }}" class="form-control focus-brand {{ $errors->has('email') ? ' is-invalid' : '' }}" required>
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
            <div class="form-group small d-sm-flex align-items-center text-center mb-0">
                <button type="submit" class="btn btn-brand rounded-pill col-12 col-sm-auto px-5 my-3">Login</button>
                <div class="d-flex justify-content-between flex-grow-1 ml-sm-3">
                    <a class="text-brand" href="{{ route('password.request') }}">Forgot Password?</a>
                    <a class="text-brand" href="{{ route('register') }}">New to BrandXpend?</a>
                </div>
            </div>
        </form>
    </div>
@endsection