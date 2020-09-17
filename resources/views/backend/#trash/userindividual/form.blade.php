@extends('backend._base.content_form')

@php
    // ----------------------------------------------------------------------
    // Index URL
    // ----------------------------------------------------------------------
    $indexURL = route('admin.user.individual.index');
    // ----------------------------------------------------------------------

    // ----------------------------------------------------------------------
    // Post URLs
    // ----------------------------------------------------------------------
    $storeUrl = route('admin.user.individual.store');
    if( 'edit' == $page_type ){
    // ------------------------------------------------------------------
    // Update URL
    // ------------------------------------------------------------------
    $postURL = $form_action;

    // ------------------------------------------------------------------
    // Delete URL
    // ------------------------------------------------------------------
    $deleteURL = route('admin.user.individual.destroy', $item->id );
    }
@endphp

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
            <a href="{{route('admin.user.individual.index')}}" class="btn btn-default">
                <i class="fas fa-chevron-left"></i>
                <span class="m-0 text-dark h1title">{{ $page_title }}</span>
            </a>
        </div>
    </div>
@endsection

@section('breadcrumbs')
    <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item"><a href="{{route('dashboard')}}"><i class="fas fa-tachometer-alt"></i> @lang('label.dashboard')</a></li>
        <li class="breadcrumb-item"><a href="{{route('admin.company.index')}}">@lang('label.company')</a></li>
        <li class="breadcrumb-item active">{{ $page_title }}</li>
    </ol>
@endsection

@section('top_buttons')
    @if ($page_type == "create")
        <a href="{{route('admin.user.individual.index')}}" class="btn btn-info float-sm-right">@lang('label.list')</a>
    @else
        <a href="{{route('admin.user.individual.create')}}" class="btn btn-info float-sm-right">@lang('label.createNew')</a>
        <a href="{{route('admin.user.individual.index')}}" class="btn btn-info float-sm-right">@lang('label.list')</a>
    @endif
@endsection

@section('content')
    <form action="{{ $form_action }}" method="POST" data-parsley class="parsley-minimal">
        @csrf {{ $page_type == 'create' ? '' : method_field('PUT') }}
        {{--first name and last name section--}}
        <div class="row form-group border-0">
            <div class="col-md-3 col-header">
                <span class="bg-danger label-required">@lang('label.required')</span>
                <strong class="field-title">@lang('label.name')</strong>
            </div>
            <div class="col-md-4 col-content">
                <input v-model="item.last_name" :required="true" type="text" placeholder="氏名（姓）" id="last_name" name="last_name" class="form-control" data-parsley-no-focus
                        required data-parsley-trigger="change focusout" :disabled="status.loading" data-parsley-maxlength="128" />
            </div>
            <div class="col-md-4 col-content">
                <input v-model="item.first_name" :required="true" type="text" placeholder="氏名（名）" id="first_name" name="first_name" class="form-control" data-parsley-no-focus
                        required data-parsley-trigger="change focusout" :disabled="status.loading" data-parsley-maxlength="128" />
            </div>
        </div>
        {{--//first name and last name section--}}

        {{--first name KANA and last name KANA section--}}
        <div class="row form-group">
            <div class="col-md-3 col-header">
                <span class="bg-danger label-required">@lang('label.required')</span>
                <strong class="field-title">@lang('users.name_kana')</strong>
            </div>
            <div class="col-md-4 col-content">
                <input v-model="item.last_name_kana" :required="true" type="text" placeholder="氏名（カナ）（姓）" id="last_name_kana" name="last_name_kana" class="form-control" data-parsley-no-focus
                        required data-parsley-trigger="change focusout" :disabled="status.loading" data-parsley-maxlength="128" />
            </div>
            <div class="col-md-4 col-content">
                <input v-model="item.first_name_kana" :required="true" type="text" placeholder="氏名（カナ）（名）" id="first_name_kana" name="first_name_kana" class="form-control" data-parsley-no-focus
                        required data-parsley-trigger="change focusout" :disabled="status.loading" data-parsley-maxlength="128" />
            </div>
        </div>
        {{--//first name KANA and last name KANA section--}}

        @if(Auth::user()->admin_role_id != 3)

            <div class="row form-group border-0 align-items-start">
                <div class="col-md-3 col-header">
                    <div class="icheck-cyan">
                        <input v-bind:true-value="1" v-bind:false-value="0" :value="item.cooperation_registration" type="checkbox" id="cooperation_registration" name="cooperation_registration" v-model="item.cooperation_registration" data-parsley-checkmin="1" />
                        <label for="cooperation_registration" class="text-uppercase mr-5 fs-12 noselect">@lang('users.cooperation_registration')</label>
                    </div>
                </div>
                <div class="col-md-9 col-content" v-if="item.cooperation_registration">
                    <div class="partner">
                        <div class="px-2 paste-in">
                            <div class="icheck-cyan">
                                <input v-bind:true-value="1" v-bind:false-value="0" :value="item.real_estate_information" type="checkbox" id="real_estate_information" name="real_estate_information" v-model="item.real_estate_information" />
                                <label for="real_estate_information" class="text-uppercase mr-5 fs-12">@lang('users.real_estate_information')</label>
                            </div>
                        </div>
                        <div class="col-md-9 col-lg-9 col-content">
                            <div class="py-2 border-0">
                                <textarea v-model="item.real_estate_information_text" id="real_estate_information_text" name="real_estate_information_text" class="form-control" rows="4" data-parsley-maxlength="1024"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="partner">
                        <div class="px-2 col-4 paste-in">
                            <div class="icheck-cyan">
                                <input v-bind:true-value="1" v-bind:false-value="0" :value="item.registration" type="checkbox" id="registration" name="registration" v-model="item.registration" />
                                <label for="registration" class="text-uppercase mr-5 fs-12">@lang('users.registration')</label>
                            </div>
                        </div>
                        <div class="col-md-9 col-lg-9 col-content">
                            <div class="py-2 border-0">
                                <textarea v-model="item.registration_text" id="registration_text" name="registration_text" class="form-control" rows="4" data-parsley-maxlength="1024"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="partner">
                        <div class="px-2 col-4 paste-in">
                            <div class="icheck-cyan">
                                <input v-bind:true-value="1" v-bind:false-value="0" :value="item.surveying" type="checkbox" id="surveying" name="surveying" v-model="item.surveying" />
                                <label for="surveying" class="text-uppercase mr-5 fs-12">@lang('users.surveying')</label>
                            </div>
                        </div>
                        <div class="col-md-9 col-lg-9 col-content">
                            <div class="py-2 border-0">
                                <textarea v-model="item.surveying_text" id="surveying_text" name="surveying_text" class="form-control" rows="4" data-parsley-maxlength="1024"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="partner">
                        <div class="px-2 col-4 paste-in">
                            <div class="icheck-cyan">
                                <input v-bind:true-value="1" v-bind:false-value="0" :value="item.clothes" type="checkbox" id="clothes" name="clothes" v-model="item.clothes" />
                                <label for="clothes" class="text-uppercase mr-5 fs-12">@lang('users.clothes')</label>
                            </div>
                        </div>
                        <div class="col-md-9 col-lg-9 col-content">
                            <div class="py-2 border-0">
                                <textarea v-model="item.clothes_text" id="clothes_text" name="clothes_text" class="form-control" rows="4" data-parsley-maxlength="1024"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="partner">
                        <div class="px-2 col-4 paste-in">
                            <div class="icheck-cyan">
                                <input v-bind:true-value="1" v-bind:false-value="0" :value="item.other" type="checkbox" id="other" name="other" v-model="item.other" />
                                <label for="other" class="text-uppercase mr-5 fs-12">@lang('users.other')</label>
                            </div>
                        </div>
                        <div class="col-md-9 col-lg-9 col-content">
                            <div class="py-2 border-0">
                                <textarea v-model="item.other_text" id="other_text" name="other_text" class="form-control" rows="4" data-parsley-maxlength="1024"></textarea>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        @endif

        <div class="card-footer text-center">
            <div class="row mx-n1 justify-content-center">
                <div class="px-1 col-auto">
                    <button type="submit" class="btn btn-info" id="input-submit">
                        <i class="fas fa-save"></i> {{ $page_type == 'create' ? __('label.register') : __('label.update')  }}
                    </button>
                </div>
                @if( 'edit' == $page_type && 'super_admin' == $role['name'] )
                    <div class="px-1 col-auto">
                        <button type="submit" class="btn btn-wide btn-outline-danger" id="input-delete" @click="deleteUser">
                            <i v-if="status.loading && status.deleting" class="fas fa-cog fa-spin"></i>
                            <i class="fas fa-trash-alt"></i> @lang('label.delete')
                        </button>
                    </div>
                @endif
            </div>
        </div>
        <input type="hidden" name="id" value="{{ $page_type == 'edit' ? $item->id : null }}">
        <input type="hidden" name="company_id" value="">
    </form>
@endsection

@if(0)<script> @endif
 @push('extend-parsley')
     options.successClass = false;
 @endpush
@if(0) </script> @endif

@push('vue-scripts')
    <script>

        let refreshParsley = function () {
            setTimeout(function () {
                let $form = $('form[data-parsley]');
                $form.parsley().refresh();
            });
        }
        // ----------------------------------------------------------------------
        // Scroll page to a target destination with predefined animation
        // ----------------------------------------------------------------------
        var animateScroll = function (scroll, duration) {

            duration = duration || 800;
            let offset = 160;
            if (!_.isInteger(scroll)) {
                let $target = $(scroll);
                if ($target.length) scroll = $target.offset().top;
            }
            let $html = $('html');
            let scrolltop = scroll - offset;
            if (scrolltop <= 0) scrolltop = 0;
            anime({
                targets: $html.get()[0], scrollTop: scrolltop,
                duration: duration, easing: 'linear'
            });
        }

        mixin = {
            mounted() {
                let vm = this;
                // --------------------------------------------------------------
                // Set mounted status
                // --------------------------------------------------------------
                vm.status.mounted = true;
                // --------------------------------------------------------------
                // Refresh form validation
                // --------------------------------------------------------------
                refreshParsley();
                // Trigger custom vue loaded event for jQuery
                // and other plugins to listen to
                // --------------------------------------------------------------
                $(document).trigger('vue-loaded', this);
            },

            data: function(){
                let data = {
                    status: { mounted: false, loading: false, deleting: false },
                    item: @json( $item ),
                };
                return data;
            },

            watch: {
                item: {
                    deep: true,
                    handler: function (value) {
                        console.log(value);
                    }
                },
            },

            methods: {
                @if( 'edit' == $page_type )
                    // ----------------------------------------------------------
                    // Delete this user data,
                    // soft-deletion will be applied at the server side
                    // ----------------------------------------------------------
                    deleteUser: function(){
                        // ------------------------------------------------------
                        let vm = this;
                        let item = this.item;
                        let confirmed = confirm('@lang( 'label.jsConfirmDeleteData' )');
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
                            request.then(function (response) {
                                if( response && 200 == response.status ){
                                    // ------------------------------------------
                                    // Toast notification
                                    // ------------------------------------------
                                    $.toast({
                                        heading: '@lang('label.success')', icon: 'success',
                                        position: 'top-right', stack: false, hideAfter: 3000,
                                        text: '@lang('label.jsInfoDeletedData')',
                                        position: { right: 18, top: 68 }
                                    });
                                    // ------------------------------------------
                                    setTimeout( function(){
                                        window.location.href = '{{ $indexURL }}';
                                    }, 3000 );
                                    // ------------------------------------------
                                }
                            });
                            // --------------------------------------------------
                            request.catch( function( error ){
                                $.toast({
                                    heading: '@lang('label.error')', icon: 'error',
                                    position: 'top-right', stack: false, hideAfter: 3000,
                                    text: [error.response.data.message, error.response.data.error],
                                    position: { right: 18, top: 68 }
                                });
                            });
                            // --------------------------------------------------
                            request.finally( function(){
                                vm.status.loading = false;
                                vm.status.deleting = false;
                            });
                            // --------------------------------------------------
                        }
                    }
                @endif
            },
        };

        // ----------------------------------------------------------------------
        // After Vue has been mounted
        // ----------------------------------------------------------------------
        $(document).on('vue-loaded', function (event, vm) {

            let $form = $('form[data-parsley]');
            let form = $form.parsley();
            // ------------------------------------------------------------------

            // ------------------------------------------------------------------
            // On form submit
            // ------------------------------------------------------------------
            form.on('form:validated', function () {
                // --------------------------------------------------------------
                let valid = form.isValid();
                if (!valid) setTimeout(function () {
                    // ----------------------------------------------------------
                    let $errors = $('.parsley-errors-list.filled').first();
                    animateScroll($errors);
                    // ----------------------------------------------------------
                });
                else {
                    // ----------------------------------------------------------
                    vm.status.loading = true;
                    // ----------------------------------------------------------
                    let request = null;
                    let data = {entry: vm.item};
                    let url = '{{ $form_action }}';
                    let page = '{{ $page_type }}';
                    // console.log(vm.item, data);
                    // ----------------------------------------------------------
                    if (page === 'edit') request = axios.patch(url, data);
                    else {
                        console.log(vm.item, data);
                        request = axios.post(url, data);
                    }
                    // ----------------------------------------------------------

                    // ----------------------------------------------------------
                    // If request succeed
                    // ----------------------------------------------------------
                    request.then(function (response) {
                        console.log(response);
                        if (response.data) {
                            // --------------------------------------------------
                            // Edit mode
                            // --------------------------------------------------
                            if ('edit' === page) {
                                // ----------------------------------------------
                                // Toast notification
                                // ----------------------------------------------
                                $.toast({
                                    position: 'top-right', stack: false, hideAfter: 3000,
                                    text: '@lang('label.jsInfoDeletedData')',
                                    position: {right: 18, top: 68}
                                });

                                vm.item = response.data; // Reset vue model
                                return vm.item;
                            }
                            else if (page === 'create') {
                                // console.log(response);
                                response.data;
                                window.location.href = '{{ $indexURL }}';
                            }
                            else {
                                if( 422 == error.response.status || 500 == error.response.status ){
                                    $.toast({
                                        heading: '@lang('label.error')', icon: 'error',
                                        position: 'top-right', stack: false, hideAfter: false,
                                        text: '@lang('label.failed_delete_message')',
                                        position: {right: 18, top: 68}
                                    });
                                }
                                window.location.href = '{{ $indexURL }}';
                            }
                        }
                    });
                    // ----------------------------------------------------------
                    request.catch(function (e) {
                        $.toast({
                            heading: '@lang('label.error')', icon: 'error',
                            position: 'top-right', stack: false, hideAfter: false,
                            text: '@lang('label.failed_delete_message')',
                            position: {right: 18, top: 68}
                        });
                        console.log(e)
                    });
                    // ----------------------------------------------------------
                    request.finally(function () {
                        vm.status.loading = false
                    });
                    // ----------------------------------------------------------
                }

            }).on('form:submit', function () {
                return false
            });
        });

    </script>
@endpush