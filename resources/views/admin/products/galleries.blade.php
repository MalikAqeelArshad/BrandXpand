@extends('layouts.admin')

@section('title', 'Gallery - Product')

@push('pluginCSS')
{{-- <link rel="stylesheet" href="{{ asset('plugins/bootstrap-datepicker/bootstrap-datepicker.min.css') }}"> --}}
@endpush

@push('pluginJS')
<script type="text/javascript" src="{{ asset('plugins/bootstrap-notify/bootstrap-notify.min.js') }}"></script>
{{-- <script type="text/javascript" src="{{ asset('plugins/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script> --}}
@endpush

@section('content')
<!-- Page Content -->
<main class="content">
    <!--begin:: Breadcrumb -->
    <ol class="breadcrumb d-flex align-items-center shadow-sm small">
        <li class="breadcrumb-item">
            <a href="{{ route('products.index') }}"><i class="fa fa-lg fa-chart-pie fa-fw"></i> Products</a>
        </li>
        <li class="breadcrumb-item">{{ $product->name }}</li>
        <li class="breadcrumb-item active">Gallery</li>
        <a href="javascript:;" class="btn btn-sm btn-warning rounded-pill ml-auto" data-toggle="modal" data-target="#addModal">
            <i class="fa fa-plus-circle fa-fw"></i> Add Gallery Image
        </a>
    </ol>
    <!--end:: Breadcrumb -->

    <!--begin:: Flash Message -->
    @include('flash-message')
    <!--end:: Flash Message -->

    <!--begin:: Row -->
    <div class="row">
        <div class="col-sm-5 mb-3">
            <div class="alert alert-secondary bg-light border-light shadow-sm h-100 py-4">
                <h6 class="alert-heading">Prodcut Detail</h6><hr>
                <div class="row">
                    <div class="col-lg-4 mb-3">
                        <img src="{{ $product->image ? asset('uploads/products/large/'.$product->image) : asset('images/default.png') }}" class="img-thumbnail" alt="Logo">
                    </div>
                    <div class="col-lg-8">
                        <label>{{ $product->name }}</label>
                        <ul class="list-unstyled">
                            <li><code>Code:</code> <small>{{ $product->code }}</small></li>
                            <li><code>Price:</code> <small>{{ $product->sale ?? '-' }} AED</small></li>
                            <li><code>Discount:</code> <small>{{ $product->discount ?? 0 }} %</small></li>
                        </ul>
                    </div>
                </div>
                <div class="note-editor small">{!! $product->description !!}</div>
            </div>
        </div>
        <div class="col-sm-7">
            <!--begin:: Table -->
            <section class="table-responsive table-borderless small rounded mb-3">
                <table class="table table-striped table-hover shadow-sm mb-0">
                    <thead class="bg-danger text-white">
                        <tr>
                            <th width="1%">#</th>
                            <th width="80%">Image</th>
                            <th>Publish</th>
                            <th width="5%">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($galleries as $gallery)
                        <tr>
                            <th>{{ $loop->iteration + ($galleries->currentPage() - 1) * $galleries->perPage() }}</th>
                            <td>
                                <img src="{{ $gallery->filename ? asset('uploads/products/small/'.$gallery->filename) : asset('images/default.png') }}" class="img-thumbnail" width="50px" alt="Logo">
                            </td>
                            <td>
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" name="publish" data-action="{{ route('galleries.edit', $gallery->id) }}" class="dynamic-switch custom-control-input" id="publish{{ $gallery->id }}" {{ checked(true, $gallery->publish) }}>
                                    <label class="custom-control-label" for="publish{{ $gallery->id }}"></label>
                                </div>
                            </td>
                            <td>
                                {{-- <a href="javascript:;" data-toggle="modal" data-target="#editModal" data-action="{{ route('products.show', $gallery->id) }}" data-hover="tooltip" title="Edit" class="dynamic-modal"><i class="far fa-edit fa-fw text-info fa-lg"></i></a> --}}
                                <a href="javascript:;" data-toggle="modal" data-target="#delModal" data-action="{{ route('galleries.destroy', $gallery->id) }}" data-hover="tooltip" title="Delete" class="dynamic-modal"><i class="far fa-trash-alt text-danger fa-lg"></i></a>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="text-center">There is no record exist.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </section>
            <!--end:: Table -->

            <!--begin:: Pagination -->
            {{ $galleries->links() }}
            <!--end:: Pagination -->
        </div>
    </div>
    <!--end:: Row -->

</main>

<!--begin:: Add Modal -->
<div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Gallery Image</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" action="{{ route('products.galleries', $product->id) }}" enctype="multipart/form-data">
                @csrf
                <div class="modal-body px-4 text-center">
                    <figure class="d-inline-block">
                        <img src="{{ asset('images/default.png') }}" class="img-thumbnail" wcs-file-image="file" style="max-height: 17rem">
                        <figcaption class="mt-2">
                            <a href="javascript:;" class="btn btn-sm btn-block btn-secondary px-4 position-relative">
                                <small>BROWSE</small>
                                <input type="file" name="file" wcs-file-input="file" class="w-100 h-100" style="opacity: 0; position: absolute; left: 0; top: 0; cursor: pointer">
                            </a>
                        </figcaption>
                    </figure>
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
                <h5 class="modal-title">Edit Gallery</h5>
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
                <h6 class="modal-title text-danger">Delete Gallery</h6>
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