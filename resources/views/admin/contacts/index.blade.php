@extends('layouts.admin')

@section('title', 'Manage Contacts')

@section('content')
<!-- Page Content -->
<main class="content">
    <!--begin:: Breadcrumb -->
    <ol class="breadcrumb d-flex align-items-center shadow-sm small">
        <li class="breadcrumb-item">
            <a href="{{ route('contacts.index') }}"><i class="fa fa-lg fa-address-book fa-fw"></i> Contacts</a>
        </li>
        <li class="breadcrumb-item active">All</li>
        <a href="javascript:;" class="text-white btn-sm ml-auto" data-toggle="modal" data-target="#addModal"></a>
    </ol>
    <!--end:: Breadcrumb -->

    <!--begin:: Flash Message -->
    @include('flash-message')
    <!--end:: Flash Message -->

    <!--begin:: Table -->
    <section class="table-responsive table-borderless small rounded mb-3">
        <table class="table table-striped table-hover shadow-sm mb-0">
            <thead class="tbg-brand text-white">
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Mobile</th>
                    <th>Message</th>
                    <th width="5%" class="not-sorted">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($contacts as $contact)
                <tr>
                    <td>{{$contact->name}}</td>
                    <td>{{$contact->email}}</td>
                    <td>{{ $contact->mobile }}</td>
                    <td>{{ $contact->message }} </td>
                    <td class="text-center">
                        <a href="javascript:;" data-toggle="modal" data-target="#delModal"
                        data-action="{{ route('contacts.destroy', $contact->id) }}" data-hover="tooltip" title="Delete"
                        class="dynamic-modal"><i class="far fa-trash-alt text-danger fa-lg"></i></a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center">There is no record exist.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </section>
    <!--end:: Table -->

    <!--begin:: Pagination -->
    {{ $contacts->links() }}
    <!--end:: Pagination -->
</main>

<!--begin:: Permanent Delete Modal -->
<div class="modal fade" id="delModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-light rounded-0">
                <h6 class="modal-title text-danger">Delete Contact</h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" action="">
                @csrf @method('DELETE')
                <div class="modal-body small pb-5">
                    <p>Are you sure want to delete permanently?</p>
                </div>
                <div class="modal-footer bg-light rounded-0">
                    <button type="button" class="btn btn-light border" data-dismiss="modal">
                        <small>Close</small>
                    </button>
                    <button type="submit" class="btn btn-danger">
                        <small>Delete</small>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<!--end:: Permanent Delete Modal -->
@endsection