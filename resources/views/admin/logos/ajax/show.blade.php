<form method="POST" action="{{ route('logos.update', $logo->id) }}" enctype="multipart/form-data">
    @csrf @method('PUT')
    <div class="modal-body px-4">
        <div class="form-group">
            <label>Company Name</label>
            <input type="text" name="name" value="{{ old('name') ?: $logo->name }}" class="form-control" placeholder="Enter the name of company">
        </div>
        <div class="form-group">
            <label>Company Website</label>
            <input type="text" name="url" value="{{ old('url') ?: $logo->url }}" class="form-control" placeholder="Enter the website link of company">
        </div>
        <div class="form-group">
            <label>Description</label>
            <textarea name="description" class="form-control" placeholder="Write a short description about the company...">{{ old('description') ?: $logo->description }}</textarea>
        </div>
        <div class="form-group">
            <figure class="d-inline-block">
                <img src="{{ @$logo->gallery->filename ? asset('uploads/logos/'.$logo->gallery->filename) : asset('images/default.png') }}" class="img-thumbnail" wcs-file-image="logo" style="max-height: 17rem">
                <figcaption class="mt-2">
                        <a href="javascript:;" class="btn btn-sm btn-block btn-secondary px-4 position-relative">
                            <small>BROWSE</small>
                            <input type="file" name="logo" wcs-file-input="logo" class="w-100 h-100" style="opacity: 0; position: absolute; left: 0; top: 0; cursor: pointer">
                        </a>
                </figcaption>
            </figure>
            <small class="form-text text-muted pt-2">Company logo dimensions must be: <kbd>width and height equal</kbd> and format <code>jpeg, jpg, png, bmp.</code></small>
        </div>
    </div>
    <div class="modal-footer px-4">
        <button type="reset" class="btn btn-light border" data-dismiss="modal"><small>Close</small></button>
        <button type="submit" class="btn btn-info"><small>Save changes</small></button>
    </div>
</form>