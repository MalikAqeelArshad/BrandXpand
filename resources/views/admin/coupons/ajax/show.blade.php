<form method="POST" action="{{ route('coupons.update', $coupon->id) }}">
    @csrf @method('PUT')
    <div class="modal-body px-4">
        <div class="form-group">
            <label>Name</label>
            <input type="text" name="name" value="{{ $coupon->name }}" class="form-control" placeholder="Enter the name of category" required>
        </div>
        <div class="form-group">
            <label>Discount</label>
            <input type="number" name="discount" value="{{ $coupon->discount }}" class="form-control isNumeric" min="0" max="100" placeholder="Enter the amount of coupon" required>
        </div>
        <div class="form-group">
            <label>Expiry Date</label>
            <input type="text" name="expiry_date" value="{{ $coupon->expiry_date }}" class="form-control bg-transparent" data-provide="datepicker" data-date-format="yyyy-mm-dd" placeholder="yyyy-mm-dd" readonly required>
        </div>
        <div class="form-group">
            <label>Status</label>
            <select name="status" class="custom-select">
                <option disabled>please choose</option>
                <option value="1" {{ selected(1, $coupon->status) }}>Active</option>
                <option value="0" {{ selected(0, $coupon->status) }}>Deactive</option>
            </select>
        </div>
    </div>
    <div class="modal-footer px-4">
        <button type="reset" class="btn btn-light border" data-dismiss="modal"><small>Close</small></button>
        <button type="submit" class="btn btn-info"><small>Save changes</small></button>
    </div>
</form>