@extends('layouts.app')

@push('pluginCSS')
<link rel="stylesheet" href="{{ asset('plugins/swiper/swiper.min.css') }}">
@endpush

@push('pluginJS')
<script type="text/javascript" src="{{ asset('plugins/swiper/swiper.min.js') }}"></script>
@endpush

@push('scripts')
<script type="text/javascript">
    $(document).ready(function () {
        $('.swiper-container').each(function (index) {
            var klass = $(this).addClass('s'+index);
            var next = $(this).closest(klass).children(".swiper-button-next").addClass('s'+index);
            var prev = $(this).closest(klass).children(".swiper-button-prev").addClass('s'+index);
            new Swiper(klass, {
                slidesPerView: 4,
                spaceBetween: 30,
                navigation: {
                    nextEl: next,
                    prevEl: prev,
                },
                breakpoints: {
                    991: {slidesPerView: 3},
                    420: {slidesPerView: 2}
                }
            });
        });
        // logo swiper
        var logos = {{ \App\Logo::wherePublish(true)->count() }};
        var swiper = new Swiper('#swiper', {
            observer: true,
            observeParents: true,
            spaceBetween: 0,
            slidesPerView: logos < 6 ? logos : 6,
            loop: logos > 6 ? true : false,
            autoplay: {
                delay: 5000,
            },
            breakpoints: {
                640: {
                    slidesPerView: logos < 4 ? logos : 3
                },
                320: {
                    slidesPerView: logos < 3 ? logos : 2
                }
            }
        });
        $("#swiper").hover(function(){
            swiper.autoplay.stop();
        }, function(){
            swiper.autoplay.start();
        });
    });

    $(document).on('click', '.addToCart', function(e){
        e.preventDefault(); var btn = $(this);
        $.ajax({
            type: 'post',
            data: { '_token': "{{ csrf_token() }}", 'product_id': btn.val(), 'qty' : 1 , 'attrs' : 'main' },
            url : "{{ route('add.to.cart') }}",
            success: function(data) {
                if(data == "error") {
                    toastr.error('Quantity not available in stock!');
                } else {
                    btn.html('<i class="fa fa-check"></i> Added to Cart').attr('disabled', true);
                    $('.cartCount').html(data); toastr.success('Item added to cart successfully');
                }
            }
        });
    });
</script>
@endpush

@section('content')

    @include('layouts.menu')
    @include('layouts.slider-main')

    <main class="site-content pt-5">

        @if (count($promotions = __products('promotion', $publish = 1)))
        <section class="promotion-products border-bottom border-light mb-5">
            <div class="text-center">
                <h2 class="font-weight-bold mb-0 text-uppercase">{{ __siteMeta('promotion') ?: 'PROMOTION' }}</h2>
                <small class="text-danger">Newest trends from top brands</small>
            </div>
            <div class="container container-max">
                {{-- <div class="row stock-list size-md py-4">
                    @each('partials.product-item', $promotions, 'product')
                </div> --}}

                <div class="swiper-container p-0 m-0">
                    <div class="swiper-wrapper stock-list size-md py-4">
                        @foreach ($promotions as $product)
                        <div class="swiper-slide h-auto">
                            @include('partials.product-item', ['class' => 'h-100'])
                        </div>
                        @endforeach
                    </div>
                    @if (count($promotions) > 4)
                    <div class="swiper-button-next bg-none"><i class="fa fa-fw fa-lg fa-arrow-right text-brand"></i></div>
                    <div class="swiper-button-prev bg-none"><i class="fa fa-fw fa-lg fa-arrow-left text-brand"></i></div>
                    @endif
                </div>
            </div>
        </section>
        @endif

        @if (count($arrivals = __products('arrival', $publish = 1)))
        <section class="new-arrival-products border-bottom border-light mb-5">
            <div class="text-center">
                <h2 class="font-weight-bold mb-0 text-uppercase">{{ __siteMeta('arrival') ?: 'NEW ARRIVAL' }}</h2>
                <small class="text-danger">Newest trends from top brands</small>
            </div>
            <div class="container container-max">
                {{-- <div class="row stock-list size-md py-4">
                    @each('partials.product-item', $arrivals, 'product')
                </div> --}}

                <div class="swiper-container p-0 m-0">
                    <div class="swiper-wrapper stock-list size-md py-4">
                        @foreach ($arrivals as $product)
                        <div class="swiper-slide h-auto">
                            @include('partials.product-item', ['class' => 'h-100'])
                        </div>
                        @endforeach
                    </div>
                    @if (count($arrivals) > 4)
                    <div class="swiper-button-next bg-none"><i class="fa fa-fw fa-lg fa-arrow-right text-brand"></i></div>
                    <div class="swiper-button-prev bg-none"><i class="fa fa-fw fa-lg fa-arrow-left text-brand"></i></div>
                    @endif
                </div>
            </div>
        </section>
        @endif

        <section class="container container-max">
            <div class="row pt-5 pb-4">
                <div class="col-md-3">
                    <h3 class="m-0"><b>BEST SELLERS</b></h3>
                    <code><i class="small text-info weight-md">The best productions from us</i></code>
                    <p class="size-sm weight-md mt-3">Our lastest and best products that you would love to have. Check it out </p>
                </div>
                <div class="col-md-9">
                    <div class="row stock-list small">
                        @each('partials.product-item', $bestProducts, 'product')
                    </div>
                </div>
            </div>
        </section>

        <section class="container-fluid bg-light">
            <div class="row text-center small">
                <a class="col-md-3 col-6 py-4 px-1 border border-white text-decoration-none" href="#" data-toggle="modal" data-target="#dealsModal">
                    <i class="fa fa-gem h1 text-brand"></i><br>
                    <b class="text-dark">DEALS & SPECIAL OFFERS<br><small>Shop Big Save Big</small></b>
                </a>
                <a class="col-md-3 col-6 py-4 px-1 border border-white text-decoration-none" href="#" data-toggle="modal" data-target="#freeShippingModal">
                    <i class="fa fa-paper-plane h1 text-brand"></i><br>
                    <b class="text-dark">FREE DELIVERY<br><small>On High Value Shopping</small></b>
                </a>
                <a class="col-md-3 col-6 py-4 px-1 border border-white text-decoration-none" href="#" data-toggle="modal" data-target="#returnModal">
                    <i class="fa fa-retweet h1 text-brand"></i><br>
                    <b class="text-dark">RETURN & WARRANTY<br><small>Policy We Offers</small></b>
                </a>
                <a class="col-md-3 col-6 py-4 px-1 border border-white text-decoration-none" href="#" data-toggle="modal" data-target="#fastShippingModal">
                    <i class="fa fa-rocket h1 text-brand"></i><br>
                    <b class="text-dark">FASTEST SHIPPING<br><small>Express Services to Globally</small></b>
                </a>
            </div>
        </section>

        @if (count($videos = \App\Video::wherePublish(true)->get()))
        <section class="container-fluid text-center">
            <div class="row pt-5">
                @foreach ($videos as $video)
                <div class="col-md-3 col-sm-6 mb-4">
                    <video class="shadow" width="100%" height="180" poster="{{ $video->getPoster() ? asset('uploads/videos/'.$video->getPoster()) : asset('images/default.png') }}" controls preload="none" style="object-fit: cover;">
                        <source src="{{ asset('uploads/videos/'.$video->getVideo()) }}" type="video/mp4">
                        Your browser does not support HTML5 video.
                    </video>
                    <p class="text-brand">{{ $video->title }}</p>
                </div>
                @endforeach
            </div>
        </section>
        @endif

        <section class="shop-now-banner">
            @include('layouts.slider-banner')
            {{-- <img src="{{ asset('images/bicycle-bg.jpg') }}" class="img-fluid">
            <h1>SPORTS & OUTDOORS</h1>
            <a href="javascript:;" class="btn btn-lg btn-dark">SHOP NOW</a> --}}
        </section>

        <section class="new-in-stock py-5">
            <div class="text-center">
                <h2 class="font-weight-bold mb-0">NEW IN STOCK</h2>
                <small class="text-danger">Newest trends from top brands</small>
            </div>
            <div class="container container-max">
                <div class="row stock-list size-md py-4">
                    @each('partials.product-item', $products, 'product')
                </div>
            </div>
            @if (count($products) > 4)
            <center>
                <a href="{{ route('search', 'products') }}" class="btn btn-sm btn-outline-dark rounded-pill">
                    <small class="px-4">More in New Stock</small>
                </a>
            </center>
            @endif
        </section>

        <section class="brands container-fluid">
            <div class="row">
                <div class="col-lg-6 bg-brand text-white text-center py-5">
                    <div class="small col-xl-10 mx-auto">
                        <h5>Sign up for the Newsletter</h5>
                        <p>Keep up with the latest products and offers from BrandXpend Newsletter</p>
                        <div class="row">
                            <div class="col-sm-8 px-sm-0 mb-2">
                                <input type="text" class="form-control rounded-0" placeholder="E-mail address ...">
                            </div>
                            <div class="col-sm-4 px-sm-0 mb-2">
                                <button class="btn btn-dark rounded-0 h-100 btn-block"><small>NEWSLETTER</small></button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 p-0">
                    <div id="swiper" class="swiper-container bg-light h-100 p-0 m-0">
                        <div class="swiper-wrapper text-center">
                            @forelse ($logos = \App\Logo::wherePublish(true)->get() as $logo)
                            <a class="swiper-slide p-3" href="{{ make_slug($logo->url) ?: 'javascript:;' }}" target="_blank">
                                <img src="{{ asset('uploads/logos/'.$logo->gallery->filename) }}" class="img-fluid">
                            </a>
                            @empty
                            <nav class="row h-100 align-items-center bg-light">
                                <a href="javascript:;" class="col-3 px-md-4 py-2 h-100 d-flex align-items-center">
                                    <img src="{{ asset('images/brands/logo1.png') }}" class="img-fluid">
                                </a>
                                <a href="javascript:;" class="col-3 px-md-4 py-2 h-100 d-flex align-items-center">
                                    <img src="{{ asset('images/brands/logo2.png') }}" class="img-fluid">
                                </a>
                                <a href="javascript:;" class="col-3 px-md-4 py-2 h-100 d-flex align-items-center">
                                    <img src="{{ asset('images/brands/logo3.png') }}" class="img-fluid">
                                </a>
                                <a href="javascript:;" class="col-3 px-md-4 py-2 h-100 d-flex align-items-center">
                                    <img src="{{ asset('images/brands/logo4.png') }}" class="img-fluid">
                                </a>
                            </nav> 
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="border-top border-light py-2">
            <div class="container container-max">
                <div class="d-sm-flex justify-content-between align-items-center mt-auto text-sm-left text-center">
                    <p class="m-0 small">We promise to deliver excellent service and quality products. Stay and Connect with us.</p>
                    <nav>
                        <a href="{{ __siteMeta('facebook') }}" target="_blank"><i class="fab fa-facebook-f mx-2"></i></a>
                        <a href="{{ __siteMeta('twitter') }}" target="_blank"><i class="fab fa-twitter mx-2"></i></a>
                        <a href="{{ __siteMeta('instagram') }}" target="_blank"><i class="fab fa-instagram mx-2"></i></a>
                        <a href="{{ __siteMeta('linkedin') }}" target="_blank"><i class="fab fa-linkedin-in mx-2"></i></a>
                        <a href="{{ __siteMeta('youtube') }}" target="_blank"><i class="fab fa-youtube mx-2"></i></a>
                    </nav>
                </div>
            </div>
        </section>
    </main>

    <div class="modal fade" id="dealsModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">DEALS & SPECIAL OFFERS</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div>
                    <div class="row">
                        <div class="col-md-12 mx-auto ">
                            <p class="p-3">All products Deals and Special Offers are published under the Deals & Special Offers Category.
                                Our all special offer prices are very competitive considering the product Quality.
                            </p>
                            <p class="mt-2 ml-3"> For More Details Feel Free Contact Us : </p>
                            <p class="mt-2 ml-3"><i class="fa fa-envelope text-brand"></i> {{ __siteMeta('email') }}</p>
                            <p class="mt-2 ml-3"><i class="fab fa-whatsapp text-brand"></i> {{ __siteMeta('whatsapp') }} </p>
                            <p class="mt-2 ml-3"><i class="fa fa-phone-alt text-brand"></i> {{ __siteMeta('contact') }} </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="freeShippingModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">FREE SHIPPING</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div>
                    <div class="row">
                        <div class="col-md-12 mx-auto ">
                            <p class="p-3">Free deliveries are applicable only for the selected products and destination within the UAE, which will be indicated at the time order creation.<br/>
                                <b>BrandXpend </b> give option for self-collection which considered as free delivery without shipping cost. <br/>
                                <b>BrandXpend</b> provide  free shipping depends on high value and Bulk orders based on the approval
                            </p>
                            <p class="mt-2 ml-3"> For Free deliveries, Please contact us prior to the order confirmation : </p>
                            <p class="mt-2 ml-3"><i class="fa fa-envelope text-brand"></i> {{ __siteMeta('email') }}</p>
                            <p class="mt-2 ml-3"><i class="fab fa-whatsapp text-brand"></i> {{ __siteMeta('whatsapp') }} </p>
                            <p class="mt-2 ml-3"><i class="fa fa-phone-alt text-brand"></i> {{ __siteMeta('contact') }} </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="returnModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">RETURN & WARRANTY</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div>
                    <div class="row">
                        <div class="col-md-12 mx-auto ">
                            <p class="p-3">Customer has full right to return the material, if any discrepancies at the time of delivery. BrandXpend is not having any kind of responsibilities on damages and qty shortage after the delivery acknowledgement. We always care about our valued customers highest satisfaction and product safety until the delivery.
                            </p>
                            <p class="mt-2 ml-3"> For Return, Warranty, Guarantee and Complaints, Please contact our support team : </p>
                            <p class="mt-2 ml-3"><i class="fa fa-envelope text-brand"></i> {{ __siteMeta('email') }}</p>
                            <p class="mt-2 ml-3"><i class="fab fa-whatsapp text-brand"></i> {{ __siteMeta('whatsapp') }} </p>
                            <p class="mt-2 ml-3"><i class="fa fa-phone-alt text-brand"></i> {{ __siteMeta('contact') }} </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div><div class="modal fade" id="fastShippingModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">FATEST SHIPPING</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div>
                    <div class="row">
                        <div class="col-md-12 mx-auto ">
                            <p class="p-3">For Express Special Shipping services within UAE and outside UAE, are applicable prior to the order confirmation.
                            </p>
                            <p class="mt-2 ml-3"> For Special shipping rates request, Please contact our support desk : </p>
                            <p class="mt-2 ml-3"><i class="fa fa-envelope text-brand"></i> {{ __siteMeta('email') }} </p>
                            <p class="mt-2 ml-3"><i class="fab fa-whatsapp text-brand"></i> {{ __siteMeta('whatsapp') }} </p>
                            <p class="mt-2 ml-3"><i class="fa fa-phone-alt text-brand"></i> {{ __siteMeta('contact') }} </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

