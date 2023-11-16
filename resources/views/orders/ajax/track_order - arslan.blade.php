<div class="shadow small py-5 mt-5">
    <div class="row d-flex justify-content-between top">
        <div class="">
            <h6><b>ORDER</b> # <mark class="rounded px-2">{{ $order->reference_number }}</mark></h6>
            <div class="d-flex flex-column pt-2">
                @if($order->created_at)
                <p>Order Date : <b>{{ $order->created_at->format('d M, Y') }}</b></p>
                @endif
                @if($order->shipped_date)
                <p>Shipped Date : <b>{{ $order->shipped_date->format('d M, Y') }}</b></p>
                @endif
                @if($order->delivered_date)
                <p>Delivered Date : <b>{{ $order->delivered_date->format('d M, Y') }}</b></p>
                @endif
                @if($order->status == "cancelled")
                <p>Cancelled Date : <b>{{ $order->updated_at->format('d M, Y') }}</b></p>
                @endif
            </div>
        </div>
        <div class="d-flex flex-column text-md-right">
            <p>Order Total : <span class="font-weight-bold">{{ number_format($order->grand_total,2) }} AED</span></p>
            <p>Whatsapp : <span class="font-weight-bold">+971553800166</span></p>
        </div>
    </div>
    <div class="row d-flex justify-content-center">
        <div class="col-12">
            <ul id="progressbar" class="text-center">
                <li class="active step0"></li>
                <li class="@if($order->status == 'shipped' || $order->status == 'delivered') active @endif step0"></li>
                <li class="@if($order->status == 'delivered') active @endif step0"></li>
            </ul>
        </div>
    </div>
    <div class="row justify-content-between top">
        <div class="row d-flex icon-content" style="margin-left:3% !important;">
            <img class="icon" src="{{asset('images/order-processed.png')}}">
            <div class="d-flex flex-column">
                <p class="font-weight-bold">Order<br>Created</p>
            </div>
        </div>
        <div class="row d-flex icon-content"><img class="icon" src="{{ asset('images/order-shipped.png') }}">
            <div class="d-flex flex-column">
                <p class="font-weight-bold">Order<br>Shipped</p>
            </div>
        </div>
        <div class="row d-flex icon-content"><img class="icon" src="{{ asset('images/order-arrived.png') }}">
            <div class="d-flex flex-column">
                <p class="font-weight-bold">Order<br>Delivered</p>
            </div>
        </div>
    </div>
</div>