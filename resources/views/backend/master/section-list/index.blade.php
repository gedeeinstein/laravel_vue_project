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

        <!-- Navigation tabs - Start -->
        @relativeInclude('includes.tabs')
        <!-- Navigation tabs - End -->

        <!-- Result filter - Start -->
        @relativeInclude('includes.filter')
        <!-- Result filter - End -->

        @relativeInclude('includes.query')

        <!-- Result stats - Start -->
        @relativeInclude('includes.statistic')
        <!-- Result stats - End -->

        <!-- Pagination - Start -->
        @relativeInclude('includes.pagination')
        <!-- Pagination - End -->

        <!-- Request entries - Start -->
        <div class="project-entries section-entries mb-4">

            <!-- Content placeholder - Start -->
            @relativeInclude('includes.section.placeholder')
            <!-- Content placeholder - End -->

            <!-- Section entry - Start -->
            @relativeInclude('includes.section.entry')
            <!-- Section entry - End -->

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
@relativeInclude('vue.section.import')

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
        // Master section list index route
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
                        var url = '{{ route( 'master.section.list.filter' )}}';
                        var request = axios.post( url, { filter: filter });
                        // --------------------------------------------------
                        request.then( function( response ){
                            // console.log( response );
                            var result = io.get( response, 'data.result' );
                            if( 'success' == io.get( response, 'data.status' ) && result ){
                                // ------------------------------------------
                                $parent.result = result;
                                pagination.total = result.total || 0;
                                // ------------------------------------------
                                if( result.per_page ){
                                    pagination.perpage = result.per_page;
                                    if( config.placeholder > result.per_page ) config.placeholder = result.per_page;
                                }
                                // ------------------------------------------
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
                path: '/master/section/list', name: 'index', component: index
            }]
        };
        // ------------------------------------------------------------------


        // ------------------------------------------------------------------
        // Default empty filter
        // ------------------------------------------------------------------
        var defaultFilter = { 
            // --------------------------------------------------------------
            min: null, max: null, rank: null, page: null,
            title: null, organizer: null,
            // --------------------------------------------------------------
            sale_year: null, sale_month: null,
            // --------------------------------------------------------------
            contract_year: null, contract_month: null,
            // --------------------------------------------------------------
            loan_year: null, loan_month: null, loan_status: null,
            // --------------------------------------------------------------
            payment_year: null, payment_month: null, payment_period: null,
            // --------------------------------------------------------------
            sale_payment_year: null, sale_payment_month: null, sale_payment_period: null,
            // --------------------------------------------------------------
            empty_contract: null, different_price: null,
            // --------------------------------------------------------------
            loan_undecided: null, loan_expected: null, 
            loan_confirmed: null, loan_applied: null, loan_completed: null
            // --------------------------------------------------------------
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
                        tabs: @json( $tabs ),
                        organizers: @json( $organizers ),
                        years: @json( $preset->years ),
                        periods: @json( $preset->periods )
                    },
                    config: {
                        placeholder: 3,
                        pagination: @json( $pagination )
                    },
                    // ------------------------------------------------------
                    pagination: { page: 1, perpage: null, total: null },
                    // ------------------------------------------------------
                    filter: io.clone( defaultFilter ),
                    // ------------------------------------------------------
                    check: { 
                        id: false, title: false, organizer: false,
                        payment: false, paymentPeriod: false,
                        salePayment: false, salePaymentPeriod: false,
                        contract: false, saleContract: false, loan: false,
                    }, 
                    // ------------------------------------------------------
                    result: {}
                    // ------------------------------------------------------
                };
                // ----------------------------------------------------------

                // ----------------------------------------------------------
                // Query group reference
                // ----------------------------------------------------------
                var queryGroup = {
                    // ------------------------------------------------------
                    id: [ 'min', 'max' ],
                    title: [ 'title' ], 
                    organizer: [ 'organizer' ],
                    // ------------------------------------------------------
                    payment: [ 'payment_year', 'payment_month' ],
                    paymentPeriod: [ 'payment_period' ],
                    // ------------------------------------------------------
                    salePayment: [ 'sale_payment_year', 'sale_payment_month' ],
                    salePaymentPeriod: [ 'sale_payment_period' ],
                    // ------------------------------------------------------
                    contract: [ 'contract_year', 'contract_month' ],
                    saleContract: [ 'sale_contract_year', 'sale_contract_month' ],
                    loan: [ 'loan_year', 'loan_month' ],
                    // ------------------------------------------------------
                };
                // ----------------------------------------------------------

                // ----------------------------------------------------------
                // Replace filter with the defined URL query
                // Prune integer-string parameter into real integer
                // ----------------------------------------------------------
                if( $query ){
                    // ------------------------------------------------------
                    var check = data.check;
                    var filter = $.extend( data.filter, $query );
                    // ------------------------------------------------------
                    $.each( filter, function( name, prop ){
                        // --------------------------------------------------
                        if( isNumeric( prop )) filter[name] = io.parseInt( prop);
                        // --------------------------------------------------
                        if( 'true' == prop ) filter[name] = true;
                        else if( 'false' == prop ) filter[name] = false;
                        // --------------------------------------------------

                        // --------------------------------------------------
                        // Iterate query group and enable the related filter-check
                        // --------------------------------------------------
                        $.each( queryGroup, function( group, queries ){
                            $.each( queries, function( i, query ){
                                if( name === query && prop ) check[group] = true;
                            });
                        });
                        // --------------------------------------------------
                    });
                    // ------------------------------------------------------
                    data.filter = filter;
                    // ------------------------------------------------------
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
                isDisabled: function(){ return this.status.loading },
                // ----------------------------------------------------------

                // ----------------------------------------------------------
                // Get current page start
                // ----------------------------------------------------------
                pageStart: function(){
                    // ------------------------------------------------------
                    var vm = this;
                    var start = 0;
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
                    var end = 0;
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
                    var total = io.get( this, 'pagination.total' );
                    return total || 0;
                },
                // ----------------------------------------------------------
            },
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            watch: {
                // ----------------------------------------------------------
                // Reset filter when id checkbox is unchecked
                // ----------------------------------------------------------
                'check.id': function( value ){
                    var filter = this.filter;
                    if( !value ){
                        filter.min = null;
                        filter.max = null;
                        this.submitFilter();
                    }
                },
                // ----------------------------------------------------------

                // ----------------------------------------------------------
                // Reset filter when title checkbox is unchecked
                // ----------------------------------------------------------
                'check.title': function( value ){
                    var filter = this.filter;
                    if( !value ){
                        filter.title = null;
                        this.submitFilter();
                    }
                },
                // ----------------------------------------------------------

                // ----------------------------------------------------------
                // Reset filter when title checkbox is unchecked
                // ----------------------------------------------------------
                'check.organizer': function( value ){
                    var filter = this.filter;
                    if( !value ){
                        filter.organizer = null;
                        this.submitFilter();
                    }
                },
                // ----------------------------------------------------------

                // ----------------------------------------------------------
                // Reset filter when contract-pyamnet checkbox is unchecked
                // ----------------------------------------------------------
                'check.payment': function( value ){
                    var filter = this.filter;
                    if( !value ){
                        filter.payment_year = null;
                        filter.payment_month = null;
                        this.submitFilter();
                    }
                },
                // ----------------------------------------------------------

                // ----------------------------------------------------------
                // Reset filter when contract-payment-period checkbox is unchecked
                // ----------------------------------------------------------
                'check.paymentPeriod': function( value ){
                    var filter = this.filter;
                    if( !value ){
                        filter.payment_period = null;
                        this.submitFilter();
                    }
                },
                // ----------------------------------------------------------

                // ----------------------------------------------------------
                // Reset filter when sale-contract-payment checkbox is unchecked
                // ----------------------------------------------------------
                'check.salePayment': function( value ){
                    var filter = this.filter;
                    if( !value ){
                        filter.sale_payment_year = null;
                        filter.sale_payment_month = null;
                        this.submitFilter();
                    }
                },
                // ----------------------------------------------------------

                // ----------------------------------------------------------
                // Reset filter when sale-contract-payment-period checkbox is unchecked
                // ----------------------------------------------------------
                'check.salePaymentPeriod': function( value ){
                    var filter = this.filter;
                    if( !value ){
                        filter.sale_payment_period = null;
                        this.submitFilter();
                    }
                },
                // ----------------------------------------------------------

                // ----------------------------------------------------------
                // Reset filter when contract checkbox is unchecked
                // ----------------------------------------------------------
                'check.contract': function( value ){
                    var filter = this.filter;
                    if( !value ){
                        filter.contract_year = null;
                        filter.contract_month = null;
                        this.submitFilter();
                    }
                },
                // ----------------------------------------------------------

                // ----------------------------------------------------------
                // Reset filter when sale-contract checkbox is unchecked
                // ----------------------------------------------------------
                'check.saleContract': function( value ){
                    var filter = this.filter;
                    if( !value ){
                        filter.sale_contract_year = null;
                        filter.sale_contract_month = null;
                        this.submitFilter();
                    }
                },
                // ----------------------------------------------------------

                // ----------------------------------------------------------
                // Reset filter when loan checkbox is unchecked
                // ----------------------------------------------------------
                'check.loan': function( value ){
                    var filter = this.filter;
                    if( !value ){
                        filter.loan_year = null;
                        filter.loan_month = null;
                        this.submitFilter();
                    }
                },
                // ----------------------------------------------------------

                // ----------------------------------------------------------
                // Empty contract date
                // ----------------------------------------------------------
                'filter.empty_contract': function(){ this.submitFilter()},
                // ----------------------------------------------------------

                // ----------------------------------------------------------
                // Different book price
                // ----------------------------------------------------------
                'filter.different_price': function(){ this.submitFilter()},
                // ----------------------------------------------------------

                // ----------------------------------------------------------
                // Loan statuses
                // ----------------------------------------------------------
                'filter.loan_undecided': function(){ this.submitFilter()},
                'filter.loan_expected' : function(){ this.submitFilter()},
                'filter.loan_confirmed': function(){ this.submitFilter()},
                'filter.loan_applied'  : function(){ this.submitFilter()},
                'filter.loan_completed': function(){ this.submitFilter()},
                // ----------------------------------------------------------
            },
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Methods
            // --------------------------------------------------------------
            methods: {
                // ----------------------------------------------------------
                // Apply the filter and move to the destination route
                // ----------------------------------------------------------
                applyFilter: function( e, resetPage ){
                    // ------------------------------------------------------
                    filter = this.filter;
                    resetPage = resetPage || false;
                    // ------------------------------------------------------
                    var query = {};
                    var $route = this.$route;
                    // ------------------------------------------------------
                    if( resetPage ) filter.page = 1;
                    // ------------------------------------------------------

                    // ------------------------------------------------------
                    // Rank filter
                    // ------------------------------------------------------
                    if( filter.rank ){
                        var found = io.find( this.preset.tabs, { id: filter.rank });
                        if( found ) query.rank = filter.rank;
                    }
                    // ------------------------------------------------------
                    // Pagination
                    // ------------------------------------------------------
                    if( filter.page ) query.page = io.parseInt( filter.page );
                    // ------------------------------------------------------

                    // ------------------------------------------------------
                    // Property property filters
                    // ------------------------------------------------------
                    if( filter.min ) query.min = io.parseInt( filter.min );
                    if( filter.max ) query.max = io.parseInt( filter.max );
                    if( filter.title ) query.title = filter.title;
                    if( filter.organizer ) query.organizer = io.parseInt( filter.organizer );
                    // ------------------------------------------------------

                    // ------------------------------------------------------
                    // Contract payment filters
                    // ------------------------------------------------------
                    if( filter.payment_year ) query.payment_year = io.parseInt( filter.payment_year );
                    if( filter.payment_month ) query.payment_month = io.parseInt( filter.payment_month );
                    if( filter.payment_period ) query.payment_period = io.parseInt( filter.payment_period );
                    // ------------------------------------------------------

                    // ------------------------------------------------------
                    // Sale contract payment filters
                    // ------------------------------------------------------
                    if( filter.sale_payment_year ) query.sale_payment_year = io.parseInt( filter.sale_payment_year );
                    if( filter.sale_payment_month ) query.sale_payment_month = io.parseInt( filter.sale_payment_month );
                    if( filter.sale_payment_period ) query.sale_payment_period = io.parseInt( filter.sale_payment_period );
                    // ------------------------------------------------------

                    // ------------------------------------------------------
                    // Contract filters
                    // ------------------------------------------------------
                    if( filter.contract_year ) query.contract_year = io.parseInt( filter.contract_year );
                    if( filter.contract_month ) query.contract_month = io.parseInt( filter.contract_month );
                    // ------------------------------------------------------

                    // ------------------------------------------------------
                    // Sale contract filters
                    // ------------------------------------------------------
                    if( filter.sale_contract_year ) query.sale_contract_year = io.parseInt( filter.sale_contract_year );
                    if( filter.sale_contract_month ) query.sale_contract_month = io.parseInt( filter.sale_contract_month );
                    // ------------------------------------------------------

                    // ------------------------------------------------------
                    // Loan period filters
                    // ------------------------------------------------------
                    if( filter.loan_year ) query.loan_year = io.parseInt( filter.loan_year );
                    if( filter.loan_month ) query.loan_month = io.parseInt( filter.loan_month );
                    // ------------------------------------------------------

                    // ------------------------------------------------------
                    // Empty contract and different-book-price flag filters
                    // ------------------------------------------------------
                    if( filter.empty_contract ) query.empty_contract = !!filter.empty_contract;
                    if( filter.different_price ) query.different_price = !!filter.different_price;
                    // ------------------------------------------------------

                    // ------------------------------------------------------
                    // Loan status filters
                    // ------------------------------------------------------
                    if( filter.loan_undecided ) query.loan_undecided = !!filter.loan_undecided;
                    if( filter.loan_expected ) query.loan_expected = !!filter.loan_expected;
                    if( filter.loan_confirmed ) query.loan_confirmed = !!filter.loan_confirmed;
                    if( filter.loan_applied ) query.loan_applied = !!filter.loan_applied;
                    if( filter.loan_completed ) query.loan_completed = !!filter.loan_completed;
                    // ------------------------------------------------------

                    // ------------------------------------------------------
                    // console.log( filter );
                    var callback = function(){};
                    this.$router.push({ name: 'index', query: query }, callback );
                    // ------------------------------------------------------
                },
                // ----------------------------------------------------------

                // ----------------------------------------------------------
                // Apply filter on enter at the filter inputs
                // ----------------------------------------------------------
                submitFilter: function(e){ this.applyFilter( e, true )},
                // ----------------------------------------------------------

                // ----------------------------------------------------------
                // Filter section-rank by the filter tabs
                // ----------------------------------------------------------
                filterRank: function( filter ){
                    this.filter.rank = filter.id;
                    this.applyFilter();
                },
                // ----------------------------------------------------------

                // ----------------------------------------------------------
                // Reset all filter
                // ----------------------------------------------------------
                resetFilter: function(){
                    // ------------------------------------------------------
                    var vm = this;
                    vm.filter = io.clone( defaultFilter );
                    // ------------------------------------------------------
                    $.each( vm.filter, function( index, filter ){ Vue.set( vm.filter, index, null )});
                    $.each( vm.check, function( index, check ){ Vue.set( vm.check, index, null )});
                    // ------------------------------------------------------
                    this.applyFilter();
                    // ------------------------------------------------------
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
                // Enable ID query group
                // ----------------------------------------------------------
                enableID: function(){
                    var vm = this; 
                    if( !vm.check.id ){
                        vm.check.id = true;
                        setTimeout( function(){ 
                            $( vm.$refs.min ).find('.form-control').focus();
                        });
                    }
                },
                // ----------------------------------------------------------

                // ----------------------------------------------------------
                // Enable title search filter
                // ----------------------------------------------------------
                enableTitle: function(){
                    var vm = this; 
                    if( !vm.check.title ){
                        vm.check.title = true;
                        setTimeout( function(){ 
                            $( vm.$refs.title ).find('.form-control').focus();
                        });
                    }
                },
                // ----------------------------------------------------------

                // ----------------------------------------------------------
                // Enable organizer filter
                // ----------------------------------------------------------
                enableOrganizer: function(){
                    if( !this.check.organizer ) this.check.organizer = true;
                },
                // ----------------------------------------------------------

                // ----------------------------------------------------------
                // Enable contract payment filter
                // ----------------------------------------------------------
                enablePayment: function(){
                    var vm = this; 
                    if( !vm.check.payment ){
                        vm.check.payment = true;
                        setTimeout( function(){ 
                            $( vm.$refs.payment ).find('.form-control:first').focus();
                        });
                    }
                },
                // ----------------------------------------------------------

                // ----------------------------------------------------------
                // Enable contract payment period filter
                // ----------------------------------------------------------
                enablePaymentPeriod: function(){
                    var vm = this; 
                    if( !vm.check.paymentPeriod ){
                        vm.check.paymentPeriod = true;
                        setTimeout( function(){ 
                            $( vm.$refs.paymentPeriod ).find('.form-control:first').focus();
                        });
                    }
                },
                // ----------------------------------------------------------

                // ----------------------------------------------------------
                // Enable sale contract payment filter
                // ----------------------------------------------------------
                enableSalePayment: function(){
                    var vm = this; 
                    if( !vm.check.salePayment ){
                        vm.check.salePayment = true;
                        setTimeout( function(){ 
                            $( vm.$refs.salePayment ).find('.form-control:first').focus();
                        });
                    }
                },
                // ----------------------------------------------------------

                // ----------------------------------------------------------
                // Enable sale contract payment period filter
                // ----------------------------------------------------------
                enableSalePaymentPeriod: function(){
                    var vm = this; 
                    if( !vm.check.salePaymentPeriod ){
                        vm.check.salePaymentPeriod = true;
                        setTimeout( function(){ 
                            $( vm.$refs.salePaymentPeriod ).find('.form-control:first').focus();
                        });
                    }
                },
                // ----------------------------------------------------------

                // ----------------------------------------------------------
                // Enable contract filter
                // ----------------------------------------------------------
                enableContract: function(){
                    var vm = this; 
                    // console.log( vm.check );
                    if( !vm.check.contract ){
                        vm.check.contract = true;
                        setTimeout( function(){ 
                            $( vm.$refs.contract ).find('.form-control:first').focus();
                        });
                    }
                },
                // ----------------------------------------------------------

                // ----------------------------------------------------------
                // Enable sale contract filter
                // ----------------------------------------------------------
                enableSaleContract: function(){
                    var vm = this; 
                    if( !vm.check.saleContract ){
                        vm.check.saleContract = true;
                        setTimeout( function(){ 
                            $( vm.$refs.saleContract ).find('.form-control:first').focus();
                        });
                    }
                },
                // ----------------------------------------------------------

                // ----------------------------------------------------------
                // Enable loan filter
                // ----------------------------------------------------------
                enableLoan: function(){
                    var vm = this; 
                    if( !vm.check.loan ){
                        vm.check.loan = true;
                        setTimeout( function(){ 
                            $( vm.$refs.loan ).find('.form-control:first').focus();
                        });
                    }
                },
                // ----------------------------------------------------------
            }
            // --------------------------------------------------------------
        };
        // ------------------------------------------------------------------
    }( jQuery, _, document, window ));
@endminify </script>
@endpush
