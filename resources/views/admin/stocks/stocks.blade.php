@extends('layouts.admin')

@section('title', 'Stocks')

@push('pluginCSS')
<link rel="stylesheet" href="{{ asset('plugins/select2/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/bootstrap-datepicker/bootstrap-datepicker.min.css') }}">
@endpush

@push('pluginJS')
<script type="text/javascript" src="{{ asset('plugins/select2/select2.min.js') }}"></script>
{{-- <script type="text/javascript" src="{{ asset('plugins/bootstrap-notify/bootstrap-notify.min.js') }}"></script> --}}
<script type="text/javascript" src="{{ asset('plugins/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
@endpush

@section('content')
<!-- Page Content -->
<main class="content">
    <!--begin:: Breadcrumb -->
    <ol class="breadcrumb shadow-sm small">
        <li class="breadcrumb-item">
            <a href="{{ route('stocks.index') }}"><i class="fa fa-lg fa-layer-group fa-fw"></i> Stocks</a>
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
            <div class="col-sm-8 col-lg-3 form-group mb-3">
                <select name="product" class="custom-select border-0 rounded shadow-sm select2">
                    <option disabled selected>select product</option>
                    <option value="all" {{ selected('all', request('product')) }}>All Products</option>
                    @foreach ($products as $product)
                    <option value="{{ $product->id }}" {{ selected($product->id, request('product')) }}>
                        {{ $product->name }} [{{ $product->code }}]
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="col-sm-4 col-lg-auto form-group mb-3">
                <select name="status" class="custom-select border-0 rounded shadow-sm">
                    <option disabled selected>select status</option>
                    <option value="all" {{ selected('all', request('status')) }}>All Status</option>
                    <option value="sold" {{ selected('sold', request('status')) }}>Sold</option>
                    <option value="unsold" {{ selected('unsold', request('status')) }}>Unsold</option>
                    <option value="booked" {{ selected('booked', request('status')) }}>Booked</option>
                    <option value="damaged" {{ selected('damaged', request('status')) }}>Damaged</option>
                </select>
            </div>
            <div class="col-sm-8 col-lg-5 form-group mb-3">
                <div class="input-group">
                    <input type="text" name="from" value="{{ old('from') ?: request('from') }}" class="form-control bg-white" data-provide="datepicker" data-date-format="yyyy-mm-dd" placeholder="From" readonly>
                    <input type="text" name="to" value="{{ old('to') ?: request('to') }}" class="form-control bg-white" data-provide="datepicker" data-date-format="yyyy-mm-dd" placeholder="To" readonly>
                </div>
            </div>
            <div class="col-sm-4 col-lg-auto flex-grow-1 form-group mb-3">
                <button type="submit" class="btn btn-block btn-danger mb-2 pb-2"><small>Submit</small></button>
            </div>
        </div>
    </form>
    <!--end:: Form -->

    <!--begin:: Table -->
    <section class="table-responsive table-borderless small rounded mb-3">
        <table class="table table-striped table-hover shadow-sm mb-0" wcs-sorting-table>
            <thead class="bg-danger text-white">
                <tr>
                    <th width="1%">#</th>
                    <th width="50px">Image</th>
                    <th width="20%">Name/Code <i class="fa fa-fw fa-caret-down"></i></th>
                    {{-- <th>Category <i class="fa fa-fw fa-caret-down"></i></th> --}}
                    <th>Status <i class="fa fa-fw fa-caret-down"></i></th>
                    <th>Purchase <i class="fa fa-fw fa-caret-down"></i></th>
                    <th>Sale <i class="fa fa-fw fa-caret-down"></i></th>
                    <th>Discount</th>
                    <th class="text-center">Price <small>(Inc. Discount)</small></th>
                    <th>Profit <i class="fa fa-fw fa-caret-down"></i></th>
                    {{-- <th>Stock <i class="fa fa-fw fa-caret-down"></i></th> --}}
                </tr>
            </thead>
            <tbody>
                @forelse ($stocks as $stock)
                <tr>
                    <td><b>{{ $loop->iteration + ($stocks->currentPage() - 1) * $stocks->perPage() }}</b></td>
                    <td>
                        <img src="{{ $stock->product->image ? asset('uploads/products/small/'.$stock->product->image) : asset('images/default.png') }}" class="img-thumbnail" width="50px" alt="Logo"> 
                    </td>
                    <td>{{ $stock->product->name }}<br><mark>{{ $stock->product->code }}</mark></td>
                    {{-- <td>{{ $stock->product->category->name }}</td> --}}
                    <td>
                        <span class="font-weight-normal badge 
                        @if ($stock->status == 'sold') badge-success 
                        @elseif ($stock->status == 'booked') badge-info 
                        @elseif ($stock->status == 'damaged') badge-danger 
                        @else badge-warning @endif">{{ $stock->status }}</span>
                    </td>
                    <td>{{ $stock->purchase }}</td>
                    <td>{{ $stock->sale }}</td>
                    <td>{{ $stock->discount ? $stock->discount.' %' : '-' }}</td>
                    <td class="text-center">{{ number_format($stock->discount_price, 2) }}</td>
                    <td class="font-weight-bold">{{ $stock->status == 'sold' ? $stock->profit : null }}</td>
                </tr>
                @empty
                <tr><td colspan="11" class="text-center">There is no record exist.</td></tr>
                @endforelse
            </tbody>
        </table>
    </section>
    <!--end:: Table -->

    <!--begin:: Pagination -->
    {{ $stocks->appends(request()->all())->links() }}
    <!--end:: Pagination -->
</main>
@endsection