<div class="modal fade" id="reviewModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title">Rate our products</h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div>
                <div class="row">
                    <div class="col-sm-12">
                        <p class="text-center" style="margin-top:10px">ORDER ID : <b>{{ $orders[0]->reference_number }}</b>
                        </p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 mx-auto">
                        <ul class="list-group shadow">
                            @foreach($orders as $order)
                                <li class="list-group-item">
                                    <div class="media align-items-lg-center flex-column flex-lg-row p-3">
                                        <div class="media-body order-2 order-lg-1 ml-3">
                                            <h5 class="mt-0 mb-2">{{ $order->product->name }}</h5>
                                            <div class="d-flex align-items-center justify-content-between mt-1">
                                            </div>
                                        </div>
                                        <img src="{{ asset('uploads/products/large/'.$order->product->image) }}"
                                             style="height:80px !important;">
                                    </div>
                                    <div class="col-md-12">
                                        @if(@$order->review)
                                            <div>
                                                <h6 class="text-brand"> Your Review</h6>
                                                <p >{{ $order->review->review }}</p>
                                            </div>
                                        @else
                                            <section class='rating-widget'>
                                                <div class='rating-stars'>
                                                    <ul id='stars'>
                                                        <li class='star' title='Poor' data-value='1'>
                                                            <i class='fa fa-star fa-fw'></i>
                                                        </li>
                                                        <li class='star' title='Fair' data-value='2'>
                                                            <i class='fa fa-star fa-fw'></i>
                                                        </li>
                                                        <li class='star' title='Good' data-value='3'>
                                                            <i class='fa fa-star'></i>
                                                        </li>
                                                        <li class='star' title='Excellent' data-value='4'>
                                                            <i class='fa fa-star fa-fw'></i>
                                                        </li>
                                                        <li class='star' title='WOW!!!' data-value='5'>
                                                            <i class='fa fa-star fa-fw'></i>
                                                        </li>
                                                    </ul>
                                                </div>
                                                <div class='success-box'>
                                                    <div class='clearfix'></div>
                                                    <div class='text-message'></div>
                                                    <div class='clearfix'></div>
                                                </div>
                                                <form>
                                                    @csrf
                                                    <div class="appendRate"></div>
                                                    <input type="hidden" value="{{ $order->id }}" name="order_id">
                                                    <div class="form-group">
                                                    <textarea name="review" class="form-control"
                                                              placeholder="Write your Review..."></textarea>
                                                    </div>
                                                    <button type="button" class="btn btn-danger btn-sm reviewSubmitBtn">
                                                        Submit
                                                    </button>
                                                </form>
                                            </section>
                                        @endif
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<button class="d-none" data-toggle="modal" data-target="#reviewModal" id="reviewModalBtn"></button>