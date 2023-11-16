@extends('layouts.app')

@section('title', $category->name)

@push('scripts')
<script type="text/javascript">
    var page = 1, flag = {{ count($products) > 0 }} ? true : false, loading = false;
    $(window).scroll(function () {
        if (!loading && flag && ($(window).scrollTop() >= $(document).height() - $(window).height() * 2) ) {
            page++; loadMoreData(page);
        } else if (!flag) { $('.ajax-load').show().html("No more records found"); }
    });

    function loadMoreData(page) {
        var category = "@if (request('category'))&category= {{ request('category') }} @elseif(request('sub-category'))&sub-category= {{ request('sub-category') }} @endif";
        $.ajax({
            url: '?page=' + page + category,
            type: "get",
            beforeSend: function () {
                $('.ajax-load').show(); loading = true;
            }
        }).done(function (data) {
            loading = false;
            if (data.html == "") {
                $('.ajax-load').html("No more records found");
                return flag = false;
            } else {
                $('.ajax-load').hide();
                $("#products-data").append(data.html);
            }
        }).fail(function (jqXHR, ajaxOptions, thrownError) {
            alert('server not responding...');
        });
    }
</script>
@endpush

@section('content')
@include('layouts.menu')
<main class="site-content">
    <section class="py-5">
        <div class="text-center">
            <h2 class="font-weight-bold mb-0">{{ $category->name }}</h2>
        </div>
        <div class="container container-max">
            <div class="row stock-list size-md py-4" id="products-data">
                @forelse($products as $product)
                <div class="col-lg-3 col-sm-4 col-6 mb-4 d-flex flex-column item">
                    <a class="mb-1 text-dark weight-md" href="{{ route('product.detail',$product->id) }}">
                        <figure class="transition-all text-center shadow-sm border border-light p-2 mb-2">
                            <img src="{{ asset('uploads/products/large/'.$product->image) }}" alt="Product Photo" class="img-fluid">
                        </figure>
                        <span class="title">{{ str_limit($product->name, 45, '...') }}</span>
                    </a>
                    <p class="d-flex justify-content-between align-items-center mt-auto mb-2">
                        <time class="flex-shrink-0 text-success font-weight-bold">
                            {{ $product->discountPrice ?? '-' }} AED
                        </time>
                        <small class="flex-shrink-0 text-brand">
                            @for($i = 0; $i < 5; $i++)
                            <i class="fa{{ $product->reviews->avg('rating') > $i ? '' : 'r' }} fa-star"></i>
                            @endfor
                        </small>
                    </p>
                    <button type="button" class="addToCart btn btn-block btn-brand rounded-pill size-sm" value="{{ $product->id }}">
                        Add to Cart
                    </button>
                </div>
                @empty
                <center class="col-12 text-muted">There is no record exist.</center>
                @endforelse
            </div>
        </div>
        <div class="ajax-load py-4 text-center" style="display:none">
            <p><img src="{{ asset('images/loader.gif') }}">Loading...</p>
        </div>
    </section>
</main>
@endsection