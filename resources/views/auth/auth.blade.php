{{--@extends('auth.layouts.admin_app')--}}
@extends('auth.layouts.superadmin')

<!-- title -->
@section('title', 'Administrator Login Page | ' . env('APP_NAME',''))

@section('content')
    <div class="login-logo">
        <a href="javascript:;">@lang("label.userloginscreen")</a>
    </div>
    <div class="text-sm">
    @include("backend._includes.alert")
    </div>
    <div class="card">
        <div class="card-body login-card-body">
            <h4 class="login-box-msg mb-3">@lang('label.login_form')</h4>

            <form action="{{ route('login') }}" method="post">
                @csrf
                @error('email')
                <span class="help-block text-sm" role="alert"> <strong>{{ $message }}</strong> </span>
                @enderror
                <div class="input-group mb-3 {{ $errors->has('email') ? 'has-error' : '' }}">
                    <input id="email" type="email" class="form-control @error('email')is-invalid @enderror"
                        name="email" value="{{ old('email') }}" required autocomplete="email"
                        placeholder="@lang('label.enterEmailAddress')" autofocus>
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-envelope"></span>
                        </div>
                    </div>
                </div>
                @error('password')
                <span class="invalid-feedback text-sm" role="alert"><strong>{{ $message }}</strong></span>
                @enderror
                <div class="input-group mb-3{{ $errors->has('password') ? ' has-error' : '' }}">
                    <input id="password" type="password"
                        class="form-control @error('password')is-invalid @enderror" name="password"
                        required autocomplete="current-password" placeholder="@lang('label.enterPassword')">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-lock"></span>
                        </div>
                    </div>
                </div>
                <div class="row mt-5">
                    <div class="col-8">
                        <div class="icheck-primary">
                            <input id="remember" type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }} >
                            <label for="remember" style="font-weight: normal; font-size: 0.8rem">
                                @lang('label.remember')
                            </label>
                        </div>
                    </div>
                    <!-- /.col -->
                    <div class="col-4">
                        <button type="submit" class="btn btn-info btn-block">@lang('label.login')</button>
                    </div>
                    <!-- /.col -->
                </div>
            </form>

        {{--<div class="social-auth-links text-center mb-3">--}}
        {{--    <p>- OR -</p>--}}
        {{--    <a href="#" class="btn btn-block btn-primary">--}}
        {{--        <i class="fab fa-facebook mr-2"></i> Sign in using Facebook--}}
        {{--    </a>--}}
        {{--    <a href="#" class="btn btn-block btn-danger">--}}
        {{--        <i class="fab fa-google-plus mr-2"></i> Sign in using Google+--}}
        {{--    </a>--}}
        {{--</div>--}}
        <!-- /.social-auth-links -->

            @if (Route::has('password.request'))
                <p class="mb-1 mt-3" style="text-align: right">
                    <a href="{{ route('password.request') }}"  style="font-weight: normal; font-size: 0.8rem">@lang('label.IForgotMyPassword')</a>
                </p>
            @endif
            {{--<p class="mb-0">--}}
            {{--    <a href="#" class="text-center">Register a new membership</a>--}}
            {{--</p>--}}
        </div>
        <!-- /.login-card-body -->
    </div>
@endsection
