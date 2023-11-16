@extends('layouts.admin')

@section('title', 'Sliders')

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
            <a href="{{ route('sliders.index') }}"><i class="far fa-lg fa-images fa-fw"></i> Sliders</a>
        </li>
        <li class="breadcrumb-item active">All</li>
        <a href="javascript:;" class="btn btn-sm btn-warning rounded-pill ml-auto" data-toggle="modal" data-target="#addModal">
            <i class="fa fa-plus-circle fa-fw"></i> Add Slider
        </a>
    </ol>
    <!--end:: Breadcrumb -->

    <!--begin:: Flash Message -->
    @include('flash-message')
    <!--end:: Flash Message -->

    <!--begin:: Table -->
    <table class="table table-responsive table-borderless table-striped table-hover small shadow-sm rounded">
        <thead class="tbg-brand text-white">
            <tr>
                <th width="1%">#</th>
                <th>Name</th>
                <th>Description</th>
                <th>Slides</th>
                <th width="5%">Publish</th>
                <th width="5%">Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($sliders as $slider)
            <tr>
                <th>{{ $loop->iteration + ($sliders->currentPage() - 1) * $sliders->perPage() }}</th>
                <td>{{ $slider->name }}</td>
                <td>{{ $slider->description }}</td>
                <td><a href="{{ route('sliders.slides', $slider->id) }}" class="btn-link">({{ $slider->slides->count() }}) view</a></td>
                <td>
                    <div class="custom-control custom-switch">
                        <input type="checkbox" name="publish" data-action="{{ route('sliders.edit', $slider->id) }}" class="dynamic-switch custom-control-input" id="publish{{ $slider->id }}" {{ checked(true, $slider->publish) }}>
                        <label class="custom-control-label" for="publish{{ $slider->id }}"></label>
                    </div>
                </td>
                <td>
                    <a href="javascript:;" data-toggle="modal" data-target="#editModal" data-action="{{ route('sliders.show', $slider->id) }}" data-hover="tooltip" title="Edit" class="dynamic-modal"><i class="far fa-edit fa-fw text-info fa-lg"></i></a>
                    <a href="javascript:;" data-toggle="modal" data-target="#delModal" data-action="{{ route('sliders.destroy', $slider->id) }}" data-hover="tooltip" title="Delete" class="dynamic-modal"><i class="far fa-trash-alt text-danger fa-lg"></i></a>
                </td>
            </tr>
            @empty
            <tr><td colspan="6" class="text-center">There is no record exist.</td></tr>
            @endforelse
        </tbody>
    </table>
    <!--end:: Table -->

    <!--begin:: Pagination -->
    {{ $sliders->links() }}
    <!--end:: Pagination -->

</main>

<!--begin:: Add Modal -->
<div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Slider</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" action="{{ route('sliders.store') }}">
                @csrf
                <div class="modal-body px-4">
                    <div class="form-group">
                        <label>Select Slider</label>
                        <select name="type" class="custom-select" required>
                            <option value="" disabled selected>please choose</option>
                            <option value="1">Main Slider</option>
                            <option value="2">Banner Slider</option>
                            {{-- <option value="3">Footer Slider</option> --}}
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Name</label>
                        <input type="text" name="name" class="form-control" placeholder="Enter the name of slider" required>
                    </div>
                    <div class="form-group">
                        <label>Description</label>
                        <textarea name="description" class="form-control" placeholder="Write a short description about the slider..."></textarea>
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
                <h5 class="modal-title">Edit Slider</h5>
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
                <h6 class="modal-title text-danger">Delete Slider</h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" action="">
                @csrf @method('DELETE')
                <div class="modal-body small pb-5">
                    <p>Are you sure want to delete?</p>
                    <div class="text-danger"><i class="fa fa-exclamation-triangle mr-2"></i> Slider relevant data will be deleted.</div>
                    <ul class="pl-3">
                        <li>Slider will be deleted permanently.</li>
                        <li>All slide(s) of slider also will be deleted.</li>
                    </ul>
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