@extends('backend._base.content_project')

@section('preloader')
    <transition name="preloader">
        <div class="preloader preloader-fullscreen d-flex justify-content-center align-items-center" v-if="!status.mounted">
            <div class="sk-folding-cube">
                <div class="sk-cube1 sk-cube"></div>
                <div class="sk-cube2 sk-cube"></div>
                <div class="sk-cube4 sk-cube"></div>
                <div class="sk-cube3 sk-cube"></div>
            </div>
        </div>
    </transition>
@endsection

@php
    $component = (object) array(
        'preset' => 'backend._components.preset'
    );
@endphp

@section('breadcrumbs')
<ol class="breadcrumb float-sm-right">
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="fas fa-tachometer-alt mr-1"></i> @lang('label.dashboard')</a></li>
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">仕入</a></li>
    <li class="breadcrumb-item"><a href="{{ route('project.purchase.contract', $project->id) }}">仕入契約</a></li>
    <li class="breadcrumb-item active">{{ $page_title }}</li>
</ol>
@endsection

@section('content')
    <form method="POST" data-parsley class="parsley-minimal" v-on:keydown.enter.prevent ref="form"> @csrf
        <div class="cards-purchase">
            <div class="card card-project" style="border-color:#191970; min-width:1100px;">
                <div class="card-header" style="color:#fff; background:#191970;">@lang('project_purchase_contract.purchase')No. {{ $target->purchase_number }}</div>
                <div class="card-body">
                    @relativeInclude('form.purchase-information')
                </div>
            </div>
        </div>

        <div class="purchase-input">
            <div class="tabs">
                <ul class="nav nav-tabs  nav-tabs-parent mt-2 nav-fill" role="tablist">
                    <li class="nav-item dropdown">

                        <!-- Contract tab - Start -->
                        <a class="nav-link active" id="home-tab" data-toggle="tab" href="#tab-contract"
                            role="tab" aria-controls="home" aria-selected="true">
                            <span>契約書</span>
                        </a>
                        <!-- Contract tab - End -->

                    </li>
                    <li class="nav-item dropdown">

                        <!-- Important note tab - Start -->
                        <a class="nav-link " id="home-tab" data-toggle="tab" href="#tab-note"
                            role="tab" aria-controls="home" aria-selected="false">
                            <span>重要事項説明書</span>
                        </a>
                        <!-- Important note tab - End -->
                    </li>
                </ul>
            </div>
            <div class="tab-content" id="all_tabs">

                <!-- Contract panel - Start -->
                @relativeInclude('form.contract')
                <!-- Contract panel - Start -->

                <!-- Important note panel - Start -->
                @relativeInclude('form.important-note')
                <!-- Important note panel - End -->

            </div>

            <!-- Form controls - Start -->
            @relativeInclude('form.controls')
            <!-- Form controls - End -->

            <!-- Project approval form - Start -->
            <inspection v-if="inspection" v-model="inspection" :project="project" :form="$refs.form" :queue="queue"
                :disabled="status.loading" :user="user">
            </inspection>
            <!-- Project approval form - End -->

        </div>
    </form>
@endsection

@push('scripts')
    <script src="{{ asset('/components/js-file-downloader/dist/js-file-downloader.min.js') }}"></script>
@endpush

@push('vue-scripts')

@relativeInclude('vue.contract.real-estate.import')
@relativeInclude('vue.contract.real-estate.building.import')
@relativeInclude('vue.contract.property.import')
@relativeInclude('vue.contract.road.import')

@relativeInclude('vue.contract.group.import')
@relativeInclude('vue.contract.group.area.import')
@relativeInclude('vue.contract.group.boundary.import')
@relativeInclude('vue.contract.group.registration.import')
@relativeInclude('vue.contract.group.delivery.import')
@relativeInclude('vue.contract.group.penalty.import')
@relativeInclude('vue.contract.group.finance.import')
@relativeInclude('vue.contract.group.stamp.import')
@relativeInclude('vue.contract.group.confirmation.import')
@relativeInclude('vue.contract.group.remark.import')
@relativeInclude('vue.important-note.real-estate.import')

@relativeInclude('vue.important-note.seller-and-occupancy.import')

@relativeInclude('vue.important-note.directly-related.import')
@relativeInclude('vue.important-note.directly-related.building.import')
@relativeInclude('vue.important-note.directly-related.building-standard-restriction.import')
@relativeInclude('vue.important-note.directly-related.city-planning-restriction.import')
@relativeInclude('vue.important-note.directly-related.land.import')
@relativeInclude('vue.important-note.directly-related.law-restriction.import')
@relativeInclude('vue.important-note.directly-related.site-and-road.import')

@relativeInclude('vue.important-note.infrastructure.import')
@relativeInclude('vue.important-note.infrastructure.drainage-remark.import')
@relativeInclude('vue.important-note.infrastructure.drinking-water.import')
@relativeInclude('vue.important-note.infrastructure.earth-and-sand.import')
@relativeInclude('vue.important-note.infrastructure.electrical.import')
@relativeInclude('vue.important-note.infrastructure.gas.import')
@relativeInclude('vue.important-note.infrastructure.infrastructure-remark.import')
@relativeInclude('vue.important-note.infrastructure.maintenance.import')
@relativeInclude('vue.important-note.infrastructure.miscellaneous-water.import')
@relativeInclude('vue.important-note.infrastructure.performance-evaluation.import')
@relativeInclude('vue.important-note.infrastructure.rain-water.import')
@relativeInclude('vue.important-note.infrastructure.seismic-diagnosis.import')
@relativeInclude('vue.important-note.infrastructure.sewage.import')
@relativeInclude('vue.important-note.infrastructure.shape-structure.import')
@relativeInclude('vue.important-note.infrastructure.survey-status.import')
@relativeInclude('vue.important-note.infrastructure.use-asbestos.import')

@relativeInclude('vue.important-note.transaction.import')
@relativeInclude('vue.important-note.other.import')

@relativeInclude('vue.inspection.import')

<script>
( function( $, io, window, document, undefined ){
    // ----------------------------------------------------------------------
    // Toast default options
    // ----------------------------------------------------------------------
    var toast = {
        heading: '@lang('label.error')', icon: 'error',
        stack: false, hideAfter: 3000,
        position: { right: 18, top: 68 }
    };
    // ----------------------------------------------------------------------


    // ----------------------------------------------------------------------
    // Factory template
    // ----------------------------------------------------------------------
    var template = {
        create: @json( $new->create ),
        building: @json( $new->building )
    };
    // ----------------------------------------------------------------------


    // ----------------------------------------------------------------------
    // Request promises
    // ----------------------------------------------------------------------
    var queue = { save: null, scrollTop: false };
    // ----------------------------------------------------------------------


    // ----------------------------------------------------------------------
    mixin = {
        /*
        ## ------------------------------------------------------------------
        ## VUE DATA
        ## vue data binding, difine a properties
        ## ------------------------------------------------------------------
        */
        data: function(){
            // --------------------------------------------------------------
            // Initial data
            // --------------------------------------------------------------
            var data = {
                status: {
                    loading: false, submited: false, mounted: false,
                    approval: { loading: false },
                    approvalRequest: { loading: false },
                    report: {
                        contract: { loading: false },
                        notes: { loading: false }
                    }
                },
                user: {
                    role: @json( $user->user_role, JSON_NUMERIC_CHECK )
                },
                users: @json( $users ),
                target: @json( $target ),
                project: @json( $project ),
                master_value: @json( $master_value ),
                parcel_city: @json( $parcel_city ),
                residentials: @json( $residentials ),
                roads: @json( $roads ),
                contractors_and_owners_name: @json( $contractors_and_owners_name ),
                companies: @json( $companies ),
                inspection: @json( $inspection, JSON_NUMERIC_CHECK ),
                queue: queue,
            }
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Append entry data from contract-create
            // --------------------------------------------------------------
            data.entry = io.get( data.target, 'contract.create' );
            if( !data.entry ) data.entry = io.clone( template.create );
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Append building data
            // --------------------------------------------------------------
            data.buildings = io.get( data.project, 'property.buildings' );
            if( data.buildings ) $.each( data.buildings, function( index, building ){
                if( !building.product_building ){
                    // ------------------------------------------------------
                    var product = io.clone( template.building );
                    product.pj_lot_building_a_id = building.id;
                    // ------------------------------------------------------
                    building.product_building = product;
                    // ------------------------------------------------------
                }
            });
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Parse datetime format for the datepicker
            // --------------------------------------------------------------
            var fields = [
                'c_article23_create_date',
                'c_article15_loan_release_date_contract',
            ];
            // --------------------------------------------------------------
            $.each( fields, function( f, field ){
                var prop = io.get( data.entry, field );
                if( prop ) data.entry[field] = moment( prop ).format('YYYY/MM/DD');
            })
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            data.contract = io.get( data.target, 'contract' );
            data.purchase_contract_mediations = io.get( data.contract, 'purchase_contract_mediations' );
            data.purchase_sale = io.get( data.project, 'purchase_sale' );
            data.contractors_name = io.get( data.contractors_and_owners_name, 'contractors_name' );
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // console.log( data.project );
            return data; // Return data
            // --------------------------------------------------------------
        },

        /*
        ## ------------------------------------------------------------------
        ## VUE MOUNTED
        ## vue on mounted state
        ## ------------------------------------------------------------------
        */
        mounted: function(){
            // --------------------------------------------------------------
            refreshParsley(); // Refresh parsley form validation
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            this.status.mounted = true;
            $(document).trigger( 'vue-loaded', this ); // Triger event on loaded
            // --------------------------------------------------------------
        },

        /*
        ## ------------------------------------------------------------------
        ## VUE COMPUTED
        ## define a property with custom logic
        ## ------------------------------------------------------------------
        */
        computed: {
            // --------------------------------------------------------------
            // Find if inspection request button is available
            // --------------------------------------------------------------
            isInspectionDisabled: function(){
                return this.project.approval_request && this.inspection;
            }
            // --------------------------------------------------------------
        },

        /*
        ## ------------------------------------------------------------------
        ## VUE WATCH
        ## vue reactive data watch
        ## ------------------------------------------------------------------
        */
        watch: {

        },

        /*
        ## ------------------------------------------------------------------
        ## VUE METHOD
        ## function associated with the vue instance
        ## ------------------------------------------------------------------
        */
        methods: {
            // --------------------------------------------------------------
            // Submit the form
            // --------------------------------------------------------------
            submit: function(){
                // ----------------------------------------------------------
                var $form = $('form[data-parsley]');
                // ----------------------------------------------------------
                queue.scrollTop = true; // Enable scroll-top
                $form.submit();
                // ----------------------------------------------------------
            },
            // --------------------------------------------------------------


            // --------------------------------------------------------------
            // Request project inspection
            // --------------------------------------------------------------
            requestInspection: function(){
                // ----------------------------------------------------------
                // Ask for confirmation
                // ----------------------------------------------------------
                var confirmed = confirm('本当にリクエストしますか？');
                if( !confirmed ) return;
                // ----------------------------------------------------------

                // ----------------------------------------------------------
                var vm = this;
                $( vm.$refs.form ).submit();
                // ----------------------------------------------------------

                @php
                    $route = array( 'project' => $project->id, 'type' => 3 );
                    $url = route( 'project.inspection.request', $route );
                @endphp

                // ----------------------------------------------------------
                // Catch the form submit promise
                // ----------------------------------------------------------
                setTimeout( function(){
                    if( queue && queue.save ) queue.save.then( function( response ){
                        if( vm.project ){
                            // ----------------------------------------------
                            vm.status.approvalRequest.loading = true;
                            // ----------------------------------------------
                            var url = '{{ $url }}';
                            var data = { project: vm.project.id, context: {{ $target->id }} };
                            var request = axios.post( url, data );
                            // ----------------------------------------------

                            // ----------------------------------------------
                            // Receive the request response
                            // ----------------------------------------------
                            request.then( function( response ){
                                // ------------------------------------------
                                var option = $.extend( {}, toast );
                                var status = io.get( response, 'data.status' );
                                var project = io.get( response, 'data.project' );
                                var inspection = io.get( response, 'data.inspection' );
                                // ------------------------------------------

                                // ------------------------------------------
                                if( 'success' == status ){
                                    // --------------------------------------
                                    option.icon = 'success';
                                    option.heading = '@lang('label.success')';
                                    option.text = '{{ __('label.approval_requested') }}';
                                    // --------------------------------------

                                    // --------------------------------------
                                    // Update root project data
                                    // --------------------------------------
                                    if( project ) io.assign( vm.project, project );
                                    // --------------------------------------

                                    // --------------------------------------
                                    // Update the inspection data
                                    // --------------------------------------
                                    vm.inspection = null;
                                    vm.$nextTick( function(){ vm.inspection = inspection });
                                    // --------------------------------------
                                }
                                // ------------------------------------------
                                else option.text = '{{ __('label.approval_failed') }}';
                                // ------------------------------------------

                                // ------------------------------------------
                                $.toast( option );
                                // ------------------------------------------
                            });
                            // ----------------------------------------------

                            // ----------------------------------------------
                            request.catch( function(){
                                var option = $.extend( {}, toast, {
                                    text: '{{ __('label.approval_failed') }}'
                                }); $.toast( option );
                            });
                            // ----------------------------------------------
                            request.finally( function(){ vm.status.approvalRequest.loading = false });
                            // ----------------------------------------------
                        }
                    });
                });
                // ----------------------------------------------------------
            },
            // --------------------------------------------------------------


            // --------------------------------------------------------------
            // Request and generate report file
            // --------------------------------------------------------------
            generateReport: function( report ){
                // ------------------------------------------------------
                var vm = this;
                var $form = $( vm.$refs.form );
                $form.submit();
                // ------------------------------------------------------

                // ------------------------------------------------------
                @php // Generate report route
                    $route = array( 'project' => $project->id, 'target' => $target->id );
                    $report = route( 'project.purchase.create.report', $route );
                @endphp
                // ------------------------------------------------------

                // ------------------------------------------------------
                var target = this.target;
                var project = this.project;
                // ------------------------------------------------------

                // ------------------------------------------------------
                // Catch the form submit promise
                // ------------------------------------------------------
                setTimeout( function(){
                    if( queue && queue.save ) queue.save.then( function( response ){
                        if( project && project.id && target && target.id ){
                            // ------------------------------------------

                            // ------------------------------------------
                            // Download on progress notification
                            // ------------------------------------------
                            var option = io.clone( toast );
                            option.icon = 'success';
                            option.hideAfter = false;
                            option.heading = '@lang('label.success')';
                            // ------------------------------------------
                            if( 'contract' === report ) option.text = '契約書を出力中です、しばらくお待ちください。';
                            else if( 'notes' === report ) option.text = '重要事項説明書を出力中です、しばらくお待ちください。';
                            // ------------------------------------------
                            var alert = $.toast( option );
                            // ------------------------------------------


                            // ------------------------------------------
                            vm.status.loading = true;
                            vm.status.report[report].loading = true;
                            // ------------------------------------------
                            var url = '{{ $report }}/' +report;
                            var data = { project: project.id, target: target.id, data: vm.entry };
                            var request = axios.post( url, data );
                            // ------------------------------------------

                            // ------------------------------------------
                            // Receive the request response
                            // ------------------------------------------
                            request.then( function( response ){
                                // console.log( response );
                                if( response.data && response.data.status ){
                                    // ----------------------------------
                                    var data = response.data;
                                    var status = io.get( response, 'data.status');
                                    // ----------------------------------

                                    // ----------------------------------
                                    if( 'success' == status ){
                                        // ------------------------------
                                        // Download the file
                                        // ------------------------------
                                        if( data.report && data.report.length ){
                                            // --------------------------
                                            var download = new jsFileDownloader({ url: data.report });
                                            download.then( function(){
                                                setTimeout( function(){ alert.reset()}, 1000 );
                                            });
                                            // --------------------------
                                        }
                                        // ------------------------------

                                        // ------------------------------
                                        // Download the file
                                        // ------------------------------
                                        // var reportFile = io.get( response, 'data.report' );
                                        // if( reportFile ){
                                        //     // --------------------------
                                        //     $.fileDownload( reportFile );
                                        //     // --------------------------

                                        //     // --------------------------
                                        //     // Close the alert 1 second after download finished
                                        //     // --------------------------
                                        //     setTimeout( function(){ alert.reset()}, 1000 );
                                        //     // --------------------------
                                        // }
                                    }
                                    // ----------------------------------

                                    // ----------------------------------
                                    vm.status.report[report].loading = false;
                                    // ----------------------------------
                                }
                            });
                            // ------------------------------------------

                            // ------------------------------------------
                            request.catch( function(){
                                // --------------------------------------
                                var option = $.extend( {}, toast, {
                                    text: '{{ __('label.report_failed') }}'
                                }); $.toast( option );
                                // --------------------------------------
                                vm.status.report[report].loading = false;
                                // --------------------------------------
                            });
                            // ------------------------------------------
                            request.finally( function(){ vm.status.loading = false });
                            // ------------------------------------------
                        }
                    });
                });
                // ------------------------------------------------------
            },
            // --------------------------------------------------------------
        }
    }
    // ----------------------------------------------------------------------


    /*
    ## ----------------------------------------------------------------------
    ## VUE LOADED EVENT
    ## Handle submit data and form validation
    ## ----------------------------------------------------------------------
    */
    $(document).on('vue-loaded', function( event, vm ){
        // init parsley form validation
        // ------------------------------------------------------------------
        let $form = $('form[data-parsley]');
        let form = $form.parsley();
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // On form submit
        // ------------------------------------------------------------------
        form.on( 'form:validated', function(){
            // --------------------------------------------------------------
            // On form invalid
            // --------------------------------------------------------------
            let valid = form.isValid();
            if( !valid ) setTimeout( function(){
                // ----------------------------------------------------------
                var $error = $('.parsley-errors-list.filled').first();
                // ----------------------------------------------------------

                // ----------------------------------------------------------
                // If error is inside a collapsible, open it
                // ----------------------------------------------------------
                if( $error.closest('.collapsible').length ){
                    var $collapsible = $error.closest('.collapsible');
                    $collapsible.find('.btn-accordion.collapsed').trigger('click');
                }
                // ----------------------------------------------------------

                // ----------------------------------------------------------
                // If error is inside a tab panel, activate the tab
                // ----------------------------------------------------------
                if( $error.closest('.tab-pane').length ){
                    // ------------------------------------------------------
                    var $panel = $error.closest('.tab-pane');
                    var tabID = $panel.attr('id');
                    var $tabs = $panel.parent().siblings('.tabs');
                    // ------------------------------------------------------
                    $tabs.find('li a[href="#' +tabID+ '"]').tab('show');
                    // ------------------------------------------------------
                }
                // ----------------------------------------------------------

                // ----------------------------------------------------------
                // Scroll to the error
                // ----------------------------------------------------------
                setTimeout( function(){
                    $(window).scrollTo( $error, 600, {
                        offset: -200,
                        easing: 'easeOutQuart'
                    });
                }, 100 );
                // ----------------------------------------------------------

                // ----------------------------------------------------------
                return false;
                // ----------------------------------------------------------
            });
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // On form valid
            // --------------------------------------------------------------
            else {
                // ----------------------------------------------------------
                vm.status.loading = true;
                // ----------------------------------------------------------

                // ----------------------------------------------------------
                var data = {
                    entry: vm.entry, project: vm.project,
                    purchase_contract_mediations: vm.purchase_contract_mediations,
                    buildings: vm.buildings
                };
                var url = '{{ url()->current() }}';
                var option = $.extend({}, toast );
                // ----------------------------------------------------------

                // ----------------------------------------------------------
                // console.log( data ); return;
                queue.save = axios.post( url, data ); // Do the request
                // ----------------------------------------------------------

                // ----------------------------------------------------------
                // Handle success response
                // ----------------------------------------------------------
                queue.save.then( function( response ){
                    // ------------------------------------------------------
                    // console.log( response );
                    if( response.data ){
                        // --------------------------------------------------
                        vm.status.loading = false;
                        // --------------------------------------------------

                        // --------------------------------------------------
                        // Update the Vue data with server return
                        // --------------------------------------------------
                        var updates = io.get( response, 'data.updates' );
                        if( updates ) io.assign( vm.entry, updates );
                        // --------------------------------------------------

                        // --------------------------------------------------
                        var buildings = io.get( response, 'data.project.property.buildings' );
                        if( buildings ) vm.buildings = buildings;
                        // --------------------------------------------------
                    }
                    // ------------------------------------------------------
                    var alert = io.get( response, 'data.alert' );
                    $.toast( $.extend( option, alert ));
                    // ------------------------------------------------------

                    // ------------------------------------------------------
                    // Scroll to top page
                    // ------------------------------------------------------
                    if( queue.scrollTop ) scrollTop();
                    // ------------------------------------------------------
                });
                // ----------------------------------------------------------

                // ----------------------------------------------------------
                // Handle error response
                // ----------------------------------------------------------
                queue.save.catch( function(e){ console.log(e);
                    $.toast( option );
                });
                // ----------------------------------------------------------
                queue.save.finally( function(){ vm.status.loading = false });
                // ----------------------------------------------------------

                // ----------------------------------------------------------
                return false;
                // ----------------------------------------------------------
            }
            // --------------------------------------------------------------
        }).on('form:submit', function(){ return false });
    });
    // ----------------------------------------------------------------------


    // ----------------------------------------------------------------------
    // Custom function refresh validator
    // ----------------------------------------------------------------------
    var refreshParsley = function(){
        Vue.nextTick(function () {
            var $form = $('form[data-parsley]');
            $form.parsley().refresh();
        });
    }
    // ----------------------------------------------------------------------
    var resetParsley = function(){
        Vue.nextTick(function () {
            var $form = $('form[data-parsley]');
            $form.parsley().reset();
        });
    }
    // ----------------------------------------------------------------------
    var refreshTooltip = function(){
        $('[data-toggle="tooltip"]').tooltip('hide');
        Vue.nextTick(function () {
            $('[data-toggle="tooltip"]').tooltip()
        });
    }
    // ----------------------------------------------------------------------


    // ----------------------------------------------------------------------
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
                    $result = $group.next( formResult );
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

}( jQuery, _, window, document ));
</script>
@endpush
