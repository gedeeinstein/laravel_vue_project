@extends('backend._base.content_datatables')

@section('page_title')
    <div class="row mx-n2">
        <div class="px-2 col-auto">
            <div class="d-block d-md-none">
                <a href="{{ route('admin.company.index') }}" class="btn btn-sm btn-default">
                    <i class="fas fa-chevron-left"></i>
                    <span>@lang('label.$company.list')</span>
                </a>
            </div>
            <div class="d-none d-md-block">
                <a href="{{ route('admin.company.index') }}" class="btn btn-default">
                    <i class="fas fa-chevron-left"></i>
                    <span>@lang('label.$company.list')</span>
                </a>
            </div>
        </div>
        <div class="px-2 col d-flex align-items-center">
            <h1 class="m-0 text-dark h1title">{{ $page_title }}</h1>
        </div>
    </div>
@endsection

@section('breadcrumbs')
    <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item">
            <a href="{{ route('dashboard') }}"><i class="fas fa-tachometer-alt"></i> @lang('label.dashboard')</a></li>
        @if( Auth::user()->admin_role_id != 3 )
            <li class="breadcrumb-item"><a href="{{ route('admin.company.index') }}">@lang('users.individual')</a></li>
        @endif
        <li class="breadcrumb-item active">{{ $page_title }}</li>
    </ol>
@endsection

@section('panel_header')
    <div class="row">
        <div class="col">
            <ul class="nav nav-tabs card-header-tabs m-0 mt-3">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('admin.company.index') }}">@lang('label.$company.tab.about')</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="{{ route('admin.user.individual.index') }}">@lang('label.$company.tab.users')</a>
                </li>
            </ul>
        </div>
        <div class="col-auto d-flex align-items-center">
            @if( Auth::user()->admin_role_id != 3 )
                <a class="btn btn-info fs-14" href="{{ route('admin.user.individual.create')}}">
                    <i class="fas fa-plus-circle"></i>
                    <span>@lang('label.$user.add')</span>
                </a>
            @endif
        </div>
    </div>
@endsection

@section('content')
    <th data-hidden data-col="id">ID</th>
    <th data-col="last_name">@lang('users.last_name')</th>
    <th data-col="first_name">@lang('users.first_name')</th>
    <th data-col="last_name_kana">@lang('users.last_name_kana')</th>
    <th data-col="first_name_kana">@lang('users.first_name_kana')</th>
    <th data-col="login_authority">@lang('users.login_authority')</th>
    <th data-col="action">@lang('label.action')</th>
@endsection


{{--GRAB DATA FROM CONFIG.CONST--}}
@php
    $filters = [];
    $types = config('const.USER_LOGIN_AUTHORITIES');
    if( !empty( $types )) {
    	foreach( $types as $key => $name ){
            $filters[ $key ] = $name;
        }
    }
@endphp

@if(0) <script> @endif
    @section('extend-datatable')
        // ------------------------------------------------------------------
        // Extend datatable options
        // Customize User type login authority group
        // ------------------------------------------------------------------
        options.columnDefs.push({
            render: function (data, type, row) {
                // ----------------------------------------------------------
                var $container = $('<div/>').addClass('row justify-content-end mx-n1');
                // ----------------------------------------------------------

                // ----------------------------------------------------------
                // Display the login authority string into badges
                // ----------------------------------------------------------
                if (row.login_authority && row.login_authority.length) {
                    // ------------------------------------------------------
                    var types = [];
                    @foreach( $filters as $filter => $label )
                    types.push({id: '{{ $filter }}', label: '{{ $label }}'});
                    @endforeach

                    // --------------------------------------------------
                    var $column = $('<div/>').addClass('p-1 col-auto').appendTo($container);
                    var $badge = $('<span/>').addClass('badge badge-info badge-type p-2 cursor-pointer');
                    // --------------------------------------------------

                    // --------------------------------------------------
                    // Add the filter id reference and change to label
                    // --------------------------------------------------
                    $.grep(types, function (v) {
                        if (v.id === row.login_authority) {
                            $badge.html(v.label).appendTo($column);
                        }
                    }, true);

                    $badge.attr('data-filter', row.login_authority);
                }

                // ----------------------------------------------------------
                return $container.get(0).outerHTML;
                // ----------------------------------------------------------
            },
            targets: 5,
            sortable: false,
            searchable: false
        });
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        options.initComplete = function (settings, json) {
            // --------------------------------------------------------------
            let column = this.api().column(5);
            let $container = $(this.api().table().container());
            let $filters = $container.find('tr.head-filters');
            if ($filters.length) $filters = $($filters[0]);
            let $filterBox = $filters.find('th[data-col="login_authority"]');

            //hidden class search box to activate the search filters.
            $filterBox.find('.searchbox').css('display', 'none');

            // --------------------------------------------------------------
            // Build the select box for Login authority
            // --------------------------------------------------------------
            let $select = $('<select/>').addClass('type-filter-authority form-control form-control-sm').appendTo($filterBox);
            $select.append('<option value="" selected>@lang('label.all')</option>');
            @foreach( $filters as $filter => $label )
            $select.append('<option value="{{ $filter }}">{{ $label }}</option>');
            @endforeach

            // --------------------------------------------------------------
            // Do the the filtering when the user changes the select box
            // in column 5 login authority.
            // --------------------------------------------------------------
            $select.on('change', function () {
                let value = $select.val();
                column.search(value, true, true).draw();
            });

            // --------------------------------------------------------------
            // Filtering when the user clicks the company type badges
            // --------------------------------------------------------------
            $container.on('click', '.badge-type', function () {
                let $badge = $(this);
                let filter = $badge.attr('data-filter');
                if (filter && filter !== $select.val()) {
                    $select.val(filter).trigger('change');
                }
            });

        };
    @endsection
@if(0) </script> @endif
