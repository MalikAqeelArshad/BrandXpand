@extends('layouts.admin')

@section('title', 'Product - Stocks')

@push('pluginCSS')
<link rel="stylesheet" href="{{ asset('plugins/bootstrap-datepicker/bootstrap-datepicker.min.css') }}">
@endpush

@push('scripts')
<script type="text/javascript">
    $(document).on('click', '[data-attrs]', function () {
        var select = $('#delModal select[name="attrs"]'), attrs = $(this).data('attrs');
        select[0].selectedIndex = 0;
        select.children('option').each(function(index, value) {
            $(this).val() == attrs ? $(this).hide() : $(this).show();
        });
    });
</script>
@endpush

@push('pluginJS')
<script type="text/javascript" src="{{ asset('plugins/bootstrap-notify/bootstrap-notify.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('plugins/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
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
        <li class="breadcrumb-item active">Stock Detail</li>
        <a href="javascript:;" class="btn btn-sm btn-warning rounded-pill ml-auto" data-toggle="modal" data-target="#addStockModal">
            <i class="fa fa-plus-circle fa-fw"></i> Add Stock
        </a>
    </ol>
    <!--end:: Breadcrumb -->

    <!--begin:: Flash Message -->
    @include('flash-message')
    <!--end:: Flash Message -->

    <!--begin:: Row -->
    <div class="row">
        <div class="col-sm-5 d-none mb-3">
            <div class="alert alert-secondary bg-light shadow-sm border-0 py-4">
                <h6 class="alert-heading">Prodcut Detail</h6><hr>
                <div class="row">
                    <div class="col-lg-4 mb-3">
                        <img src="{{ $product->image ? asset('uploads/products/large/'.$product->image) : asset('images/default.png') }}" class="img-thumbnail" alt="Prodcut Image">
                    </div>
                    <div class="col-lg-8">
                        <label>{{ $product->name }}</label>
                        <ul class="list-unstyled">
                            <li><code>Code:</code> <small>{{ $product->code }}</small></li>
                            <li><code>Price:</code> <small>{{ $product->sale ?? '-' }} AED</small></li>
                            <li><code>Discount:</code> <small>{{ $product->discount ?? '-' }} %</small></li>
                            <li><code>Total Stock:</code> <small>{{ $product->stocks('unsold')->count() }}</small></li>
                        </ul>
                    </div>
                </div>
                <div class="small note-editor">{!! $product->description !!}</div>
            </div>
        </div>
        <div class="col-sm-12">
            <!--begin:: Table -->
            <section class="table-responsive table-light table-bordered border-light small">
                <table class="table table-hover shadow-sm mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th>Attrs</th>
                            <th>Available Stock</th>
                            <th width="5%">Action</th>
                        </tr>
                    </thead>
                    <tbody class="table-bordered">
                        @forelse ($product->stocks('unsold')->uniqueAttrs() as $attrs)
                        @php
                        $stocks = $product->stocks('unsold')->whereAttrs($attrs);
                        $purchases = $product->stocks('unsold')->uniquePurchase($attrs);
                        // dd($purchases);
                        @endphp

                        @if ($stock = $stocks->first())
                        <tr>
                            <td class="text-brand text-uppercase">{{ $attrs }}</td>
                            <td>
                                <table class="table table-sm table-light bg-light">
                                    <thead>
                                        <tr>
                                            <th>Purchase</th>
                                            <th>Date</th>
                                            <th class="text-right">Stock</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($purchases as $purchase)
                                        @php
                                        $dates = $product->stocks('unsold')->distinctDate($attrs, $purchase);
                                        $counts = array();
                                        foreach ($dates as $date) {
                                            $counts[] = $product->stocks('unsold')->stockCount($attrs, $purchase, $date);
                                        }
                                        @endphp
                                        <tr>
                                            <td class="align-middle">{{ $purchase }}</td>
                                            <td>
                                                @foreach ($dates as $date)
                                                <div>{{ date('d M, Y', strtotime($date)) }}</div>
                                                @endforeach
                                            </td>
                                            <td class="text-right">
                                                @foreach ($counts as $count)
                                                <div>{{ $count }}</div>
                                                @endforeach
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td><b>Sale :</b> <span class="d-inline-block">{{ $stock->sale }} AED</span></td>
                                            <th>Total</th>
                                            <th class="text-right">{{ $stocks->count() }}</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </td>
                            <td class="align-middle">
                                {{-- @if (strtolower($attrs) != 'main') --}}
                                <a href="javascript:;" data-toggle="modal" data-target="#editModal" data-action="{{ route('stocks.show', $stock->id.'?product='.$product->id) }}" data-hover="tooltip" title="Edit" class="dynamic-modal"><i class="far fa-edit fa-fw text-info fa-lg"></i></a>
                                <a href="javascript:;" data-toggle="modal" data-target="#delModal" data-action="{{ route('stocks.destroy', $stock->id.'?product='.$product->id) }}" data-hover="tooltip" title="Delete" class="dynamic-modal" data-attrs="{{ strtolower($stock->attrs) }}"><i class="far fa-trash-alt text-danger fa-lg"></i></a>
                                {{-- @endif --}}
                            </td>
                        </tr>
                        @endif
                        @empty
                        <tr><td colspan="6" class="text-center">There is no record exist.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </section>
            <!--end:: Table -->
        </div>
    </div>
    <!--end:: Row -->
</main>

<!--begin:: add Stock Modal -->
<div class="modal fade" id="addStockModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title text-brand">Add More Stock</h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" action="{{ route('products.stocks', $product->id) }}" enctype="multipart/form-data">
                @csrf @method('POST')
                <div class="modal-body px-4">
                    <div class="form-group">
                        <label class="mr-2">Attribute :</label>
                        <div class="custom-control custom-radio custom-control-inline">
                            <input type="radio" id="r1" name="attr" value="attr1" class="custom-control-input" wcs-radio="input" checked>
                            <label class="custom-control-label" for="r1">New</label>
                        </div>
                        <div class="custom-control custom-radio custom-control-inline">
                            <input type="radio" id="r2" name="attr" value="attr2" class="custom-control-input" wcs-radio="select">
                            <label class="custom-control-label" for="r2">Existing</label>
                        </div>
                        <div wcs-collapse="attr-input">
                            <input type="text" name="attr1" value="{{ old('attrs') ?: null }}" class="form-control" placeholder="Enter the name of attribute">
                        </div>
                        <div wcs-collapse="attr-select" style="display: none">
                            <select name="attr2" class="custom-select">
                                {{-- @foreach ($product->stocks('unsold')->uniqueAttrs() as $attrs) --}}
                                @foreach ($product->stocks()->uniqueAttrs() as $attrs)
                                <option value="{{ $attrs }}">{{ $attrs }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Purchase <code class="text-danger">- AED</code></label>
                        <input type="text" name="purchase" value="{{ old('purchase') ?: null }}" class="form-control isNumeric" placeholder="Enter the purchase price" required>
                    </div>
                    <div class="form-group">
                        <label>Sale <code class="text-danger">- AED</code></label>
                        <input type="text" name="sale" value="{{ old('sale') ?: null }}" class="form-control isNumeric" placeholder="Enter the sale price" required>
                        <small class="form-text text-muted pt-2"><code>New sale price will be apply for available stock.</code></small>
                    </div>
                    <div class="form-group">
                        <label>Discount <small class="text-muted">%</small></label>
                        <input type="text" name="discount" value="{{ old('discount') ?: null }}" class="form-control isNumeric" placeholder="Enter the product discount">
                        <small class="form-text text-muted pt-2"><code>New discount will be apply for available stock.</code></small>
                    </div>
                    <div class="form-group">
                        <label>Stock<small>(s)</small></label>
                        <input type="text" name="stock" value="{{ old('stock') ?: null }}" class="form-control isNumeric" placeholder="Enter the product stock" min="0" required>
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
<!--end:: add Stock Modal -->

<!--begin:: Edit Modal -->
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Stock</h5>
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
                <h6 class="modal-title text-danger">Delete Stock</h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" action="">
                @csrf @method('DELETE')
                <div class="modal-body small pb-5">
                    <p>Are you sure want to delete?</p>
                    <p class="text-danger"><i class="fa fa-exclamation-triangle mr-2"></i> Stock relevant data and history will be deleted.</p>
                    <div class="form-group">
                        <label>Set Attribute/Variation <code class="text-danger">*</code></label>
                        <select name="attrs" class="custom-select" required>
                            <option value="" disabled selected>please choose</option>
                            @foreach ($product->stocks('unsold')->uniqueAttrs() as $attrs)
                            <option value="{{ strtolower($attrs) }}">{{ $attrs }}</option>
                            @endforeach
                        </select>
                        <div class="form-text text-muted pt-2"><code>Set main stock attribute/variation of product for public users.</code></div>
                    </div>
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