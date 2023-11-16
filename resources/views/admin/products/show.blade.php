@extends('layouts.admin')

@section('title', 'Products')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.15/dist/summernote.min.css" rel="stylesheet">
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.15/dist/summernote.min.js"></script>
<script>
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
    <ol class="breadcrumb shadow-sm small">
        <li class="breadcrumb-item">
            <a href="{{ route('products.index') }}"><i class="fa fa-lg fa-chart-pie fa-fw"></i> Products</a>
        </li>
        <li class="breadcrumb-item active">{{ $product->name }}</li>
    </ol>
    <!--end:: Breadcrumb -->

    <!--begin:: Flash Message -->
    @include('flash-message')
    <!--end:: Flash Message -->

    <!--begin:: Form -->
    <form method="POST" action="{{ route('products.update', $product->id) }}" enctype="multipart/form-data" class="bg-light rounded-lg shadow-sm my-4">
        @csrf @method('PUT')
        <div class="modal-body px-4">
            <div class="form-group">
                <label>Name <code class="text-danger">*</code></label>
                <input type="text" name="name" value="{{ old('name') ?: $product->name }}" class="form-control" placeholder="Enter the name of product" required>
            </div>
            <div class="form-group">
                <label>Brand</label>
                <select name="brand_id" class="custom-select">
                    <option value="" selected>please choose.</option>
                    @forelse (auth()->user()->brands()->get() as $brand)
                    <option value="{{ $brand->id }}" {{ selected($brand->id, $product->brand_id) }}>{{ $brand->name }}</option>
                    @empty
                    <option disabled selected>No record exist, please add brand.</option>
                    @endforelse
                </select>
            </div>
            <div class="row">
                <div class="col-sm-6 form-group">
                    <label>Category <code class="text-danger">*</code></label>
                    <select name="category_id" class="custom-select" required>
                        <option disabled>please choose</option>
                        @foreach (__all('Category') as $category)
                        <option value="{{ $category->id }}" {{ selected($category->id, $product->category_id) }}>{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-sm-6 form-group">
                    <label>Sub Category</label>
                    <select name="sub_category_id" class="custom-select">
                        <option disabled>please choose</option>
                        @foreach (__all('SubCategory') as $subCategory)
                        <option value="{{ $subCategory->id }}" {{ selected($subCategory->id, $product->sub_category_id) }}>{{ $subCategory->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6 form-group">
                    <label>Sale Price <code class="text-danger">* AED</code></label>
                    <input type="text" name="sale" value="{{ old('sale') ?: $product->sale ?? 0 }}" class="form-control isNumeric" placeholder="Enter the sale price" required wcs-price="editModal">
                    <small class="form-text text-muted pt-2"><code>Price will be apply for available stock.</code></small>
                </div>
                <div class="col-sm-6 form-group">
                    <label>Discount <small class="text-muted">%</small></label>
                    <div class="input-group">
                        <input type="text" name="discount" value="{{ old('discount') ?: $product->discount ?? 0 }}" class="form-control isNumeric" placeholder="Enter the product discount" wcs-discount="editModal">
                        <div class="input-group-append">
                            <span class="input-group-text text-danger"><small>Discount Price</small></span>
                            <span class="input-group-text" wcs-discount-price="editModal">
                                {{ $product->discountPrice ?? 0 }}
                            </span>
                        </div>
                    </div>
                    <small class="form-text text-muted pt-2"><code>Discount will be apply for available stock.</code></small>
                </div>
            </div>
            <div class="form-group">
                <label>Excerpt</label>
                <textarea name="excerpt" class="form-control" placeholder="Write a short summery of product...">{{ old('excerpt') ?: $product->excerpt }}</textarea>
            </div>
            <div class="form-group">
                <label>Description</label>
                <textarea name="description" class="form-control summernote">{{ old('description') ?: $product->description }}</textarea>
            </div>
            @role(['administrator', 'admin'])
            <div class="row">
                <div class="col-sm-6 form-group">
                    <label>Publish</label>
                    <select name="publish" class="custom-select" required>
                        <option disabled>please choose</option>
                        <option value="1" {{ selected(1, $product->publish) }}>Active</option>
                        <option value="0" {{ selected(0, $product->publish) }}>Deactive</option>
                    </select>
                </div>
                <div class="col-sm-6 form-group">
                    <label>Product Type</label>
                    <select name="type" class="custom-select">
                        <option disabled>please choose</option>
                        <option value="">Normal</option>
                        <option value="arrival" {{ selected('arrival', $product->type) }}>Arrival</option>
                        <option value="promotion" {{ selected('promotion', $product->type) }}>Promotion</option>
                    </select>
                </div>
            </div>
            @endrole
            <div class="form-group">
                <figure class="d-inline-block">
                    <img src="{{ $product->image ? asset('uploads/products/large/'.$product->image) : asset('images/default.png') }}" class="img-thumbnail" wcs-file-image="logo" style="max-height: 17rem">
                    <figcaption class="mt-2">
                            <a href="javascript:;" class="btn btn-sm btn-block btn-secondary px-4 position-relative">
                                <small>BROWSE</small>
                                <input type="file" name="logo" wcs-file-input="logo" class="w-100 h-100" style="opacity: 0; position: absolute; left: 0; top: 0; cursor: pointer">
                            </a>
                    </figcaption>
                </figure>
            </div>
            <div class="form-group alert alert-info">
                <div class="custom-control custom-checkbox custom-control-inline">
                    <input type="checkbox" id="cb" name="shipping_cost" class="custom-control-input" {{ checked(1, $product->shipping_cost) }}>
                    <label class="custom-control-label" for="cb">Would you like to set free shipping cost?</label>
                </div>
            </div>
        </div>
        <div class="modal-footer px-4">
            <button type="reset" class="btn btn-light border" data-dismiss="modal"><small>Close</small></button>
            <button type="submit" class="btn btn-info"><small>Save changes</small></button>
        </div>
    </form>
    <!--end:: Form -->
</main>
@endsection