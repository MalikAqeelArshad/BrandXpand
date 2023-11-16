@extends('layouts.app')
@push('styles')
    <link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet"
          integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ asset('styles/success.css') }}">
@endpush

@section('content')
@include('layouts.menu')
<div class="container py-5">
    <div class="text-center pb-3">
        <h3>Track Your Order</h3>
        <p class="text-secondary">You may track your order by entering the order number.</p>
    </div>
    <form id="trackOrderForm">
        <div class="input-group shadow-sm col-lg-6 px-0 mx-auto mb-3">
            <div class="input-group-prepend d-none d-sm-flex">
                <span class="input-group-text bg-light border-light"><small>Enter Order #</small></span>
            </div>
            <input type="text" class="form-control border-light" name="order_number" id="orderNum" placeholder="e.g. 000001234567">
            <div class="input-group-append">
                <button type="submit" class="btn btn-brand">Track Order</button>
            </div>
        </div>
    </form>
    <div id="addOrderResult"></div>
</div>
@endsection

@section('scripts')
<script>
    $(document).on('submit','#trackOrderForm',function(e){
        e.preventDefault();
        var data = $.trim($('#orderNum').val());
        var url = '{{ route("order.status", ":data") }}';
        url = url.replace(':data', data);
        $.ajax({
            type:'get',
            url :url,
            success:function(data)
            {
                $('#addOrderResult').html(data);
            },
            error:function(e)
            {
                $('#addOrderResult').html('<div class="alert alert-danger text-center mt-3">Sorry, No Order Found !</div>');
            }
        });
    });
</script>
@endsection