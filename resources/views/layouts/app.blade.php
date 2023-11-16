<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Google Site Verification -->
    <meta name="google-site-verification" content="Pz2OK9fwHLUuEeyrBG2svgkYnILN813RLCrlX-ephaI" />

    <!-- SEO Meta Tags -->
    <meta name="description" content="{{ __metaTag('description') }}">
    <meta name="keywords" content="{{ __metaTag('keywords') }}">
    <meta name="author" content="{{ __metaTag('author') }}">
    {{-- <title>@yield('title', 'Home') | {{ config('app.name') }}</title> --}}
    <title>@yield('title', __metaTag('title') ?: 'Welcome') | {{ config('app.name') }}</title>

    <!-- Common Font, CSS -->
    <script type="text/javascript" src="{{ asset('scripts/fontawesome.min.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('styles/bootstrap.min.css') }}">

    <link rel="stylesheet" type="text/css" href="{{ asset('plugins/bootstrap-toastr/toastr.min.css') }}">

    <!-- Plugin CSS -->
    @stack('pluginCSS')

    <!-- Custom CSS - Public -->
    <link rel="stylesheet" href="{{ asset('styles/style.css') }}">

    <!-- Internal CSS -->
    @stack('styles')
    
</head>
<body>
    @include('layouts.header')

    <div class="wrapper">
        @yield('content')
    </div>

    @include('layouts.footer')

    <!-- jQuery first, then Popper.js and Bootstrap JS Bundle -->
    <script src="{{ asset('scripts/jquery-3.4.1.min.js') }}"></script>
    <script src="{{ asset('scripts/bootstrap.bundle.min.js') }}"></script>

    <script src="{{ asset('plugins/bootstrap-toastr/toastr.min.js') }}"></script>
    
    <!-- Plugin JS -->
    @stack('pluginJS')

    <!-- Custom JS - Public -->
    <script type="text/javascript" src="{{ asset('scripts/script.js') }}"></script>
    {{-- <script>
        function initFreshChat() {
            window.fcWidget.init({
                token: "59f8355a-8f69-491f-b72e-7c2a1ebb6a5d",
                host: "https://wchat.freshchat.com"
            });
        }
        function initialize(i,t){var e;i.getElementById(t)?initFreshChat():((e=i.createElement("script")).id=t,e.async=!0,e.src="https://wchat.freshchat.com/js/widget.js",e.onload=initFreshChat,i.head.appendChild(e))}function initiateCall(){initialize(document,"freshchat-js-sdk")}window.addEventListener?window.addEventListener("load",initiateCall,!1):window.attachEvent("load",initiateCall,!1);
    </script> --}}
    <script>
        @if(Session::has('success'))
        toastr.success('{{Session::get('success')}}');
        {{Session::forget('success')}}
        @endif

        @if(Session::has('info'))
        toastr.info('{{Session::get('info')}}');
        {{Session::forget('info')}}
        @endif

        @if(Session::has('warning'))
        toastr.warning('{{Session::get('warning')}}');
        @endif

        @if(Session::has('error'))
        toastr.error('{{Session::get('error')}}');
        {{Session::forget('error')}}
        @endif
    </script>

    <!-- Internal JS -->
    @stack('scripts')
    @yield('scripts')

</body>
</html>