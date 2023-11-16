<ul class="list-group list-group-flush size-md">
    @foreach($orders as $order)
        <li class="list-group-item">
            @role(['administrator','admin'])
            <div class="clearfix">
                <a href="{{ route('users.edit', $order->user->id) }}" target="_blank" class="badge badge-light float-right font-weight-light">{{ $order->product->user->profile->full_name ?? $order->product->user->email }}</a>
            </div>
            @endrole
            <div class="media flex-column flex-sm-row align-items-center">
                <figure class="mr-3">
                    <img src="{{ asset('uploads/products/small/'.$order->product->image) }}" class="img-fluid">
                </figure>
                <div class="media-body w-100">
                    <h6 class="mb-1"><a href="{{ route('products.show', $order->product->id) }}" target="_blank" class="btn-link text-dark">{{ $order->product->name }}</a></h6>
                    @php
                        $ids = explode(',', $order->product_stock_ids);
                        $stock = $order->stocks($ids)->first();
                    @endphp
                    <code class="text-muted small">Variation : {{ $stock->attrs }}</code>
                    <div class="d-flex justify-content-between align-items-center">
                        {{-- <p class="mb-2">Price : {{ $order->product->discountPrice }} <small>AED</small></p> --}}
                        <p class="my-2">Price : {{ $stock->discountPrice }} <small>AED</small></p>
                        <p class="my-2">Qty : {{ count($ids) }}</p>
                        <p class="my-2">SubTotal : {{ $order->total }}</p>
                    </div>
                </div>
            </div>
        </li>
    @endforeach

    @php
        $firstOrder = $orders->first();
    @endphp

    <li class="list-group-item">
        <h6 class="text-muted">Shipping Detail</h6>
        <p class="my-2"><b>Name</b> : <span>{{ optional($firstOrder->user->profile)->full_name ?: $firstOrder->user->email }}</span></p>
        <p class="my-2"><b>Mobile</b> : <span>{{ optional($firstOrder->address)->mobile }}</span></p>
        <p class="my-2"><b>Address</b> : <span>{{ optional($firstOrder->address)->address }}</span></p>
        <p class="my-2"><b>State</b> : <span>{{ optional($firstOrder->address)->state }}</span></p>
    </li>

    <li class="list-group-item">
        <p class="d-flex align-items-center justify-content-between my-2">
            <b>Order ID</b>
            <b>{{ $firstOrder->reference_number }}</b>
        </p>
        <p class="d-flex align-items-center justify-content-between my-2">
            <b>Total Price</b>
            <span> {{ $orders->sum('total') }} <small>AED</small></span>
        </p>
        @role(['administrator', 'admin'])
        <p class="d-flex align-items-center justify-content-between my-2">
            <b>Shipping Cost</b>
            <span>{{ $firstOrder->shipping_charges }} <small>AED</small></span>
        </p>
        <hr>
        <p class="d-flex align-items-center justify-content-between my-2">
            <b>Grand Total</b>
            {{-- <span>{{ $firstOrder->grand_total }} <small>AED</small></span> --}}
            <span>{{ $orders->sum('total') + $firstOrder->shipping_charges }} <small>AED</small></span>
        </p>
        @endrole
    </li>

    @role(['administrator', 'admin'])
    <li class="list-group-item p-0">
        <form method="POST" action="{{ route('orders.update', $firstOrder->reference_number) }}">
            @csrf @method('PUT')
            <div class="d-sm-flex align-items-center justify-content-end alert alert-secondary rounded-0 m-0">
                <label class="flex-shrink-0" for="status">Change Status</label>
                <select name="status" class="custom-select mx-sm-2 mb-sm-0 mb-3 rounded" id="status">
                    <option value="pending" {{ selected('pending', $firstOrder->status) }}>Pending</option>
                    <option value="shipped" {{ selected('shipped', $firstOrder->status) }}>Shipped</option>
                    <option value="delivered" {{ selected('delivered', $firstOrder->status) }}>Delivered</option>
                    <option value="cancelled" {{ selected('cancelled', $firstOrder->status) }}>Cancelled</option>
                </select>
                <button type="submit" class="btn btn-info btn-block"><small>Update</small></button>
            </div>
        </form>
    </li>
    @endrole
</ul>