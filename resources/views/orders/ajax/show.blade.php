<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">View Order</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div >
                <div class="row">
                    <div class="col-lg-12 mx-auto">
                        <ul class="list-group shadow">
                            @foreach($orders as $order)
                                <li class="list-group-item">
                                    <div class="media align-items-lg-center flex-column flex-lg-row p-3">
                                        <div class="media-body order-2 order-lg-1 ml-3">
                                            <h5 class="mt-0 font-weight-bold mb-2">{{ $order->product->name }}</h5>

                                            <div class="d-flex align-items-center justify-content-between mt-1">
                                                <h6 class="my-2"><span> Price : </span>
                                                    @if(!empty($order->product->lastStock()))
                                                            @if($order->product->lastStock()->discount > 0) {{ __getDiscountPrice($order->product->lastStock()->id) }}
                                                            @else {{ $order->product->lastStock()->sale }}
                                                            @endif
                                                        <small>AED</small>
                                                    @else
                                                        @if($order->product->getLastStock()->discount > 0)
                                                            {{ __getDiscountPrice($order->product->getLastStock()->id) }}
                                                        @else
                                                            {{ $order->product->getLastStock()->sale }}
                                                        @endif
                                                        <small>AED</small>
                                                    @endif
                                                </h6>
                                                @php $stockQty = explode(',' , $order->product_stock_ids); @endphp
                                                <p><span> Quantity : </span>{{ sizeof($stockQty) }}</p>

                                            </div>
                                        </div>
                                        <img src="{{ asset('uploads/products/large/'.$order->product->image) }}" style="height:100px !important;">
                                    </div>
                                </li>
                            @endforeach
                            <li class="list-group-item">
                                <div class="row p-3">
                                    <div class="align-items-center justify-content-between col-sm-6 mt-1 pl-3">
                                        <p class="my-2">Order ID<span class="float-right"> <b>{{ $orders[0]->reference_number }}</b> </span>
                                        </p>
                                        <p class="my-2">Price <span class="float-right"> <b>{{$orders[0]->grand_total}}</b>  <small>AED</small></span>
                                        </p>
                                        <p class="my-2">Shipping Cost<span class="float-right"> <b>{{$orders[0]->shipping_charges}}</b>  <small>AED</small></span>
                                        </p>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
