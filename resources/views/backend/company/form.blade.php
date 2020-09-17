@extends('backend._base.content_form')
@php
    // ----------------------------------------------------------------------
    // Company type list
    // ----------------------------------------------------------------------
    $checkTypes = array();
    $companyTypes = config('const.COMPANY_TYPES');
    $companyTooltips = config('const.COMPANY_TYPE_TOOLTIPS');
    // ----------------------------------------------------------------------
    foreach( $companyTypes as $name => $type ){
        $type = array( $type );
        if( !empty( $companyTooltips[ $name ])){
            $type[] = $companyTooltips[ $name ];
        }
        $checkTypes[ $name ] = $type;
    }
    // ----------------------------------------------------------------------

    // ----------------------------------------------------------------------
    // Index URL
    // ----------------------------------------------------------------------
    $indexURL = route( 'company.index' );
    // ----------------------------------------------------------------------

    // ----------------------------------------------------------------------
    // Post URLs
    // ----------------------------------------------------------------------
    $postURL = route( 'company.store' );
    if( 'edit' == $page_type ){
        // ------------------------------------------------------------------
        // Update URL
        // ------------------------------------------------------------------
        $route = array( 'company' => $item->id );
        $postURL = route( 'company.update', $route );
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Delete URL
        // ------------------------------------------------------------------
        $deleteURL = route( 'company.destroy', $route );
        // ------------------------------------------------------------------
    }
    // ----------------------------------------------------------------------

    // ----------------------------------------------------------------------
    // Confirmation message
    // ----------------------------------------------------------------------
    $confirm = 'label.$company.form.phrase.delete.confirm';
    // ----------------------------------------------------------------------
@endphp

@section('page_title')
    <div class="row mx-n2">
        <div class="px-2 col-auto">
            <div class="d-block d-md-none">
                <a href="{{ route('company.index') }}" class="btn btn-sm btn-default">
                    <i class="fas fa-chevron-left"></i>
                    <span>@lang('label.$company.list')</span>
                </a>
            </div>
            <div class="d-none d-md-block">
                <a href="{{ route('company.index') }}" class="btn btn-default">
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
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="fas fa-tachometer-alt"></i> @lang('label.dashboard')</a></li>
        <li class="breadcrumb-item active">{{ $page_title }}</li>
    </ol>
@endsection

@section('panel_header')
    <ul class="nav nav-tabs card-header-tabs m-0 mt-3">
        <li class="nav-item">
            <a class="nav-link active" href="#">@lang('label.$company.tab.about')</a>
        </li>
        @if( 'edit' == $page_type )
            <li class="nav-item">
                @php
                    $route = array( 'company' => $item->id );
                    $user = route( 'company.user.index', $route );
                @endphp
                <a class="nav-link" href="{{ $user }}">@lang('label.$company.tab.users')</a>
            </li>
        @endif
    </ul>
@endsection


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
    <form action="{{ $form_action }}" method="POST" data-parsley class="parsley-minimal">
        @csrf {{ $page_type == 'create' ? '' : method_field('PUT') }}

        <div class="innerset py-4">

            <!-- Base form - Start -->
            @include('backend.company.form.base')
            <!-- Base form - Start -->

            <!-- Company bank form - Start -->
            @include('backend.company.form.bank')
            <!-- Company bank form - End -->

            <!-- Real estate agent - Start -->
            @include('backend.company.form.agent')
            <!-- Real estate agent - End -->

            <!-- In-house group company - Start -->
            @include('backend.company.form.group')
            <!-- In-house group company - End -->


        </div>

        <div class="card-footer text-center">
            <div class="row mx-n1 justify-content-center">
                <div class="px-1 col-auto">

                    <!-- Submit button - Start -->
                    <button type="submit" class="btn btn-wide btn-info" id="input-submit">
                        <i v-if="status.loading && !status.deleting" class="fas fa-cog fa-spin"></i>
                        <i v-else class="fas fa-save"></i>
                        <span>{{ $page_type == 'create' ? __('label.register') : __('label.update')  }}</span>
                    </button>
                    <!-- Submit button - End -->

                </div>
                @if( 'edit' == $page_type && 'global_admin' == $role->name )
                    <div class="px-1 col-auto">

                        <!-- Delete button - Start -->
                        <button type="button" class="btn btn-wide btn-outline-danger" id="input-delete" @click="deleteCompany">
                            <i v-if="status.loading && status.deleting" class="fas fa-cog fa-spin"></i>
                            <i class="far fa-trash-alt"></i>
                            <span>@lang('label.$company.form.button.delete')</span>
                        </button>
                        <!-- Delete button - End -->

                    </div>
                @endif
            </div>
        </div>

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

@push('vue-scripts')
<script>
    // ----------------------------------------------------------------------
    var refreshParsley = function(){
        setTimeout( function(){
            var $form = $('form[data-parsley]');
            $form.parsley().refresh();
        });
    }
    // ----------------------------------------------------------------------


    // ----------------------------------------------------------------------
    // Set default item properties
    // ----------------------------------------------------------------------
    var setDefaultProperties = function( item ){
        // ------------------------------------------------------------------
        // Default empty bank branch entry
        // ------------------------------------------------------------------
        if( item.banks && !item.banks.length ) item.banks.push({
            name_branch: '', name_branch_abbreviation: ''
        });
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Default empty office entry
        // ------------------------------------------------------------------
        if( item.offices && !item.offices.length ) item.offices.push({
            name: '', address: '', number: null
        });
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Default empty bank-account entry
        // ------------------------------------------------------------------
        if( item.accounts && !item.accounts.length ) item.accounts.push({
            bank_id: 0, company_id: 0, account_kind: 0, account_number: ''
        });
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Default empty borrower entry
        // ------------------------------------------------------------------
        if( item.borrowers && !item.borrowers.length ) item.borrowers.push({
            bank_id: 0, company_id: 0, loan_limit: ''
        });
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        return item;
        // ------------------------------------------------------------------
    }
    // ----------------------------------------------------------------------

    // ----------------------------------------------------------------------
    mixin = {
        // ------------------------------------------------------------------
        mounted: function(){
            var vm = this;
            // --------------------------------------------------------------
            // Set mounted status
            // --------------------------------------------------------------
            vm.status.mounted = true;
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Refresh form validation
            // --------------------------------------------------------------
            refreshParsley();
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Trigger custom vue loaded event for jQuery
            // and other plugins to listen to
            // --------------------------------------------------------------
            $(document).trigger( 'vue-loaded', this );
            // --------------------------------------------------------------
        },
        // ------------------------------------------------------------------
        data: function(){
            // --------------------------------------------------------------
            var item = @json( $item );
            var data = {
                status: { mounted: false, loading: false, deleting: false },
                item: item, types: [], banks: [], associations: [],
                config: {
                    currency: { 
                        currency: null, negative: false, 
                        precision: { min: 0, max: 4 }
                    },
                    loan: {
                        thousands: ',',
                        precision: 0,
                        masked: false
                    }
                }
            };
            // --------------------------------------------------------------
            @foreach( $checkTypes as $name => $type )
                data.types.push({
                    name: '{{ $name }}', type: '{{ $type[0] }}',
                    label: '@lang( 'label.$company.type.'.$name )',
                    tooltip: '{{ !empty( $type[1]) ? $type[1] : '' }}'
                });
            @endforeach
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Add association model to item
            // --------------------------------------------------------------
            item.association = '{{ $association }}';
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Real estate agent guarantee association
            // --------------------------------------------------------------
            @foreach( $associations as $association )
                data.associations.push( @json( $association ));
            @endforeach
            // --------------------------------------------------------------


            // --------------------------------------------------------------
            // Populate default properties
            // --------------------------------------------------------------
            setDefaultProperties( item );
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // console.log( data );
            return data;
            // --------------------------------------------------------------
        },
        // ------------------------------------------------------------------
        watch: {
            // --------------------------------------------------------------
            item: {
                deep: true,
                handler: function(){ refreshParsley()}
            },
            // --------------------------------------------------------------
        },
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Methods
        // ------------------------------------------------------------------
        methods: {
            // --------------------------------------------------------------
            // Add an empty branch
            // --------------------------------------------------------------
            addBankBranch: function(){
                this.item.banks.push({ name_branch: '', name_branch_abbreviation: '' });
                setTimeout( function(){
                    $('.bank-branches .sortable-item:last input:first').focus();
                });
            },
            // --------------------------------------------------------------
            // Remove the selected branch
            // --------------------------------------------------------------
            removeBankBranch: function( index ){
                // ----------------------------------------------------------
                var confirmed = true;
                var item = this.item;
                var entry = item.banks[index];
                // ----------------------------------------------------------
                var name = entry.name_branch && entry.name_branch.length;
                var abbr = entry.name_branch_abbreviation && entry.name_branch_abbreviation.length;
                // ----------------------------------------------------------

                // ----------------------------------------------------------
                // If the item is the last empty item
                // ----------------------------------------------------------
                if( item.banks.length === 1 && ( !name && !abbr )) return;
                // ----------------------------------------------------------

                // ----------------------------------------------------------
                if( name || abbr ) var confirmed = confirm('@lang( $confirm )');
                // ----------------------------------------------------------
                if( confirmed ){
                    item.banks.splice( index, 1 );
                    if( !item.banks.length ) this.addBankBranch();
                }
                // ----------------------------------------------------------
            },
            // --------------------------------------------------------------
            // Find out if the selected branch is required by certain condition
            // --------------------------------------------------------------
            isBankBranchRequired: function( index, field ){
                // ----------------------------------------------------------
                var empty = true; var item = this.item;
                var banks = item.banks; var current = banks[ index ];
                // ----------------------------------------------------------
                var name = 'branch_name';
                var abbr = 'name_branch_abbreviation';
                // ----------------------------------------------------------

                // ----------------------------------------------------------
                // Find out if bank-branch forms are empty
                // ----------------------------------------------------------
                $.each( banks, function( i, bank ){
                    // ------------------------------------------------------
                    var branch = bank[name] && bank[name].trim().length;
                    var branchAbbr = bank[abbr] && bank[abbr].trim().length;
                    // ------------------------------------------------------
                    if( branch || branchAbbr ) empty = false;
                    // ------------------------------------------------------
                });
                // ----------------------------------------------------------

                if( 'name' == field && current[abbr].trim().length ) return true;

                if( empty ){
                    if( !index ) return true;
                }
                return false;

                // ----------------------------------------------------------
                // Branch is required if index is zero and forms are empty.
                // ----------------------------------------------------------
                // return !index && empty;
                // ----------------------------------------------------------
            },
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Add new empty office
            // --------------------------------------------------------------
            addAgentOffice: function(){
                this.item.offices.push({ name: '', address: '', number: null });
            },
            // --------------------------------------------------------------
            // Duplicate the selected item
            // --------------------------------------------------------------
            duplicateAgentOffice: function( office ){
                office.address = this.item.real_estate_agent_office_main_address;
                office.number = this.item.real_estate_agent_office_main_phone_number;
                refreshParsley();
            },
            // --------------------------------------------------------------
            // Remove the selected item
            // --------------------------------------------------------------
            removeAgentOffice: function( index ){
                // ----------------------------------------------------------
                var confirmed = true;
                var item = this.item;
                var entry = this.item.offices[index];
                // ----------------------------------------------------------
                var name = entry.name && entry.name.length;
                var address = entry.address && entry.address.length;
                var number = entry.number;
                // ----------------------------------------------------------

                // ----------------------------------------------------------
                // If the item is the last empty item
                // ----------------------------------------------------------
                if( item.offices.length === 1 && ( !name && !address && !number )) return;
                // ----------------------------------------------------------

                // ----------------------------------------------------------
                if( name || address || number ) var confirmed = confirm('@lang( $confirm )');
                // ----------------------------------------------------------
                if( confirmed ){
                    item.offices.splice( index, 1 );
                    if( !item.offices.length ) this.addAgentOffice();
                }
                // ----------------------------------------------------------
            },
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Add new empty bank account
            // --------------------------------------------------------------
            addBankAccount: function(){
                this.item.accounts.push({ bank_id: 0, account_kind: 0, account_number: '' });
            },
            // --------------------------------------------------------------
            // Remove selected bank account
            // --------------------------------------------------------------
            removeBankAccount: function( index ){
                // ----------------------------------------------------------
                var confirmed = true;
                var item = this.item;
                var entry = item.accounts[index];
                // ----------------------------------------------------------
                var bank = entry.bank_id; var type = entry.account_kind;
                var number = entry.account_number && entry.account_number.length;
                // ----------------------------------------------------------

                // ----------------------------------------------------------
                // If the item is the last empty item
                // ----------------------------------------------------------
                if( item.accounts.length === 1 && ( !bank && !type && !number )) return;
                // ----------------------------------------------------------

                // ----------------------------------------------------------
                if( bank || type || number ) var confirmed = confirm('@lang( $confirm )');
                // ----------------------------------------------------------
                if( confirmed ){
                    item.accounts.splice( index, 1 );
                    if( !item.accounts.length ) this.addBankAccount();
                }
                // ----------------------------------------------------------
            },
            // --------------------------------------------------------------
            // Apply company ID to the selected account
            // --------------------------------------------------------------
            applyAccountCompany: function( e, account ){
                // ----------------------------------------------------------
                var $select = $(e.target);
                var $selected = $select.children('option:selected');
                var company = $selected.attr('data-company');
                // ----------------------------------------------------------
                account.company_id = parseInt( company );
                // ----------------------------------------------------------
            },
            // --------------------------------------------------------------
            // Find ouf if the current account is required or not
            // --------------------------------------------------------------
            isAccountBankRequired: function( account ){
                var required = false;
                // ----------------------------------------------------------
                if( account.account_kind ) required = true;
                if( account.account_number.length ) required = true;
                // ----------------------------------------------------------
                return required;
                // ----------------------------------------------------------
            },
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Add empty borrower entry
            // --------------------------------------------------------------
            addBorrower: function(){
                this.item.borrowers.push({ bank_id: 0, loan_limit: '' });
            },
            // --------------------------------------------------------------
            // Remove the selected borrower entry
            // --------------------------------------------------------------
            removeBorrower: function( index ){
                // ----------------------------------------------------------
                var confirmed = true;
                var item = this.item;
                var entry = item.borrowers[index];
                // ----------------------------------------------------------
                var bank = entry.bank_id;
                var loan = entry.loan_limit;
                // ----------------------------------------------------------

                // ----------------------------------------------------------
                // If the item is the last empty item
                // ----------------------------------------------------------
                if( item.borrowers.length === 1 && ( !bank && !loan )) return;
                // ----------------------------------------------------------

                // ----------------------------------------------------------
                if( bank || loan ) var confirmed = confirm('@lang( $confirm )');
                // ----------------------------------------------------------
                if( confirmed ){
                    item.borrowers.splice( index, 1 );
                    if( !item.borrowers.length ) this.addBorrower();
                }
                // ----------------------------------------------------------
            },
            // --------------------------------------------------------------
            // Apply company ID to the selected borrower
            // --------------------------------------------------------------
            applyBorrowerCompany: function( e, borrower ){
                // ----------------------------------------------------------
                var $select = $(e.target);
                var $selected = $select.children('option:selected');
                var company = $selected.attr('data-company');
                // ----------------------------------------------------------
                borrower.company_id = parseInt( company );
                // ----------------------------------------------------------
            },
            // --------------------------------------------------------------
            // Find out if the selected borrower is required
            // --------------------------------------------------------------
            isBorrowerBankRequired: function( borrower ){
                return !! borrower.loan_limit;
            },
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            @if( 'edit' == $page_type )
                // ----------------------------------------------------------
                // Delete this company data,
                // soft-deletion will be applied at the server side
                // ----------------------------------------------------------
                deleteCompany: function(){
                    // ------------------------------------------------------
                    var vm = this; var item = this.item;
                    var confirmed = confirm('@lang( $confirm )');
                    // ------------------------------------------------------
                    if( confirmed ){
                        // --------------------------------------------------
                        vm.status.loading = true;
                        vm.status.deleting = true;
                        // --------------------------------------------------
                        var url = '{{ $deleteURL }}';
                        var request = axios.delete( url );
                        // --------------------------------------------------

                        // --------------------------------------------------
                        // If request succeed
                        // --------------------------------------------------
                        request.then( function( response ){
                            // console.log( response );
                            if( response && 200 == response.status ){
                                window.location.href = '{{ $indexURL }}';
                            }
                        });
                        // --------------------------------------------------
                        request.catch( function(e){ console.log(e)});
                        request.finally( function(){
                            vm.status.loading = false;
                            vm.status.deleting = false;
                        });
                        // --------------------------------------------------
                    }
                    // ------------------------------------------------------
                }
                // ----------------------------------------------------------
            @endif
            // --------------------------------------------------------------
        }
        // ------------------------------------------------------------------
    }
    // ----------------------------------------------------------------------


    // ----------------------------------------------------------------------
    // After Vue has been mounted
    // ----------------------------------------------------------------------
    $(document).on('vue-loaded', function( event, vm ){
        // ------------------------------------------------------------------
        var $form = $('form[data-parsley]');
        var form = $form.parsley();
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // On form submit
        // ------------------------------------------------------------------
        form.on( 'form:validated', function(){
            // --------------------------------------------------------------
            var valid = form.isValid();
            if( !valid ) setTimeout( function(){
                // ----------------------------------------------------------
                var $errors = $('.parsley-errors-list.filled').first();
                if( _.isFunction( animateScrollTo )){
                    var options = { speed: 500, verticalOffset: -150 };
                    animateScrollTo( $errors.get(0), options );
                }
                // ----------------------------------------------------------
            });
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            else {
                // ----------------------------------------------------------
                vm.status.loading = true;
                // ----------------------------------------------------------
                var request = null; var data = { entry: vm.item };
                var url = '{{ $postURL }}'; var page = '{{ $page_type }}';
                // console.log( data );
                // ----------------------------------------------------------
                if( 'edit' == page ) request = axios.patch( url, data );
                else request = axios.post( url, data );
                // ----------------------------------------------------------

                // ----------------------------------------------------------
                // If request succeed
                // ----------------------------------------------------------
                request.then( function( response ){
                    // console.log( response );
                    if( response.data ){
                        // --------------------------------------------------
                        // Edit mode
                        // --------------------------------------------------
                        if( 'edit' == page ){
                            // ----------------------------------------------
                            // Toast notification
                            // ----------------------------------------------
                            $.toast({
                                heading: '@lang('label.success')', icon: 'success',
                                position: 'top-right', stack: false, hideAfter: 3000,
                                text: '{{ config('const.SUCCESS_UPDATE_MESSAGE') }}',
                                position: { right: 18, top: 68 }
                            });
                            // ----------------------------------------------

                            // ----------------------------------------------
                            vm.item = response.data; // Reset vue model
                            setDefaultProperties( vm.item ); // Set default properties
                            // ----------------------------------------------
                        }
                        // --------------------------------------------------

                        // --------------------------------------------------
                        // Create mode
                        // --------------------------------------------------
                        else {
                            // ----------------------------------------------
                            // Redirect to index page
                            // ----------------------------------------------
                            window.location.href = '{{ $indexURL }}';
                            // ----------------------------------------------
                        }
                        // --------------------------------------------------

                        // --------------------------------------------------
                        scrollTop(); // Add scroll-top on save
                        // --------------------------------------------------
                    }
                });
                // ----------------------------------------------------------
                request.catch( function(e){ 
                    // ------------------------------------------------------
                    // Toast notification
                    // ------------------------------------------------------
                    $.toast({
                        heading: '@lang('label.error')', icon: 'error',
                        position: 'top-right', stack: false, hideAfter: 3000,
                        text: '{{ config('const.FAILED_UPDATE_MESSAGE') }}',
                        position: { right: 18, top: 68 }
                    });
                    // ------------------------------------------------------
                    console.log(e);
                    // ------------------------------------------------------
                });
                request.finally( function(){ vm.status.loading = false });
                // ----------------------------------------------------------
            }
            // --------------------------------------------------------------

            // --------------------------------------------------------------
        }).on('form:submit', function(){ return false });
        // ------------------------------------------------------------------


        // ------------------------------------------------------------------
        // Display custom bootstrap tooltips
        // ------------------------------------------------------------------
        var $tooltips = $('[data-tooltip]');
        // ------------------------------------------------------------------
        var template = null;
        var $container = $('<div/>').addClass('tooltip tooltip-offset-left').attr('role', 'tooltip');
        var $arrow = $('<div/>').addClass('arrow').appendTo( $container );
        var $inner = $('<div/>').addClass('tooltip-inner').appendTo( $container );
        var template = $container.get(0).outerHTML;
        // ------------------------------------------------------------------
        $tooltips.tooltip({ template: template });
        // ------------------------------------------------------------------
    });
    // ----------------------------------------------------------------------
</script>
@endpush
