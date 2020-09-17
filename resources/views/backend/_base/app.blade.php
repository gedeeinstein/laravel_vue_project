<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $page_title ? "{$page_title} | " : '' }}{{ config('app.name') }}</title>
    <link href="{{asset('favicon.ico')}}" type="image/vnd.microsoft.icon" rel="shortcut icon">
    <link href="{{asset('favicon.ico')}}" type="image/vnd.microsoft.icon" rel="icon">

    <!-- FontAwesome icons - Start -->
    <link rel="stylesheet" href="{{asset('plugins/fontawesome-pro/css/all.min.css')}}">
    <!-- FontAwesome icons - End -->

    <!-- Port icons - Start -->
    <link rel="stylesheet" href="{{asset('icons/webfont/css/porticons.css')}}">
    <!-- Port icons - End -->

    {{-- <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css"> --}}
    <!-- DataTables -->
    <link rel="stylesheet" href="{{asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
    <!-- DataTables Button -->
    <link rel="stylesheet" href="{{asset('plugins/datatables-buttons/css/buttons.bootstrap4.min.css')}}">
    {{--multi select--}}
    <link href="https://unpkg.com/multiple-select@1.5.2/dist/multiple-select.min.css" rel="stylesheet">
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="{{asset('plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css')}}">
    <!-- Toastr -->
    <link rel="stylesheet" href="{{asset('plugins/toastr/toastr.min.css')}}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{asset('css/backend/adminlte/adminlte.css')}}">
    <!-- Google Font: Source Sans Pro -->
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset( 'components/jquery-confirm/dist/jquery-confirm.min.css' )}}">
    <link rel="stylesheet" href="{{ asset( 'css/backend/preloader.css' )}}">
    <link rel="stylesheet" href="{{ asset( 'css/backend/backend-custom.css' )}}">

@stack('css')
<!-- vue init -->
    <link rel="stylesheet" href="{{asset('css/app.css')}}">
    <!-- End Init Bue -->
</head>
<body class="hold-transition layout-top-nav layout-navbar-fixed">
<div class="wrapper" id="app">
    <!-- Preloader - Start -->
    @hasSection('preloader') @yield('preloader') @endif
    <!-- Preloader - End -->

    <!-- Navbar Top -->
    <header>
        @include('backend._base.nav_top')
    </header>
    <!-- /.navbar top -->

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        @yield('content-wrapper')
    </div>
    <!-- /.content-wrapper -->

    @stack('templates')

    <!-- Control Right Sidebar -->
{{-- @yield('control-right-sidebar') --}}
<!-- /.control-Right sidebar -->

    <!-- Main Footer -->
    <footer class="main-footer text-sm">
        <!-- To the right -->
        <div class="float-right d-none d-sm-inline">
            Version 1
        </div>
        <!-- Default to the left -->
        <strong>Copyright &copy; {{ date('Y') }}
            <a href="{{ config('app.url') }}">{{ config('app.name') }}</a>.</strong> All rights reserved.
    </footer>
</div>
<!-- ./wrapper -->

@stack('vue-xtemplates')

<!-- Polyfills - Start -->
<script src="{{ asset( 'js/plugins/request-animation-frame/polyfill.js' )}}"></script>
<!-- Polyfills - Start -->

<!-- REQUIRED SCRIPTS -->
<!-- vue init -->
<script src="{{asset('js/manifest.js')}}"></script>
<script src="{{asset('js/vendor.js')}}"></script>
<script src="{{asset('js/app.js')}}"></script>
<!-- jQuery -->
{{-- <script src="{{asset('plugins/jquery/jquery.min.js')}}"></script> --}}
<!-- Bootstrap 4 -->
<script src="{{asset('plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<!-- DataTables -->
<script src="{{asset('plugins/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js')}}"></script>
{{--multiselect--}}
<script src="https://unpkg.com/multiple-select@1.5.2/dist/multiple-select.min.js"></script>
<!-- SweetAlert2 -->
<script src="{{asset('plugins/sweetalert2/sweetalert2.min.js')}}"></script>
<!-- Toastr -->
<script src="{{asset('plugins/toastr/toastr.min.js')}}"></script>
<!-- AdminLTE App -->
<script src="{{asset('js/backend/adminlte/adminlte.min.js')}}"></script>
<!-- custom backend -->
<script src="{{asset('js/backend/backend.js')}}"></script>

<!-- Components - Start -->
<script src="{{ asset( 'components/animejs/lib/anime.min.js' )}}"></script>
<script src="{{ asset( 'components/jquery.scrollto/jquery.scrollTo.min.js' )}}"></script>
<script src="{{ asset( 'components/jquery.easing/jquery.easing.min.js' )}}"></script>

<script src="{{ asset( 'components/q/q.js' )}}"></script>
<script src="{{ asset( 'components/jquery-confirm/dist/jquery-confirm.min.js' )}}"></script>
<!-- Components - End -->

{{--custom js--}}
@stack('scripts')
<script>
    $(function () {
        @if ($message = Session::get('success'))
        toastr.success('{{ $message }}');
        @endif
        @if ($errors->any())
        toastr.error('@foreach ($errors->all() as $error)' +
            '<p>{{ $error }}</p>' +
            '@endforeach');
        @endif
    });
</script>

<script>
    let mixin = {};
    let store = null;
    let router = null;
</script>

@stack('vue-scripts')
<script src="{{ asset('/js/vue/vue.configs.js') }}"></script>
<script src="{{ asset('/js/vue/vue.filters.js') }}"></script>
<script src="{{ asset('/js/vueapp.js') }}"></script>
@stack('vue-components')

</body>
</html>
