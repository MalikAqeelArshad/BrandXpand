@extends('layouts.admin')

@section('title', 'Shipping Costs')

@section('content')
<!-- Page Content -->
<main class="content">
    <!--begin:: Breadcrumb -->
    <ol class="breadcrumb d-flex align-items-center shadow-sm small">
        <li class="breadcrumb-item">
            <a href="{{ route('shipping-costs.index') }}"><i class="fa fa-lg fa-hand-holding-usd fa-fw"></i> Shipping Costs</a>
        </li>
        <li class="breadcrumb-item active">All</li>
        <a href="javascript:;" class="btn btn-sm btn-warning rounded-pill ml-auto" data-toggle="modal" data-target="#addModal">
            <i class="fa fa-plus-circle fa-fw"></i> Add Shipping Cost
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
                    <th>Country</th>
                    <th>State</th>
                    <th>Shipping Charges</th>
                    <th width="5%">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($shippingCosts as $shippingCost)
                <tr>
                    <th>{{ $loop->iteration + ($shippingCosts->currentPage() - 1) * $shippingCosts->perPage() }}</th>
                    <td>@foreach (get_countries_list() as $code => $name) {{ selected($code, $shippingCost->country, false) ? $name : '' }} @endforeach</td>
                    <td>{{ $shippingCost->state }}</td>
                    <td>{{ number_format($shippingCost->charges, 2) }} <code class="text-danger">AED</code></td>
                    <td>
                        <a href="javascript:;" data-toggle="modal" data-target="#editModal" data-action="{{ route('shipping-costs.show', $shippingCost->id) }}" data-hover="tooltip" title="Edit" class="dynamic-modal"><i class="far fa-edit fa-fw text-info fa-lg"></i></a>
                        <a href="javascript:;" data-toggle="modal" data-target="#delModal" data-action="{{ route('shipping-costs.destroy', $shippingCost->id) }}" data-hover="tooltip" title="Delete" class="dynamic-modal"><i class="far fa-trash-alt text-danger fa-lg"></i></a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="text-center">There is no record exist.</td></tr>
                @endforelse
            </tbody>
        </table>
    </section>
    <!--end:: Table -->

    <!--begin:: Pagination -->
    {{ $shippingCosts->links() }}
    <!--end:: Pagination -->

</main>

<!--begin:: Add Modal -->
<div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Shipping Cost</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" action="{{ route('shipping-costs.store') }}">
                @csrf
                <div class="modal-body px-4">
                    {{-- <div class="form-group">
                        <label>Shipping State</label>
                        <select name="state" class="custom-select">
                            <option disabled selected>please choose</option>
                            <option value="Abu Dhabi">Abu Dhabi</option>
                            <option value="Dubai">Dubai</option>
                            <option value="Sharjah">Sharjah</option>
                            <option value="Umm al-Qaiwain">Umm al-Qaiwain</option>
                            <option value="Fujairah">Fujairah</option>
                            <option value="Ajman">Ajman</option>
                            <option value="Ra's al-Khaimah">Ra's al-Khaimah</option>
                        </select>
                    </div> --}}
                    <div class="form-group">
                        <label>Country</label>
                        <select name="country" class="custom-select">
                            <option disabled selected>please choose</option>
                            @foreach (get_countries_list() as $code => $name)
                            <option value="{{ $code }}">{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>State</label>
                        <input type="text" name="state" value="{{ old('state') ?: '' }}" class="form-control" placeholder="Enter the state of shipping" required>
                    </div>
                    <div class="form-group">
                        <label>Shipping Charges</label>
                        <input type="text" name="charges" value="{{ old('charges') ?: '' }}" class="form-control isNumeric" placeholder="Enter the charges of shipping">
                    </div>
                    <div class="form-group">
                        <label>Description</label>
                        <textarea name="description" class="form-control" placeholder="Write a short description about the shipping...">{{ old('description') ?: '' }}</textarea>
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
                <h5 class="modal-title">Edit Shipping Cost</h5>
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
                <h6 class="modal-title text-danger">Delete Shipping Cost</h6>
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