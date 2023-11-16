@extends('layouts.app')

@section('title', $product->name)

@push('pluginCSS')
<link rel="stylesheet" href="{{ asset('plugins/swiper/swiper.min.css') }}">
@endpush

@push('pluginJS')
<script type="text/javascript" src="{{ asset('plugins/swiper/swiper.min.js') }}"></script>
@endpush

@push('styles')
<style>
.galleryTop.swiper-container{min-height: 150px; max-height: 400px}
.galleryTop .swiper-slide{height: auto}
.galleryTop .swiper-slide img{max-height: 400px}
.galleryThumbs .swiper-slide{max-width: 96px; max-height: 96px; background-color: var(--light)}
.galleryThumbs .swiper-slide-thumb-active{opacity:.7}
</style>
@endpush

@push('scripts')
<script type="text/javascript">
    var galleryThumbs = new Swiper('.galleryThumbs', {
        freeMode: true,
        spaceBetween: 5,
        slidesPerView: 5,
        {{-- centeredSlides: {{ $product->galleries->count() < 5  ? true : false }}, --}}
        watchSlidesProgress: true,
        watchSlidesVisibility: true,
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
        },
        breakpoints: {
            1024: { slidesPerView: 4 },
            768 : { slidesPerView: 6 },
            640 : { slidesPerView: 4 },
            480 : { slidesPerView: 3 },
        }
    });
    var galleryTop = new Swiper('.galleryTop', {
        zoom: true,
        effect: 'slide',
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
        },
        thumbs: {
            swiper: galleryThumbs
        }
    });

    $(document).on('click', '#addToCart', function (e) {
        e.preventDefault(); var btn = $(this);
        $.ajax({
            type: 'post',
            data: {
                '_token': "{{ csrf_token() }}",
                'product_id': parseInt({{ $product->id }}),
                'qty'  : $('[wcs-quantity]').val(),
                'attrs'  : $('[name="attrs"]').val()
            },
            url: "{{ route('add.to.cart') }}",
            success: function (data) {
                if(data == "error") {
                    toastr.error('Quantity not available in stock!');
                } else {
                    $('.cartCount').html(data);
                    $(btn).attr('disabled', true).html('<i class="fa fa-check"></i> Added to Cart');
                    toastr.success('Item added to cart successfully.');
                }
            }
        });
    });

    $.fn.PRODUCT_PRICE = function () {
        var attrs = $('[name="attrs"]').val(), option = $('[value="'+attrs+'"]');
        if (!attrs) { return false; }
        $('.newPrice').text($(option).data('discount-price') + ' AED');
        $('.oldPrice').text($(option).data('sale-price') ? $(option).data('sale-price') + ' AED' : null);
    }
    $(document).on('change', '[name="attrs"]', function () {
        $.fn.PRODUCT_PRICE();
    }).ready(function () { $.fn.PRODUCT_PRICE(); });

    $.fn.ITEM_QUANTITY = function($this) {
        var name = $this.attr('wcs-plus') || $this.attr('wcs-minus');
        $input = name ? $('[wcs-quantity="'+name+'"]') : $this;
        var value = Number($input.val());
        var attrs = $('[name="attrs"]').val(), qty = $('[value="'+attrs+'"]').data('qty');
        if (value < 1) { $(this).val(1); return false; }
        if (value > qty) { toastr.error('Quantity not available in stock!'); }
    }
    $(document).on('input', '[wcs-quantity]', function () {
        $.fn.ITEM_QUANTITY($(this));
    }).on('click', '[wcs-plus], [wcs-minus]', function () {
        $.fn.ITEM_QUANTITY($(this));
    });
</script>
@endpush

@section('content')

    @include('layouts.menu')

    <div class="container container-max py-5">
        <section class="row">
            <div class="col-md-6 mb-4">
                <section class="swiper-container galleryTop shadow-sm border border-light pb-0 my-0">
                    <div class="swiper-wrapper">
                        {{-- <div class="swiper-slide" style="background: url({{ asset('/uploads/products/'.$product->image) }}) center / cover no-repeat"></div> --}}
                        <div class="swiper-slide">
                            <img src="{{ asset('/uploads/products/'.$product->image) }}">
                        </div>
                        @foreach ($product->galleries()->wherePublish(true)->get() as $gallery)
                        <div class="swiper-slide">
                            <img src="{{ asset('/uploads/products/'.$gallery->filename) }}">
                        </div>
                        @endforeach
                    </div>
                    @if ($product->galleries->count())
                    <div class="swiper-button-prev"></div>
                    <div class="swiper-button-next"></div>
                    @endif
                </section>
                <section class="swiper-container galleryThumbs p-2 my-3">
                    <div class="swiper-wrapper @if ($product->galleries->count() < 4) d-flex justify-content-center @endif">
                        <div class="swiper-slide shadow-sm border border-white">
                            <img src="{{ asset('/uploads/products/small/'.$product->image) }}" class="img-fluid">
                        </div>
                        @foreach ($product->galleries()->wherePublish(true)->get() as $gallery)
                        <div class="swiper-slide shadow-sm border border-white">
                            <img src="{{ asset('/uploads/products/small/'.$gallery->filename) }}" class="img-fluid">
                        </div>
                        @endforeach
                    </div>
                    @if ($product->galleries->count())
                    <div class="swiper-button-prev swiper-button-white" style="background-size:1rem"></div>
                    <div class="swiper-button-next swiper-button-white" style="background-size:1rem"></div>
                    @endif
                </section>
            </div>
            <div class="col-md-6 size-md">
                <div class="shadow-sm border border-light sticky-top p-4" style="top: 5rem; z-index: 0">
                    <h6 class="pb-3">
                        @if($product->brand)
                        <span class="badge badge-warning font-weight-light float-right ml-2">{{ $product->brand->name }}</span>
                        @endif
                        <span class="text-uppercase">{{ $product->name }}</span>
                    </h6>
                    <p><span class="text-muted">Product Code :</span> <mark class="rounded-pill text-brand px-3">{{ $product->code }}</mark></p>
                    <div class="note-editor mb-3">
                        {{ substr($product->excerpt, 0, 150) }}
                        @if (strlen($product->excerpt) > 150) ... <a href="#detail">detail</a> @endif
                    </div>
                    <p>
                        <span class="text-brand mr-2">
                            @for($i = 0; $i < 5; $i++)
                            <i class="fa{{ $product->reviews->avg('rating') > $i ? '' : 'r' }} fa-star"></i>
                            @endfor
                        </span>
                        <small>({{ $product->reviews->count() }} reviews)</small>
                    </p>
                    <h6><span class="text-muted mr-2">Stock :</span>
                        @if ($product->stocks('unsold')->count())
                        <b class="text-success">Available</b>
                        @else
                        <b class="text-danger">Out of Stock</b>
                        @endif
                    </h6>
                    <hr class="border-light">
                    <h4><small class="size-xs text-info">( Including VAT )</small><br>
                        {{-- @php
                            $lastStock = $product->lastStock('unsold', 'main');
                        @endphp
                        <b class="newPrice">{{ $lastStock->discountPrice ?? '-' }} AED</b>
                        @if ($lastStock->discountPrice != $lastStock->sale)
                        <del class="text-muted small ml-2 oldPrice">{{ $lastStock->sale ?? '-' }} AED</del>
                        @endif --}}
                        <b class="newPrice">{{ $product->discountPrice ?? '-' }} AED</b>
                        @if ($product->discountPrice != $product->sale)
                        <del class="text-muted small ml-2 oldPrice">{{ $product->sale ?? '-' }} AED</del>
                        @endif 
                    </h4>
                    <div class="d-flex py-2">
                        <div class="form-group flex-grow-1">
                            <label>Package / Variation</label>
                            <select name="attrs" class="custom-select shadow-sm border-light">
                                @forelse ($product->stocks('unsold')->uniqueAttrs() as $attrs)
                                @php
                                $stocks = $product->stocks('unsold')->whereAttrs($attrs);
                                $lastStock = $product->lastStock('unsold', $attrs);
                                @endphp
                                <option value="{{ strtolower($attrs) }}" data-qty="{{ $stocks->count() }}" data-discount-price="{{ $lastStock->discountPrice }}" data-sale-price="{{ $lastStock->discountPrice != $lastStock->sale ? $lastStock->sale : '' }}">
                                    {{ $attrs }}
                                </option>
                                @empty
                                <option disabled selected>Out of Stock</option>
                                @endforelse
                            </select>
                        </div>
                        <div class="form-group col pr-0">
                            <label>Quantity</label>
                            <div class="input-group shadow-sm rounded">
                                <input type="text" name="quant[1]" class="form-control border-light" value="1" min="1" max="100" maxlength="2" wcs-quantity="qty">
                                <div class="input-group-append">
                                    <nav class="d-flex flex-column border border-light rounded-right">
                                        <a href="javascript:;" class="px-2 border-bottom border-light" wcs-plus="qty">
                                            <i class="fa fa-chevron-up"></i>
                                        </a>
                                        <a href="javascript:;" class="px-2" wcs-minus="qty">
                                            <i class="fa fa-chevron-down"></i>
                                        </a>
                                    </nav>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="d-sm-flex align-items-center pt-2">
                        <button type="button" id="addToCart" class="btn btn-brand rounded-pill flex-grow-1 size-md mr-4" @if (!$product->stocks('unsold')->count()) disabled @endif>Add to Cart</button>
                        <a href="{{ route('front.home') }}" class="text-brand small d-inline-block">Continue to shopping</a>
                    </div>
                </div>
            </div>
        </section>

        <section id="detail" class="pt-5 pb-lg-0 pb-5">
            <div class="small shadow-sm">
                <nav class="nav nav-pills" role="tablist">
                    <a data-toggle="pill" role="tab" class="nav-item nav-link active" href="#descriptionTab">Description</a>
                    <a data-toggle="pill" role="tab" class="nav-item nav-link" href="#reviewsTab">Reviews <small>({{ $product->reviews->count() }})</small></a>
                </nav>
                <main class="tab-content bg-light p-3" style="min-height: 10rem">
                    <article class="tab-pane fade show active" id="descriptionTab" role="tabpanel">
                        <p>{{ $product->excerpt }}</p>
                        <div class="note-editor">
                            @if ($product->description)
                            {!! $product->description !!}
                            @else
                            <small class="text-muted">There is no description yet.</small>
                            @endif
                        </div>
                    </article>
                    <article class="tab-pane fade" id="reviewsTab" role="tabpanel">
                        @forelse($product->reviews as $review)
                        <span class="text-brand">
                            @for($i = 0; $i < 5; $i++)
                            <i class="fa{{ $review->rating > $i ? '' : 'r' }} fa-star"></i>
                            @endfor
                        </span>
                        <p class="small mt-2">
                            Posted @isset($review->user->first_name) {{ 'by '.$review->user->first_name }} @endisset 
                            on {{ $review->updated_at->diffForHumans() }}
                        </p> {{ $review->review }}
                        @empty
                        <small class="text-muted">No any review yet.</small>
                        @endforelse
                    </article>
                </main>
            </div>
        </section>
        @if (count($relatedProducts))
        <section class="pt-5">
            <div class="text-center">
                <h2 class="font-weight-bold mb-0">RELATED PRODUCTS</h2>
                <small class="text-danger">Newest trends from top brands</small>
            </div>
            <div class="container container-max">
                <div class="row stock-list size-md py-4">
                    @each('partials.product-item', $relatedProducts, 'product')
                </div>
            </div>
        </section>
        @endif
    </div>
@endsection