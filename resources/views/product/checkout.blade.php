@extends('layouts.app')
@section('title', 'Checkout')

@push('styles')
<style>
.fz80 {
    font-size: 80%
}
a.PT_open_iframe {
    display: none !important;
}
</style>
@endpush

@section('content')
@include('layouts.menu')
<div class="container mt-5">
    <div class="row">
        <div class="col-md-4 order-md-2 mb-3">
            <div class="sticky-top" style="top: 6rem">
                <h4 class="d-flex justify-content-between align-items-center mb-3">
                    <span class="text-muted">Your cart</span>
                    <span class="badge badge-secondary badge-pill">{{ Cart::content()->count() }}</span>
                </h4>
                <ul class="list-group list-group-flush shadow">
                    @foreach(Cart::content() as $cart)
                    <li class="list-group-item border-light">
                        <div class="d-flex justify-content-between">
                            <h6 title="{{ $cart->model->name }}">{{ substr($cart->model->name, 0, 25) }}</h6>
                            <span class="text-muted">{{ $cart->model->lastStock('unsold', $cart->options->attrs)->discountPrice * $cart->qty }} AED</span>
                        </div>
                        <div class="d-flex justify-content-between text-muted size-xs">
                            <span>Variation : {{ $cart->options->attrs }}</span>
                            <span>Quantity : {{ $cart->qty }}</span>
                        </div>
                        {{-- <span class="text-muted">{{ $cart->model->lastStock()->discount > 0 ? __getDiscountPrice($cart->model->lastStock()->id) * $cart->qty :$cart->model->lastStock()->sale * $cart->qty }} AED</span> --}}
                    </li>
                    @endforeach
                    @if(session('discount'))
                    <li class="list-group-item d-flex justify-content-between">
                        <div class="text-success">
                            <h6 class="my-0">Coupon Discount</h6>
                        </div>
                        <span class="text-success">{{ session('discount') }} %</span>
                    </li>
                    @endif
                    <div id="addShipCost">
                        @if(session('shipping_cost'))
                        <li class="list-group-item d-flex justify-content-between bg-light border-top">
                            <div class="text-danger">
                                <h6 class="my-0">Shipping Cost</h6>
                            </div>
                            <span class="text-danger">{{ session('shipping_cost') }} <small>AED</small></span>
                        </li>
                        @endif
                    </div>
                    <li class="list-group-item d-flex justify-content-between" id="checkoutPageTotal">
                        <span>Total (AED)</span>
                        <strong id="checkoutTotal"> {{ round($total, 2) }} </strong>
                        {{-- <strong id="checkoutTotal"> {{ number_format(str_replace(',', '', $total), 2) }} </strong> --}}
                    </li>
                </ul>
            </div>
        </div>
        <div class="col-md-8 order-md-1 mb-5">
            @if(Session::has('stripeErr'))
            <div class="row">
                <div class="alert alert-danger mt-1 col-md-6 col-md-offset-3" id="showError" style="display:none"></div>
            </div>
            @endif
            @if(Auth::check())
            <form method="post" action="{{ route('cash.on.delivery') }}" id="shippingForm" class="size-md shadow-sm border border-light p-4" novalidate>
                @csrf <input type="hidden" name="type" value="shipping">
                <h5>Add Shipping Address</h5> <hr>
                <div class="form-group">
                    <small class="text-brand"><b>* For self collect skip this step</b></small>
                </div>
                <div class="form-group e-address">
                    <label for="address">Address</label>
                    <input type="text" class="form-control form-control-lg" name="address" id="address" value="{{ session('address')?: old('address')}}" placeholder="1234 Main St">
                    @if($errors->has('address'))
                    <div class="alert alert-danger mt-1"> {{ str_replace('address','Address',$errors->first('address')) }} </div>
                    @endif
                </div>
                <div class="row">
                    <div class="col-md-12 mb-3 e-mobile">
                        <label for="mobile">Mobile</label>
                        <input type="text" class="form-control form-control-lg" name="mobile" id="mobile"
                        value="{{session('mobile')?: old('mobile')}}" required>
                        @if($errors->has('mobile'))
                        <div class="alert alert-danger mt-1"> {{ str_replace('mobile','Mobile',$errors->first('mobile')) }} </div>
                        @endif
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3 e-country">
                        <label for="country">Country</label>
                        <select name="country" id="country" class="custom-select custom-select-lg" required>
                            <option value="ARE">United Arab Emirates</option>
                            @foreach(get_countries_list() as $code => $name)
                            @if ($code != 'ARE' && in_array($code, \App\ShippingCost::pluck('country')->toArray()))
                            <option value="{{ $code }}" @if(in_array($code, [old('country'), session('country')])) selected @endif> {{ $name }} </option>
                            @endif
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6 mb-3 e-state">
                        <label for="state">State</label>
                        <div class="input-group">
                            <select name="state" id="state" class="custom-select custom-select-lg" required>
                                <option value="" disabled selected>please choose</option>
                                @foreach(__all('ShippingCost') as $obj)
                                <option value="{{ $obj->id }}" @if($obj->id == old('state') || session('shipping_state') == $obj->state) selected @endif> {{ $obj->state }} </option>
                                @endforeach
                            </select>
                        </div>
                        @if($errors->has('state'))
                        <div class="alert alert-danger mt-1 mb-0"> {{ $errors->first('state') }} </div>
                        @endif
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3 e-city">
                        <label for="city">City</label>
                        <input type="text" class="form-control form-control-lg" id="city" name="city"
                        value="{{session('city')?: old('city')}}"
                        required>
                        @if($errors->has('city'))
                        <div class="alert alert-danger mt-1"> {{ str_replace('city','City',$errors->first('city')) }} </div>
                        @endif
                    </div>

                    <div class="col-md-6 mb-3 e-postcode">
                        <label for="postcode">Post Code</label>
                        <input type="text" class="form-control form-control-lg" name="postcode" id="postcode"
                        value="{{session('postcode')?: old('postcode')}}" required>
                    </div>
                </div>
            </form>

            <div class="bg-light shadow-sm border border-light p-4">
                <h5>Select Payment Method</h5> <hr/>
                <div class="custom-control custom-radio my-1 text-muted">
                    <input id="code" value="code" name="paymentMethod" type="radio" class="custom-control-input">
                    <label class="custom-control-label" for="code">Cash on Delivery</label>
                </div>
                <div class="custom-control custom-radio my-1 text-muted">
                    <input id="paymentRadio" value="paymentRadio" name="paymentMethod" type="radio" class="custom-control-input">
                    <label class="custom-control-label" for="paymentRadio">Pay with Card</label>
                </div>
                <div class="custom-control custom-radio my-1 text-muted">
                    <input id="self" value="self" name="paymentMethod" type="radio" class="custom-control-input" >
                    <label class="custom-control-label" for="self">Self Collect From Our Store
                        <small class="text-brand ml-3">* No Shipping Cost</small>
                    </label>
                </div>

                <div id="payBtn" class="d-none mt-3">
                    <a href="javascript:;" onclick="Paytabs.openPaymentPage()" class="btn btn-outline-danger">Pay with Card</a>
                    <div id="mypayment"></div>
                </div>

                <form id="codForm" class="d-none">
                    @csrf
                    <button type="button" id="codBtn" class="btn btn-outline-danger mt-3">Confirm Order</button>
                </form>

                <form method="post" action="{{route('self.collect')}}" id="selfForm" class="d-none">
                    @csrf
                    <button type="submit" id="selfBtn" class="btn btn-outline-danger mt-3">Confirm Order</button>
                </form>
            </div>
            @else
            <div class="alert alert-danger mt-1">Currently you're not Login. Please Login to Continue ! <a href="{{ route('login') }}">Login</a></div>
            @endif
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).on('click', '#codBtn', function () {
    $('#shippingForm').submit();
}).on('change', '[name="paymentMethod"]', function () {
    var paycheck = $(this);
        // console.log(this.value);
        if (this.checked && this.value === 'code') {
            $('#state').change();
            $('#codForm').removeClass('d-none');
            $('#payBtn, #selfForm').addClass('d-none');
        } else if (this.checked && this.value === 'self') {
            $('#codForm, #payBtn').addClass('d-none');
            $('#selfForm').removeClass('d-none');

            if ($('#state').val() > 0) {
                $.ajax({
                    type: 'post',
                    data: {
                        '_token': "{{ csrf_token() }}",
                        'id': $('#state').val(),
                        'self': 1,
                    },
                    url: "{{ route('add.ship.cost') }}",
                    success: function (data) {
                        $('#state option[value=""]').attr("selected", true);
                        $('#checkoutTotal').html(parseFloat(data.total).toFixed(2));

                        $('#addShipCost').html(`<li class="list-group-item d-flex justify-content-between bg-light border-top">
                            <div class="text-danger">
                            <h6 class="my-0">Shipping Cost</h6>
                            </div>
                            <span class="text-danger">${data.shippingCost} <small>AED</small></span>
                            </li>`);
                        toastr.success('shipping cost removed!');
                    }
                });
            } else {
                //var totalCartAmount = {{Cart::total()}};
                var totalCartAmount = {{ $total }};
                var discount = {{ session('discount') ?: 0 }}, total = {{ $total }};

                if (discount) {
                    totalCartAmount -= (total / 100) * discount;
                }

                $('#state option[value=""]').attr("selected", true);
                $('#checkoutTotal').html(parseFloat(totalCartAmount).toFixed(2));

                $('#addShipCost').html(`<li class="list-group-item d-flex justify-content-between bg-light border-top">
                    <div class="text-danger">
                    <h6 class="my-0">Shipping Cost</h6>
                    </div>
                    <span class="text-danger">0 <small>AED</small></span>
                    </li>`);
                toastr.success('shipping cost removed!');
            }
        } else {
            $('#payBtn').removeClass('d-none');
            $('#codForm, #selfForm').addClass('d-none');

            var address = $('#address');
            var mobile = $('#mobile');
            var state = $('#state');
            var city = $('#city');
            var postcode = $('#postcode');
            var country = $('#country');

            $.ajax({
                type: 'post',
                data: {
                    'address': address.val(),
                    'mobile': mobile.val(),
                    'state': state.val(),
                    'city': city.val(),
                    'postcode': postcode.val(),
                    'country' : country.val()
                },
                url: "{{route('show.pay')}}",
                success: function (data) {
                    $('#address').attr('value', address.val());
                    $('#mobile').attr('value', mobile.val());
                    $('#country option[value="'+country.val()+'"]').attr("selected", true);
                    $('#state option[value="'+state.val()+'"]').attr("selected", true);
                    $('#city').attr('value', city.val());
                    $('#postcode').attr('value', postcode.val());

                    $("div").remove(".alert");
                    $('#mypayment').html(data);

                    $('#payBtn').removeClass('d-none');
                    $('#codForm').addClass('d-none');
                    if ($('#code').is(':checked')) {
                        $('#code').attr('checked', 'checked');
                    } else {
                        $('#paymentRadio').attr('checked', 'checked');
                    }
                    $('a.PT_open_iframe').remove();
                },
                error: function (error) {
                    $('[class*=e-] .alert').remove();
                    $('#code').prop('checked', true);
                    $('#payBtn, #codForm').toggleClass('d-none');
                    $.each(error.responseJSON.errors, function(key, val){
                        // console.log(key + ' ' + val);
                        $('.e-'+key).append('<div class="alert alert-danger mt-1 mb-0">' + val + '</div>');
                    });
                }
            });
        }
    });

$(document).on('change', '#country', function () {
    var data = $(this).val();
    var url = '{{ route("get.states", ":data") }}';
    url = url.replace(':data', data);
    $.ajax({
        type:'get',
        url : url,
        success:function(data) {
            $('#state').html(data);
            if (data) { $('#state').change(); }
        }
    });
}).ready($('#country').change());

$(document).on('change', '#state', function () {
    $.ajax({
        type: 'post',
        data: {'id': $(this).val()},
        url: "{{ route('add.ship.cost') }}",
        success: function (data) {
            if($('#self').is(':checked')) {
                $('#self').prop('checked', false);
            }

            $('#checkoutTotal').html(parseFloat(data.total).toFixed(2));

            $('#addShipCost').html(`<li class="list-group-item d-flex justify-content-between bg-light border-top">
                <div class="text-danger">
                <h6 class="my-0">Shipping Cost</h6>
                </div>
                <span class="text-danger">${data.shippingCost} <small>AED</small></span>
                </li>`);
            toastr.warning('shipping cost added!');
        }
    });
});
</script>
@endsection