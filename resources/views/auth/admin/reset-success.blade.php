@extends('auth.layouts.superadmin')

<!-- page title -->
@section('title', 'Password updated | ' . env('APP_NAME',''))
@section('description', 'Password updated | ' . env('APP_NAME',''))

@section('content')
	<div class="wrap-default">
		<div class="wrap-content_sm">
			<div class="text-center padd-content">
				<p class="padd40_top gold">Your password has been successfully updated!</p>
			</div>
		</div>
		<!-- button back -->
		<div class="text-center padd-content_bottom">
			<div class="btn-brown arrow-left">
				<a href="{{ url('admin/login') }}">Return to Login Page</a>
			</div>
		</div>
	</div>
@endsection
