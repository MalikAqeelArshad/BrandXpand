<form method="POST" action="{{ route('users.update', $user->id) }}">
    @csrf @method('PUT') <input type="hidden" name="tab" value="account">
    <div class="modal-body px-4">
        <div class="row form-group">
            <div class="col-sm-6 mb-sm-0 mb-3">
                <label>First Name</label>
                <input type="text" name="first_name" value="{{ @$user->profile->first_name }}" class="form-control" placeholder="Aqeel" required>
            </div>
            <div class="col-sm-6">
                <label>Last Name</label>
                <input type="text" name="last_name" value="{{ @$user->profile->last_name }}" class="form-control" placeholder="Malik" required>
            </div>
        </div>
        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" value="{{ $user->email ?: old('email') }}" class="form-control" placeholder="abc@xyz.com" required>
        </div>
        <div class="row form-group">
            <div class="col-sm-6 mb-sm-0 mb-3">
                <label>Role</label>
                <select name="role_id" class="custom-select">
                    @if ($user->hasRole(['administrator','admin']) && auth()->id() == $user->id || auth()->user()->hasRole('admin') && $user->hasRole('admin'))
                    <option value="{{ $user->role_id }}" selected>{{ $user->role->name }}</option>
                    @else
                    <option disabled>please choose</option>
                    <option value="" @if (!$user->role_id) selected @endif>public</option>
                    @foreach (__all('Role') as $role)
                    {{-- @if ($role->name !== 'administrator') --}}
                    @if (!in_array($role->name, ['administrator','admin']) || auth()->user()->hasRole('administrator') && $role->name !== 'administrator')
                    <option value="{{ $role->id }}" {{ selected($user->role_id, $role->id) }}>{{ $role->name }}</option>
                    @endif
                    @endforeach
                    @endif
                </select>
            </div>
            <div class="col-sm-6">
                <label>Status</label>
                <select name="email_verified_at" class="custom-select" required>
                    @empty($user->email_verified_at)
                    <option value="0" selected>Pending</option>
                    @endempty
                    <option value="1" @isset($user->email_verified_at) selected @endisset>Verified</option>
                </select>
                @empty($user->email_verified_at)
                <small class="form-text text-danger">Email verification is required.</small>
                @endempty
            </div>
        </div>
    </div>
    <div class="modal-footer px-4">
        <button type="button" class="btn btn-light border" data-dismiss="modal"><small>Close</small></button>
        <button type="submit" class="btn btn-info"><small>Save changes</small></button>
    </div>
</form>