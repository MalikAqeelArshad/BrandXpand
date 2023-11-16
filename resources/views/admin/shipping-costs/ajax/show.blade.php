<form method="POST" action="{{ route('shipping-costs.update', $shippingCost->id) }}">
    @csrf @method('PUT')
    <div class="modal-body px-4">
        <div class="form-group">
            <label>Country</label>
            <select name="country" class="custom-select">
                <option disabled selected>please choose</option>
                @foreach (get_countries_list() as $code => $name)
                <option value="{{ $code }}" {{ selected($code, $shippingCost->country) }}>{{ $name }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label>State</label>
            <input type="text" name="state" value="{{ old('state') ?: $shippingCost->state }}" class="form-control" placeholder="Enter the state of shipping" required>
        </div>
        <div class="form-group">
            <label>Shipping Charges</label>
            <input type="text" name="charges" value="{{ old('charges') ?: $shippingCost->charges }}" class="form-control isNumeric" placeholder="Enter the charges of shipping cost">
        </div>
        <div class="form-group">
            <label>Description</label>
            <textarea name="description" class="form-control" placeholder="Write a short description about the shipping...">{{ old('description') ?: $shippingCost->description }}</textarea>
        </div>
    </div>
    <div class="modal-footer px-4">
        <button type="reset" class="btn btn-light border" data-dismiss="modal"><small>Close</small></button>
        <button type="submit" class="btn btn-info"><small>Save changes</small></button>
    </div>
</form>