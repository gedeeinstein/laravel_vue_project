@php
    $components = (object) array(
        'index' => 'backend._components',
        'preset' => 'backend._components.preset',
    );
@endphp
@extends('backend._base.content_basic')

@section('breadcrumbs')

    <!-- Breadcrumbs - Start -->
    <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item">
            <a href="{{ route('dashboard') }}">
                <i class="fas fa-tachometer-alt"></i>
                <span>@lang('label.dashboard')</span>
            </a>
        </li>
        <li class="breadcrumb-item active">{{ $page_title }}</li>
    </ol>
    <!-- Breadcrumbs - End -->

@endsection


@section('preloader')

    <!-- Preloader - Start -->
    @component("{$components->index}.preloader")
        @slot( 'if', '!status.mounted' )
    @endcomponent
    <!-- Preloader - End -->

@endsection


@section('content')

    <!-- Content - Start -->
    <div class="innerset">

        <!-- Result filter - Start -->
        @relativeInclude('includes.filter')
        <!-- Result filter - End -->

        <!-- Result stats - Start -->
        @relativeInclude('includes.statistic')
        <!-- Result stats - End -->

        <!-- Pagination - Start -->
        @relativeInclude('includes.pagination')
        <!-- Pagination - End -->

        <!-- Request entries - Start -->
        <div class="project-entries request-entries mb-4">

            <!-- Content placeholder - Start -->
            @relativeInclude('includes.request.placeholder')
            <!-- Content placeholder - End -->

            <!-- Request entry - Start -->
            @relativeInclude('includes.request.entry')
            <!-- Request entry - End -->

        </div>
        <!-- Request entries - End -->

        <router-view></router-view>

        <!-- Pagination - Start -->
        @relativeInclude('includes.pagination')
        <!-- Pagination - End -->

    </div>
    <!-- Content - End -->

@endsection


@if(0) <script> @endif
    @push('extend-parsley')
        // ------------------------------------------------------------------
        options.successClass = false;
        // ------------------------------------------------------------------
        // Exluded elements
        // ------------------------------------------------------------------
        options.excluded = 'input[type=button], input[type=submit], input[type=reset], '+
            'input[type=hidden], input.parsley-excluded, [data-parsley-excluded]';
        // ------------------------------------------------------------------
        // Finding error container
        // ------------------------------------------------------------------
        options.errorsContainer = function( field ){
            // --------------------------------------------------------------
            var formResult = '.form-result';
            var $element = $( field.$element );
            var $result = $element.siblings( formResult );
            // --------------------------------------------------------------
            if( $result.length ) return $result;
            else {
                // ----------------------------------------------------------
                var $parent = $element.parent();
                if( $parent.is('.input-group')){
                    $result = $parent.siblings( formResult );
                    if( $result.length ) return $result;
                }
                // ----------------------------------------------------------
                var $row = $element.closest('.row');
                $result = $row.siblings('.form-result');
                // ----------------------------------------------------------
                if( $result.length ) return $result;
                else {
                    // ------------------------------------------------------
                    var $group = $element.closest('.form-group');
                    $result = $group.children( formResult );
                    // ------------------------------------------------------
                    if( $result.length ) return $result;
                    // ------------------------------------------------------
                }
                // ----------------------------------------------------------
            }
            // --------------------------------------------------------------
        }
        // ------------------------------------------------------------------
    @endpush
@if(0) </script> @endif

@push('vue-scripts')

@relativeInclude('vue.pagination.import')
@relativeInclude('vue.request.import')

<script> @minify
    (function( $, io, document, window, undefined ){
        // ------------------------------------------------------------------
        var toast = {
            heading: 'error', icon: 'error',
            stack: false, hideAfter: 3000,
            position: { right: 18, top: 68 }
        };
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        function isNumeric(n){
            return !isNaN( parseFloat(n)) && isFinite(n);
        }
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Project inspection index route
        // ------------------------------------------------------------------
        var index = {
            template: null,
            watch: {
                // ----------------------------------------------------------
                // Watch for route changes
                // ----------------------------------------------------------
                '$route': {
                    immediate: true,
                    handler: function( to, from ){
                        // --------------------------------------------------
                        var $parent = this.$parent;
                        var filter = $parent.filter;
                        var pagination = $parent.pagination;
                        var config = $parent.config;
                        // --------------------------------------------------
                        $parent.status.loading = true;
                        // --------------------------------------------------

                        // --------------------------------------------------
                        // Do server request
                        // --------------------------------------------------
                        var url = '{{ route( 'project.inspection.filter' )}}';
                        var request = axios.post( url, { filter: filter });
                        // --------------------------------------------------
                        request.then( function( response ){
                            var result = io.get( response, 'data.result' );
                            if( 'success' == io.get( response, 'data.status' ) && result ){
                                // ------------------------------------------
                                $parent.result = result;
                                pagination.total = result.total || 0;
                                if( result.per_page ){
                                    pagination.perpage = result.per_page;
                                    if( config.placeholder > result.per_page ) config.placeholder = result.per_page;
                                }
                                pagination.page = result.current_page || 1;
                                // ------------------------------------------
                            }
                        });
                        // --------------------------------------------------

                        // --------------------------------------------------
                        request.finally( function(){ $parent.status.loading = false });
                        // --------------------------------------------------
                    }
                }
                // ----------------------------------------------------------
            }
        };
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Vue routes
        // ------------------------------------------------------------------
        router = {
            mode: 'history',
            routes: [{ 
                path: '/project/inspection', name: 'index', component: index
            }]
        };
        // ------------------------------------------------------------------
        

        // ------------------------------------------------------------------
        mixin = {
            // --------------------------------------------------------------
            // router: router,
            // --------------------------------------------------------------
            mounted: function(){
                var vm = this;
                // ----------------------------------------------------------

                // ----------------------------------------------------------
                // Set mounted status
                // ----------------------------------------------------------
                vm.status.mounted = true;
                // ----------------------------------------------------------

                // ----------------------------------------------------------
                // Trigger custom vue loaded event for jQuery 
                // and other plugins to listen to
                // ----------------------------------------------------------
                $(document).trigger( 'vue-loaded', this );
                // ----------------------------------------------------------
            },
            // --------------------------------------------------------------
            data: function(){
                // ----------------------------------------------------------
                var $route = this.$route;
                var $query = $route.query;
                // ----------------------------------------------------------

                // ----------------------------------------------------------
                // Initial reactive data
                // ----------------------------------------------------------
                var data = {
                    // ------------------------------------------------------
                    status: { mounted: false, loading: false },
                    preset: {
                        tabs: @json( $tabs )
                    },
                    config: {
                        placeholder: 5,
                        pagination: @json( $pagination )
                    },
                    // // ------------------------------------------------------
                    pagination: { page: 1, perpage: null, total: null },
                    // // ------------------------------------------------------
                    filter: { type: 'all', page: 1 },
                    // // ------------------------------------------------------
                    result: {}
                    // ------------------------------------------------------
                };
                // ----------------------------------------------------------

                // ----------------------------------------------------------
                // Replace filter with the defined URL query
                // Prune integer-string parameter into real integer
                // ----------------------------------------------------------
                if( $query ){
                    var filter = $.extend( data.filter, $query );
                    $.each( filter, function( i, prop ){
                        // --------------------------------------------------
                        if( isNumeric( prop )) filter[i] = io.parseInt( prop);
                        // --------------------------------------------------
                        if( 'true' == prop ) filter[i] = true;
                        else if( 'false' == prop ) filter[i] = false;
                        // --------------------------------------------------
                    });
                    data.filter = filter;
                }
                // ----------------------------------------------------------

                // ----------------------------------------------------------
                // console.log( data );
                return data;
                // ----------------------------------------------------------
            },
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            computed: {
                // ----------------------------------------------------------
                // Get current page start
                // ----------------------------------------------------------
                pageStart: function(){
                    // ------------------------------------------------------
                    var vm = this;
                    var start = null;
                    // ------------------------------------------------------
                    var page = io.get( vm, 'pagination.page' );
                    var total = io.get( vm, 'pagination.total' );
                    var perpage = io.get( vm, 'pagination.perpage' );
                    // ------------------------------------------------------
                    if( total ) start = (( page - 1 ) * perpage ) +1;
                    // ------------------------------------------------------
                    return start;
                    // ------------------------------------------------------
                },
                // ----------------------------------------------------------

                // ----------------------------------------------------------
                // Get current page end
                // ----------------------------------------------------------
                pageEnd: function(){
                    // ------------------------------------------------------
                    var vm = this;
                    var end = null;
                    // ------------------------------------------------------
                    var page = io.get( vm, 'pagination.page' );
                    var total = io.get( vm, 'pagination.total' );
                    var perpage = io.get( vm, 'pagination.perpage' );
                    // ------------------------------------------------------
                    if( total ){
                        end = page * perpage;
                        if( total <= end ) end = total;
                    }
                    // ------------------------------------------------------
                    return end;
                    // ------------------------------------------------------
                },
                // ----------------------------------------------------------

                // ----------------------------------------------------------
                // Get total entries
                // ----------------------------------------------------------
                pageTotal: function(){
                    return io.get( this, 'pagination.total' );
                },
                // ----------------------------------------------------------
            },
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            watch: {},
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Methods
            // --------------------------------------------------------------
            methods: {
                // ----------------------------------------------------------
                // Apply the filter and move to the destination route
                // ----------------------------------------------------------
                applyFilter( filter, resetPage ){
                    // ------------------------------------------------------
                    filter = filter || this.filter;
                    resetPage = resetPage || false;
                    // ------------------------------------------------------
                    var query = {};
                    var $route = this.$route;
                    // ------------------------------------------------------
                    if( resetPage ) filter.page = 1;
                    // ------------------------------------------------------

                    // ------------------------------------------------------
                    if( filter.type ){
                        var found = io.find( this.preset.tabs, { id: filter.type });
                        if( found ) query.type = filter.type;
                    }
                    // ------------------------------------------------------
                    if( filter.page ){
                        var page = io.parseInt( filter.page );
                        if( page ) query.page = filter.page;
                    }
                    // ------------------------------------------------------

                    // ------------------------------------------------------
                    this.$router.push({ name: 'index', query: query }, function(){});
                    // ------------------------------------------------------
                },
                // ----------------------------------------------------------

                // ----------------------------------------------------------
                // Filter request-type by the filter tabs
                // ----------------------------------------------------------
                filterType( filter ){
                    this.filter.page = 1;
                    this.filter.type = filter.id;
                    this.applyFilter();
                },
                // ----------------------------------------------------------

                // ----------------------------------------------------------
                // Reset all filter
                // ----------------------------------------------------------
                resetFilter: function(){
                    this.filter = $.extend({}, this.filter, {
                        type: null, page: null
                    }); this.applyFilter();
                },
                // ----------------------------------------------------------

                // ----------------------------------------------------------
                // Navigate to destination page
                // ----------------------------------------------------------
                navigatePage: function( page ){
                    this.filter.page = page;
                    this.applyFilter();
                },
                // ----------------------------------------------------------
            }
            // --------------------------------------------------------------
        };
        // ------------------------------------------------------------------
    }( jQuery, _, document, window ));
@endminify </script>
@endpush
