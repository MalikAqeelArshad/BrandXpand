@extends('layouts.admin')

@section('title', 'Logos')

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
            <a href="{{ route('logos.index') }}"><i class="fa fa-lg fa-theater-masks fa-fw"></i> Companies Logo</a>
        </li>
        <li class="breadcrumb-item active">All</li>
        <a href="javascript:;" class="btn btn-sm btn-warning rounded-pill ml-auto" data-toggle="modal" data-target="#addModal">
            <i class="fa fa-plus-circle fa-fw"></i> Add Logo
        </a>
    </ol>
    <!--end:: Breadcrumb -->

    <!--begin:: Flash Message -->
    @include('flash-message')
    <!--end:: Flash Message -->

    <!--begin:: Table -->
    <section class="table-responsive table-borderless small rounded mb-3">
        <table class="table table-striped table-hover shadow-sm mb-0">
            <thead class="tbg-brand text-white">
                <tr>
                    <th width="1%">#</th>
                    <th width="50px">Photo</th>
                    <th>Name</th>
                    <th>URL</th>
                    <th>Description</th>
                    <th width="5%">Publish</th>
                    <th width="5%">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($logos as $logo)
                <tr>
                    <td><b>{{ $loop->iteration + ($logos->currentPage() - 1) * $logos->perPage() }}</b></td>
                    <td>
                        <img src="{{ @$logo->gallery->filename ? asset('uploads/logos/small/'.$logo->gallery->filename) : asset('images/default.png') }}" class="img-thumbnail" width="50px" alt="Logo"> 
                    </td>
                    <td>{{ $logo->name }}</td>
                    <td>{{ $logo->url }}</td>
                    <td>{{ $logo->description }}</td>
                    <td>
                        <div class="custom-control custom-switch">
                            <input type="checkbox" name="publish" data-action="{{ route('logos.edit', $logo->id) }}" class="dynamic-switch custom-control-input" id="publish{{ $logo->id }}" {{ checked(true, $logo->publish) }}>
                            <label class="custom-control-label" for="publish{{ $logo->id }}"></label>
                        </div>
                    </td>
                    <td>
                        <a href="javascript:;" data-toggle="modal" data-target="#editModal" data-action="{{ route('logos.show', $logo->id) }}" data-hover="tooltip" title="Edit" class="dynamic-modal"><i class="far fa-edit fa-fw text-info fa-lg"></i></a>
                        <a href="javascript:;" data-toggle="modal" data-target="#delModal" data-action="{{ route('logos.destroy', $logo->id) }}" data-hover="tooltip" title="Delete" class="dynamic-modal"><i class="far fa-trash-alt text-danger fa-lg"></i></a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="text-center">There is no record exist.</td></tr>
                @endforelse
            </tbody>
        </table>
    </section>
    <!--end:: Table -->

    <!--begin:: Pagination -->
    {{ $logos->links() }}
    <!--end:: Pagination -->

</main>

<!--begin:: Add Modal -->
<div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Logo</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" action="{{ route('logos.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="modal-body px-4">
                    <div class="form-group">
                        <label>Company Name</label>
                        <input type="text" name="name" value="{{ old('name') ?: '' }}" class="form-control" placeholder="Enter the name of company">
                    </div>
                    <div class="form-group">
                        <label>Company Website</label>
                        <input type="text" name="url" value="{{ old('url') ?: '' }}" class="form-control" placeholder="Enter the website link of company">
                    </div>
                    <div class="form-group">
                        <label>Company Logo</label>
                        <input type="file" name="logo" class="form-control" accept="image/*" required>
                        <small class="form-text text-muted pt-2">Company logo dimensions must be: <kbd>width and height equal</kbd> and format <code>jpeg, jpg, png, bmp.</code></small>
                    </div>
                    <div class="form-group">
                        <label>Description</label>
                        <textarea name="description" class="form-control" placeholder="Write a short description about the company...">{{ old('description') ?: '' }}</textarea>
                    </div>
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
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Logo</h5>
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
                <h6 class="modal-title text-danger">Delete Logo</h6>
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