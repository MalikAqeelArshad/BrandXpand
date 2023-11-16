@extends('layouts.admin')

@section('title', 'Reviews')

@push('pluginJS')
    <script type="text/javascript" src="{{ asset('plugins/bootstrap-notify/bootstrap-notify.min.js') }}"></script>
@endpush

@section('content')
<!-- Page Content -->
<main class="content">
    <!--begin:: Breadcrumb -->
    <ol class="breadcrumb shadow-sm small">
        <li class="breadcrumb-item">
            <a href="{{ route('product.reviews') }}"><i class="far fa-lg fa-star fa-fw"></i> Reviews </a>
        </li>
        <li class="breadcrumb-item active">All</li>
    </ol>
    <!--end:: Breadcrumb -->

    <!--begin:: Flash Message -->
    @include('flash-message')
    <!--end:: Flash Message -->

    <!--begin:: Table -->
    <section class="table-responsive table-borderless small rounded mb-3">
        <table class="table table-striped table-hover shadow-sm mb-0" wcs-sorting-table>
            <thead class="tbg-brand text-white">
                <tr>
                    <th width="1%">#</th>
                    <th>Order ID<i class="fa fa-fw fa-caret-down"></i></th>
                    <th>Product <i class="fa fa-fw fa-caret-down"></i></th>
                    <th>Rate <i class="fa fa-fw fa-caret-down"></i></th>
                    <th width="40%">Review</th>
                    @role(['administrator','admin'])
                    <th width="5%">Publish</th>
                    <th width="5%" class="not-sorted">Action</th>
                    @endrole
                </tr>
            </thead>
            <tbody>
                @forelse ($reviews as $review)
                <tr>
                    <td><b>{{ $loop->iteration + ($reviews->currentPage() - 1) * $reviews->perPage() }}</b></td>
                    <td>{{ @$review->order->reference_number }}</td>
                    <td>{{ @$review->product->name }}</td>
                    <td>{{ @$review->rating }}</td>
                    <td>{{ @$review->review}}</td>
                    @role(['administrator','admin'])
                    <td>
                        <div class="custom-control custom-switch">
                            <input type="checkbox" name="publish" data-action="{{ route('review.edit', $review->id) }}" class="dynamic-switch custom-control-input" id="publish{{ $review->id }}" {{ checked(true, $review->publish) }}>
                            <label class="custom-control-label" for="publish{{ $review->id }}"></label>
                        </div>
                    </td>
                    <td>
                        <a href="javascript:;" data-toggle="modal" data-target="#delModal" data-action="{{ route('review.destroy', $review->id) }}" data-hover="tooltip" title="Delete" class="dynamic-modal"><i class="far fa-trash-alt text-danger fa-lg"></i></a>
                    </td>
                    @endrole
                </tr>
                @empty
                <tr><td colspan="7" class="text-center">There is no record exist.</td></tr>
                @endforelse
            </tbody>
        </table>
    </section>
    <!--end:: Table -->

    <!--begin:: Pagination -->
    {{ $reviews->links() }}
    <!--end:: Pagination -->

</main>
    
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