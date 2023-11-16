<form method="POST" action="{{ route('videos.update', $video->id) }}" enctype="multipart/form-data">
    @csrf @method('PUT')
    <div class="modal-body px-4">
        <div class="form-group">
            <label>Video Title</label>
            <input type="text" name="title" value="{{ old('title') ?: $video->title }}" class="form-control" placeholder="Enter the title of video">
        </div>
        <div class="form-group">
            <label>Description</label>
            <textarea name="description" class="form-control" placeholder="Write a short description about the video...">{{ old('description') ?: $video->description }}</textarea>
        </div>
        <div class="form-group">
            <label>Poster</label>
            <input type="file" name="poster" class="form-control" accept="image/*">
            <small class="form-text text-muted pt-2">Poster image dimensions must be: <kbd>width > 320px</kbd>, <kbd>height > 180px</kbd> and format <code>jpeg, jpg, png, bmp.</code></small>
        </div>
        <div class="form-group">
            <label>Video</label>
            <input type="file" name="video" class="form-control" accept="video/mp4">
            <small class="form-text text-muted pt-2">Video size must be: <kbd>size < 10MB</kbd> and format <code>.mp4</code></small>
        </div>
    </div>
    <div class="modal-footer px-4">
        <button type="reset" class="btn btn-light border" data-dismiss="modal"><small>Close</small></button>
        <button type="submit" class="btn btn-info"><small>Save changes</small></button>
    </div>
</form>