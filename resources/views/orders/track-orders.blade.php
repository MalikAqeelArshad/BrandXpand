@extends('layouts.app')

@section('title','Track  Orders')
<link rel="stylesheet" href="{{ asset('styles/rating.css') }}">
@section('content')
    <div class="container pt-5 pb-5" style="height: 50vh">
        <div class="row justify-content-center">
            <div class="col-md-3">
                <div class="list-group">
                    <a href="" class="list-group-item list-group-item-action active orders-menu">
                        All Orders
                    </a>
                </div>
            </div>
            <div class="col-md-9">
                <table class="table table-responsive table-borderless table-striped table-hover small shadow-sm rounded">
                    <thead class="bg-secondary text-white">
                    <tr>
                        <th width="1%"></th>
                        <th>Order #</th>
                        <th>Payment Method</th>
                        <th>Shipping Charges</th>
                        <th>Status</th>
                        <th>Total</th>
                        <th width="15%">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $ref = []; ?>
                    @forelse ($orders as $order)
                        @if(!in_array($order->reference_number , $ref))
                            <tr>
                                <th></th>
                                <td>{{$order->reference_number}}</td>
                                <td>{{ $order->payment_method }}</td>
                                <td>{{number_format($order->shipping_charges,2) }}
                                    <small>AED</small>
                                </td>
                                <td>
                                    @if ($order->status == "pending")
                                        <span class="badge badge-danger font-weight-normal">pending</span>
                                    @elseif ($order->status == "delivered")
                                        <span class="badge badge-success font-weight-normal">delivered</span>
                                    @else
                                        <span class="badge badge-warning font-weight-normal">shipped</span>
                                    @endif
                                </td>

                                <td>{{ number_format($order->grand_total,2 ) }}
                                    <small>AED</small>
                                </td>
                                <td>
                                    <a href="javascript:;" id="getOrder"
                                       data-action="{{ route('order.show', $order->reference_number) }}"
                                       data-hover="tooltip"
                                       title="View"
                                    ><i class="far fa-eye fa-fw text-info fa-lg"></i></a>
                                    @if($order->status == "delivered")
                                        <a class="ml-1 badge badge-danger" href="javascript:;" id="review"
                                           data-action="{{ route('order.review', $order->reference_number) }}"
                                           data-hover="tooltip"
                                           title="View">
                                            <i class="far fa-star fa-fw fa-lg"></i>Review</a>
                                    @endif

                                </td>
                            </tr>
                        @endif
                        <?php $ref[] = $order->reference_number; ?>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center">There is no record exist.</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Rate Product</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="" method="post">
                    @csrf
                    <div class="modal-body">
                        <section class='rating-widget'>

                            <!-- Rating Stars Box -->
                            <div class='rating-stars text-center'>
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
                            <div class="form-group">
                                <textarea name="user_review" id="" class="form-control"
                                          placeholder="Write your Review..." style="resize:none" rows="10"></textarea>
                            </div>
                        </section>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">Submit</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div id="appendModal"></div>

@endsection
@section('scripts')
    <script>
        $(document).on('click', '#getOrder', function () {
            $.ajax({
                type: 'get',
                url: $(this).attr('data-action'),
                success: function (data) {
                    $('#appendModal').html(data);
                    $('#editModalBtn').click();
                }
            });
        });

        $(document).on('click', '#review', function () {
            $.ajax({
                type: 'get',
                url: $(this).attr('data-action'),
                success: function (data) {
                    $('#appendModal').html(data);
                    $('#reviewModalBtn').click();
                    /* 1. Visualizing things on Hover - See next part for action on click */
                    $('#stars li').on('mouseover', function () {
                        var onStar = parseInt($(this).data('value'), 10); // The star currently mouse on

                        // Now highlight all the stars that's not after the current hovered star
                        $(this).parent().children('li.star').each(function (e) {
                            if (e < onStar) {
                                $(this).addClass('onHover');
                            } else {
                                $(this).removeClass('onHover');
                            }
                        });

                    }).on('mouseout', function () {
                        $(this).parent().children('li.star').each(function (e) {
                            $(this).removeClass('onHover');
                        });
                    });
                    /* 2. Action to perform on click */
                    $('#stars li').on('click', function () {
                        var rate = $(this).attr('data-value');
                        $(this).closest('.rating-widget').find('.appendRate').html('<input type="hidden" value="' + rate + '" name="rating">');
                        var onStar = parseInt($(this).data('value'), 10); // The star currently selected
                        var stars = $(this).parent().children('li.star');

                        for (i = 0; i < stars.length; i++) {
                            $(stars[i]).removeClass('selected');
                        }
                        for (i = 0; i < onStar; i++) {
                            $(stars[i]).addClass('selected');
                        }


                        var ratingValue = parseInt($('#stars li.selected').last().data('value'), 10);
                        var msg = "";
                        if (ratingValue > 1) {
                            msg = "Thanks! You rated this " + ratingValue + " stars.";
                        } else {
                            msg = "We will improve ourselves. You rated this " + ratingValue + " stars.";
                        }

                        $(this).closest('.rating-widget').find('.text-message').html("<span class='text-success f-size14'>" + msg + "</span>");

                    });
                }
            });
        });

        $(document).on('click','.reviewSubmitBtn',function(){
            var btn = $(this);
            var order_id = $(this).closest('form').find("input[name='order_id']").val();
            var rating = $(this).closest('form').find("input[name='rating']").val();
            var review = $.trim($(this).closest('form').find("textarea").val());
            $.ajax({
                type:"post",
                data:{
                    '_token': "{{csrf_token()}}",
                    'order_id' : order_id,
                    'rating' : rating,
                    'review' : review
                },
                url: "{{ route('save.review') }}",
                success:function(data)
                {
                    var getReview = $.parseJSON(data);
                    $(btn).closest('.col-md-12').append('<div> <h6 class="text-brand">Your Review</h6> <p>'+ getReview.review +'</p> </div>');
                    $(btn).closest('section').remove();
                    toastr.success('Review Added Successfully');
                },
                error:function (error) {
                    toastr.options={
                        "positionClass": "toast-top-center",
                    };
                    toastr.warning('Rate Product!');
                }

            });
        });

    </script>

@endsection