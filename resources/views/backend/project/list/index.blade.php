@php
    $components = (object) array(
        'index' => 'backend._components',
        'includes' => 'backend.project.list.includes',
        'xtemplate' => 'backend.project.list.xtemplate'
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
    <form method="POST" data-parsley class="parsley-minimal">
        <div class="innerset">

            <!-- New project form - Start -->
            @include("{$components->includes}.project.new")
            <!-- New project form - End -->

            <!-- Project status filter - Start -->
            @include("{$components->includes}.project.filter")
            <!-- Project status filter - End -->

            <!-- Search filter form - Start -->
            @include("{$components->includes}.search")
            <!-- Search filter form - End -->

            <!-- Search result stats - Start -->
            @include("{$components->includes}.statistic")
            <!-- Search result stats - End -->

            <!-- Pagination - Start -->
            @include("{$components->includes}.pagination")            
            <!-- Pagination - End -->

            <!-- Toggle print button - Start -->
            @include("{$components->includes}.print-button")
            <!-- Toggle print button - End -->

            <!-- Project entries - Start -->
            <div class="project-entries mb-4">

                <!-- Content placeholder - Start -->
                @include("{$components->includes}.project.placeholder")
                <!-- Content placeholder - End -->

                <!-- Project entry - Start -->
                @include("{$components->includes}.project.entry")
                <!-- Project entry - End -->

            </div>
            <!-- Project entries - End -->

            <router-view></router-view>

            <!-- Pagination - Start -->
            @include("{$components->includes}.pagination")
            <!-- Pagination - End -->

        </div>
    </form>
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
@relativeInclude('vue.project.entry.import')
@relativeInclude('vue.project.memo.import')

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
        // Vuex store
        // ------------------------------------------------------------------
        store = {
            // --------------------------------------------------------------
            // State / data
            // --------------------------------------------------------------
            state: function(){
                // ----------------------------------------------------------
                var state = {
                    // ------------------------------------------------------
                    print: false
                    // ------------------------------------------------------
                }; 
                // ----------------------------------------------------------

                // ----------------------------------------------------------
                return state;
                // ----------------------------------------------------------
            },
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Getters / computed
            // --------------------------------------------------------------
            getters: {},
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Mutations / methods
            // --------------------------------------------------------------
            mutations: {
                // ----------------------------------------------------------
                // Set print button state
                // ----------------------------------------------------------
                setPrint: function( state, value ){ state.print = !!value },
                togglePrint: function( state ){ state.print = !state.print },
                // ----------------------------------------------------------
            }
            // --------------------------------------------------------------
        };
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Project list index route
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
                        var url = '/project/list';
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
                path: '/project/list', name: 'index', component: index
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
                // --------------------------------------------------------------
                // Set mounted status
                // --------------------------------------------------------------
                vm.status.mounted = true;
                // --------------------------------------------------------------

                // --------------------------------------------------------------
                // Trigger custom vue loaded event for jQuery 
                // and other plugins to listen to
                // --------------------------------------------------------------
                $(document).trigger( 'vue-loaded', this );
                // --------------------------------------------------------------
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
                    status: { mounted: false, loading: false, creating: false },
                    preset: {
                        tabs: @json( $tabs ),
                        filter: {
                            years: @json( $filter->year ),
                            periods: @json( $filter->period )
                        }
                    },
                    config: {
                        placeholder: 3,
                        pagination: @json( $pagination )
                    },
                    // ------------------------------------------------------
                    entry: { title: null },
                    // ------------------------------------------------------
                    pagination: { page: 1, perpage: null, total: null },
                    // ------------------------------------------------------
                    filter: {
                        status: 'all', min: null, max: null, title: null,
                        year: null, month: null, period: null, empty: null, page: 1
                    },
                    // ------------------------------------------------------
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
                    if( total ) start = ((page - 1) * perpage ) +1;
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

                // ----------------------------------------------------------
                emptyTitle: function(){
                    var empty = false; var entry = this.entry;
                    if( !entry.title || !io.trim( entry.title )) empty = true;
                    return empty;
                }
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
                applyFilter( e, filter, resetPage ){
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
                    if( filter.status ){
                        var found = io.find( this.preset.tabs, { id: filter.status });
                        if( found ) query.status = filter.status;
                    }
                    // ------------------------------------------------------
                    if( filter.min ){
                        var min = io.parseInt( filter.min );
                        if( min ) query.min = min;
                    }
                    // ------------------------------------------------------
                    if( filter.max ){
                        var max = io.parseInt( filter.max );
                        if( max ) query.max = max;
                    }
                    // ------------------------------------------------------
                    if( filter.title ){
                        var title = io.trim( filter.title );
                        if( title.length ) query.title = title;
                    }
                    // ------------------------------------------------------
                    if( filter.year ){
                        var year = io.parseInt( filter.year );
                        if( year ) query.year = year;
                    }
                    // ------------------------------------------------------
                    if( filter.month ){
                        var month = io.parseInt( filter.month );
                        if( month ) query.month = month;
                    }
                    // ------------------------------------------------------
                    if( filter.period ){
                        var period = io.parseInt( filter.period );
                        if( period ) query.period = period;
                    }
                    // ------------------------------------------------------
                    if( filter.empty ){
                        if( !!filter.empty ) query.empty = filter.empty;
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
                // Filter project-status by the filter tabs
                // ----------------------------------------------------------
                filterStatus( filter ){
                    this.filter.status = filter.id;
                    this.applyFilter();
                },
                // ----------------------------------------------------------

                // ----------------------------------------------------------
                // On search submit
                // ----------------------------------------------------------
                submitSearch( event ){
                    event.preventDefault();
                    this.applyFilter( this.filter, true ); // Apply filter and reset page
                },
                // ----------------------------------------------------------

                // ----------------------------------------------------------
                // Reset all filter
                // ----------------------------------------------------------
                resetFilter: function(){
                    this.filter = $.extend({}, this.filter, {
                        status: null, min: null, max: null, title: null,
                        year: null, month: null, period: null, empty: null, page: null
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

                // ----------------------------------------------------------
                // Create new project
                // ----------------------------------------------------------
                newProject: function(){
                    // ------------------------------------------------------
                    var vm = this; var entry = this.entry;
                    if( this.emptyTitle ) return;
                    // ------------------------------------------------------

                    // ------------------------------------------------------
                    if( entry.title ){
                        // --------------------------------------------------
                        vm.status.creating = true;
                        // --------------------------------------------------
                        var url = '{{ route('project.create') }}';
                        var request = axios.put( url, { entry: entry });
                        // --------------------------------------------------
                        request.then( function( response ){
                            // ----------------------------------------------
                            var status = io.get( response, 'data.status' );
                            // ----------------------------------------------
                            var alert = io.get( response, 'data.alert' );
                            var option = $.extend({}, toast, alert );
                            // ----------------------------------------------
                            if( 'success' == status ){
                                // ------------------------------------------
                                option.icon = 'success';
                                var id = io.get( response, 'data.id' );
                                // ------------------------------------------
                                if( id ){
                                    vm.filter = $.extend({}, vm.filter, {
                                        status: 'all', min: id, max: id, title: null,
                                        year: null, month: null, period: null, empty: true, page: null
                                    }); vm.applyFilter();
                                };
                                // ------------------------------------------

                                // ------------------------------------------
                                entry.title = null; // Reset new project title
                                // ------------------------------------------
                            }
                            // ----------------------------------------------
                            $.toast( option );
                            // ----------------------------------------------
                        });
                        // --------------------------------------------------

                        // --------------------------------------------------
                        request.catch( function( response ){
                            if( response.data ){
                                var alert = io.get( response, 'data.alert' );
                                var option = $.extend({}, toast, alert );
                                $.toast( option );
                            }
                        });
                        // --------------------------------------------------
                        request.finally( function(){ vm.status.creating = false });
                        // --------------------------------------------------
                    }
                },
                // ----------------------------------------------------------

                // ----------------------------------------------------------
                // When project is removed
                // ----------------------------------------------------------
                onProjectRemoved: function(){
                    this.$router.go({
                        path: '/project/list'
                    });
                }
                // ----------------------------------------------------------
            }
            // --------------------------------------------------------------
        };
        // ------------------------------------------------------------------
    }( jQuery, _, document, window ));
@endminify </script>
@endpush
