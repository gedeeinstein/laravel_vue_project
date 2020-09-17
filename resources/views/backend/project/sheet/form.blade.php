@php
    $route = array( 'project' => $project->id );
    $updateURL = route( 'project.sheet.update', $route );
    $include = (object) array(
        'basic' => 'backend.project.sheet.basic',
        'sheet' => 'backend.project.sheet.sheet',
        'approval' => 'backend.project.sheet.approval'
    );
    $lang = (object) array(
        'delete' => __('project.manager.form.phrase.delete.confirm')
    );

    $components = "backend.project.sheet.components";
    $component = (object) array(
        'basic' => "{$components}.basic",
        'common' => "{$components}.common",
        'sheet' => "{$components}.sheet",
        'preset' => 'backend._components.preset'
    );

@endphp

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

@section('content')
    <form action="{{ $form_action }}" method="POST" data-parsley class="parsley-minimal" v-on:keydown.enter.prevent ref="form"> @csrf

        <!-- Basic Project Properties and Q&A - Start -->
        @include("{$include->basic}.main")
        <!-- Basic Project Properties and Q&A - End -->

        <!-- Project Sheet Tab - Start -->
        @include("{$include->sheet}.main")
        <!-- Project Sheet Tab - End -->

        <!-- Project approval form - Start -->
        <inspection v-if="inspection" v-model="inspection" :project="project" :form="$refs.form" :queue="queue" 
            :disabled="status.loading" :user="user">
        </inspection>
        <!-- Project approval form - End -->

        <router-view></router-view>

    </form>
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

@push('scripts')
    <script src="{{ asset('/components/js-file-downloader/dist/js-file-downloader.min.js') }}"></script>
@endpush

@push('vue-scripts')
@relativeInclude('vue.project.additional-question.import')

@relativeInclude('vue.project.sheet.expense.import')
@relativeInclude('vue.project.sheet.expense.purchase.import')
@relativeInclude('vue.project.sheet.expense.registration.import')
@relativeInclude('vue.project.sheet.expense.finance.import')
@relativeInclude('vue.project.sheet.expense.tax.import')
@relativeInclude('vue.project.sheet.expense.construction.import')
@relativeInclude('vue.project.sheet.expense.survey.import')
@relativeInclude('vue.project.sheet.expense.other.import')
@relativeInclude('vue.project.sheet.expense.additional.import')

@relativeInclude('vue.project.sheet.sale.import')
@relativeInclude('vue.project.sheet.sale.calculator.import')
@relativeInclude('vue.project.sheet.sale.plan.tab.import')
@relativeInclude('vue.project.sheet.sale.plan.entry.import')
@relativeInclude('vue.project.sheet.sale.section.import')

@relativeInclude('vue.project.inspection.import')

<script>
    (function( $, io, document, window, undefined ){
        // ------------------------------------------------------------------
        var refreshParsley = function(){
            setTimeout( function(){
                var $form = $('form[data-parsley]');
                $form.parsley().refresh();
            });
        }
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // New empty data template
        // ------------------------------------------------------------------
        var template = {
            sheet: @json( $new->sheet ),
            plan: @json( $new->plan ),
            section: @json( $new->section )
        };
        // ------------------------------------------------------------------


        // ------------------------------------------------------------------
        // Toast default options
        // ------------------------------------------------------------------
        var toast = {
            heading: '@lang('label.error')', icon: 'error',
            stack: false, hideAfter: 3000,
            position: { right: 18, top: 68 }
        };
        // ------------------------------------------------------------------


        // ------------------------------------------------------------------
        // Request promises
        // ------------------------------------------------------------------
        var queue = { save: null, scrollTop: false };
        // ------------------------------------------------------------------


        // ------------------------------------------------------------------
        // Vue router
        // ------------------------------------------------------------------
        router = {
            mode: 'history',
            routes: [{ 
                path: '/project/:project/sheet/:sheet', name: 'index', 
                component: { template: null }
            }]
        };
        // ------------------------------------------------------------------


        // ------------------------------------------------------------------
        mixin = {
            // --------------------------------------------------------------
            mounted: function(){
                var vm = this;
                // ----------------------------------------------------------
                // Set mounted status
                // ----------------------------------------------------------
                vm.status.mounted = true;
                // ----------------------------------------------------------

                // ----------------------------------------------------------
                if( !vm.sheets.length ){
                    this.createNewSheet();
                }
                // ----------------------------------------------------------

                // ----------------------------------------------------------
                // Refresh form validation
                // ----------------------------------------------------------
                refreshParsley();
                // ----------------------------------------------------------

                // ----------------------------------------------------------
                // Trigger custom vue loaded event for jQuery
                // and other plugins to listen to
                // ----------------------------------------------------------
                $(document).trigger( 'vue-loaded', this );
                // ----------------------------------------------------------
            },
            // --------------------------------------------------------------
            // Reactive data tree
            // --------------------------------------------------------------
            data: function(){
                // ----------------------------------------------------------
                // Base data
                // ----------------------------------------------------------
                var data = {
                    status: {
                        loading: false, mounted: false,
                        // --------------------------------------------------
                        approval: { loading: false },
                        approvalRequest: { loading: false },
                        // --------------------------------------------------
                        report: {
                            sheet: { loading: false },
                            checklist: { loading: false },
                        }
                        // --------------------------------------------------
                    },
                    config: {
                        sheetKey: 1,
                        date: { format: 'YYYY/MM/DD' },
                        created: { format: 'YYYY年MM月DD日 HH:mm' },
                        currency: {
                            negative: false,
                            precision: { min: 0, max: 4 }
                        }
                    },
                    preset: {
                        contract: @json( $contract ),
                        loanMoney: @json( $loanMoney ),
                        loanRatio: @json( $loanRatio ),
                        sheetValues: @json( $sheetValues, JSON_NUMERIC_CHECK )
                    },
                    user: {
                        role: @json( $user->user_role, JSON_NUMERIC_CHECK )
                    },
                    project: @json( $project, JSON_NUMERIC_CHECK ),
                    additional: @json( $additional, JSON_NUMERIC_CHECK ),
                    inspection: @json( $inspection, JSON_NUMERIC_CHECK ),
                    queue: queue,
                    sheets: []
                };
                // ----------------------------------------------------------
                

                // ----------------------------------------------------------
                // Default relecting-to-budget sheet index
                // ----------------------------------------------------------
                data.reflectedSheet = null;
                // ----------------------------------------------------------


                // ----------------------------------------------------------
                // Sheets
                // ----------------------------------------------------------
                var sheets = @json( $sheets, JSON_NUMERIC_CHECK );
                if( sheets.length ) $.each( sheets, function( sheetIndex, sheet ){
                    // ------------------------------------------------------
                    // Activate the first sheet
                    // ------------------------------------------------------
                    sheet.active = false;
                    if( !sheetIndex ) sheet.active = true;
                    // ------------------------------------------------------


                    // ------------------------------------------------------
                    // If this sheet is reflecting-to-budget, save the index
                    // ------------------------------------------------------
                    if( sheet.is_reflecting_in_budget ){
                        data.reflectedSheet = sheetIndex;
                    }
                    // ------------------------------------------------------

                    // ------------------------------------------------------
                    // Activate the first sale-plan
                    // ------------------------------------------------------
                    var plans = io.get( sheet, 'sale.plans' );
                    if( plans && plans.length ) $.each( plans, function( planIndex, plan ){
                        // --------------------------------------------------
                        plan.active = false;
                        if( !planIndex ) plan.active = true; // Activate the first plan
                        // --------------------------------------------------

                        // --------------------------------------------------
                        // If plan-section is empty, add one empty section
                        // --------------------------------------------------
                        if( !plan.sections ) plan.sections = [];
                        if( !plan.sections.length ){
                            var section = $.extend({}, template.section, {
                                pj_sale_plan_id: plan.id
                            });
                            plan.sections.push( section );
                        }
                        // --------------------------------------------------
                    });
                    // ------------------------------------------------------
                });
                // ----------------------------------------------------------
                // Append sheets to main reactive data
                // ----------------------------------------------------------
                data.sheets = sheets;
                // ----------------------------------------------------------

                // ----------------------------------------------------------
                console.log( data );
                return data;
                // ----------------------------------------------------------
            },
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Methods
            // --------------------------------------------------------------
            methods: {
                // ----------------------------------------------------------
                // Avtivate sheet panel
                // ----------------------------------------------------------
                activateSheet: function( sheet ){
                    // ------------------------------------------------------
                    var vm = this; 
                    // ------------------------------------------------------

                    // ------------------------------------------------------
                    // Router navigation
                    // ------------------------------------------------------
                    var params = { project: vm.project.id };
                    if( sheet.id ) params.sheet = sheet.id;
                    else params.sheet = 'draft-' + sheet.tab_index;
                    // ------------------------------------------------------
                    vm.$router.push({ name: 'index', params: params });
                    // ------------------------------------------------------
                },
                // ----------------------------------------------------------

                // ----------------------------------------------------------
                // Create new sheet
                // ----------------------------------------------------------
                createNewSheet: function(){
                    // ------------------------------------------------------
                    var vm = this;
                    var index = vm.sheets.length + 1;
                    var name = 'Sheet ' +index;
                    // ------------------------------------------------------

                    // ------------------------------------------------------
                    // Create new sheet object from template
                    // ------------------------------------------------------
                    var sheet = $.extend( true, {}, template.sheet, {
                        active: true, tab_index: index,
                        created_at: 'pending', creator_name: '{{ $user->full_name }}'
                    });
                    // ------------------------------------------------------
                    // Request server time based on server timezone
                    // ------------------------------------------------------
                    var url = '/api/server/time';
                    var request = axios.get( url ); // Do the request
                    // ------------------------------------------------------
                    request.then( function( response ){
                        if( response.data ) sheet.created_at = response.data;
                    });
                    // ------------------------------------------------------


                    // ------------------------------------------------------
                    // Generate duplicate free sheet name
                    // ------------------------------------------------------
                    var counter = 1;
                    var name = 'New Sheet ' + counter;
                    // ------------------------------------------------------
                    while( true ){
                        var found = io.find( vm.sheets, function(o){ return o.name == name });
                        if( found ){ counter++; name = 'New Sheet ' + counter }
                        else break;
                    }
                    // ------------------------------------------------------
                    sheet.name = name;
                    // ------------------------------------------------------

                    // ------------------------------------------------------
                    vm.sheets.push( sheet );
                    vm.activateSheet( sheet );
                    // ------------------------------------------------------
                },
                // ----------------------------------------------------------

                // ----------------------------------------------------------
                // Duplicate sheet and its content
                // ----------------------------------------------------------
                duplicateSheet: function( sheetIndex ){
                    // ------------------------------------------------------
                    var vm = this;
                    var reference = vm.sheets[ sheetIndex ];
                    // ------------------------------------------------------

                    // ------------------------------------------------------
                    if( reference ){
                        // --------------------------------------------------
                        var sheet = io.cloneDeep( reference );
                        if( sheet.id ) delete sheet.id;
                        // --------------------------------------------------
                        sheet.is_reflecting_in_budget = false;
                        sheet.name = sheet.name + ' Copy';
                        sheet.creator_name = '{{ $user->full_name }}';
                        sheet.tab_index = vm.sheets.length +1;
                        sheet.created_at = 'pending';
                        // --------------------------------------------------
                        if( sheet.updated_at ) delete sheet.updated_at;
                        // --------------------------------------------------

                        // --------------------------------------------------
                        // Checklist - Remove IDs and nullify foreign IDs
                        // --------------------------------------------------
                        if( sheet.checklist ){
                            // ----------------------------------------------
                            var checklist = sheet.checklist;
                            if( checklist.id ) delete checklist.id;
                            checklist.pj_sheet_id = null;
                            // ----------------------------------------------
                            if( checklist.created_at ) delete checklist.created_at;
                            if( checklist.updated_at ) delete checklist.updated_at;
                            // ----------------------------------------------
                        }
                        // --------------------------------------------------

                        // --------------------------------------------------
                        // Stock / Expense - Remove IDs and nullify foreign IDs
                        // --------------------------------------------------
                        if( sheet.stock ){
                            // ----------------------------------------------
                            var stock = sheet.stock;
                            if( stock.id ) delete stock.id;
                            stock.pj_sheet_id = null;
                            // ----------------------------------------------
                            if( stock.created_at ) delete stock.created_at;
                            if( stock.updated_at ) delete stock.updated_at;
                            // ----------------------------------------------
                            var properties = [
                                'procurements', 'registers', 'finances', 'taxes',
                                'constructions', 'surveys', 'others'
                            ];
                            // ----------------------------------------------

                            // ----------------------------------------------
                            $.each( stock, function( index, prop ){
                                if( io.indexOf( properties, prop ) >= 0 ){
                                    // --------------------------------------
                                    if( prop.id ) delete prop.id;
                                    prop.pj_stocking_id = null;
                                    prop.pj_stock_cost_parent_id = null;
                                    // --------------------------------------
                                    if( prop.created_at ) delete prop.created_at;
                                    if( prop.updated_at ) delete prop.updated_at;
                                    // --------------------------------------

                                    // --------------------------------------
                                    // Additional costs
                                    // --------------------------------------
                                    if( prop.additional ){
                                        var additional = prop.additional;
                                        // ----------------------------------
                                        if( additional.id ) delete additional.id;
                                        // ----------------------------------
                                        if( additional.created_at ) delete additional.created_at;
                                        if( additional.updated_at ) delete additional.updated_at;
                                        // ----------------------------------

                                        // ----------------------------------
                                        // Additional cost entries
                                        // ----------------------------------
                                        if( additional.entries && additional.entries.length ){
                                            $.each( additional.entries, function( e, entry ){
                                                // --------------------------
                                                if( entry.id ) delete entry.id;
                                                entry.pj_stock_cost_parent_id = null;
                                                // --------------------------
                                                if( entry.created_at ) delete entry.created_at;
                                                if( entry.updated_at ) delete entry.updated_at;
                                                // --------------------------
                                            });
                                        }
                                        // ----------------------------------
                                    }
                                    // --------------------------------------
                                }
                            });
                            // ----------------------------------------------
                        }
                        // --------------------------------------------------

                        // --------------------------------------------------
                        // Sale - Remove IDs and nullify foreign IDs
                        // --------------------------------------------------
                        if( sheet.sale ){
                            // ----------------------------------------------
                            var sale = sheet.sale;
                            if( sale.id ) delete sale.id;
                            sale.pj_sheet_id = null;
                            // ----------------------------------------------
                            if( sale.created_at ) delete sale.created_at;
                            if( sale.updated_at ) delete sale.updated_at;
                            // ----------------------------------------------

                            // ----------------------------------------------
                            // Sale calculator
                            // ----------------------------------------------
                            if( sale.calculators && sale.calculators.length ){
                                $.each( sale.calculators, function( c, calculator ){
                                    // --------------------------------------
                                    if( calculator.id ) delete calculator.id;
                                    calculator.pj_sale_id = null;
                                    // --------------------------------------
                                    if( calculator.updated_at ) delete calculator.updated_at;
                                    // --------------------------------------
                                });
                            }
                            // ----------------------------------------------

                            // ----------------------------------------------
                            // Sale plans
                            // ----------------------------------------------
                            if( sale.plans && sale.plans.length ){
                                $.each( sale.plans, function( p, plan ){
                                    // --------------------------------------
                                    if( plan.id ) delete plan.id;
                                    plan.pj_sale_id = null;
                                    plan.created_at = 'pending';
                                    // --------------------------------------
                                    if( plan.updated_at ) delete plan.updated_at;
                                    // --------------------------------------

                                    // --------------------------------------
                                    // Plan sections
                                    // --------------------------------------
                                    if( plan.sections && plan.sections.length ){
                                        $.each( plan.sections, function( s, section ){
                                            // ------------------------------
                                            if( section.id ) delete section.id;
                                            section.pj_sale_plan_id = null;
                                            // ------------------------------
                                            if( section.created_at ) delete section.created_at;
                                            if( section.updated_at ) delete section.updated_at;
                                            // ------------------------------
                                        });
                                    }
                                    // --------------------------------------
                                });
                            }
                            // ----------------------------------------------
                        }
                        // --------------------------------------------------

                        // --------------------------------------------------
                        vm.sheets.push( sheet ); // Push new sheet to the list
                        // --------------------------------------------------

                        // --------------------------------------------------
                        // Activate sheet
                        // --------------------------------------------------
                        this.activateSheet( sheet );
                        // --------------------------------------------------

                        // --------------------------------------------------
                        // Request server time based on server timezone
                        // --------------------------------------------------
                        var url = '/api/server/time';
                        var request = axios.get( url ); // Do the request
                        // --------------------------------------------------
                        request.then( function( response ){
                            if( response.data ){
                                var serverTime = response.data;
                                // ------------------------------------------
                                sheet.created_at = serverTime; // Update sheet time
                                // ------------------------------------------
                                var plans = io.get( sheet, 'sale.plans' );
                                if( plans && plans.length ) $.each( plans, function( p, plan ){
                                    plan.created_at = serverTime; // Update sale-plan time
                                });
                                // ------------------------------------------
                            }
                        });
                        // --------------------------------------------------
                    }
                    // ------------------------------------------------------
                },
                // ----------------------------------------------------------

                // ----------------------------------------------------------
                // Reorder sheet's tab index
                // ----------------------------------------------------------
                // Due to deeply nested data, Vue can not properly watch 
                // each underlaying component, resulting in some inner data didn't 
                // get properly swapped when reordering the sheets.
                // ----------------------------------------------------------
                // To hack around this, we clone the sheet-list, 
                // empty it, and then assign it back with the clone on next tick.
                // ----------------------------------------------------------
                // This will trigger the Vue reactivity and force it to reload 
                // and recycle through whole underlaying components.
                // ----------------------------------------------------------
                reorderSheets: function( event ){
                    if( event.oldIndex == event.newIndex ) return;
                    // ------------------------------------------------------
                    var vm = this;
                    var sheets = io.cloneDeep( vm.sheets ); // Clone the list
                    // ------------------------------------------------------
                    vm.sheets = []; // Empty it
                    vm.$nextTick( function(){ vm.sheets = sheets }); // Assign it back
                    // ------------------------------------------------------
                    $.each( vm.sheets, function( index, sheet ){
                        sheet.tab_index = index +1; // Update the internal index
                    });
                    // ------------------------------------------------------
                },
                // ----------------------------------------------------------


                // ----------------------------------------------------------
                // Remove sheet
                // ----------------------------------------------------------
                removeSheet: function( sheetIndex, project ){
                    // ------------------------------------------------------
                    var vm = this; var index = sheetIndex;
                    // ------------------------------------------------------

                    // ------------------------------------------------------
                    // Find the target sheet
                    // ------------------------------------------------------
                    var sheet = vm.sheets[ index ];
                    // ------------------------------------------------------

                    // ------------------------------------------------------
                    // If target sheet found
                    // ------------------------------------------------------
                    if( index >= 0 ){
                        // --------------------------------------------------
                        var wasActive = vm.sheets[index].active;
                        // --------------------------------------------------

                        // --------------------------------------------------
                        var confirmed = confirm("@lang('label.confirm_delete')");
                        if( confirmed ){
                            // ----------------------------------------------
                            // If the sheet is newly added
                            // ----------------------------------------------
                            if( !sheet.id ){
                                // ------------------------------------------
                                vm.sheets.splice( index, 1 ); // Remove the sheet
                                // ------------------------------------------
                                // If removed sheet was an active one,
                                // move the active state to the alternate sheet
                                // ------------------------------------------
                                if( wasActive ){
                                    var alternate = 0;
                                    if( 0 !== index ) alternate = index -1;
                                    if( wasActive ) vm.activateSheet( alternate );
                                }
                                // ------------------------------------------
                                return; // Exit
                                // ------------------------------------------
                            }
                            // ----------------------------------------------

                            // ----------------------------------------------
                            // Otherwise, do an xhr request
                            // to delete the sheet data on database
                            // ----------------------------------------------
                            var url = '{{ URL::current() }}';
                            var params = { sheet: sheet.id, project: project.id };
                            var request = axios.delete( url, { params: params }); // Do the request
                            // ----------------------------------------------
                            vm.status.loading = true;
                            // ----------------------------------------------
                            request.then( function( response ){
                                // ------------------------------------------
                                // console.log( response ); return;
                                // ------------------------------------------
                                if( response.data && 'success' == response.data.status ){
                                    // --------------------------------------
                                    vm.sheets.splice( index, 1 ); // Remove the sheet
                                    // --------------------------------------
                                    var option = $.extend({}, toast, {
                                        heading: '@lang('label.success')', icon: 'success',
                                        text: '{{ config('const.SUCCESS_DELETE_MESSAGE')}}'
                                    });
                                    // --------------------------------------
                                    // If removed sheet was an active one,
                                    // move the active state to the alternate sheet
                                    // --------------------------------------
                                    if( wasActive ){
                                        var alternate = 0;
                                        if( 0 !== index ) alternate = index -1;
                                        if( wasActive ) vm.activateSheet( alternate );
                                    }
                                    // --------------------------------------
                                }
                                // ------------------------------------------
                                else option = $.extend({}, toast, {
                                    text: '{{ config('const.FAILED_DELETE_MESSAGE')}}'
                                });
                                // ------------------------------------------

                                // ------------------------------------------
                                $.toast( option ); // Display the notif
                                // ------------------------------------------
                            });
                            // ----------------------------------------------


                            // ----------------------------------------------
                            request.catch( function(){
                                var option = $.extend( {}, toast, {
                                    text: '{{ config('const.FAILED_DELETE_MESSAGE')}}'
                                }); $.toast( option );
                            });
                            // ----------------------------------------------
                            request.finally( function(){ vm.status.loading = false });
                            // ----------------------------------------------
                        }
                        // --------------------------------------------------
                    }
                    // ------------------------------------------------------
                },
                // ----------------------------------------------------------

                // ----------------------------------------------------------
                // Toggle sheet reflecting-in-budget status
                // ----------------------------------------------------------
                toggleSheetReflected: function( event, sheetIndex, active ){
                    // ------------------------------------------------------
                    var vm = this;
                    var sheets = this.sheets;
                    event = event || false;
                    // ------------------------------------------------------
                    if( sheets[ sheetIndex ]){
                        var sheet = sheets[ sheetIndex ];
                        active = active || !sheet.is_reflecting_in_budget;
                        // --------------------------------------------------
                        if( active ){
                            // ----------------------------------------------
                            vm.reflectedSheet = sheetIndex;
                            sheet.is_reflecting_in_budget = true;
                            // ----------------------------------------------
                            $.each( sheets, function( index, entry ){
                                if( index !== sheetIndex ) entry.is_reflecting_in_budget = false;
                            });
                            // ----------------------------------------------
                        }
                        // --------------------------------------------------
                        else { // Toggle all sheet reflecting flag off
                            vm.reflectedSheet = null;
                            $.each( sheets, function( index, entry ){
                                entry.is_reflecting_in_budget = false;
                            });
                        }
                        // --------------------------------------------------

                        // --------------------------------------------------
                        // Save the form if the toggle button event is fired
                        // --------------------------------------------------
                        if( event ){
                            var $target = $( event.target );
                            var $form = $target.closest('form');
                            $form.submit();
                        }
                        // --------------------------------------------------
                    }
                    // ------------------------------------------------------
                },
                // ----------------------------------------------------------


                // ----------------------------------------------------------
                // Calculate checklist effective area
                // https://bit.ly/39DfwJQ
                // [S1-1] * (100 - [S1-7]) / 100 (round down)
                // ----------------------------------------------------------
                calculateEffectiveArea: function( checklist ){
                    // ------------------------------------------------------
                    var result = checklist.effective_area;
                    var area = this.project.overall_area;
                    var breakthru = checklist.breakthrough_rate;
                    // ------------------------------------------------------
                    if( area && breakthru ){
                        result = Math.floor( area * ( 100 - breakthru ) / 100 );
                    }
                    // ------------------------------------------------------
                    checklist.effective_area = result;
                    // ------------------------------------------------------
                },
                // ----------------------------------------------------------

                
                // xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx


                // ----------------------------------------------------------
                // Submit the form
                // ----------------------------------------------------------
                submit: function(){
                    // ------------------------------------------------------
                    var $form = $('form[data-parsley]');
                    // ------------------------------------------------------
                    queue.scrollTop = true; // Enable scroll-top
                    $form.submit();
                    // ------------------------------------------------------
                },
                // ----------------------------------------------------------

                // ----------------------------------------------------------
                // Request and generate report file
                // ----------------------------------------------------------
                generateReport: function( e, report, sheet, project ){
                    // ------------------------------------------------------
                    var vm = this;
                    var $target = $( e.target );
                    var $form = $target.closest('form');
                    $form.submit();
                    // ------------------------------------------------------

                    // ------------------------------------------------------
                    // Catch the form submit promise
                    // ------------------------------------------------------
                    setTimeout( function(){
                        if( queue.save ) queue.save.then( function( response ){
                            if( project && project.id && sheet && sheet.id ){
                                // ------------------------------------------
                                // Download on progress notification
                                // ------------------------------------------
                                var option = io.clone( toast );
                                option.icon = 'success';
                                option.hideAfter = false;
                                option.heading = '@lang('label.success')';
                                // ------------------------------------------
                                if( 'sheet' === report ) option.text = 'PJシート書を出力中です、しばらくお待ちください。';
                                else if( 'checklist' === report ) option.text = 'チェックリスト書を出力中です、しばらくお待ちください。';
                                // ------------------------------------------
                                var alert = $.toast( option );
                                // ------------------------------------------

                                // ------------------------------------------
                                vm.status.loading = true;
                                vm.status.report[report].loading = true;
                                // ------------------------------------------
                                var url = '/project/' +project.id+ '/report/' +report;
                                var data = { project: project.id, sheet: sheet.id, data: sheet };
                                var request = axios.post( url, data );
                                // ------------------------------------------

                                // ------------------------------------------
                                // Receive the request response
                                // ------------------------------------------
                                request.then( function( response ){
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
                                        }
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
                // ----------------------------------------------------------

                // ----------------------------------------------------------
                // Request project inspection
                // ----------------------------------------------------------
                requestInspection: function( sheet ){
                    // ------------------------------------------------------
                    // Ask for confirmation
                    // ------------------------------------------------------
                    var confirmed = confirm('本当にリクエストしますか？');
                    if( !confirmed ) return;
                    // ------------------------------------------------------

                    // ------------------------------------------------------
                    var vm = this;
                    $( vm.$refs.form ).submit();
                    // ------------------------------------------------------

                    @php
                        $route = array( 'project' => $project->id, 'type' => 1 );
                        $url = route( 'project.inspection.request', $route );
                    @endphp

                    // ------------------------------------------------------
                    // Catch the form submit promise
                    // ------------------------------------------------------
                    setTimeout( function(){
                        if( queue.save ) queue.save.then( function( response ){
                            if( vm.project ){
                                // ------------------------------------------
                                vm.status.approvalRequest.loading = true;
                                // ------------------------------------------
                                var url = '{{ $url }}';
                                var id = io.get( sheet, 'id' ) || null;
                                var data = { project: vm.project.id, sheet: id };
                                var request = axios.post( url, data );
                                // ------------------------------------------

                                // ------------------------------------------
                                // Receive the request response
                                // ------------------------------------------
                                request.then( function( response ){
                                    // console.log( response );
                                    // --------------------------------------
                                    var option = $.extend( {}, toast );
                                    var status = io.get( response, 'data.status' );
                                    var project = io.get( response, 'data.project' );
                                    var inspection = io.get( response, 'data.inspection' );
                                    // --------------------------------------

                                    // --------------------------------------
                                    if( 'success' == status ){
                                        // ----------------------------------
                                        option.icon = 'success';
                                        option.heading = '@lang('label.success')';
                                        option.text = '{{ __('label.approval_requested') }}';
                                        // ----------------------------------

                                        // ----------------------------------
                                        // Update root project data
                                        // ----------------------------------
                                        if( project ) io.assign( vm.project, project );
                                        // ----------------------------------

                                        // ----------------------------------
                                        // Update inspection data
                                        // ----------------------------------
                                        vm.inspection = null;
                                        vm.$nextTick( function(){ vm.inspection = inspection });
                                        // ----------------------------------

                                        // ----------------------------------
                                    } else option.text = '{{ __('label.approval_failed') }}';
                                    // --------------------------------------

                                    // --------------------------------------
                                    $.toast( option );
                                    // --------------------------------------
                                });
                                // ------------------------------------------

                                // ------------------------------------------
                                request.catch( function(){
                                    var option = $.extend( {}, toast, {
                                        text: '{{ __('label.approval_failed') }}'
                                    }); $.toast( option );
                                });
                                // ------------------------------------------
                                request.finally( function(){ vm.status.approvalRequest.loading = false });
                                // ------------------------------------------
                            }
                        });
                    });
                    // ------------------------------------------------------
                },
                // ----------------------------------------------------------
            },
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Computed properties
            // --------------------------------------------------------------
            computed: {
                // ----------------------------------------------------------
                // Find if inspection request button is available
                // ----------------------------------------------------------
                isInspectionDisabled: function(){
                    return this.project.approval_request && this.inspection;
                },
                // ----------------------------------------------------------

                // ----------------------------------------------------------
                // Get current active sheet
                // ----------------------------------------------------------
                activeSheet: function(){ return io.find( this.sheets, { active: true })}
                // ----------------------------------------------------------
            },
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Watchers
            // --------------------------------------------------------------
            watch: {
                // ----------------------------------------------------------
                // Watch vue route changes
                // ----------------------------------------------------------
                '$route': {
                    immediate: true,
                    handler: function( to, from ){
                        // --------------------------------------------------
                        var vm = this;
                        var sheet = vm.sheets[0];
                        var paramSheet = io.get( to, 'params.sheet' );
                        // --------------------------------------------------
                        if( io.startsWith( paramSheet, 'draft' )){
                            var tab = io.parseInt( paramSheet.substr(6));
                            sheet = io.find( vm.sheets, { tab_index: tab });
                        } 
                        // --------------------------------------------------
                        else sheet = io.find( vm.sheets, { id: io.parseInt( paramSheet )});
                        // --------------------------------------------------

                        // --------------------------------------------------
                        // If sheet is undefined or route is invalid
                        // --------------------------------------------------
                        if( !sheet ){
                            var params = { project: vm.project.id, sheet: vm.sheets[0].id }
                            vm.$router.push({ name: 'index', params: params });
                        }
                        // --------------------------------------------------

                        // --------------------------------------------------
                        // Activate the sheet
                        // --------------------------------------------------
                        io.each( vm.sheets, function( sheet ){ sheet.active = false });
                        if( sheet ) sheet.active = true;
                        // --------------------------------------------------
                    }
                },
                // ----------------------------------------------------------


                // ----------------------------------------------------------
                // Watch sheets to refresh the form validation
                // ----------------------------------------------------------
                sheets: {
                    // ------------------------------------------------------
                    deep: true,
                    handler: _.throttle( refreshParsley, 100 )
                    // ------------------------------------------------------
                }
                // ----------------------------------------------------------
            },
        };
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // After Vue has been mounted
        // ------------------------------------------------------------------
        $(document).on('vue-loaded', function( event, vm ){
            // --------------------------------------------------------------
            var $form = $('form[data-parsley]');
            var form = $form.parsley();
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // On form submit
            // --------------------------------------------------------------
            form.on( 'form:validated', function(){
                // ----------------------------------------------------------
                var valid = form.isValid();
                if( !valid ) setTimeout( function(){
                    // ------------------------------------------------------
                    var $error = $('.parsley-errors-list.filled').first();
                    // ------------------------------------------------------

                    // ------------------------------------------------------
                    // If error is inside an accordion, open it
                    // ------------------------------------------------------
                    if( $error.closest('.accordion-collapse').length ){
                        var $collapse = $error.closest('.accordion-collapse');
                        $collapse.addClass('show');
                    }
                    // ------------------------------------------------------

                    // ------------------------------------------------------
                    // If error is inside a tab panel, select the tab
                    // ------------------------------------------------------
                    if( $error.closest('.project-sheets').length ){
                        var $panel = $error.closest('.tab-pane');
                        var index = $panel.index();
                        vm.activateSheet( index );
                    }
                    // ------------------------------------------------------

                    // ------------------------------------------------------
                    // Scroll to the error
                    // ------------------------------------------------------
                    setTimeout( function(){
                        $(window).scrollTo( $error, 600, { 
                            offset: -200,
                            easing: 'easeOutQuart'
                        });
                    }, 100 );
                    // ------------------------------------------------------
                });
                // ----------------------------------------------------------

                // ----------------------------------------------------------
                else {
                    // ------------------------------------------------------
                    vm.status.loading = true;
                    // ------------------------------------------------------
                    var url = '{{ $updateURL }}';
                    var data = {
                        project: vm.project, additional: vm.additional,
                        sheets: vm.sheets
                    };
                    // ------------------------------------------------------
                    var option = $.extend({}, toast );
                    // ------------------------------------------------------

                    // ------------------------------------------------------
                    // console.log( data ); // return; // For debugging
                    queue.save = axios.post( url, data ); // Do the request
                    // ------------------------------------------------------

                    // ------------------------------------------------------
                    // If request succeed
                    // ------------------------------------------------------
                    queue.save.then( function( response ){
                        if( response.data ){
                            // ----------------------------------------------
                            // console.log( response );
                            vm.status.loading = false;
                            // ----------------------------------------------

                            // ----------------------------------------------
                            // Update additional QA with data returned by the server
                            // ----------------------------------------------
                            var additional = io.get( response, 'data.updates.additional' );
                            if( additional ) vm.additional = additional;
                            // ----------------------------------------------

                            // ----------------------------------------------
                            // Update sheets data with data returned by the server
                            // ----------------------------------------------
                            var sheets = io.get( response, 'data.updates.sheets' );
                            if( sheets ){
                                // ------------------------------------------
                                // Apply sheet and cascading updates
                                // ------------------------------------------
                                $.each( sheets, function( sheetIndex, sheet ){
                                    // --------------------------------------
                                    var sheetTarget = vm.sheets[ sheetIndex ];
                                    var sheetUpdates = io.pick( sheet, [ 'id', 'project_id', 'created_at' ]);
                                    // --------------------------------------
                                    $.each( sheetUpdates, function( prop, update ){
                                        Vue.set( sheetTarget, prop, update );
                                    });
                                    // --------------------------------------

                                    // --------------------------------------
                                    // Sheet expense
                                    // --------------------------------------
                                    if( sheet.stock && sheetTarget.stock ){
                                        // ----------------------------------
                                        var expense = sheet.stock;
                                        var expenseTarget = sheetTarget.stock;
                                        var expenseUpdates = io.pick( expense, [ 'id', 'pj_sheet_id' ]);
                                        // ----------------------------------
                                        $.each( expenseUpdates, function( prop, update ){
                                            Vue.set( expenseTarget, prop, update );
                                        });
                                        // ----------------------------------

                                        // ----------------------------------
                                        // Expense sections
                                        // ----------------------------------
                                        $.each( expense, function( key, section ){
                                            if( io.isObject( section ) && section.id ){
                                                // --------------------------
                                                var sectionTarget = expenseTarget[key];
                                                var sectionUpdates = io.pick( section, [ 'id', 'pj_stocking_id', 'pj_stocking_id' ]);
                                                // --------------------------
                                                $.each( sectionUpdates, function( prop, update ){
                                                    Vue.set( sectionTarget, prop, update );
                                                });
                                                // --------------------------

                                                // --------------------------
                                                // Section additional expense
                                                // --------------------------
                                                if( section.additional ){
                                                    // ----------------------
                                                    var additional = section.additional;
                                                    var additionalTarget = sectionTarget.additional;
                                                    var additionalUpdates = io.pick( additional, [ 'id' ]);
                                                    // ----------------------
                                                    $.each( additionalUpdates, function( prop, update ){
                                                        Vue.set( additionalTarget, prop, update );
                                                    });
                                                    // ----------------------

                                                    // ----------------------
                                                    // Additional entries
                                                    // ----------------------
                                                    if( additional.entries ) $.each( additional.entries, function( e, entry ){
                                                        // ------------------
                                                        var entryTarget = additionalTarget.entries[e];
                                                        var entryUpdates = io.pick( entry, [ 'id' ]);
                                                        // ------------------
                                                        $.each( entryUpdates, function( prop, update ){
                                                            Vue.set( entryTarget, prop, update );
                                                        });
                                                        // ------------------
                                                    });
                                                    // ----------------------
                                                }
                                                // --------------------------
                                            }
                                        });
                                        // ----------------------------------
                                    }
                                    // --------------------------------------

                                    // --------------------------------------
                                    // Sheet sale
                                    // --------------------------------------
                                    if( sheet.sale && sheetTarget.sale ){
                                        // ----------------------------------
                                        var sale = sheet.sale;
                                        var saleTarget = sheetTarget.sale;
                                        var saleUpdates = io.pick( sale, [ 'id', 'pj_sheet_id' ]);
                                        // ----------------------------------
                                        $.each( saleUpdates, function( prop, update ){
                                            Vue.set( saleTarget, prop, update );
                                        });
                                        // ----------------------------------

                                        // ----------------------------------
                                        // Sale plans
                                        // ----------------------------------
                                        if( sale.plans && saleTarget.plans ){
                                            $.each( sale.plans, function( planIndex, plan ){
                                                // --------------------------
                                                var planTarget = saleTarget.plans[ planIndex ];
                                                var planUpdates = io.pick( plan, [ 'id', 'pj_sale_id', 'created_at' ]);
                                                // --------------------------
                                                $.each( planUpdates, function( prop, update ){
                                                    Vue.set( planTarget, prop, update );
                                                });
                                                // --------------------------

                                                // --------------------------
                                                // Plan sections
                                                // --------------------------
                                                if( plan.sections && planTarget.sections ){
                                                    $.each( plan.sections, function( sectionIndex, section ){
                                                        // ------------------
                                                        var sectionTarget = planTarget.sections[ sectionIndex ];
                                                        var sectionUpdates = io.pick( section, [ 'id', 'pj_sale_plan_id' ]);
                                                        // ------------------
                                                        $.each( sectionUpdates, function( prop, update ){
                                                            Vue.set( sectionTarget, prop, update );
                                                        });
                                                        // ------------------
                                                    });
                                                }
                                                // --------------------------
                                            });
                                        }
                                        // ----------------------------------
                                    }
                                    // --------------------------------------
                                });
                                // ------------------------------------------
                            };
                            // ----------------------------------------------

                            // ----------------------------------------------
                            // Toast notification
                            // ----------------------------------------------
                            option.icon = 'success'
                            option.heading = '@lang('label.success')';
                            option.text = '@lang('label.success_update_message')';
                            $.toast( option );
                            // ----------------------------------------------

                            // ----------------------------------------------
                            // Scroll to top page
                            // ----------------------------------------------
                            if( queue.scrollTop ) scrollTop();
                            // ----------------------------------------------
                        }
                    });
                    // ------------------------------------------------------

                    // ------------------------------------------------------
                    // On failed
                    // ------------------------------------------------------
                    queue.save.catch( function(e){ console.log(e);
                        option.text = '@lang('label.failed_update_message')';
                        $.toast( option );
                    });
                    // ------------------------------------------------------
                    queue.save.finally( function(){ vm.status.loading = false });
                    // ------------------------------------------------------
                }
                // ----------------------------------------------------------

                // ----------------------------------------------------------
            }).on('form:submit', function(){ return false });
            // --------------------------------------------------------------
        });
        // ------------------------------------------------------------------
    }( jQuery, _, document, window ));
</script>
@endpush
