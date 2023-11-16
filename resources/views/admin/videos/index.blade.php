@extends('layouts.admin')

@section('title', 'Videos')

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
            <a href="{{ route('videos.index') }}"><i class="far fa-lg fa-images fa-fw"></i> Videos</a>
        </li>
        <li class="breadcrumb-item active">All</li>
        <a href="javascript:;" class="btn btn-sm btn-warning rounded-pill ml-auto" data-toggle="modal" data-target="#addModal">
            <i class="fa fa-plus-circle fa-fw"></i> Add Video
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
                    <th width="50px">Video</th>
                    <th>Title</th>
                    <th>Description</th>
                    <th width="5%">Publish</th>
                    <th width="5%">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($videos as $video)
                <tr>
                    <td><b>{{ $loop->iteration + ($videos->currentPage() - 1) * $videos->perPage() }}</b></td>
                    <td>
                    @php
                        // $gallery = $video->galleries->pluck('filename','filetype');
                        $exts = ['jpeg', 'jpg', 'png', 'bmp'];
                        $file = $video->galleries()->whereIn('filetype', $exts)->first()['filename'];
                    @endphp
                    <img src="{{ asset($file ? 'uploads/videos/small/'.$file : 'images/default.png') }}" class="img-thumbnail" width="50px" alt="Poster"> 
                    </td>
                    <td>{{ $video->title }}</td>
                    <td>{{ $video->description }}</td>
                    <td>
                        <div class="custom-control custom-switch">
                            <input type="checkbox" name="publish" data-action="{{ route('videos.edit', $video->id) }}" class="dynamic-switch custom-control-input" id="publish{{ $video->id }}" {{ checked(true, $video->publish) }}>
                            <label class="custom-control-label" for="publish{{ $video->id }}"></label>
                        </div>
                    </td>
                    <td>
                        <a href="javascript:;" data-toggle="modal" data-target="#editModal" data-action="{{ route('videos.show', $video->id) }}" data-hover="tooltip" title="Edit" class="dynamic-modal"><i class="far fa-edit fa-fw text-info fa-lg"></i></a>
                        <a href="javascript:;" data-toggle="modal" data-target="#delModal" data-action="{{ route('videos.destroy', $video->id) }}" data-hover="tooltip" title="Delete" class="dynamic-modal"><i class="far fa-trash-alt text-danger fa-lg"></i></a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center">There is no record exist.</td></tr>
                @endforelse
            </tbody>
        </table>
    </section>
    <!--end:: Table -->

    <!--begin:: Pagination -->
    {{ $videos->links() }}
    <!--end:: Pagination -->

</main>

<!--begin:: Add Modal -->
<div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Video</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" action="{{ route('videos.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="modal-body px-4">
                    <div class="form-group">
                        <label>Video Title</label>
                        <input type="text" name="title" value="{{ old('title') ?: '' }}" class="form-control" placeholder="Enter the title of video">
                    </div>
                    <div class="form-group">
                        <label>Description</label>
                        <textarea name="description" class="form-control" placeholder="Write a short description about the video...">{{ old('description') ?: '' }}</textarea>
                    </div>
                    <div class="form-group">
                        <label>Poster</label>
                        <input type="file" name="poster" value="{{ old('poster') ?: '' }}" class="form-control" placeholder="Enter the poster of video" accept="image/*">
                        <small class="form-text text-muted pt-2">Poster image dimensions must be: <kbd>width > 320px</kbd>, <kbd>height > 180px</kbd> and format <code>jpeg, jpg, png, bmp.</code></small>
                    </div>
                    <div class="form-group">
                        <label>Video</label>
                        <input type="file" name="video" class="form-control" accept="video/mp4" required>
                        <small class="form-text text-muted pt-2">Video size must be: <kbd>size < 10MB</kbd> and format <code>.mp4</code></small>
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
                <h5 class="modal-title">Edit Video</h5>
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
                <h6 class="modal-title text-danger">Delete Video</h6>
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