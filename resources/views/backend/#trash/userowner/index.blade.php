@extends('backend._base.content_datatables')

@section('breadcrumbs')
    <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item"><a href="{{route('dashboard')}}"><i class="fas fa-tachometer-alt"></i> @lang('label.dashboard')</a></li>
        @if(Auth::user()->admin_role_id != 3)
            <li class="breadcrumb-item"><a href="{{route('admin.company.index')}}">@lang('label.company')</a></li>
        @endif
        <li class="breadcrumb-item active">{{ $page_title }}</li>
    </ol>
@endsection

@section('top_buttons')
    @if(Auth::user()->admin_role_id != 3)
        <a href="{{route('admin.company.user.create', $parent_id)}}" class="btn btn-info">@lang(('label.createNew'))</a>
    @endif
@endsection

@section('content')
    <th data-col="id">ID</th>
    <th data-col="display_name">@lang('label.name')</th>
    <th data-col="user_roles.label">@lang('label.user_role')</th>
    <th data-col="email">@lang('label.email')</th>
    <th data-col="updated_at">@lang('label.last_update')</th>
    <th data-col="created_at">@lang('label.created_at')</th>
    <th data-col="action">@lang('label.action')</th>
@endsection
