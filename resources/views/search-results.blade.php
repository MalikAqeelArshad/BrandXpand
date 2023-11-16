@extends('layouts.app')

@section('title', 'Search Products')

@section('content')
@include('layouts.menu')
<main class="site-content">
    <section class="container container-max py-4">
        <h6 class="bg-light border rounded px-3 py-2 @if(!request('s') && !$category) d-none @endif">
            <a href="{{ route('search', 'products') }}"><i class="far fa-times-circle fa-fw"></i></a>
            Search for : 
            <code class="ml-1">
                @if(request('s')) {{ request('s') }}
                @elseif($category) {{ $category->name }} @else All Categories @endif
            </code>
        </h6>
        <div class="row py-3">
            <aside class="col-md-3 mb-4">
                <form method="GET" action="{{ request()->fullUrl() }}" class="sticky-top" style="top: 5rem; z-index: 0">
                    <input type="hidden" name="s" value="{{ request('s') }}"> 
                    <input type="hidden" name="category" value="{{ request('category') }}">
                    <div class="form-group">
                        <select name="orderType" class="custom-select size-sm" onchange="this.form.submit()">
                            <option value="desc" {{ selected('desc', request('orderType')) }}>Newest</option>
                            <option value="asc" {{ selected('asc', request('orderType')) }}>Oldest</option>
                            <option value="arrival" {{ selected('arrival', request('orderType')) }}>Arrivals</option>
                            <option value="promotion" {{ selected('promotion', request('orderType')) }}>Promotions</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <select name="price" class="custom-select size-sm" onchange="this.form.submit()">
                            <option value="" disabled selected>Sort by Price</option>
                            <option value="lowtohigh" {{ selected('lowtohigh', request('price')) }}>Low to High</option>
                            <option value="hightolow" {{ selected('hightolow', request('price')) }}>High to Low</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <h6 class="text-brand size-md">Range</h6>
                        <div class="input-group">
                            <input type="text" name="min" value="{{ request('min') ?? null }}" class="form-control isNumeric size-xs" placeholder="min price">
                            <input type="text" name="max" value="{{ request('max') ?? null }}" class="form-control isNumeric size-xs" placeholder="max price">
                            <div class="input-group-append">
                                <button type="submit" class="btn btn-brand"><small>GO</small></button>
                            </div>
                        </div>
                    </div>
                    @if (count($brands = __all('Brand')))
                    <div class="form-group small">
                        <h6 class="text-brand size-md">Brands</h6>
                        @foreach ($brands as $brand)
                        <div class="custom-control custom-checkbox d-md-block d-inline-block mr-md-0 mr-2">
                            <input type="checkbox" name="brand[]" value="{{ $brand->id }}" onchange="this.form.submit()" class="custom-control-input" id="cb{{ $loop->index }}" @if (request('brand')) @foreach(request('brand') as $id) {{ checked($brand->id, $id) }} 
                            @endforeach @endif>
                            <label class="custom-control-label" for="cb{{ $loop->index }}">{{ $brand->name }}</label>
                        </div>
                        @endforeach
                    </div>
                    @endif
                    <input type="hidden" name="page" value="{{ request('page') }}">
                </form>
            </aside>
            <main class="col-md-9">
                <div class="row stock-list">
                    @forelse($products as $product)
                    @include('partials.product-item', ['class'=>'col-lg-3 col-md-4 col-sm-6 col-6 mb-4 small'])
                    @empty
                    <center class="col-12 text-muted">There is no record exist.</center>
                    @endforelse
                    {{-- @each('partials.product-item', $products, 'product') --}}
                </div>
            </main>
        </div>
        <center class="d-flex justify-content-center py-4">{{ $products->appends(request()->except('_token'))->links() }}</center>
    </section>
</main>
@endsection
