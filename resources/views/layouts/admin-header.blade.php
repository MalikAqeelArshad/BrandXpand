<!-- Header -->
<header>
	<nav class="navbar navbar-expand navbar-light bg-white fixed-top shadow-sm">
		<a href="{{ route('admin') }}" class="float-left text-brand">
			<h5 class="brand-lg m-0">{{ config('app.name') }}</h5>
			<strong class="brand-sm">BXT</strong>
		</a>
		<a href="javascript:;" class="text-brand ml-4" id="sidebarCollapse">
			<i class="fa fa-align-left"></i>
			<!-- <span>Toggle Sidebar</span> -->
		</a>
		<ul class="navbar-nav ml-auto">
			<li class="nav-item dropdown mx-1">
				<a href="{{ url('/') }}" class="nav-link"><i class="fa fa-home fa-lg text-brand"></i></a>
			</li>
			<li class="nav-item dropdown mx-1">
				<a href="javascript:;" class="nav-link dropdown-toggle collapsed" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
					<i class="fa fa-bell fa-lg text-brand"></i>
					<span class="badge badge-warning">{{ auth()->user()->unreadNotifications->count() }}</span>
				</a>
				<div class="dropdown-menu dropdown-menu-right p-0 border-0" style="min-width: 200px">
					<nav class="list-group small">
						@forelse (auth()->user()->unreadNotifications->take(5) as $notification)
							@if ($notification->type == "Product" && ($product = $notification->product()))
							<a href="{{ route('products.show', [$product->id, 'notification_id'=>$notification->id]) }}" class="list-group-item list-group-item-action">
								{{ $product->name }}
								<small class="d-block">
									{{ $notification->data['message'] }}
									@isset ($product->user->first_name) by <b class="text-brand">{{ $product->user->first_name }}</b> @endisset
								</small>
							</a>
							@endif

							@if ($notification->type == "Order" && ($order = $notification->order()))
							<a href="{{ route('orders.edit', [$order->reference_number, 'notification_id'=>$notification->id]) }}" class="list-group-item list-group-item-action">
								{{-- {{ $order->product->name }} --}}
								{{ $order->reference_number }}
								<small class="d-block">
									{{ $notification->data['message'] }}
									@isset ($order->user->first_name) by <b class="text-brand">{{ $order->user->first_name }}</b> @endisset
								</small>
							</a>
							@endif

							@if ($loop->last && auth()->user()->unreadNotifications->count() > 5 )
							<a href="{{ route('order.notifications') }}" class="list-group-item list-group-item-action">
								<small class="text-primary">View All Notifications ({{ auth()->user()->unreadNotifications->count() }})</small>
							</a>
							@endif
						@empty
						<a href="javascript:;" class="list-group-item list-group-item-action">
							<small>There is no any notification.</small>
						</a>
						@endforelse
					</nav>
				</div>
			</li>
			<li class="nav-item dropdown mx-1">
				<a href="javascript:;" class="nav-link dropdown-toggle collapsed" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
					<i class="fa fa-user-circle fa-lg text-brand"></i>
				</a>
				<div class="dropdown-menu dropdown-menu-right size-md shadow border-0">
					<a class="dropdown-item" href="{{ route('users.edit', auth()->id()) }}"><i class="text-brand mr-2 fa fa-user"></i> Profile</a>
					<a class="dropdown-item" href="{{ route('admin') }}"><i class="text-brand mr-2 fa fa-cog"></i> Settings</a>
					<div class="dropdown-divider"></div>
					<a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('_logout').submit();"><i class="text-brand mr-2 fa fa-unlock-alt"></i> {{ __('Logout') }}</a>
					<form method="POST" action="{{ route('logout') }}" id="_logout" style="display:none"> @csrf </form>
				</div>
			</li>
		</ul>
	</nav>
</header>