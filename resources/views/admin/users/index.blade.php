@extends('layouts.admin')

@section('title', 'Manage Users')

@section('content')
<!-- Page Content -->
<main class="content">
    <!--begin:: Breadcrumb -->
    <ol class="breadcrumb d-flex align-items-center shadow-sm small">
        <li class="breadcrumb-item">
            <a href="{{ route('users.index') }}"><i class="fa fa-users-cog fa-fw"></i> Users</a>
        </li>
        <li class="breadcrumb-item active">All</li>
        <a href="javascript:;" class="btn btn-sm btn-warning rounded-pill ml-auto" data-toggle="modal" data-target="#addModal">
            <i class="fa fa-plus-circle fa-fw"></i> Add User
        </a>
    </ol>
    <!--end:: Breadcrumb -->

    <!--begin:: Flash Message -->
    @include('flash-message')
    <!--end:: Flash Message -->

    <!--begin:: Table -->
    <section class="table-responsive table-borderless small rounded mb-3">
        <table class="table table-striped table-hover shadow-sm mb-0" wcs-sorting-table>
            <thead class="tbg-brand text-white">
                <tr>
                    <th width="1%">#</th>
                    <th>Full Name <i class="fa fa-fw fa-caret-down"></i></th>
                    <th>Email <i class="fa fa-fw fa-caret-down"></i></th>
                    <th>Status <i class="fa fa-fw fa-caret-down"></i></th>
                    <th>Role <i class="fa fa-fw fa-caret-down"></i></th>
                    <th width="5%" class="not-sorted">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($users as $user)
                <tr>
                    <td><b>{{ $loop->iteration + ($users->currentPage() - 1) * $users->perPage() }}</b></td>
                    <td>{{ $user->profile->full_name ?? '-' }}</td>
                    <td>{{ $user->email }}</td>
                    <td>
                        @if ($user->deleted_at)
                        <span class="badge badge-danger font-weight-normal">deactive</span>
                        @elseif ($user->email_verified_at)
                        <span class="badge badge-success font-weight-normal">active</span>
                        @else
                        <span class="badge badge-warning font-weight-normal">pending</span>
                        @endif
                    </td>
                    <td>{{ $user->role['name'] ?: 'public' }}</td>
                    <td class="text-center">
                        @if(($user->hasRole('administrator') == auth()->user()->hasRole('administrator')) || auth()->user()->hasRole('administrator'))
                        @if($user->deleted_at || request('status') == 'deactive')
                        <a href="javascript:;" data-toggle="modal" data-target="#resModal" data-action="{{ route('users.restore', $user->id) }}" data-hover="tooltip" title="Restore" class="dynamic-modal"><i class="fa fa-redo-alt text-muted fa-lg mr-1"></i></a>
                        @else
                        <a href="javascript:;" data-toggle="modal" data-target="#editModal" data-action="{{ route('users.show', $user->id) }}" data-hover="tooltip" title="Edit" class="dynamic-modal"><i class="far fa-edit fa-fw text-info fa-lg"></i></a>
                        @endif
                        
                        @endif
                        @if(!$user->hasRole(['administrator', 'admin']) || $user->hasRole('admin') && auth()->user()->hasRole('administrator'))
                        @if($user->deleted_at)
                        <a href="javascript:;" data-toggle="modal" data-target="#dropModal" data-action="{{ route('users.dropped', $user->id) }}" data-hover="tooltip" title="Delete Permanently" class="dynamic-modal"><i class="fa fa-ban text-danger fa-lg"></i></a>
                        @else
                        <a href="javascript:;" data-toggle="modal" data-target="#delModal" data-action="{{ route('users.trashed', $user->id) }}" data-hover="tooltip" title="Deactivate" class="dynamic-modal"><i class="far fa-trash-alt text-danger fa-lg"></i></a>
                        @endif
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="text-center">There is no record exist.</td></tr>
                @endforelse
            </tbody>
        </table>
    </section>
    <!--end:: Table -->

    <!--begin:: Pagination -->
    {{ $users->links() }}
    <!--end:: Pagination -->

</main>

<!--begin:: Add Modal -->
<div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add User</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" action="{{ route('users.store') }}">
                @csrf
                <div class="modal-body px-4">
                    <div class="row form-group">
                        <div class="col-sm-6 mb-sm-0 mb-3">
                            <label>First Name</label>
                            <input type="text" name="first_name" value="{{ old('first_name') }}" class="form-control @if($errors->first('first_name')) is-invalid @endif" placeholder="Aqeel" required>
                        </div>
                        <div class="col-sm-6">
                            <label>Last Name</label>
                            <input type="text" name="last_name" value="{{ old('last_name') }}" class="form-control @if($errors->first('last_name')) is-invalid @endif" placeholder="Malik" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" name="email" value="{{ old('email') }}" class="form-control @if($errors->first('email')) is-invalid @endif" placeholder="abc@xyz.com" required>
                    </div>
                    <div class="row form-group">
                        <div class="col-sm-6 mb-sm-0 mb-3">
                            <label>Role</label>
                            <select name="role_id" class="custom-select">
                                <option value="" disabled selected>please choose</option>
                                <option value="">public</option>
                                @foreach (__all('Role') as $role)
                                @if ($role->name !== 'administrator')
                                <option value="{{ $role->id }}">{{ $role->name }}</option>
                                @endif
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-6">
                            <label>Status</label>
                            <select name="email_verified_at" class="custom-select">
                                <option disabled>please choose</option>
                                <option value="0" selected>Pending</option>
                                <option value="1">Verified</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer px-4">
                    <button type="button" class="btn btn-light border" data-dismiss="modal"><small>Close</small></button>
                    <button type="submit" class="btn btn-info"><small>Submit</small></button>
                </div>
            </form>
        </div>
    </div>
</div>
<!--end:: Add Modal -->

<!--begin:: Edit Modal -->
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit User</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div id="dynamic-content"></div>
        </div>
    </div>
</div>
<!--end:: Edit Modal -->

<!--begin:: Delete Modal -->
<div class="modal fade" id="delModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-light rounded-0">
                <h6 class="modal-title text-danger">Deactivate User</h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" action="">
                @csrf @method('POST')
                <div class="modal-body small pb-5">
                    <p>Are you sure want to deactivated this user?</p>
                </div>
                <div class="modal-footer bg-light rounded-0">
                    <button type="button" class="btn btn-light border" data-dismiss="modal"><small>Close</small></button>
                    <button type="submit" class="btn btn-danger"><small>Deactivated</small></button>
                </div>
            </form>
        </div>
    </div>
</div>
<!--end:: Delete Modal -->

<!--begin:: Restore Modal -->
<div class="modal fade" id="resModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-light rounded-0">
                <h6 class="modal-title text-danger">Restore User</h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" action="">
                @csrf @method('POST')
                <div class="modal-body small pb-5">
                    <p>Do you want to restore?</p>
                </div>
                <div class="modal-footer bg-light rounded-0">
                    <button type="button" class="btn btn-light border" data-dismiss="modal"><small>Close</small></button>
                    <button type="submit" class="btn btn-danger"><small>Restore</small></button>
                </div>
            </form>
        </div>
    </div>
</div>
<!--end:: Restore Modal -->

<!--begin:: Permanent Delete Modal -->
<div class="modal fade" id="dropModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-light rounded-0">
                <h6 class="modal-title text-danger">Drop User</h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" action="">
                @csrf @method('DELETE')
                <div class="modal-body small pb-5">
                    <p>Are you sure want to delete permanently?</p>
                    <div class="text-danger"><i class="fa fa-exclamation-triangle mr-2"></i> User relevant data will be deleted.</div>
                    <ul class="pl-3">
                        <li>User category(s) will be deleted.</li>
                        <li>User sub-category(s) will be deleted.</li>
                        <li>User profile will be deleted.</li>
                        <li>User address(s) will be deleted.</li>
                        <li>User product(s) will be deleted.</li>
                        <li>User coupon(s) will be deleted.</li>
                    </ul>
                </div>
                <div class="modal-footer bg-light rounded-0">
                    <button type="button" class="btn btn-light border" data-dismiss="modal"><small>Close</small></button>
                    <button type="submit" class="btn btn-danger"><small>Drop</small></button>
                </div>
            </form>
        </div>
    </div>
</div>
<!--end:: Permanent Delete Modal -->
@endsection