@extends('layouts.app')

@section('content')
@include('layouts.menu')

<div class="container py-5">
    <div class="alert alert-success p-md-5 p-4 shadow-sm border-0">
        <div class="text-center">
            <i class="fa fa-check-circle display-4 text-success mb-2"></i>
            <h3>Congratulation!</h3>
            <p class="text-muted">Your order have been created successfully.</p>
            <p>Your order number is <span class="text-info">{{ $order->reference_number }}</span>, you may please track your order status through us by entering the tracking number.</p>
        </div>
        <p>Welcome to <a href="http://brandxpend.com">www.brandxpend.com</a>. I will be happy to assist your any enquiries. Please contact our customer service team for further assistance.</p>
        <ul class="list-unstyled">
            <li class="d-flex align-items-center py-1">
                <i class="fa mr-3 fa-phone-alt text-success"></i>
                <a href="javascript:;" class="text-dark">00971553800166</a>
            </li>
            <li class="d-flex align-items-center py-1">
                <i class="fab mr-3 fa-whatsapp text-success" data-fa-transform="grow-4"></i>
                <a href="javascript:;" class="text-dark">00971553800166</a>
            </li>
            <li class="d-flex align-items-center py-1">
                <i class="fa mr-3 fa-envelope text-success"></i>
                <a href="javascript:;" class="text-dark">support@brandxpend.com</a>
            </li>
        </ul>
        <small class="text-muted">Thank you for choosing us.</small>
    </div>
</div>
@endsection