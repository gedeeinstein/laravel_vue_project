@extends('backend._base.content_datatables')

@section('page_title')
    <div class="row mx-n2 justify-content-center justify-content-md-start">

        <!-- Company list button - Start -->
        @component( 'backend._components.page_button' )
            @slot( 'url', route( 'company.index' ))
            <span class="px-1 col-auto"><i class="fas fa-chevron-left"></i></span>
            <span class="px-1 col-auto">@lang('label.$company.list')</span>
        @endcomponent
        <!-- Company list button - End -->

        <div class="px-2 col d-flex align-items-center mt-3 mb-2 my-sm-0">
            <h1 class="m-0 text-dark h1title">{{ $company->name }}</h1>
        </div>
    </div>
@endsection

@section('breadcrumbs')
    <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item">
            <a href="{{ route('dashboard') }}">
                <i class="fas fa-tachometer-alt"></i>
                <span>@lang('label.dashboard')</span>
            </a>
        </li>

        <li class="breadcrumb-item">
            <a href="{{ route('company.index') }}">
                <span>@lang('label.company')</span>
            </a>
        </li>

        <li class="breadcrumb-item active">{{ $company->name }}</li>
    </ol>
@endsection

@section('panel_header')
    <div class="row">
        <div class="col">
            <ul class="nav nav-tabs card-header-tabs m-0 mt-3">
                @if( !$individual )
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route( 'company.edit', $company->id )}}">
                            <span>@lang('label.$company.tab.about')</span>
                        </a>
                    </li>
                @endif
                <li class="nav-item">
                    <a class="nav-link active" href="{{ route( 'company.user.index', $company->id ?? 'individual' )}}">
                        <span>@lang('label.$company.tab.users')</span>
                    </a>
                </li>
            </ul>
        </div>
        <div class="col-auto d-flex align-items-center">
            @if( 'global_admin' == $user->user_role->name )
                <a class="btn btn-info fs-14" href="{{ route( 'company.user.create', $company->id ?? 'individual' )}}">
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
    <th data-col="user_role_id">@lang('users.login_authority')</th>
    {{-- @if( 'global_admin' == $user->user_role->name )
        <th data-col="action">@lang('label.action')</th>
    @endif --}}
@endsection

@push('templates')
    <!-- Templates - Start -->
    <div class="template d-none">
        <div class="row row-controls mx-n2 mt-1 fs-13">
            <a class="px-2 col-auto edit-control" href="#">
                <span class="row mx-n1">
                    <span class="px-1 col-auto"><i class="far fa-edit"></i></span>
                    <span class="px-1 col-auto edit-label"></span>
                </span>
            </a>
            <a class="px-2 col-auto delete-control" href="#">
                <span class="row mx-n1">
                    <span class="px-1 col-auto"><i class="far fa-trash-alt"></i></span>
                    <span class="px-1 col-auto delete-label"></span>
                </span>
            </a>
        </div>
    </div>
    <!-- Templates - Start -->
@endpush

@if(0) <script> @endif
    @section('extend-datatable')
        // ------------------------------------------------------------------
        var userID = {{ $user->id }};
        var page = '{{ url()->current() }}';
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Customize company name column
        // ------------------------------------------------------------------
        options.columnDefs.push({
            render: function ( data, type, row ){
                // ----------------------------------------------------------
                var $container = $('<div/>');
                var $nameContainer = $('<div/>').addClass('row mx-n1').appendTo( $container );
                var $nameColumn = $('<div/>').addClass('px-1 col-auto').html( data ).appendTo( $nameContainer );
                var $controls = $('.template .row-controls').clone();
                // ----------------------------------------------------------

                // ----------------------------------------------------------
                // Edit control
                // ----------------------------------------------------------
                var $editControl = $controls.find('.edit-control');
                var $editLabel = $editControl.find('.edit-label');
                // ----------------------------------------------------------
                $editControl.attr( 'href', page + '/' + row.id + '/edit' );
                $editLabel.html( '@lang('label.edit')' );
                // ----------------------------------------------------------

                // ----------------------------------------------------------
                // Delete control
                // ----------------------------------------------------------
                var $deleteControl = $controls.find('.delete-control');
                var $deleteLabel = $deleteControl.find('.delete-label');
                // ----------------------------------------------------------
                $deleteControl.attr( 'data-user', row.id );
                $deleteLabel.html( '@lang('label.delete')' );
                // ----------------------------------------------------------

                // ----------------------------------------------------------
                // If current logged-in user is an admin
                // ----------------------------------------------------------
                @if( 'global_admin' === $user->user_role->name )
                    // ------------------------------------------------------
                    // Remove delete button for current active user
                    // ------------------------------------------------------
                    if( userID === row.id ) $deleteControl.remove();
                    // ------------------------------------------------------
                @else
                    // ------------------------------------------------------
                    // Remove delete control for non-admin
                    // ------------------------------------------------------
                    $deleteControl.remove();
                    // ------------------------------------------------------

                    // ------------------------------------------------------
                    // Remove the hover-to-see state of the current user row
                    // ------------------------------------------------------
                    if( row.id === userID ) $controls.removeClass('row-controls');
                    // ------------------------------------------------------

                    // ------------------------------------------------------
                    // Remove edit control on other users
                    // ------------------------------------------------------
                    else $editControl.remove();
                    // ------------------------------------------------------
                @endif
                // ----------------------------------------------------------

                // ----------------------------------------------------------
                $controls.appendTo( $container );
                return $container.get(0).outerHTML;
                // ----------------------------------------------------------
            },
            targets: 1
        });
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Customize user role column
        // ------------------------------------------------------------------
        options.columnDefs.push({
            render: function( data, type, row ){
                // ----------------------------------------------------------
                var $container = $('<div/>').addClass('row justify-content-end mx-n1');
                // ----------------------------------------------------------

                // ----------------------------------------------------------
                // Display the user role ID into badge
                // ----------------------------------------------------------
                if( row.user_role_id ){
                    // ------------------------------------------------------
                    var $column = $('<div/>').addClass('p-1 col-auto').appendTo( $container );
                    var $badge = $('<span/>').addClass('badge badge-type p-2 cursor-pointer');
                    // ------------------------------------------------------
                    $badge.html( row.user_role.label ).appendTo( $column );
                    $badge.attr( 'data-filter', row.user_role_id );
                    // ------------------------------------------------------

                    // ------------------------------------------------------
                    // Badge contextual colors
                    // ------------------------------------------------------
                    var badge = 'badge-info', role = row.user_role.name;
                    if( 'no_access' == role ) badge = 'badge-warning';
                    else if( 'global_admin' == role ) badge = 'badge-success';
                    $badge.addClass( badge );
                    // ------------------------------------------------------

                    // ------------------------------------------------------
                    // If it is the current logged-in user
                    // ------------------------------------------------------
                    if( row.id === userID ){
                        $column = $column.clone().empty().appendTo( $container );
                        var $user = $badge.clone().appendTo( $column );
                        $user.removeClass('badge-info badge-warning badge-success').addClass('badge-secondary');
                        $user.html( '@lang('users.yourself')' ).attr( 'data-user', row.id );
                    }
                    // ------------------------------------------------------
                }
                // ----------------------------------------------------------

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
        options.initComplete = function( settings, json ){
            // --------------------------------------------------------------
            var api = this.api();
            var column = api.column(5);
            var idColumn = api.column(0);
            var $container = $( api.table().container());
            var $filters = $container.find('tr.head-filters');
            if( $filters.length ) $filters = $($filters[0]);
            var $filterBox = $filters.find('th[data-col="user_role_id"]');
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Hide the default searchbox
            $filterBox.find('.searchbox').css('display', 'none');
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Build the select box for user-role filter
            // --------------------------------------------------------------
            var $select = $('<select/>').addClass('form-control form-control-sm')
            $select.attr( 'data-user', {{ $user->id }}).appendTo( $filterBox );
            $select.append('<option value="" selected>@lang('label.all')</option>');
            // --------------------------------------------------------------
            @foreach( $roles as $role )
                $select.append('<option value="{{ $role->id }}">{{ $role->label }}</option>');
            @endforeach
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Add active-user filter if the user belongs to this company/group
            // --------------------------------------------------------------
            @if( $individual && !$user->company_id )
                $select.append('<option value="user">@lang('users.yourself')</option>');
            @endif
            @if( !$individual && $user->company_id == $company->id )
                $select.append('<option value="user">@lang('users.yourself')</option>');
            @endif
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Do the the filtering when the user changes the select box
            // in column 5 user-role.
            // --------------------------------------------------------------
            $select.on( 'change', function(){
                // ----------------------------------------------------------
                var value = $select.val();
                // ----------------------------------------------------------
                // Filter the user-role column
                // ----------------------------------------------------------
                if( 'user' == value ){
                    column.search(''); // Reset the user-role column
                    var user = _.toInteger( $select.attr( 'data-user' ));
                    // ------------------------------------------------------
                    // Perform an "exact match" search - ^term$
                    // ------------------------------------------------------
                    idColumn.search( '^' +user+ '$', true, false, true ).draw();
                    // ------------------------------------------------------
                }
                // ----------------------------------------------------------
                // Otherwise filter the user-role column
                // ----------------------------------------------------------
                else {
                    idColumn.search('');
                    column.search( value, true, true ).draw();
                }
                // ----------------------------------------------------------
            });
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Filtering when the user clicks the company type badges
            // --------------------------------------------------------------
            $container.on( 'click', '.badge-type', function(){
                // ----------------------------------------------------------
                var $badge = $(this);
                var user = $badge.attr( 'data-user' );
                var role = $badge.attr( 'data-filter' );
                // ----------------------------------------------------------

                // ----------------------------------------------------------
                // When the user click the role badges
                // ----------------------------------------------------------
                if( role && role !== $select.val()) $select.val( role );
                // ----------------------------------------------------------
                // When the user click the active-user badge
                // ----------------------------------------------------------
                if( user ) $select.val('user');
                // ----------------------------------------------------------

                // ----------------------------------------------------------
                // Trigger the filter
                // ----------------------------------------------------------
                $select.trigger('change');
                // ----------------------------------------------------------
            });
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // User removal
            // --------------------------------------------------------------
            $container.on( 'click', '.delete-control', function(e){
                e.preventDefault();
                // ----------------------------------------------------------
                var $button = $(this);
                var user = _.toInteger( $button.attr( 'data-user' ));
                var url = '{{ route( 'company.user.index', $company->id ) }}/' +user;
                // ----------------------------------------------------------
                var toast = {
                    heading: '@lang('label.success')', icon: 'success',
                    position: 'top-right', stack: false, hideAfter: 3000,
                    text: '{{ config('const.SUCCESS_UPDATE_MESSAGE') }}',
                    position: { right: 18, top: 68 }
                };
                // ----------------------------------------------------------

                // ----------------------------------------------------------
                // Ask for confirmation
                // ----------------------------------------------------------
                var confirmed = confirm("@lang('label.jsConfirmDeleteData')");
                if( confirmed ){
                    // ------------------------------------------------------
                    // Do the delete request
                    // ------------------------------------------------------
                    var request = axios.delete( url );
                    // ------------------------------------------------------

                    // ------------------------------------------------------
                    // Validate the response
                    // ------------------------------------------------------
                    request.then( function( response ){
                        if( response.data && response.data.status ){
                            // ----------------------------------------------
                            var status = response.data.status;
                            // ----------------------------------------------

                            // ----------------------------------------------
                            // If returned status is error
                            // ----------------------------------------------
                            if( 'error' == status ){
                                toast.icon = 'error';
                                toast.heading = '@lang('label.error')';
                                toast.text = '{{ config('const.FAILED_UPDATE_MESSAGE') }}';
                            }
                            // ----------------------------------------------
                            else api.table().draw(); // Redraw table on success
                            // ----------------------------------------------

                            // ----------------------------------------------
                            $.toast( toast ); // Display notification
                            // ----------------------------------------------
                        }
                    });
                    // ------------------------------------------------------

                    // ------------------------------------------------------
                    // Catch the exception
                    // ------------------------------------------------------
                    request.catch( function(e){ 
                        // --------------------------------------------------
                        // Toast notification
                        // --------------------------------------------------
                        toast.icon = 'error';
                        toast.heading = '@lang('label.error')';
                        toast.text = '{{ config('const.FAILED_UPDATE_MESSAGE') }}';
                        // --------------------------------------------------
                        if( e.response && e.response.message ) toast.text = e.response.message;
                        // --------------------------------------------------
                        $.toast( toast ); console.log(e);
                        // --------------------------------------------------
                    });
                    // ------------------------------------------------------
                }
                // ----------------------------------------------------------

                // ----------------------------------------------------------
                return false;
                // ----------------------------------------------------------
            });
            // --------------------------------------------------------------
        };
    @endsection
@if(0) </script> @endif
