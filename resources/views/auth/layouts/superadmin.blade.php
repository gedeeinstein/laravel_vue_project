<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title'){{ config('const.TITLE_SUFFIX') }}</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{asset('plugins/fontawesome-free/css/all.min.css')}}">
    <!-- Ionicons -->
    {{-- <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css"> --}}
    <!-- icheck bootstrap -->
    <link rel="stylesheet" href="{{asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css')}}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{asset('/css/backend/adminlte/adminlte.css')}}">
    <!-- Google Font: Source Sans Pro -->
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">

    <style>
        .card-admin-login {
            border-top: 3px solid #ff253a;
        }
        .card-user-login {
            border-top: 3px solid #17a2b8;
        }
        a {
            color: #17a2b8;
        }
        .alert ul {
            margin-bottom: 0px !important;
        }
    </style>
</head>
<body class="hold-transition login-page">

<div class="login-box">

    {{--
    <div class="login-logo">
        <a href="{{url('/')}}"><b>管理者ログイン画面</b></a>
    </div>
    --}}
    @yield('content')
</div>
<!-- /.login-box -->

<script src="{{asset('plugins/jquery/jquery.min.js')}}"></script>
<script src="{{asset('plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<script src="{{asset('js/backend/adminlte/adminlte.min.js')}}"></script>
<script src="{{asset('plugins/parsley/parsley.min.js')}}"></script>
<script src="{{asset('plugins/parsley/i18n/ja.js')}}"></script>
</body>
</html>
