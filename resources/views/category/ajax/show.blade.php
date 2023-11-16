@foreach($products as $product)
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
@endforeach