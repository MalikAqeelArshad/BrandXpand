<form method="POST" action="{{ route('slides.update', $slide->id) }}" enctype="multipart/form-data">
    @csrf @method('PUT')
    <div class="modal-body px-4">
        <div class="form-group">
            <label>Parent Slider</label>
            <select class="custom-select" name="slider_id" >
                <option disabled selected>please choose</option>
                @foreach (__all('Slider') as $slider)
                <option value="{{ $slider->id }}" {{ selected($slider->id, $slide->slider_id) }}>{{ $slider->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label>Button Name</label>
            <input type="text" name="name" value="{{ old('name') ?: $slide->name }}" class="form-control" placeholder="Enter the text of slide button">
        </div>
        <div class="form-group">
            <label>Button URL</label>
            <input type="text" name="url" value="{{ old('url') ?: $slide->url }}" class="form-control" placeholder="Enter the url/link of slide button">
        </div>
        {{-- <div class="form-group">
            <label>Description</label>
            <textarea name="description" class="form-control" placeholder="Write a short description about the slide...">{{ old('description') ?: $slide->description }}</textarea>
        </div> --}}
        <div class="form-group">
            <figure class="d-inline-block">
                <img src="{{ @$slide->gallery->filename ? asset('uploads/sliders/'.$slide->gallery->filename) : asset('images/default.png') }}" class="img-thumbnail" wcs-file-image="slide" style="max-height: 17rem">
                <figcaption class="mt-2">
                        <a href="javascript:;" class="btn btn-sm btn-block btn-secondary px-4 position-relative">
                            <small>BROWSE</small>
                            <input type="file" name="slide" wcs-file-input="slide" class="w-100 h-100" style="opacity: 0; position: absolute; left: 0; top: 0; cursor: pointer">
                        </a>
                </figcaption>
            </figure>
            <small class="form-text text-muted pt-2">Slide image dimensions must be: <kbd>width > 1366px</kbd> & <kbd>height > 450px</kbd> and format <code>jpeg, jpg, png, bmp.</code></small>
        </div>
    </div>
    <div class="modal-footer px-4">
        <button type="reset" class="btn btn-light border" data-dismiss="modal"><small>Close</small></button>
        <button type="submit" class="btn btn-info"><small>Save changes</small></button>
    </div>
</form>