@extends('backend._base.content_form')

@section('breadcrumbs')
    <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item"><a href="{{route('dashboard')}}"><i class="fas fa-tachometer-alt"></i> @lang('label.dashboard')</a></li>
        <li class="breadcrumb-item active">{{ $page_title }}</li>
    </ol>
@endsection

@section('top_buttons')
    @if( $page_type == "create" )
        <a href="{{route('master.values.index')}}" class="btn btn-info float-sm-right">@lang('label.list')</a>
    @else
        @if( Auth::user()->user_role->name == 'global_admin' )
            <a href="{{route('master.values.create')}}" class="btn btn-info float-sm-right">@lang('label.createNew')</a>
        @endif
        <a href="{{route('master.values.index')}}" class="btn btn-info float-sm-right">@lang('label.list')</a>
    @endif
@endsection

@section('content')
    @component('backend._components.form_container', [ 'action' => $form_action, 'page_type' => $page_type, 'files' => false ])

        @component('backend._components.input_text', [ 'name' => 'type', 'label' => __('label.type'), 'required' => 1, 'value' => $item->type ]) @endcomponent
        @component('backend._components.input_text', [ 'name' => 'key', 'label' => __('label.key'), 'required' => 1, 'value' => $item->key ]) @endcomponent
        @component('backend._components.input_text', [ 'name' => 'value', 'label' => __('label.value'), 'required' => 1, 'value' => $item->value ]) @endcomponent
        @component('backend._components.input_number', [ 'name' => 'sort', 'label' => __('label.sort'), 'required' => 1, 'value' => $item->sort ]) @endcomponent
        @component('backend._components.input_buttons', [ 'page_type' => $page_type ]) @endcomponent

    @endcomponent
@endsection
