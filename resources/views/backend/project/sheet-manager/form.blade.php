@extends('backend._base.content_form')
@php $updateURL = route('project.sheet-manager.update') @endphp

@section('breadcrumbs')
    <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="fas fa-tachometer-alt"></i> @lang('label.dashboard')</a></li>
        <li class="breadcrumb-item active">{{ $page_title }}</li>
    </ol>
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
        @csrf {{ method_field('PUT') }}

        <div class="innerset">
            @php $includes = 'backend.project.sheet-manager.groups.includes' @endphp

            <template v-for="( group, groupIndex ) in dataset" v-if="group.fields && group.fields.length">
                @if( !empty( $groups )) 
                    @foreach( $groups as $group )
                        @include( "backend.project.sheet-manager.groups.{$group}" )
                    @endforeach
                @endif
            </template>

        </div>

        <div class="card-footer text-center">
            <div class="row mx-n1 justify-content-center">
                <div class="px-1 col-auto">

                    <!-- Submit button - Start -->
                    <button type="submit" class="btn btn-wide btn-info" id="input-submit" :disabled="status.loading">
                        <i v-if="status.loading" class="fas fa-cog fa-spin"></i>
                        <i v-else class="fas fa-save"></i>
                        <span>@lang('label.register')</span>
                    </button>
                    <!-- Submit button - End -->

                </div>
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
            // Initial reactive data
            // --------------------------------------------------------------
            var data = {
                // ----------------------------------------------------------
                dataset: @json( $dataset ),
                status: { mounted: false, loading: false },
                // ----------------------------------------------------------
                config: {
                    value: { thousands: ',', precision: 4, masked: false },
                    currency: { 
                        currency: null, 
                        allowNegative: false,
                        precision: 0,
                    }
                }
                // ----------------------------------------------------------
            };
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // console.log( data );
            return data;
            // --------------------------------------------------------------
        },
        // ------------------------------------------------------------------
        watch: {},
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Methods
        // ------------------------------------------------------------------
        methods: {
            // --------------------------------------------------------------
            // Reset to default value
            // --------------------------------------------------------------
            resetDefault: function( field ){
                if( field.default ) field.value = _.toNumber( field.default );
            }
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
                var url = '{{ $updateURL }}';
                var request = axios.post( url, { dataset: vm.dataset });
                // ----------------------------------------------------------
                
                // ----------------------------------------------------------
                // If request succeed
                // ----------------------------------------------------------
                request.then( function( response ){
                    if( response.data ){
                        // --------------------------------------------------
                        // Toast notification
                        // --------------------------------------------------
                        $.toast({
                            position: 'top-right', stack: false, hideAfter: 3000,
                            text: '@lang('project.manager.form.phrase.update.success')',
                            position: { right: 18, top: 68 }
                        });
                        // --------------------------------------------------

                        // --------------------------------------------------
                        // vm.item = response.data; // Reset vue model
                        // setDefaultProperties( vm.item ); // Set default properties
                        // --------------------------------------------------
                    }
                });
                // ----------------------------------------------------------
                // When failed
                // ----------------------------------------------------------
                request.catch( function(e){ 
                    console.log(e);
                    $.toast({
                        icon: 'error', position: 'top-right', stack: false, hideAfter: 3000,
                        text: '@lang('project.manager.form.phrase.update.error')',
                        position: { right: 18, top: 68 }
                    });
                });
                // ----------------------------------------------------------
                request.finally( function(){ vm.status.loading = false });
                // ----------------------------------------------------------
            }
            // --------------------------------------------------------------

            // --------------------------------------------------------------
        }).on('form:submit', function(){ return false });
        // ------------------------------------------------------------------
    });
    // ----------------------------------------------------------------------
</script>
@endpush
