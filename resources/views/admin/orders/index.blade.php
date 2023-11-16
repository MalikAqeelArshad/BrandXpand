@extends('layouts.admin')

@section('title', 'Manage Orders')

@push('styles')
<style>
.dataTables_filter{float:right !important}
#example_paginate, .dataTables_info, .dataTables_length{display: none !important}
</style>
@endpush

@push('scripts')
<script>
    $(document).ready(function() {
        var table = $('#example').DataTable({
                // lengthChange: false,
            });
    });
</script>
@endpush

@section('content')
<!-- Page Content -->
<main class="content">
    <!--begin:: Breadcrumb -->
    <ol class="breadcrumb d-flex align-items-center shadow-sm small">
        <li class="breadcrumb-item">
            <a href="{{ route('orders.index') }}"><i class="fa fa-lg fa-shopping-basket fa-fw"></i> Orders </a>
        </li>
        <li class="breadcrumb-item active">All</li>
        <a href="javascript:;" class="text-white btn-sm ml-auto" data-toggle="modal" data-target="#addModal"></a>
    </ol>
    <!--end:: Breadcrumb -->

    <!--begin:: Flash Message -->
    @include('flash-message')
    <!--end:: Flash Message -->

    <a href="{{ route('export.orders') }}" class="btn btn-outline-success btn-sm float-left" style="position: relative; top: 1rem">Export in Excel</a>
    <!--begin:: Table -->
    <table class="table table-striped table-hover small shadow-sm rounded" id="example">
        <thead class="tbg-brand text-white">
            <tr>
                <th>Order ID <i class="fa fa-fw fa-caret-down"></i></th>
                <th>Email</th>
                {{-- <th>Coupon</th> --}}
                <th>Payment Method</th>
                <th>Shipping Charges</th>
                <th>Status</th>
                <th>Grand Total <i class="fa fa-fw fa-caret-down"></i></th>
                <th width="5%" class="not-sorted">Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($orders->unique('reference_number') as $order)
            <tr>
                <td>{{ $order->reference_number }}</td>
                <td><a href="{{ route('users.edit', $order->user->id) }}" class="btn-link">{{ $order->user->email }}</a></td>
                {{-- <td>{{ $order->coupon_id ? $order->coupon->name : '-' }}</td> --}}
                <td>{{ $order->payment_method }}</td>
                <td>{{ number_format($order->shipping_charges, 2) }} <small>AED</small></td>
                <td>
                    @if ($order->status == 'shipped')
                    <span class="badge badge-info font-weight-normal">shipped</span>
                    @elseif ($order->status == 'delivered')
                    <span class="badge badge-success font-weight-normal">delivered</span>
                    @elseif ($order->status == 'cancelled')
                    <span class="badge badge-danger font-weight-normal">cancelled</span>
                    @else
                    <span class="badge badge-warning font-weight-normal">pending</span>
                    @endif
                </td>
                <td>
                    {{-- @role(['administrator','admin'])
                    {{ $order->grand_total - $order->discount }}
                    @else
                    {{ round($order->whereReferenceNumber($order->reference_number)->whereIn('product_id', App\Product::allByRole()->pluck('id'))->sum('total'), 2) }}
                    @endrole <small>AED</small> --}}
                    {{ $order->whereReferenceNumber($order->reference_number)->whereIn('product_id', App\Product::allByRole()->pluck('id'))->sum('total') + $order->shipping_charges }} <small>AED</small>
                </td>
                <td>
                    <a href="javascript:;" data-toggle="modal" data-target="#editModal"
                    data-action="{{ route('orders.show', $order->reference_number) }}" data-hover="tooltip" title="Edit"
                    class="dynamic-modal"><i class="far @role(['administrator','admin']) fa-edit @else fa-eye @endrole fa-fw text-info fa-lg"></i></a>
                    @role(['administrator','admin'])
                    <a href="javascript:;" data-toggle="modal" data-target="#delModal"
                    data-action="{{ route('orders.destroy', $order->reference_number) }}" data-hover="tooltip" title="Delete"
                    class="dynamic-modal"><i class="far fa-trash-alt text-danger fa-lg"></i></a>
                    @endrole
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="text-center">There is no record exist.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    <!--end:: Table -->

    <!--begin:: Pagination -->
    {{ $orders->links() }}
    <!--end:: Pagination -->
</main>

<!--begin:: Edit Modal -->
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">View Order</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div id="dynamic-content"></div>
        </div>
    </div>
</div>
<!--end:: Edit Modal -->

<!--begin:: Permanent Delete Modal -->
<div class="modal fade" id="delModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-light rounded-0">
                <h6 class="modal-title text-danger">Delete Order</h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" action="">
                @csrf @method('DELETE')
                <div class="modal-body small pb-5">
                    <p>Are you sure want to delete permanently?</p>
                    <p>All items in the order will be delete!</p>
                </div>
                <div class="modal-footer bg-light rounded-0">
                    <button type="button" class="btn btn-light border" data-dismiss="modal">
                        <small>Close</small>
                    </button>
                    <button type="submit" class="btn btn-danger">
                        <small>Delete order</small>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<!--end:: Permanent Delete Modal -->
@endsection