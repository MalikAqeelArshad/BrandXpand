@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<!-- Page Content -->
<main class="content">
    <h5 class="bg-light text-brand shadow-sm p-3 mb-4">Welcome</h5>
    <div class="row small">
        @role(['administrator','admin'])
        <div class="col-xl-4 col-sm-6 mb-3">
            <a class="card shadow-sm border-light rounded-0" href="{{ route('users.index') }}">
                <div class="d-flex">
                    <div class="bg-light d-flex justify-content-center align-items-center p-3">
                        <i class="fa fa-users fa-fw display-4 text-danger"></i>
                    </div>
                    <div class="card-body">
                        <h5 class="card-title">Users</h5>
                        <h1 class="text-muted">{{ __all('User')->count() }}</h1>
                        <p class="card-text"><small class="text-muted">The total number of users</small></p>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-xl-4 col-sm-6 mb-3">
            <form class="card shadow-sm border-light rounded-0" action="{{ route('site.options') }}" method="POST">
                @csrf @method('POST')
                <div class="d-flex">
                    <div class="bg-light d-flex justify-content-center align-items-center p-3">
                        <i class="fa fa-hand-holding-usd fa-fw display-4 text-primary"></i>
                    </div>
                    <div class="card-body">
                        <h5 class="card-title">Currency</h5>
                        <div class="form-group mb-3">
                            <div class="input-group">
                                <input type="text" name="usd" value="{{ old('usd') ?: __siteMeta('usd') }}" class="form-control isNumeric" placeholder="USD rate" wcs-usd>
                                <div class="input-group-append">
                                    {{-- <button type="submit" class="input-group-text btn-danger size-xs px-lg-3 px-2">Submit</button> --}}
                                    <button type="submit" class="btn btn-light border size-xs">Save</button>
                                </div>
                            </div>
                        </div>
                        <p class="card-text"><small class="text-muted">Set <code>USD</code> currency for <code>AED <b wcs-uae></b></code></small></p>
                    </div>
                </div>
            </form>
        </div>
        @endrole
        <div class="col-xl-4 col-sm-6 mb-3">
            <a class="card shadow-sm border-light rounded-0" href="{{ route('brands.index') }}">
                <div class="d-flex">
                    <div class="bg-light d-flex justify-content-center align-items-center p-3">
                        <i class="far fa-copyright fa-fw display-4 text-brand"></i>
                    </div>
                    <div class="card-body">
                        <h5 class="card-title">Brands</h5>
                        <h1 class="text-muted">{{ auth()->user()->hasRole(['administrator','admin']) ? __all('Brand')->count() : auth()->user()->brands->count() }}</h1>
                        <p class="card-text"><small class="text-muted">The total number of brands</small></p>
                    </div>
                </div>
            </a>
        </div>
        {{-- @role(['administrator','admin'])
        <div class="col-xl-4 col-sm-6 mb-3">
            <a class="card shadow-sm border-light rounded-0" href="{{ route('coupons.index') }}">
                <div class="d-flex">
                    <div class="bg-light d-flex justify-content-center align-items-center p-3">
                        <i class="far fa-credit-card fa-fw display-4 text-primary"></i>
                    </div>
                    <div class="card-body">
                        <h5 class="card-title">Coupons</h5>
                        <h1 class="text-muted">{{ auth()->user()->hasRole(['administrator','admin']) ? __all('Coupon')->count() : auth()->user()->coupons->count() }}</h1>
                        <p class="card-text"><small class="text-muted">The total number of coupons</small></p>
                    </div>
                </div>
            </a>
        </div>
        @endrole --}}
        <div class="col-xl-4 col-sm-6 mb-3">
            <a class="card shadow-sm border-light rounded-0" href="{{ route('products.index') }}">
                <div class="d-flex">
                    <div class="bg-light d-flex justify-content-center align-items-center p-3">
                        <i class="fa fa-chart-pie fa-fw display-4 text-success"></i>
                    </div>
                    <div class="card-body">
                        <h5 class="card-title">Products</h5>
                        <h1 class="text-muted">{{ auth()->user()->hasRole(['administrator','admin']) ? __all('Product')->count() : auth()->user()->products->count() }}</h1>
                        <p class="card-text"><small class="text-muted">The total number of products</small></p>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-xl-4 col-sm-6 mb-3">
            <a class="card shadow-sm border-light rounded-0" href="{{ route('orders.index') }}">
                <div class="d-flex">
                    <div class="bg-light d-flex justify-content-center align-items-center p-3">
                        <i class="fa fa-shopping-basket fa-fw display-4 text-secondary"></i>
                    </div>
                    <div class="card-body">
                        <h5 class="card-title">Orders</h5>
                        <h1 class="text-muted">{{ auth()->user()->hasRole(['administrator','admin']) ? __all('Order')->unique('reference_number')->count() : \App\Order::whereIn('product_id', auth()->user()->products->pluck('id'))->get()->unique('reference_number')->count() }}</h1>
                        <p class="card-text"><small class="text-muted">The total number of orders</small></p>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-xl-4 col-sm-6 mb-3">
            <a class="card shadow-sm border-light rounded-0" href="{{ route('stocks.profit') }}">
                <div class="d-flex">
                    <div class="bg-light d-flex justify-content-center align-items-center p-3">
                        <i class="fa fa-cart-plus fa-fw display-4 text-warning"></i>
                    </div>
                    <div class="card-body">
                        @role(['administrator','admin'])
                        <h5 class="card-title">All Profit</h5>
                        <h1 class="text-muted">{{ number_format(\App\ProductStock::totalProfit()) }}</h1>
                        <p class="card-text"><small class="text-muted">The total profit of all sellers</small></p>
                        @else
                        <h5 class="card-title">Total Profit</h5>
                        <h1 class="text-muted">{{ number_format(\App\ProductStock::totalProfit(auth()->id())) }}</h1>
                        <p class="card-text"><small class="text-muted">The total profit of all products</small></p>
                        @endrole
                    </div>
                </div>
            </a>
        </div>
    </div>
</main>
@endsection