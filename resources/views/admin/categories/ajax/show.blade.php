<form method="POST" action="{{ route('categories.update', $category->id) }}">
    @csrf @method('PUT')
    <div class="modal-body px-4">
        <div class="form-group">
            <label>Name</label>
            <input type="text" name="name" value="{{ $category->name }}" class="form-control" placeholder="Enter the name of category" required>
        </div>
        <div class="form-group">
            <label>Description</label>
            <textarea name="description" class="form-control" placeholder="Write a short description about the category...">{{ $category->description }}</textarea>
        </div>
    </div>
    <div class="modal-footer px-4">
        <button type="reset" class="btn btn-light border" data-dismiss="modal"><small>Close</small></button>
        <button type="submit" class="btn btn-info"><small>Save changes</small></button>
    </div>
</form>