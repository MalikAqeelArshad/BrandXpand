<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" {{-- oncontextmenu="return false" --}}>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Home') | {{ config('app.name') }}</title>

    <!-- Common Font, CSS -->
    <script type="text/javascript" src="{{ asset('scripts/fontawesome.min.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('styles/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatable/buttons-bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('styles/admin.css') }}">

    <!-- Plugin CSS -->
    @stack('pluginCSS')

    <!-- Internal CSS -->
    @stack('styles')

</head>
<body class="background">
    <script type="text/javascript">
        if (typeof(Storage) !== "undefined" && localStorage.getItem("collapse-sidebar") != null) {
            document.body.classList.add('collapse-sidebar');
        }
    </script>
    
    @include('layouts.admin-header')

    <div class="wrapper">
        @include('layouts.admin-sidebar')
        @yield('content')
    </div>

    {{-- @include('layouts.admin-footer') --}}

    <!-- jQuery first, then Popper.js and Bootstrap JS Bundle -->
    <script src="{{ asset('scripts/jquery-3.4.1.min.js') }}"></script>
    <script src="{{ asset('scripts/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('plugins/datatable/jquery-dataTables.min.js') }}"></script>
    <script src="{{ asset('plugins/datatable/datatables-bootstrap4.min.js') }}"></script>


    <!-- Plugin JS -->
    @stack('pluginJS')

    <!-- Custom JS - Aqeel Malik -->
    <script src="{{ asset('scripts/admin.js') }}"></script>

    <!-- Internal JS -->
    @stack('scripts')

</body>
</html>