@extends('backend._base.content_datatables')

@section('breadcrumbs')
    <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item"><a href="{{route('dashboard')}}"><i class="fas fa-tachometer-alt"></i> @lang('label.dashboard')</a></li>
        <li class="breadcrumb-item active">{{ $page_title }}</li>
    </ol>
@endsection

@section('content')
    <th data-col="id">ID</th>
    <th data-col="type">@lang('label.type')</th>
    <th data-col="key">@lang('label.key')</th>
    <th data-col="value">@lang('label.value')</th>
    <th data-col="sort">@lang('label.sort')</th>
    <th data-col="updated_at">@lang('label.last_update')</th>
@endsection
