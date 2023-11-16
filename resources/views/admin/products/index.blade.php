@extends('layouts.admin')

@section('title', 'Products')

@push('pluginCSS')
<link rel="stylesheet" href="{{ asset('plugins/select2/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/bootstrap-datepicker/bootstrap-datepicker.min.css') }}">
@endpush

@push('pluginJS')
<script type="text/javascript" src="{{ asset('plugins/select2/select2.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('plugins/bootstrap-notify/bootstrap-notify.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('plugins/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
@endpush

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.15/dist/summernote.min.css" rel="stylesheet">
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.15/dist/summernote.min.js"></script>
<script>
    $(document).ready(function() {
        $('.summernote').summernote({
            placeholder: 'Write a short description about the product...',
            fontSize: 10,
            tabsize: 2,
            height: 150,
            toolbar: [
            ['style', ['style']],
            ['font', ['bold', 'underline']],
            ['para', ['ul', 'ol']],
            ['view', ['codeview']]
            ]
        });
    });
</script>
<script>
    $(document).on('change', '[name="category_id"]', function () {
        var $form = $(this).closest('form');
        var id = $form.find('[name="category_id"]').val();
        $.ajax({
            type: 'GET',
            url: '{{ url("/admin") }}' + '/categories/' + id + '/sub-categories',
            data: { '_token':'{{ @csrf_token() }}', 'id':id, 'json':true },
            success: function(data) {
                $form.find('[name="sub_category_id"]').html('');
                if (data.length) {
                    for(i = 0; i < data.length; i++) {
                        $form.find('[name="sub_category_id"]').append('<option value="'+data[i]['id']+'">'+data[i]["name"]+'</option>');
                    }
                } else {
                    $form.find('[name="sub_category_id"]').append('<option selected disabled>No sub category exist.</option>');
                }
            }
        });
    })
</script>
@endpush

@section('content')
<!-- Page Content -->
<main class="content">
    <!--begin:: Breadcrumb -->
    <ol class="breadcrumb d-flex align-items-center shadow-sm small">
        <li class="breadcrumb-item">
            <a href="{{ route('products.index') }}"><i class="fa fa-lg fa-chart-pie fa-fw"></i> Products</a>
        </li>
        <li class="breadcrumb-item active">All</li>
        <a href="javascript:;" class="btn btn-sm btn-warning rounded-pill ml-auto" data-toggle="modal" data-target="#addModal">
            <i class="fa fa-plus-circle fa-fw"></i> Add Product
        </a>
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
                    <option value="empty" {{ selected('empty', request('product')) }}>Products - Empty Stock</option>
                    @foreach ($productsByRole as $product)
                    <option value="{{ $product->id }}" {{ selected($product->id, request('product')) }}>
                        {{ $product->name }} [{{ $product->code }}]
                    </option>
                    @endforeach
                </select>
            </div>
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
                <button type="submit" class="btn btn-block btn-secondary mb-2 pb-2"><small>Submit</small></button>
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
                    <th width="30%">Name/Code <i class="fa fa-fw fa-caret-down"></i></th>
                    <th>Category <i class="fa fa-fw fa-caret-down"></i></th>
                    <th>Price <i class="fa fa-fw fa-caret-down"></i></th>
                    <th>Discount</th>
                    <th>Stock <i class="fa fa-fw fa-caret-down"></i></th>
                    <th class="text-center">Gallery</th>
                    <th width="5%">Publish</th>
                    <th width="100px" class="not-sorted text-center">Action</th>
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
                    <td>{{ $product->sale ?? '-' }}</td>
                    <td>{{ $product->discount ?? 0 }} %</td>
                    <td><a href="{{ route('products.stocks', $product->id) }}" class="btn-link">View ({{ $product->stocks('unsold')->count() }})</a></td>
                    <td class="text-center">
                        <a href="{{ route('products.galleries', $product->id) }}" data-hover="tooltip" title="Add Gallery" class="dynamic-modal"><i class="fa fa-images fa-fw fa-lg"></i></a>
                    </td>
                    <td>
                        @role(['administrator', 'admin'])
                        <div class="custom-control custom-switch">
                            <input type="checkbox" name="publish" data-action="{{ route('products.edit', $product->id) }}" class="dynamic-switch custom-control-input" id="publish{{ $product->id }}" {{ checked(true, $product->publish) }}>
                            <label class="custom-control-label" for="publish{{ $product->id }}"></label>
                        </div>
                        @else
                        @if ($product->publish)
                        <span class="badge badge-info font-weight-normal">Yes</span>
                        @else
                        <span class="badge badge-warning font-weight-normal">No</span>
                        @endif
                        @endrole
                    </td>
                    <td class="text-center">
                        <a href="javascript:;" data-toggle="modal" data-target="#editModal" data-action="{{ route('products.show', $product->id) }}" data-hover="tooltip" title="Edit" class="dynamic-modal"><i class="far fa-edit fa-fw text-info fa-lg"></i></a>
                        <a href="javascript:;" data-toggle="modal" data-target="#delModal" data-action="{{ route('products.destroy', $product->id) }}" data-hover="tooltip" title="Delete" class="dynamic-modal"><i class="far fa-trash-alt text-danger fa-lg"></i></a>
                    </td>
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

<!--begin:: Add Modal -->
<div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Product</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" action="{{ route('products.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="modal-body px-4">
                    <div class="form-group">
                        <label>Name <code class="text-danger">*</code></label>
                        <input type="text" name="name" value="{{ old('name') ?: '' }}" class="form-control" placeholder="Enter the name of product" required>
                    </div>
                    <div class="form-group">
                        <label>Brand</label>
                        <select name="brand_id" class="custom-select">
                            <option value="" selected>please choose.</option>
                            @forelse (auth()->user()->brands()->get() as $brand)
                            <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                            @empty
                            <option disabled>No record exist, please add brand.</option>
                            @endforelse
                        </select>
                    </div>
                    <div class="row">
                        <div class="col-sm-6 form-group">
                            <label>Category <code class="text-danger">*</code></label>
                            <select name="category_id" class="custom-select" required>
                                <option selected disabled>please choose</option>
                                @forelse (__all('Category') as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @empty
                                <option disabled>No record exist, please add category.</option>
                                @endforelse
                            </select>
                        </div>
                        <div class="col-sm-6 form-group">
                            <label>Sub Category</label>
                            <select name="sub_category_id" class="custom-select">
                                <option selected disabled>please choose</option>
                                @forelse (__all('SubCategory') as $subCategory)
                                <option value="{{ $subCategory->id }}">{{ $subCategory->name }}</option>
                                @empty
                                <option disabled>No record exist, please add sub category.</option>
                                @endforelse
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6 form-group">
                            <label>Purchase <code class="text-danger">* AED</code></label>
                            <input type="text" name="purchase" value="{{ old('purchase') ?: '' }}" class="form-control isNumeric" placeholder="Enter the purchase price" required>
                        </div>
                        <div class="col-sm-6 form-group">
                            <label>Sale <code class="text-danger">* AED</code></label>
                            <input type="text" name="sale" value="{{ old('sale') ?: '' }}" class="form-control isNumeric" placeholder="Enter the sale price" required wcs-price="addModal">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6 form-group">
                            <label>Discount <small class="text-muted">%</small></label>
                            <div class="input-group">
                                <input type="text" name="discount" value="{{ old('discount') ?: '' }}" class="form-control isNumeric" placeholder="Enter the product discount" wcs-discount="addModal">
                                <div class="input-group-append">
                                    <span class="input-group-text text-danger"><small>Discount Price</small></span>
                                    <span class="input-group-text" wcs-discount-price="addModal">-</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 form-group">
                            <label>Stock<small>(s)</small></label>
                            <input type="text" name="stock" value="{{ old('stock') ?: '' }}" class="form-control isNumeric" placeholder="Enter the product stock" min="0" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Image <code class="text-danger">*</code></label>
                        <input type="file" name="logo" class="form-control" accept="image/*" required>
                    </div>
                    <div class="form-group">
                        <label>Excerpt</label>
                        <textarea name="excerpt" class="form-control" placeholder="Write a short summery of product...">{{ old('excerpt') ?: '' }}</textarea>
                    </div>
                    <div class="form-group">
                        <label>Description</label>
                        <textarea name="description" class="form-control summernote">{!! old('description') ?: '' !!}</textarea>
                    </div>
                    @role(['administrator', 'admin'])
                    <div class="row">
                        <div class="col-sm-6 form-group">
                            <label>Publish</label>
                            <select name="publish" class="custom-select" required>
                                <option disabled>please choose</option>
                                <option value="1">Active</option>
                                <option value="0">Deactive</option>
                            </select>
                        </div>
                        <div class="col-sm-6 form-group">
                            <label>Product Type</label>
                            <select name="type" class="custom-select">
                                <option disabled>please choose</option>
                                <option value="" selected>Normal</option>
                                <option value="arrival">Arrival</option>
                                <option value="promotion">Promotion</option>
                            </select>
                        </div>
                    </div>
                    @endrole
                    {{-- <div class="form-group alert alert-info">
                        <div class="custom-control custom-checkbox custom-control-inline">
                            <input type="checkbox" id="cb" name="shipping_cost" class="custom-control-input">
                            <label class="custom-control-label" for="cb">Would you like to set free shipping cost?</label>
                        </div>
                    </div> --}}
                </div>
                <div class="modal-footer px-4">
                    <button type="reset" class="btn btn-light border" data-dismiss="modal"><small>Close</small></button>
                    <button type="submit" class="btn btn-info"><small>Submit</small></button>
                </div>
            </form>
        </div>
    </div>
</div>
<!--end:: Add Modal -->

<!--begin:: Edit Modal -->
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Product</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div id="dynamic-content"></div>
        </div>
    </div>
</div>
<!--end:: Edit Modal -->

<!--begin:: Delete Modal -->
<div class="modal fade" id="delModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-light rounded-0">
                <h6 class="modal-title text-danger">Delete Product</h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" action="">
                @csrf @method('DELETE')
                <div class="modal-body small pb-5">
                    <p>Are you sure want to delete?</p>
                </div>
                <div class="modal-footer bg-light rounded-0">
                    <button type="button" class="btn btn-light border" data-dismiss="modal"><small>Close</small></button>
                    <button type="submit" class="btn btn-danger"><small>Delete</small></button>
                </div>
            </form>
        </div>
    </div>
</div>
<!--end:: Delete Modal -->
@endsection