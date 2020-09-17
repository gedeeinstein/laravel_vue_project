@extends('backend._base.content_form')

@section('breadcrumbs')
    <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item"><a href="{{route('dashboard')}}"><i class="fas fa-tachometer-alt"></i> @lang('label.dashboard')</a></li>
        <li class="breadcrumb-item active">{{ $page_title }}</li>
    </ol>
@endsection

@section('top_title')
    <h3 class="card-title row mx-n1">
        @php
            $title = 'label.edit';
            if( 'create' == $page_type ) $title = 'label.add';
            $info = "{$prefecture->prefecture_code} {$prefecture->name}";
        @endphp
        <span class="px-1 col-auto">@lang( $title )</span>
        @if( isset( $prefecture ))
            <span class="px-1 col-auto">- {{ $info }}</span>
        @endif
    </h3>
@endsection

@section('top_buttons')
    @php
        $query = array( 'prefecture' => $param->prefecture );
        $route_index = route('master.area.index', $query );
        $route_create = route('master.area.create', $query );
    @endphp
    @if( $page_type == "create" )
        <a href="{{ $route_index }}" class="btn btn-info m-0">@lang(( 'label.list' ))</a>
    @else
        @if( Auth::user()->user_role->name == 'global_admin' )
            <a href="{{ $route_create }}" class="btn btn-info float-sm-right">@lang('label.createNew')</a>
        @endif
        <a href="{{ $route_index }}" class="btn btn-info m-0">@lang(( 'label.list' ))</a>
    @endif
@endsection

@section('content')
    @component('backend._components.form_container', [ 'action' => $form_action, 'page_type' => $page_type, 'files' => false ])
        @php $city = 'government_designated_city' @endphp

        @component('backend._components.input_text', [ 'name' => 'group_code', 'label' => __('label.group_code'), 'required' => 1, 'value' => $item->group_code ]) @endcomponent
        @component('backend._components.input_text', [ 'name' => 'name', 'label' => __('label.name_alt'), 'required' => 1, 'value' => $item->name ]) @endcomponent
        @component('backend._components.input_text', [ 'name' => 'kana', 'label' => __('label.name_kana'), 'required' => 1, 'value' => $item->kana ]) @endcomponent
        @component('backend._components.input_number', [ 'name' => 'order', 'label' => __('label.order'), 'required' => 1, 'value' => $item->order ]) @endcomponent
        @component('backend._components.input_switch', [ 'name' => 'is_enable', 'label' => __('label.enabled'), 'required' => 1, 'value' => $item->is_enable, 'checked' => true ]) @endcomponent
        @component('backend._components.input_switch', [ 'name' => $city, 'label' => __('label.designated_city'), 'required' => 1, 'value' => $item->{$city}, 'checked' => false ]) @endcomponent

        @component('backend._components.input_buttons', [ 'page_type' => $page_type ]) @endcomponent

    @endcomponent
@endsection

@push('css')
    <link rel="stylesheet" href="{{ asset('components/mohithg-switchery/dist/switchery.css') }}">
@endpush

@push('scripts')
    <script src="{{ asset('components/mohithg-switchery/dist/switchery.js') }}"></script>
    <script>
        (function( $, window, document, undefined ){
            $(document).ready( function(){
                // ----------------------------------------------------------
                var $switches = $('.form-switch');
                $switches.each( function(){
                    new Switchery( this, { size: 'small' });
                })
                // ----------------------------------------------------------
            });
        }( jQuery, window, document ));
    </script>
@endpush
