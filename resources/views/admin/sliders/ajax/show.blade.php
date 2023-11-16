<form method="POST" action="{{ route('sliders.update', $slider->id) }}">
    @csrf @method('PUT')
    <div class="modal-body px-4">
        <div class="form-group">
            <label>Select Slider</label>
            <select name="type" class="custom-select" required>
                <option value="" disabled selected>please choose</option>
                <option value="1" {{ selected(1, $slider->type) }}>Main Slider</option>
                <option value="2" {{ selected(2, $slider->type) }}>Banner Slider</option>
                {{-- <option value="3">Footer Slider</option> --}}
            </select>
        </div>
        <div class="form-group">
            <label>Name</label>
            <input type="text" name="name" value="{{ old('name') ?: $slider->name }}" class="form-control" placeholder="Enter the name of slider" required>
        </div>
        <div class="form-group">
            <label>Description</label>
            <textarea name="description" class="form-control" placeholder="Write a short description about the slider...">{{ old('description') ?: $slider->description }}</textarea>
        </div>
    </div>
    <div class="modal-footer px-4">
        <button type="reset" class="btn btn-light border" data-dismiss="modal"><small>Close</small></button>
        <button type="submit" class="btn btn-info"><small>Save changes</small></button>
    </div>
</form>