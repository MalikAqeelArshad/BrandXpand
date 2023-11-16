@extends('layouts.app')

@section('title','Cart Items')

@push('scripts')
<script type="text/javascript">
    $.fn.AJAX_ITEM_QUANTITY = function($this) {
        var name = $this.attr('wcs-plus') || $this.attr('wcs-minus');
        $input = name ? $('[wcs-quantity="'+name+'"]') : $this;
        var value = Number($input.val()); if (value < 1) { $input.val(1); return false; }
        $.ajax({
            type: 'post',
            data: { '_token': "{{ csrf_token() }}", 'rowId': $input.data('cart-id'), 'qty': $input.val(), },
            url: "{{ route('update.cart') }}",
            success: function (data) {
                if(data == "error") {
                    toastr.error('Quantity not available in stock!');
                } else {
                    $('#subTotal').html(data.subtotal);
                    $('#totalCost').html(data.total + " AED");
                    // $('#totalCost').html(parseFloat(parseInt(data.subtotal)).toFixed(2) + " AED");
                    
                    @if ($usd = __siteMeta('usd') ? (1 / __siteMeta('usd')) : '')
                    // var total = parseFloat(parseInt(data.total)).toFixed(2);
                    var usd = parseFloat(data.total / {{ $usd }}).toFixed(2);
                    $('#approxUSD').html("AED " + data.total + " ~ " + usd + " USD");
                    @endif

                    toastr.success('Cart item updated successfully.');
                }
            }
        });
    }
    $(document).on('input', '[wcs-quantity]', function () {
        $.fn.AJAX_ITEM_QUANTITY($(this));
    }).on('click', '[wcs-plus], [wcs-minus]', function () {
        $.fn.AJAX_ITEM_QUANTITY($(this));
    });

    $(document).on('click', '.removeCartItem', function () {
        var row = $(this);
        $.ajax({
            type: 'get',
            url: "{{ url('/delete/cart-item') }}/" + row.data('cart-id'),
            success: function (data) {
                $('#subTotal').html(data.subtotal);
                $('.cartCount').html(data.cartcount);

                if (data.total > 0) {
                    $('#totalCost').html(data.total + " AED");
                    // $('#totalCost').html(parseFloat(parseInt(data.total)).toFixed(2) + " AED");
                } else {
                    $('#totalItems').remove();
                    $('table tbody').append('<tr><td colspan="4" class="text-center py-5"><i class="fa fa-shopping-cart fa-fw fa-lg text-danger"></i> Cart is empty, please add item to cart!</td></tr>');
                }
                
                @if ($usd = __siteMeta('usd') ? (1 / __siteMeta('usd')) : '')
                // var total = parseFloat(parseInt(data.total)).toFixed(2);
                var usd = parseFloat(data.total / {{ $usd }}).toFixed(2);
                $('#approxUSD').html("AED " + data.total + " ~ " + usd + " USD");
                @endif

                $(row).closest('tr').remove();
                toastr.success('Item removed successfully.');
            }
        });
    });

    $(document).on('click', '#proceedToCheckout', function () {
        $.ajax({
            type:'post',
            data:{ '_token': "{{csrf_token()}}", },
            url:"{{ route('set.ship.cost') }}",
            success:function() {
                window.location.href ="/checkout";
            }
        });
    });
</script>
@endpush

@section('content')
    @include('layouts.menu')

    <div class="container container-max py-5">
        <div class="shadow-sm rounded">
            <table class="table table-responsive table-hover size-md mb-0 rounded-top">
                <thead class="thead-dark text-uppercase">
                    <tr>
                        <th width="1%">#</th>
                        <th style="min-width:320px">Product <small>(s)</small></th>
                        <th>Price</th>
                        <th width="100" class="text-center">Quantity</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($items as $item)
                    <tr>
                        <td class="pt-3">
                            <a href="javascript:;" data-cart-id="{{ $item->rowId }}" class="text-danger removeCartItem" title="Delete Item"><i class="far fa-trash-alt fa-lg"></i></a>
                        </td>
                        <td>
                            <div class="d-flex align-items-start">
                                <figure class="mb-0">
                                    <img src="{{ asset('uploads/products/small/'.$item->model->image) }}" alt="Photo" width="75" class="rounded">
                                </figure>
                                <div class="flex-grow-1 ml-2">
                                    <ul class="list-unstyled small">
                                        <li class="h6 mb-1">
                                            <a href="{{ route('product.detail', $item->model->id) }}" class="text-dark">{{ $item->model->name }}</a>
                                        </li>
                                        <li class="py-1 text-muted">
                                            <span class="weight-md">Category : </span> {{ $item->model->category->name }}
                                        </li>
                                        <li class="py-1 text-muted">
                                            <span class="weight-md">Variation : </span> {{ ucfirst($item->options->attrs) }}
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle">AED {{ $item->model->lastStock('unsold', $item->options->attrs)->discountPrice ?? '-' }}</td>
                        <td class="align-middle">
                            <div class="input-group input-group-sm qtyInput" style="width: 100px">
                                <div class="input-group-prepend">
                                    <button class="btn btn-light border" wcs-minus="q{{$loop->index}}">&minus;</button>
                                </div>
                                <input type="text" name="quant[1]" data-cart-id="{{ $item->rowId }}" value="{{ $item->qty }}" min="1" max="100" class="form-control form-control-sm text-center isNumeric productQty" placeholder="n/a" maxlength="2" wcs-quantity="q{{$loop->index}}">
                                <div class="input-group-append">
                                    <button class="btn btn-light border" wcs-plus="q{{$loop->index}}">&plus;</button>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center py-5">
                            <i class="fa fa-cart-arrow-down fa-fw fa-lg text-danger"></i> Cart is empty, please add item to cart !
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
            @if (Cart::count() > 0)
            @php
            // $total = str_replace(',', '', Cart::total());
            $total = round(Cart::total(), 2);
            $subtotal = round(Cart::subtotal(), 2);
            @endphp
            <div class="row font-weight-light p-4" id="totalItems">
                <div class="col-lg-6 d-flex flex-column">
                    <h6 class="bg-light rounded-pill px-4 py-3"><b>TERMS & CONDITIONS</b></h6>
                    <ul class="size-md pl-sm-5 pl-3 my-4" style="list-style-type: circle">
                        <li class="my-1">Shipping charges will be added according to your state.</li>
                        <li class="my-1">You will get your order with 1 - 2 working days.</li>
                    </ul>
                    <div class="mx-sm-4 mx-0 my-4 mt-auto">
                    <a href="{{ route('front.home') }}" class="btn btn-sm btn-block btn-outline-light rounded-pill px-4 py-2 text-brand">Continue to shopping</a>
                    </div>
                </div>
                <div class="col-lg-6">
                    <h6 class="bg-light rounded-pill px-4 py-3"><b>ORDER SUMMARY</b></h6>
                    <div class="mx-sm-4 mx-0 my-4">
                        <p class="small">Shipping and additional costs are calculated based on values you have entered.</p>
                        <ul class="list-unstyled mb-4">
                            <li class="d-flex justify-content-between py-3 border-bottom">
                                <b class="text-muted">Order Subtotal</b>
                                <b><span id="subTotal">{{ $subtotal }}</span> AED</b>
                            </li>

                            @if ($usd = __siteMeta('usd') ? (1 / __siteMeta('usd')) : '')
                            <li class="d-flex justify-content-between py-3 border-bottom">
                                <b class="text-muted">Approx.</b>
                                <code id="approxUSD">AED {{ $total }} ~ {{ $total / $usd }} USD</code>
                            </li>
                            @endif

                            <li class="d-flex justify-content-between py-3">
                                <b class="text-muted">Total</b>
                                <h5><strong id="totalCost">{{ $subtotal }} AED</strong></h5>
                            </li>
                        </ul>
                        <a href="javascript:;" id="proceedToCheckout" class="btn btn-sm btn-block btn-brand rounded-pill m-1 px-4 py-2">Procceed to checkout</a>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
@endsection