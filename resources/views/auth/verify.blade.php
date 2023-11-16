@extends('layouts.app')

@section('title', 'Email Verify')

@section('content')
    @include('layouts.menu')
    <div class="container-fluid py-5" style="background:url(https://www.bird-wittenbergdental.com/wp-content/uploads/2017/01/top-line-management-login-background-1.jpg) center / cover no-repeat">
        <div class="form-horizontal bg-white rounded-lg shadow col-xl-5 col-md-7 p-4 mx-auto my-4">
            <h6 class="mb-5"><b class="pb-3 pr-sm-4" style="border-bottom: 3px solid var(--brand)">Verify Your Email Address</b></h6>
            @if (session('resent'))
            <div class="alert alert-success small" role="alert">
                {{ __('A fresh verification link has been sent to your email address.') }}
            </div>
            @endif
            {{ __('Before proceeding, please check your email for a verification link.') }}
            {{ __('If you did not receive the email') }}, <a href="{{ route('verification.resend') }}">{{ __('click here to request another') }}</a>.
        </div>
    </div>
@endsection
