@extends('layouts.admin')

@section('title', 'Stocks')

@push('pluginCSS')
<link rel="stylesheet" href="{{ asset('plugins/bootstrap-datepicker/bootstrap-datepicker.min.css') }}">
@endpush

@push('pluginJS')
{{-- <script type="text/javascript" src="{{ asset('plugins/bootstrap-notify/bootstrap-notify.min.js') }}"></script> --}}
<script type="text/javascript" src="{{ asset('plugins/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
@endpush

@section('content')
<!-- Page Content -->
<main class="content">
    <!--begin:: Breadcrumb -->
    <ol class="breadcrumb shadow-sm small">
        <li class="breadcrumb-item">
            <a href="{{ route('stocks.profit') }}"><i class="fa fa-lg fa-layer-group fa-fw"></i> Profit</a>
        </li>
        <li class="breadcrumb-item active">All</li>
    </ol>
    <!--end:: Breadcrumb -->

    <!--begin:: Flash Message -->
    @include('flash-message')
    <!--end:: Flash Message -->

    <!--begin:: Form -->
    <form action="" class="shadow bg-light px-3 pt-3">
        <div class="row">
            @role(['administrator', 'admin'])
            <div class="col-sm-8 col-lg-3 form-group mb-3">
                <select name="user" class="custom-select border-0 rounded shadow-sm">
                    <option disabled selected>select user</option>
                    <option value="all" {{ selected('all', request('user')) }}>All Users</option>
                    @foreach ($users as $user)
                    <option value="{{ $user->id }}" {{ selected($user->id, request('user')) }}>
                        @isset ($user->profile->full_name) {{ $user->profile->full_name }} -> @endisset {{ $user->email }}
                    </option>
                    @endforeach
                </select>
            </div>
            @else
            <div class="col-sm-8 col-lg-3 form-group mb-3">
                <select name="product" class="custom-select border-0 rounded shadow-sm">
                    <option disabled selected>select product</option>
                    <option value="all" {{ selected('all', request('product')) }}>All Products</option>
                    @foreach ($productsByRole as $product)
                    <option value="{{ $product->id }}" {{ selected($product->id, request('product')) }}>{{ $product->name }}</option>
                    @endforeach
                </select>
            </div>
            @endrole
            <div class="col-sm-4 col-lg-auto form-group mb-3">
                <select name="take" class="custom-select border-0 rounded shadow-sm">
                    <option disabled selected>select limit</option>
                    <option value="all" {{ selected('all', request('take')) }}>All</option>
                    <option value="10" {{ selected('10', request('take')) }}>10</option>
                    <option value="25" {{ selected('25', request('take')) }}>25</option>
                    <option value="50" {{ selected('50', request('take')) }}>50</option>
                    <option value="100" {{ selected('100', request('take')) }}>100</option>
                </select>
            </div>
            <div class="col-sm-8 col-lg-5 form-group mb-3">
                <div class="input-group">
                    <input type="text" name="from" value="{{ old('from') ?: request('from') }}" class="form-control bg-white" data-provide="datepicker" data-date-format="yyyy-mm-dd" placeholder="From" readonly>
                    <input type="text" name="to" value="{{ old('to') ?: request('to') }}" class="form-control bg-white" data-provide="datepicker" data-date-format="yyyy-mm-dd" placeholder="To" readonly>
                </div>
            </div>
            <div class="col-sm-4 col-lg-auto flex-grow-1 form-group mb-3">
                <button type="submit" class="btn btn-block btn-warning mb-2 pb-2"><small>Submit</small></button>
            </div>
        </div>
    </form>
    <!--end:: Form -->

    <!--begin:: Table -->
    <section class="table-responsive table-borderless small rounded mb-3">
        <table class="table table-striped table-hover shadow-sm mb-0" wcs-sorting-table>
            <thead class="tbg-brand text-white">
                <tr>
                    <th width="1%">#</th>
                    <th width="50px">Image</th>
                    <th width="20%">Name/Code <i class="fa fa-fw fa-caret-down"></i></th>
                    <th>Category <i class="fa fa-fw fa-caret-down"></i></th>
                    <th>Total Sell of Product <i class="fa fa-fw fa-caret-down"></i></th>
                    <th>Total Sell of Profit <i class="fa fa-fw fa-caret-down"></i></th>
                </tr>
            </thead>
            <tbody>
                @forelse ($products as $product)
                <tr>
                    <td><b>{{ $loop->iteration + ($products->currentPage() - 1) * $products->perPage() }}</b></td>
                    <td>
                        <img src="{{ $product->image ? asset('uploads/products/small/'.$product->image) : asset('images/default.png') }}" class="img-thumbnail" width="50px" alt="Logo"> 
                    </td>
                    <td>{{ $product->name }}<br><mark>{{ $product->code }}</mark></td>
                    <td>{{ $product->category->name }}</td>
                    <td>{{ $product->stocks('sold')->filters()->count() ?: '-' }}</td>
                    <td>{{ $product->stocks('sold')->filters()->totalProfit($product->user_id, $product->id) ?: '-' }}</td>
                </tr>
                @empty
                <tr><td colspan="11" class="text-center">There is no record exist.</td></tr>
                @endforelse
            </tbody>
        </table>
    </section>
    <!--end:: Table -->

    <!--begin:: Pagination -->
    {{ $products->appends(request()->all())->links() }}
    <!--end:: Pagination -->

</main>
@endsection