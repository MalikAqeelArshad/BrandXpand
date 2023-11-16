<form method="POST" action="{{ route('stocks.update', $stock->id.'?product='.$stock->product_id) }}">
    @csrf @method('PUT')
    <div class="modal-body px-4">
        <div class="form-group">
            <label>Attribute</label>
            <input type="text" name="attrs" value="{{ old('attrs') ?: $stock->attrs }}" class="form-control" placeholder="Enter the name of attrs" required>
        </div>
        <div class="form-group">
            <label>Sale Price <code class="text-danger">* AED</code></label>
            <input type="text" name="sale" value="{{ old('sale') ?: $stock->sale ?? 0 }}" class="form-control isNumeric" placeholder="Enter the sale price" required wcs-price="editDiscount">
            <small class="form-text text-muted pt-2"><code>Price will be apply for available {{ strtolower($stock->attrs) }} stock.</code></small>
        </div>
        <div class="form-group">
            <label>Discount <small class="text-muted">%</small></label>
            <div class="input-group">
                <input type="text" name="discount" value="{{ old('discount') ?: $stock->discount ?? 0 }}" class="form-control isNumeric" placeholder="Enter the attribute discount" wcs-discount="editDiscount">
                <div class="input-group-append">
                    <span class="input-group-text text-danger"><small>Discount Price</small></span>
                    <span class="input-group-text" wcs-discount-price="editDiscount">{{ $stock->discountPrice ?? 0 }}</span>
                </div>
            </div>
        </div>
        <div class="form-group alert alert-warning">
            <div class="custom-control custom-checkbox custom-control-inline">
                <input type="checkbox" id="cb" name="changeStock" class="custom-control-input" wcs-checkbox="changeStock">
                <label class="custom-control-label" for="cb">Do you want change the purchase price?</label>
            </div>
            <div wcs-toggle="changeStock" style="display: none">
                <div class="form-group">
                    <label><b class="form-text text-muted"><code>Select date of price which you want to change.</code></b></label>
                    <input type="text" name="date" class="form-control bg-white" data-provide="datepicker" data-date-format="yyyy-mm-dd" placeholder="yyyy-mm-dd" readonly>
                </div>
                <div class="row">
                    <label class="col-12"><b class="form-text text-muted"><code>Select purchase price which you want to change.</code></b></label>
                    <div class="form-group col-6 pr-0">
                        <label>Old Price <code class="text-danger">*</code></label>
                        <select name="price" class="custom-select">
                            <option value="" disabled selected>please choose</option>
                            @foreach ($stock->product->stocks('unsold')->uniquePurchase($stock->attrs) as $purchase)
                            <option value="{{ $purchase }}">{{ $purchase }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-6 pl-0">
                        <label>New Price <code class="text-danger">* AED</code></label>
                        <input type="text" name="purchase" value="{{ old('purchase') ?: null }}" class="form-control isNumeric" placeholder="Enter the purchase price">
                    </div>
                </div>
            </div>
        </div>
        <div class="alert alert-danger">
            <div class="custom-control custom-checkbox custom-control-inline">
                <input type="checkbox" id="cbr" name="deleteStock" class="custom-control-input" wcs-checkbox="deleteStock">
                <label class="custom-control-label" for="cbr">Do you want delete stock?</label>
            </div>
            <div wcs-toggle="deleteStock" style="display: none">
                <div class="form-group">
                    <label><b class="form-text text-muted"><code>Select date of purchase which you want to delete the stock.</code></b></label>
                    <input type="text" name="delete_date" class="form-control bg-white" data-provide="datepicker" data-date-format="yyyy-mm-dd" placeholder="yyyy-mm-dd" readonly>
                </div>
                <div class="form-group">
                    <label>Stock<small>(s)</small> <code class="text-danger">*</code></label>
                    <input type="text" name="stock" class="form-control isNumeric" placeholder="Enter the stock value">
                    <small class="form-text text-muted"><code>How many stock would you want delete.</code></small>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer px-4">
        <button type="reset" class="btn btn-light border" data-dismiss="modal"><small>Close</small></button>
        <button type="submit" class="btn btn-info"><small>Save changes</small></button>
    </div>
</form>