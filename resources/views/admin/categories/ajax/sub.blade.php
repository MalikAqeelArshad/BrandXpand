<form method="POST" action="{{ route('sub-categories.update', $subCategory->id) }}">
    @csrf @method('PUT')
    <div class="modal-body px-4">
        <div class="form-group">
            <label>Parent Category</label>
            <input type="text" value="{{ $subCategory->category->name }}" class="form-control" readonly>
        </div>
        <div class="form-group">
            <label>Sub Category Name</label>
            <input type="text" name="name" value="{{ $subCategory->name }}" class="form-control" placeholder="Enter the name of sub category" required>
        </div>
        <div class="form-group">
            <label>Description</label>
            <textarea name="description" class="form-control" placeholder="Write a short description about the sub category...">{{ $subCategory->description }}</textarea>
        </div>
    </div>
    <div class="modal-footer px-4">
        <button type="reset" class="btn btn-light border" data-dismiss="modal"><small>Close</small></button>
        <button type="submit" class="btn btn-info"><small>Save changes</small></button>
    </div>
</form>