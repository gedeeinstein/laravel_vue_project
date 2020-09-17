@extends('backend._base.content_form')

@php
    // ----------------------------------------------------------------------
    // Index URL
    // ----------------------------------------------------------------------
    $indexURL = route( 'company.user.index', $company->id );
    // ----------------------------------------------------------------------

    // ----------------------------------------------------------------------
    // Post URLs
    // ----------------------------------------------------------------------
    $storeUrl = route( 'company.user.store', $company->id  );
    if( 'edit' == $page_type ){
        // ------------------------------------------------------------------
        // Update URL
        // ------------------------------------------------------------------
        $postURL = $form_action;
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Delete URL
        // ------------------------------------------------------------------
        $deleteURL = route( 'company.user.destroy', [ $company->id, $item->id ]);
        // ------------------------------------------------------------------
    }
    // ----------------------------------------------------------------------
@endphp

@section('page_title')
    <div class="row mx-n2 justify-content-center justify-content-md-start">

        <!-- Company list button - Start -->
        @component( 'backend._components.page_button' )
            @slot( 'url', route( 'company.index' ))
            <span class="px-1 col-auto"><i class="fas fa-chevron-left"></i></span>
            <span class="px-1 col-auto">@lang('label.$company.list')</span>
        @endcomponent
        <!-- Company list button - End -->

        <!-- Company users button - Start -->
        @component( 'backend._components.page_button' )
            @slot( 'url', route( 'company.user.index', $company->id ))
            <span class="px-1 col-auto"><i class="fas fa-chevron-left"></i></span>
            <span class="px-1 col-auto ellipsis">
                @if( $individual )
                    <span>@lang('users.individual')</span>
                @else
                    <span>{{ $company->name }}</span>
                @endif
                <span>@lang('users.user_suffix')</span>
            </span>
        @endcomponent
        <!-- Company users button - End -->

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
            <a href="{{ route('company.index') }}">@lang('label.company')</a>
        </li>
        <li class="breadcrumb-item">
            <a href="{{ route('company.user.index', $company->id )}}">@lang('label.user')</a>
        </li>
        @isset( $page_type )
            @if( 'edit' == $page_type )
                <li class="breadcrumb-item active">@lang('label.edit')</li>
            @elseif( 'create' == $page_type )
                <li class="breadcrumb-item active">@lang('label.createNew')</li>
            @endif
        @endif
    </ol>
@endsection

@section('preloader')
    <transition name="preloader">
        @component('backend._components.preloader')
            @slot( 'fullscreen', true )
            @slot( 'if', '!status.mounted' )
        @endcomponent
    </transition>
@endsection

@php 
    $include = 'backend.user.includes';
    $component = 'backend.user.components';
    $hasUserRole = !$individual && ( $company->kind_in_house || $company->kind_advisory_accounting );
@endphp

@section('content')
    <form action="{{ $form_action }}" method="POST" data-parsley class="parsley-minimal">
        @csrf {{ $page_type == 'create' ? '' : method_field('PUT') }}

        <!-- Last and first names - Start -->
        @include( "{$include}.names" )
        <!-- Last and first names - End -->

        <!-- Last and first Kana names - Start -->
        @include( "{$include}.kana-names" )
        <!-- Last and first Kana names - End -->

        @if( 'create' == $page_type ) {{-- Create page --}}

            <!-- Nickname - Start -->
            @include( "{$include}.nickname" )
            <!-- Nickname - End -->

            @if( $hasUserRole )

                <!-- User Role - Start -->
                @include( "{$include}.user-role" )
                <!-- User Role - End -->

            @endif

            <!-- Email - Start -->
            @include( "{$include}.email" )
            <!-- Email - End -->

            <!-- Password - Start -->
            @include( "{$include}.password" )
            <!-- Password - End -->

        @elseif( 'edit' == $page_type ) {{-- Edit page --}}

            {{-- Individual users - Start --}}
            @if( $individual )

                <!-- Nickname - Start -->
                @include( "{$include}.nickname" )
                <!-- Nickname - End -->

                <!-- Company - Start -->
                @include( "{$include}.company" )
                <!-- Company - End -->

                <!-- User Role - Start -->
                @include( "{$include}.user-role" )
                <!-- User Role - End -->

                <!-- Email - Start -->
                @include( "{$include}.email" )
                <!-- Email - End -->

                {{-- If the user is editing their own data - Start --}}
                @if( $item->id === $user->id )

                    <!-- Password - Start -->
                    @include( "{$include}.password" )
                    <!-- Password - End -->

                @endif
                {{-- If the user is editing their own data - End --}}

            @endif
            {{-- Individual users - Start --}}

            {{-- Company users - Start --}}
            @if( !$individual )

                @if( $company->kind_in_house || $company->kind_advisory_accounting )

                    <!-- Nickname - Start -->
                    @include( "{$include}.nickname" )
                    <!-- Nickname - End -->

                    {{-- If an admin - Start --}}
                    @if( $admin )

                        <!-- User Role - Start -->
                        @include( "{$include}.user-role" )
                        <!-- User Role - End -->

                        <!-- Email - Start -->
                        @include( "{$include}.email" )
                        <!-- Email - End -->

                    @endif
                    {{-- If an admin - End --}}

                    {{-- If the user is editing their own data - Start --}}
                    @if( $item->id === $user->id )

                        <!-- Password - Start -->
                        @include( "{$include}.password" )
                        <!-- Password - End -->

                    @endif
                    {{-- If the user is editing their own data - End --}}

                @endif

                <!-- Real estate form - Start -->
                @if( $company->kind_real_estate_agent )

                    <!-- Real estate notary - Start -->
                    @include( "{$include}.real-estate" )
                    <!-- Real estate notary - End -->

                @endif
                <!-- Real estate form - End -->

                <!-- Cooperation registration form - Start -->
                @if( !$company->kind_in_house )

                    <!-- Cooperation registration - Start -->
                    @include( "{$include}.cooperation" )
                    <!-- Cooperation registration - End -->

                @endif
                <!-- Cooperation registration form - End -->

            @endif
            {{-- Company users - End --}}

        @endif

        <div class="card-footer text-center mt-4">
            <div class="row mx-n1 justify-content-center">
                <div class="px-1 col-auto">
                    <button type="submit" class="btn btn-info" :disabled="status.loading || verification.loading">
                        <div class="row mx-n1">
                            <div class="px-1 col-auto">
                                <i v-if="status.loading && !status.deleting" class="fas fa-cog fa-spin"></i>
                                <i v-else class="fas fa-save"></i>
                            </div>
                            <div class="px-1 col-auto">
                                <span>@lang( $page_type == 'create' ? 'label.register' : 'label.update')</span>
                            </div>
                        </div>
                    </button>
                </div>
                @if( 'edit' == $page_type && 'global_admin' == $user->user_role->name )
                    <div class="px-1 col-auto">
                        <button type="button" class="btn btn-outline-danger" id="input-delete" @click="deleteUser">
                            <div class="row mx-n1">
                                <div class="px-1 col-auto">
                                    <i v-if="status.loading && !status.deleting" class="fas fa-cog fa-spin"></i>
                                    <i v-else class="far fa-trash-alt"></i>
                                </div>
                                <div class="px-1 col-auto">
                                    <span>@lang( 'label.delete' )</span>
                                </div>
                            </div>
                        </button>
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
        var mode = '{{ $page_type }}';
        // ----------------------------------------------------------------------

        // ----------------------------------------------------------------------
        // Refresh validation
        // ----------------------------------------------------------------------
        var refreshParsley = function(){
            setTimeout( function(){
                var $form = $('form[data-parsley]');
                $form.parsley().refresh();
            });
        }
        // ----------------------------------------------------------------------


        // ----------------------------------------------------------------------
        // Toast default options
        // ----------------------------------------------------------------------
        var toast = {
            heading: '@lang('label.success')', icon: 'success',
            position: 'top-right', stack: false, hideAfter: 3000,
            position: { right: 18, top: 68 }
        };
        if( 'edit' == mode ) toast.text = '{{ config('const.SUCCESS_UPDATE_MESSAGE')}}';
        else toast.text = '{{ config('const.SUCCESS_CREATE_MESSAGE')}}';
        // ----------------------------------------------------------------------


        // ----------------------------------------------------------------------
        // Vue mixins
        // ----------------------------------------------------------------------
        mixin = {
            // ------------------------------------------------------------------
            // On mounted
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

            // ------------------------------------------------------------------
            // Reactive data tree
            // ------------------------------------------------------------------
            data: function(){
                var data = {
                    item: @json( $item ), 
                    company: @json( $company ),
                    individual: @json( $individual ),
                    status: {  mounted: false, loading: false, deleting: false },
                    verification: { 
                        loading: false, requested: false, 
                        verified: true, message: null
                    }
                };
                // console.log(data);
                return data;
            },
            // ------------------------------------------------------------------

            // ------------------------------------------------------------------
            // Data watchers
            // ------------------------------------------------------------------
            watch: {
                item: {
                    deep: true,
                    handler: function( value ){ refreshParsley()}
                },
            },
            // ------------------------------------------------------------------

            // ------------------------------------------------------------------
            // Methods
            // ------------------------------------------------------------------
            methods: {
                // --------------------------------------------------------------
                // Verify user email remotely
                // --------------------------------------------------------------
                verifyUserEmail: function( user ){
                    // ----------------------------------------------------------
                    var vm = this;
                    vm.verification.loading = true;
                    // ----------------------------------------------------------

                    // ----------------------------------------------------------
                    // Preparing request
                    // ----------------------------------------------------------
                    var data = { email: user.email, user: user.id || null };
                    var url = '{{ url( 'api/user/email' )}}';
                    var request = axios.post( url, data );
                    // ----------------------------------------------------------

                    // ----------------------------------------------------------
                    // Error messages
                    // ----------------------------------------------------------
                    var duplicated = '@lang('users.duplicated_email')';
                    var failed = '@lang('users.email_verification_failed')';
                    // ----------------------------------------------------------

                    // ----------------------------------------------------------
                    request.then( function( response ){
                        if( response.data ){
                            var status = response.data;
                            // --------------------------------------------------
                            // Verification failed
                            // --------------------------------------------------
                            if( !status.verified && status.error && status.error.length ){
                                vm.verification.verified = false;
                                if( 'registered' == status.error ) vm.verification.message = duplicated;
                                else vm.verification.message = failed;
                            } 
                            // --------------------------------------------------
                            // Verification succeed
                            // --------------------------------------------------
                            else {
                                vm.verification.message = null;
                                vm.verification.verified = true;
                            }
                            // --------------------------------------------------
                        }
                        console.log( vm.verification );
                    });
                    // ----------------------------------------------------------

                    // ----------------------------------------------------------
                    // Catch exception
                    // ----------------------------------------------------------
                    request.catch( function( e ){
                        console.log( e );
                        vm.verification.verified = false;
                    });
                    // ----------------------------------------------------------
                    request.finally( function(){ 
                        vm.verification.loading = false;
                        vm.verification.requested = true;
                    });
                    // ----------------------------------------------------------
                },
                // --------------------------------------------------------------

                // --------------------------------------------------------------
                @if( 'edit' == $page_type )
                    // ----------------------------------------------------------
                    // Delete this user data,
                    // soft-deletion will be applied at the server side
                    // ----------------------------------------------------------
                    deleteUser: function(){
                        // ------------------------------------------------------
                        var vm = this;
                        var item = this.item;
                        var confirmed = confirm('@lang( 'label.jsConfirmDeleteData' )');
                        // ------------------------------------------------------
                        if( confirmed ){
                            // --------------------------------------------------
                            vm.status.loading = true;
                            vm.status.deleting = true;
                            // --------------------------------------------------
                            var request = axios.delete('{{ $deleteURL }}');
                            // --------------------------------------------------

                            // --------------------------------------------------
                            // If request succeed
                            // --------------------------------------------------
                            request.then( function( response ){
                                if( response && 200 == response.status ){
                                    // ------------------------------------------
                                    // Redirect back to list page
                                    // ------------------------------------------
                                    window.location.href = '{{ $indexURL }}';
                                    // ------------------------------------------
                                }
                            });
                            // --------------------------------------------------

                            // --------------------------------------------------
                            // Catch exception
                            // --------------------------------------------------
                            request.catch( function(e){ 
                                console.log(e);
                                toast.icon = 'error';
                                toast.heading = '@lang('label.error')';
                                toast.text = '@lang('const.FAILED_DELETE_MESSAGE')';
                                $.toast( toast );
                            });
                            // --------------------------------------------------
                            request.finally( function(){
                                vm.status.loading = false;
                                vm.status.deleting = false;
                            });
                            // --------------------------------------------------
                        }
                        // ------------------------------------------------------
                        // console.log( confirmed );
                        // ------------------------------------------------------
                    }
                @endif
            },
            // ------------------------------------------------------------------
        };
        // ----------------------------------------------------------------------


        // ----------------------------------------------------------------------
        // After Vue has been mounted
        // ----------------------------------------------------------------------
        $(document).on( 'vue-loaded', function( event, vm ){
            var $form = $('form[data-parsley]');
            var form = $form.parsley();
            // ------------------------------------------------------------------

            // ------------------------------------------------------------------
            // On form submit
            // ------------------------------------------------------------------
            form.on( 'form:validated', function(){
                // --------------------------------------------------------------
                var valid = form.isValid();
                var verified = vm.verification.verified;
                // --------------------------------------------------------------
                if( !valid || !verified ) setTimeout( function(){
                    // ----------------------------------------------------------
                    var $errors = $('.parsley-errors-list.filled, .verification-error');
                    if( $errors.length && _.isFunction( animateScrollTo )){
                        var options = { speed: 500, verticalOffset: -150 };
                        animateScrollTo( $errors.get(0), options );
                    }
                    // ----------------------------------------------------------
                });
                else {
                    // ----------------------------------------------------------
                    vm.status.loading = true;
                    // ----------------------------------------------------------
                    var request = null;
                    var data = { entry: vm.item };
                    var url = '{{ $form_action }}';
                    var page = '{{ $page_type }}';
                    // ----------------------------------------------------------
                    if( 'edit' === page ) request = axios.patch( url, data );
                    else request = axios.post( url, data );
                    // ----------------------------------------------------------

                    // ----------------------------------------------------------
                    // If request succeed
                    // ----------------------------------------------------------
                    request.then( function( response ){
                        // console.log( response ); return;
                        if( response.data ){
                            // --------------------------------------------------
                            // Toast notification
                            // --------------------------------------------------
                            $.toast( toast );
                            scrollTop(); // Add scroll-top on save
                            // --------------------------------------------------

                            // --------------------------------------------------
                            // Edit mode
                            // --------------------------------------------------
                            if( 'edit' === page ){
                                vm.item = response.data; // Reset vue model
                                return vm.item;
                            }
                            // --------------------------------------------------

                            // --------------------------------------------------
                            // Create mode
                            // --------------------------------------------------
                            else if( 'create' === page ){
                                // ----------------------------------------------
                                setTimeout( function(){
                                    window.location.href = '{{ $indexURL }}';
                                }, 3000 );
                                // ----------------------------------------------
                            }
                            // --------------------------------------------------

                            // --------------------------------------------------
                            // --------------------------------------------------
                        }
                    });
                    // ----------------------------------------------------------

                    // ----------------------------------------------------------
                    // Catch exception
                    // ----------------------------------------------------------
                    request.catch( function( e ){
                        var status = _.get( e, 'response.data.status' );
                        var message = _.get( e, 'response.data.message' );
                        if( 'error' == status && message ){
                            toast.icon = 'error';
                            toast.heading = '@lang('label.error')';
                            toast.text = message;
                            $.toast( toast );
                        }
                    });
                    // ----------------------------------------------------------
                    request.finally( function(){ vm.status.loading = false });
                    // ----------------------------------------------------------
                }
            }).on( 'form:submit', function(){ return false });
        });
    </script>
@endpush
