<!-- Sidebar -->
<aside class="sidebar shadow small">
	<ul id="sidebar" class="list-unstyled sticky-top" style="top: 60px">
		<li @if(Request::is('admin')) class="active" @endif>
			<a href="{{ url('admin') }}"><i class="fa fa-lg fa-tachometer-alt fa-fw"></i> Dashboard</a>
		</li>
		<li @if(Request::is('admin/users/'.auth()->id().'/edit')) class="active" @endif>
			<a href="{{ route('users.edit', auth()->id()) }}"><i class="far fa-lg fa-user fa-fw"></i> Profile</a>
		</li>
		@role(['administrator', 'admin'])
		<li @if(Request::is('admin/users')) class="active" @endif>
			<a href="#userSubmenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle collapsed"><i class="fa fa-lg fa-users-cog fa-fw"></i> Users</a>
			<ul class="collapse list-unstyled" id="userSubmenu">
				<li @if(Request::is('admin/users') && !request('status')) class="active" @endif>
					<a href="{{ url('admin/users') }}">Manage Users</a>
				</li>
				<li @if(Request::is('admin/users') && request('status') == 'active') class="active" @endif>
					<a href="{{ url('admin/users?status=active') }}">Active Users</a>
				</li>
				<li @if(Request::is('admin/users') && request('status') == 'pending') class="active" @endif>
					<a href="{{ url('admin/users?status=pending') }}">Pending Users</a>
				</li>
				<li @if(Request::is('admin/users') && request('status') == 'deactive') class="active" @endif>
					<a href="{{ url('admin/users?status=deactive') }}">Deactive Users</a>
				</li>
			</ul>
		</li>
		@endrole
		@role('administrator')
		<li @if(Request::is('admin/categories*') || Request::is('admin/sub-categories*')) class="active" @endif>
			<a href="#categorySubmenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle collapsed"><i class="fa fa-lg fa-cubes fa-fw"></i> Categories</a>
			<ul class="collapse list-unstyled" id="categorySubmenu">
				<li @if(Request::is('admin/categories')) class="active" @endif>
					<a href="{{ url('admin/categories') }}">Manage Categories</a>
				</li>
				<li @if(Request::is('admin/sub-categories')) class="active" @endif>
					<a href="{{ url('admin/sub-categories') }}">Sub Categories</a>
				</li>
			</ul>
		</li>
		@endrole
		@if (auth()->user()->hasAnyRole())
		<li @if(Request::is('admin/brands*')) class="active" @endif>
			<a href="{{ url('admin/brands') }}"><i class="far fa-lg fa-copyright fa-fw"></i> Brands</a>
		</li>
		<li @if(Request::is('admin/products*')) class="active" @endif>
			<a href="{{ url('admin/products') }}"><i class="fa fa-lg fa-chart-pie fa-fw"></i> Products</a>
		</li>
		<li @if(Request::is('admin/stocks*')) class="active" @endif>
			<a href="#stockSubmenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle collapsed"><i class="fa fa-lg fa-layer-group fa-fw"></i> Profit & Stock</a>
			<ul class="collapse list-unstyled" id="stockSubmenu">
				<li @if(Request::is('admin/stocks/profit')) class="active" @endif>
					<a href="{{ url('admin/stocks/profit') }}">Profit</a>
				</li>
				<li @if(Request::is('admin/stocks')) class="active" @endif>
					<a href="{{ url('admin/stocks') }}">Stocks</a>
				</li>
			</ul>
		</li>
    	<li @if(Request::is('admin/orders*')) class="active" @endif>
			<a href="#ordersSubmenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle collapsed"><i class="fa fa-lg fa-shopping-basket fa-fw"></i> Orders</a>
			<ul class="collapse list-unstyled" id="ordersSubmenu">
				<li @if(Request::is('admin/orders') && !request('status')) class="active" @endif>
					<a href="{{ url('admin/orders') }}">Manage Orders</a>
				</li>
				<li @if(Request::is('admin/orders') && request('status') == 'shipped') class="active" @endif>
					<a href="{{ url('admin/orders?status=shipped') }}">Shipped Orders</a>
				</li>
				<li @if(Request::is('admin/orders') && request('status') == 'pending') class="active" @endif>
					<a href="{{ url('admin/orders?status=pending') }}">Pending Orders</a>
				</li>
				<li @if(Request::is('admin/orders') && request('status') == 'delivered') class="active" @endif>
					<a href="{{ url('admin/orders?status=delivered') }}">Delivered Orders</a>
				</li>
			</ul>
		</li>
		<li @if(Request::is('admin/product-reviews*')) class="active" @endif>
			<a href="{{ url('admin/product-reviews') }}"><i class="far fa-lg fa-star fa-fw"></i> Product Reviews</a>
		</li>
		@endif
		{{-- @role(['administrator', 'admin'])
		<li @if(Request::is('admin/coupons*')) class="active" @endif>
			<a href="{{ url('admin/coupons') }}"><i class="far fa-lg fa-credit-card fa-fw"></i> Coupons</a>
		</li>
		@endrole --}}
		@role('administrator')
		<li @if(Request::is('admin/shipping-costs*')) class="active" @endif>
			<a href="{{ url('admin/shipping-costs') }}"><i class="fa fa-lg fa-hand-holding-usd fa-fw"></i> Shipping Costs</a>
		</li>
		<li @if(Request::is('admin/site-options*')) class="active" @endif>
			<a href="{{ url('admin/site-options') }}"><i class="fab fa-lg fa-weebly fa-fw"></i> Site Options</a>
		</li>
		<li @if(Request::is('admin/meta-tags*')) class="active" @endif>
			<a href="{{ url('admin/meta-tags') }}"><i class="fa fa-lg fa-tags fa-fw"></i> Meta Tags</a>
		</li>
		{{-- <li @if(Request::is('admin/slides*')) class="active" @endif>
			<a href="{{ url('admin/slides') }}"><i class="far fa-lg fa-images fa-fw"></i> Slider Photos</a>
		</li> --}}
		<li @if(Request::is('admin/sliders*') || Request::is('admin/slides*')) class="active" @endif>
			<a href="#sliderSubmenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle collapsed"><i class="far fa-lg fa-images fa-fw"></i> Sliders</a>
			<ul class="collapse list-unstyled" id="sliderSubmenu">
				<li @if(Request::is('admin/sliders*')) class="active" @endif>
					<a href="{{ url('admin/sliders') }}">Manage Sliders</a>
				</li>
				<li @if(Request::is('admin/slides*')) class="active" @endif>
					<a href="{{ url('admin/slides') }}">Slider Slides</a>
				</li>
			</ul>
		</li>
		<li @if(Request::is('admin/videos*')) class="active" @endif>
			<a href="{{ url('admin/videos') }}"><i class="fa fa-lg fa-photo-video fa-fw"></i> Upload Videos</a>
		</li>
		<li @if(Request::is('admin/logos*')) class="active" @endif>
			<a href="{{ url('admin/logos') }}"><i class="fa fa-lg fa-theater-masks fa-fw"></i> Companies Logo</a>
		</li>
		@endrole
		@role(['administrator', 'admin'])
		<li @if(Request::is('admin/contacts*')) class="active" @endif>
			<a href="{{ url('admin/contacts') }}"><i class="fa fa-lg fa-address-book fa-fw"></i> Contact Us </a>
		</li>
		@endrole
	</ul>
</aside>