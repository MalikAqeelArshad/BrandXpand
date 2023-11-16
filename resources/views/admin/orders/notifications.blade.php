@extends('layouts.admin')

@section('title', 'Edit Order')

@section('content')
<!-- Page Content -->
<main class="content">
    <h5 class="bg-light text-brand shadow-sm p-3 mb-4">Notifications</h5>

    <!--start:: Notifications -->
    <nav class="list-group small mb-4">
        @forelse ($notifications as $notification)
            @if ($notification->type == "Product" && $product = $notification->product())
            <a href="{{ route('products.show', [$product->id, 'notification_id'=>$notification->id]) }}" class="list-group-item list-group-item-action">
                {{ $product->name }}
                <small class="d-block">
                    {{ $notification->data['message'] }}
                    @isset ($product->user->first_name) by <b class="text-brand">{{ $product->user->first_name }}</b> @endisset
                </small>
            </a>
            @endif

            @if ($notification->type == "Order" && $order = $notification->order())
            <a href="{{ route('orders.edit', [$order->reference_number, 'notification_id'=>$notification->id]) }}" class="list-group-item list-group-item-action">
                {{-- {{ $order->product->name }} --}}
                {{ $order->reference_number }}
                <small class="d-block">
                    {{ $notification->data['message'] }}
                    @isset ($order->user->first_name) by <b class="text-brand">{{ $order->user->first_name }}</b> @endisset
                </small>
            </a>
            @endif
        @empty
        <a href="javascript:;" class="list-group-item list-group-item-action">
            <small>There is no any order exist.</small>
        </a>
        @endforelse
    </nav>
    <!--end:: Notifications -->

    <!--begin:: Pagination -->
    {{ $notifications->links() }}
    <!--end:: Pagination -->
</main>
@endsection