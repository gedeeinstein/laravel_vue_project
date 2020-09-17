@extends('backend._base.content_datatables')

@section('breadcrumbs')
    <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item"><a href="{{route('dashboard')}}"><i class="fas fa-tachometer-alt"></i> @lang('label.dashboard')</a></li>
        <li class="breadcrumb-item active">{{ $page_title }}</li>
    </ol>
@endsection

@section('top_buttons')
@endsection

@section('content')
    <th data-col="id">ID</th>
    <th data-col="user.full_kana_name">@lang('label.$log.user')</th>
    <th data-col="email">@lang('label.$log.email')</th>
    <th data-col="activity">@lang('label.$log.activity')</th>
    <th data-col="ip">@lang('label.$log.ip')</th>
    <th data-col="access_time">@lang('label.$log.time')</th>
@endsection

@if(0) <script> @endif
    @section('extend-datatable')
        // ------------------------------------------------------------------
        // Extend datatable options
        // ------------------------------------------------------------------

        // disable sever side filtering
        options.serverSide = false;

        // ------------------------------------------------------------------
        // Customize activity column
        // ------------------------------------------------------------------
        options.columnDefs.push({
            render: function ( data, type, row ){
                // ----------------------------------------------------------
                var $container = $('<div/>').html( data );
                var $icon = $('<i/>').addClass('far fa-bookmark mr-2').prependTo( $container );
                $icon.attr( 'data-toggle', 'tooltip' ).attr( 'title', row.detail );
                return $container.get(0).outerHTML;
                // ----------------------------------------------------------
            },
            targets: 3
        });
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // On render
        // ------------------------------------------------------------------
        options.initComplete = function( settings, json ){
            // --------------------------------------------------------------
            var column = this.api().column(3);
            var $container = $( this.api().table().container());
            // --------------------------------------------------------------
            var $tooltips = $('[data-toggle="tooltip"');
            $tooltips.tooltip({ placement: 'top', trigger: 'hover focus click' });
            // --------------------------------------------------------------
        }
        // ------------------------------------------------------------------

    @endsection
@if(0) </script> @endif