@extends('backend._base.content_datatables')

@section('breadcrumbs')
    <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item">
            <a href="{{ route('dashboard') }}">
                <i class="fas fa-tachometer-alt"></i>
                <span>@lang('label.dashboard')</span>
            </a>
        </li>
        <li class="breadcrumb-item active">{{ $page_title }}</li>
    </ol>
@endsection

@if( !empty( $role ) && 'global_admin' == $role->name )
    @section('top_buttons')
        <a href="{{ route('company.create') }}" class="btn btn-info">
            <div class="row">
                <span class="px-1 col-auto"><i class="fas fa-plus-circle"></i></span>
                <span class="px-1 col-auto">@lang('label.createNew')</span>
            </div>
        </a>
    @endsection
@endif

@section('intro')
    <p class="mt-2 mb-4">port各画面から参照する会社や個人（不動産業者、支払先、宅建士など）の情報を管理することができます。また、「グループ会社」「顧問会計事務所」の社員にはportへのログイン権限を設定することもできます。</p>
@endsection

@php
    $filters = array();
    $types = config('const.COMPANY_TYPES');
    if( !empty( $types )) foreach( $types as $name => $type ){
        $filters[ $type ] = __( 'label.$company.type.'.$name );
    }
@endphp

@section('content')
    <th data-hidden data-col="id">ID</th>
    <th data-col="name">@lang('label.company_name')</th>
    <th data-col="name_kana">@lang('label.company_name_kana')</th>
    <th data-col="groups" data-search="false">@lang('label.$company.type.label')</th>

    <!-- Hidden columns -->
    @foreach( $filters as $filter => $label )
        <th class="d-none" id="{{ $filter }}" data-hidden data-col="{{ $filter }}">{{ $label }}</th>
    @endforeach
    <!-- Hidden columns -->
@endsection


@push('templates')
    <!-- Templates - Start -->
    <div class="template d-none">

        <!-- Company name - Start -->
        <div class="row mx-n1 row-company">
            <div class="px-1 col-auto d-flex-align-items-center">
                <i class="company-icon far fs-14"></i>
            </div>
            <div class="px-1 col-auto company-name"></div>
        </div>
        <!-- Company name - End -->

        <!-- Row controls - Start -->
        <div class="row row-controls mx-n2 mt-1 fs-13">
            <a class="px-2 col-auto mt-1 company-edit" href="#">
                <div class="row mx-n1">
                    <div class="px-1 col-auto"><i class="far fa-edit"></i></div>
                    <div class="px-1 col-auto">@lang('label.$company.edit')</div>
                </div>
            </a>
            <a class="px-2 col-auto mt-1 user-list" href="#">
                <div class="row mx-n1">
                    <div class="px-1 col-auto"><i class="fas fa-users"></i></div>
                    <div class="px-1 col-auto">@lang('label.$user.list')</div>
                    <div class="px-1 col-auto">(<span class="users-count"></span>)</div>
                </div>
            </a>
            @if( $admin )
                <a class="px-2 col-auto mt-1 user-create" href="#">
                    <div class="row mx-n1">
                        <div class="px-1 col-auto"><i class="fas fa-user-plus"></i></div>
                        <div class="px-1 col-auto">@lang('label.$user.add')</div>
                    </div>
                </a>
            @endif
        </div>
        <!-- Row controls - End -->

    </div>
    <!-- Templates - Start -->
@endpush


@if(0) <script> @endif
    @section('extend-datatable')
        // ------------------------------------------------------------------
        // Extend datatable options
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        var page = '{{ url()->current() }}';
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Hide ID column
        // ------------------------------------------------------------------
        options.columnDefs.push({ visible: false, targets: 0 });
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Customize company name column
        // ------------------------------------------------------------------
        options.columnDefs.push({
            render: function ( data, type, row ){
                // ----------------------------------------------------------
                var $container = $('<div/>');
                var $template = $('.template');
                // ----------------------------------------------------------

                // ----------------------------------------------------------
                // Company name and icon
                // ----------------------------------------------------------
                var $company = $template.find('.row-company').clone().appendTo( $container );
                var $icon = $company.find('.company-icon');
                $company.find('.company-name').html( data );
                // ----------------------------------------------------------
                if( row.type && 'individual' == row.type ) $icon.addClass('fa-user');
                else $icon.addClass('fa-building');
                // ----------------------------------------------------------

                // ----------------------------------------------------------
                // Control buttons
                // ----------------------------------------------------------
                var $controls = $template.find('.row-controls').clone();
                $controls.appendTo( $container );
                // ----------------------------------------------------------

                // ----------------------------------------------------------
                // Edit button
                // ----------------------------------------------------------
                var $edit = $controls.find('a.company-edit');
                if( row.type && 'individual' !== row.type ){
                    $edit.attr( 'href', page + '/' + row.id + '/edit' );
                } else $edit.remove();
                // ----------------------------------------------------------

                // ----------------------------------------------------------
                // User list button
                // ----------------------------------------------------------
                var $list = $controls.find('a.user-list');
                var $count = $list.find('.users-count').html( row.users_count )
                $list.attr( 'href', page + '/' + row.id + '/user' );
                // ----------------------------------------------------------

                // ----------------------------------------------------------
                // Create user button
                // ----------------------------------------------------------
                @if( $admin )
                    var $create = $controls.find('a.user-create');
                    $create.attr( 'href', page + '/' + row.id + '/user/create' );
                @endif
                // ----------------------------------------------------------

                // ----------------------------------------------------------
                return $container.get(0).outerHTML;
                // ----------------------------------------------------------
            },
            targets: 1
        });
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Customize company type groups
        // ------------------------------------------------------------------
        options.columnDefs.push({
            render: function ( data, type, row ){
                // ----------------------------------------------------------
                var $container = $('<div/>').addClass('row justify-content-end mx-n1');
                // ----------------------------------------------------------

                // ----------------------------------------------------------
                // Display the groups string into badges
                // ----------------------------------------------------------
                if( row.groups && row.groups.length ){
                    // ------------------------------------------------------
                    var types = [];
                    @foreach( $filters as $filter => $label )
                        types.push({ id: '{{ $filter }}', label: '{{ $label }}' });
                    @endforeach
                    // ------------------------------------------------------

                    // ------------------------------------------------------
                    var filters = row.groups.split(', ');
                    $.each( filters, function( i, label ){
                        // --------------------------------------------------
                        var $column = $('<div/>').addClass('p-1 col-auto').appendTo( $container );
                        var $badge = $('<span/>').addClass('badge badge-info badge-type p-2 cursor-pointer');
                        // --------------------------------------------------

                        // --------------------------------------------------
                        // Add the filter id reference
                        // --------------------------------------------------
                        var filter = io.find( types, function(o){ return o.label == label });
                        if( filter ) $badge.attr('data-filter', filter.id );
                        // --------------------------------------------------

                        // --------------------------------------------------
                        $badge.html( label ).appendTo( $column );
                        // --------------------------------------------------
                    });
                    // ------------------------------------------------------
                }
                // ----------------------------------------------------------

                // ----------------------------------------------------------
                return $container.get(0).outerHTML;
                // ----------------------------------------------------------
            },
            targets: 3,
            sortable: false,
            searchable: false
        });
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        options.initComplete = function( settings, json ){
            // --------------------------------------------------------------
            var column = this.api().column(3);
            var $container = $( this.api().table().container());
            var $filters = $container.find('tr.head-filters');
            if( $filters.length ) $filters = $( $filters[0] );
            var $filterBox = $filters.find('th[data-col="groups"]');
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Build the selectbox
            // --------------------------------------------------------------
            var $select = $('<select/>').addClass('type-filter form-control form-control-sm').appendTo( $filterBox );
            // --------------------------------------------------------------
            $select.append('<option value="all" selected>@lang('label.all')</option>');
            @foreach( $filters as $filter => $label )
                $select.append('<option value="{{ $filter }}">{{ $label }}</option>');
            @endforeach
            // --------------------------------------------------------------


            // --------------------------------------------------------------
            // Do the the filtering when the user changes the selectbox
            // --------------------------------------------------------------
            var table = this;
            $select.on('change', function(){
                // ----------------------------------------------------------
                var value = $select.val();
                var id = '#' + value;
                // ----------------------------------------------------------

                // ----------------------------------------------------------
                // Reset column searching
                // ----------------------------------------------------------
                @foreach( $filters as $filter => $label )
                    table.api().column('{{ "#{$filter}" }}').search('');
                @endforeach
                // ----------------------------------------------------------

                // ----------------------------------------------------------
                // If all is selected, redraw the reset table,
                // otherwise search the column flag and then redraw the table
                // ----------------------------------------------------------
                if( 'all' == value ) table.api().draw();
                else table.api().column( id ).search(1).draw();
                // ----------------------------------------------------------
            });
            // --------------------------------------------------------------


            // --------------------------------------------------------------
            // Filtering when the user clicks the company type badges
            // --------------------------------------------------------------
            $container.on('click', '.badge-type', function(){
                // ----------------------------------------------------------
                var $badge = $(this);
                var filter = $badge.attr('data-filter');
                // ----------------------------------------------------------
                if( filter && filter != $select.val()){
                    $select.val( filter ).trigger('change');
                }
                // ----------------------------------------------------------
            });
            // --------------------------------------------------------------
        }
        // ------------------------------------------------------------------
    @endsection
@if(0) </script> @endif
