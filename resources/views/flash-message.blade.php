@if ($errors->any())
<div class="alert alert-danger small">
	<button type="button" class="close" data-dismiss="alert" aria-label="Close">
		<span aria-hidden="true">&times;</span>
	</button>
	<ul class="pl-3">
		@foreach($errors->all() as $error)
		<li>{{ $error }}</li>
		@endforeach
	</ul>
</div>
@endif

@if ($message = session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
	<button type="button" class="close" data-dismiss="alert" aria-label="Close">
		<span aria-hidden="true">&times;</span>
	</button>
	<small>{{ $message }}</small>
</div>
@endif

@if ($message = session('danger') ?: session('error'))
<div class="alert alert-danger alert-dismissible fade show" role="alert">
	<button type="button" class="close" data-dismiss="alert" aria-label="Close">
		<span aria-hidden="true">&times;</span>
	</button>
	<small>{{ $message }}</small>
</div>
@endif

@if ($message = session('warning'))
<div class="alert alert-warning alert-dismissible fade show" role="alert">
	<button type="button" class="close" data-dismiss="alert" aria-label="Close">
		<span aria-hidden="true">&times;</span>
	</button>
	<small>{{ $message }}</small>
</div>
@endif

@if ($message = session('info'))
<div class="alert alert-info alert-dismissible fade show" role="alert">
	<button type="button" class="close" data-dismiss="alert" aria-label="Close">
		<span aria-hidden="true">&times;</span>
	</button>
	<small>{{ $message }}</small>
</div>
@endif