<form method="POST" action="{{ route('meta.tags.update', $metaTag->id) }}">
    @csrf @method('PUT')
    <div class="modal-body px-4">
        <div class="form-group">
            <label>Slug</label>
            <input type="text" name="slug" value="{{ old('slug') ?: $metaTag->slug }}" class="form-control" placeholder="Enter the slug of page" required>
            <small class="form-text text-muted mt-2">
                <b>Note:</b>
                <code>*</code> is used for dynamic ids.
                Like <mark>page-name/<code>*</code></mark> maybe <mark>product/1</mark> or <mark>product/2</mark> and so on. 
            </small>
        </div>
        <div class="form-group">
            <label>Title</label>
            <input type="text" name="title" value="{{ old('title') ?: $metaTag->title }}" class="form-control" placeholder="Enter the title of page" required>
        </div>
        <div class="form-group">
            <label>Description</label>
            <textarea name="description" class="form-control" placeholder="Write a short description about the page..." required>{{ old('description') ?: $metaTag->description }}</textarea>
        </div>
        <div class="form-group">
            <label>Keywords</label>
            <textarea name="keywords" class="form-control" placeholder="Write few keywords for page..." required>{{ old('keywords') ?: $metaTag->keywords }}</textarea>
        </div>
    </div>
    <div class="modal-footer px-4">
        <button type="reset" class="btn btn-light border" data-dismiss="modal"><small>Close</small></button>
        <button type="submit" class="btn btn-info"><small>Save changes</small></button>
    </div>
</form>