<header class="site-header">
	<section class="bg-brand py-3">
		<div class="container-fluid d-flex justify-content-between align-items-center">
			<div class="d-flex small">
				<ul class="list-inline mb-0">
					<li class="list-inline-item">
						<a href="javascript:;" class="text-white"><i class="fa fa-phone-alt mr-2"></i> {{ __siteMeta('contact') }}</a>
					</li>
					<li class="list-inline-item">
						<a href="javascript:;" class="text-white"><i class="fa fa-envelope mr-2"></i> {{ __siteMeta('email') }}</a>
					</li>
				</ul>
				<nav class="d-none d-md-block ml-3">
					<a href="{{ __siteMeta('facebook') }}" target="_blank"><i class="fab fa-facebook-f text-white mx-2"></i></a>
					<a href="{{ __siteMeta('twitter') }}" target="_blank"><i class="fab fa-twitter text-white mx-2"></i></a>
					<a href="{{ __siteMeta('instagram') }}" target="_blank"><i class="fab fa-instagram text-white mx-2"></i></a>
					<a href="{{ __siteMeta('linkedin') }}" target="_blank"><i class="fab fa-linkedin-in text-white mx-2"></i></a>
					<a href="{{ __siteMeta('youtube') }}" target="_blank"><i class="fab fa-youtube text-white mx-2"></i></a>
				</nav>
			</div>
			<nav class="dropdown size-sm font-weight-medium">
				<a href="{{ route('track.order') }}" class="text-white mr-2">TRACK ORDER</a>
				<a href="{{ route('front.home') }}" class="text-white mr-2">HOME</a>
				@if(auth()->check())
				<a class="text-white dropdown-toggle" href="#" id="userMenu" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
					ACCOUNT
				</a>
				<div class="dropdown-menu dropdown-menu-right size-md shadow border-0 mt-1" aria-labelledby="userMenu" style="z-index:9999999999">
					<a class="dropdown-item" href="{{ route('profile') }}"><i class="text-brand mr-2 fa fa-fw fa-user-alt"></i> Profile</a>
					<a class="dropdown-item" href="{{route('track.orders')}}"><i class="text-brand mr-2 fa fa-fw fa-shopping-basket"></i> Orders</a>
					<div class="dropdown-divider"></div>
					@if(auth()->user()->hasAnyRole())
					<a class="dropdown-item" href="{{ route('admin') }}"><i class="text-brand mr-2 fa fa-fw fa-cog"></i> Settings</a>
					@endif
					<a href="{{ route('logout') }}" class="dropdown-item" onclick="event.preventDefault(); document.getElementById('_logout').submit();"><i class="text-brand mr-2 fa fa-fw fa-sign-out-alt"></i> Logout</a>
					<form method="POST" action="{{ route('logout') }}" id="_logout" style="display:none"> @csrf </form>
				</div>
				@else
				<a href="{{ route('login') }}" class="text-white mr-2">LOGIN</a>
				<a href="{{ route('register') }}" class="text-white">REGISTER</a>
				@endif
			</nav>
		</div>
	</section>
	
	<section class="container container-max d-md-flex align-items-top py-4">
		<div class="d-flex justify-content-between align-items-center">
			<a href="{{ url('/') }}" class="mr-5"><img src="{{ asset('images/logo.png') }}" alt="Logo" class="img-fluid"></a>
			<nav class="d-sm-flex d-md-none align-items-center flex-shrink-0">

				<a href="{{ route('cart.show') }}" class="position-relative" >
					<i class="fa fa-cart-plus fa-lg text-dark h2 mb-0 mt-2"></i>
					<code class="badge badge-warning rounded-pill position-absolute cartCount" style="right:0; top:-23px">{{ Cart::content()->count() }}</code>
				</a>

				<a class="navbar-toggler" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation" href="javascript:;">
					<i class="fa fa-bars fa-lg text-brand"></i>
				</a>
			</nav>
		</div>
		<div class="d-flex align-items-end w-100">
			<form method="GET" action="{{ route('search', 'products') }}" class="mx-md-4 w-100">
				<div class="input-group mt-4">
					<input type="text" name="s" class="form-control rounded-pill rounded-right-0" placeholder="Search" />
					<select class="custom-select col-4" name="category">
						<option value="">All categories</option>
						@foreach(__all('Category') as $category)
							<option value="{{ $category->id }}" {{ selected($category->id, request('category')) }}> {{ $category->name }} </option>
						@endforeach
					</select>
					<button type="submit" class="btn border focus-0 rounded-pill rounded-left-0 border-left-0 px-4 border"><i class="fa fa-search"></i></button>
				</div>
			</form>
			<div class="d-none d-md-inline-block">
			<a href="{{ route('cart.show') }}" class="nav-link">
					<i class="fa fa-cart-plus fa-lg text-dark h2 mb-0 mt-2"></i>
					<code class="badge badge-warning rounded-pill float-right mt-n5 ml-1 position-relative cartCount">{{__cartCount()}}</code>
				</a>
			</div>
		</div>
	</section>
</header>
