@extends("backend._base.app")

@section("content-wrapper")
    <!-- Start - Master Title -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    @hasSection( 'page_title' )
                        @yield( 'page_title' )
                    @else
                        <h1 class="m-0 text-dark h1title">{{ $page_title }}</h1>
                    @endif
                </div>
                <div class="col-sm-6 text-sm mt-3 mt-sm-0 d-flex align-items-center justify-content-start justify-content-sm-end">
                    @yield("breadcrumbs")
                </div>
            </div>
        </div>
    </div>
    <!-- End - Master Title -->

    @php
        $project_id = request()->project;
        $currentURL = url()->full();
    @endphp
    @include("backend._includes.alert")
    <section id="project-form" class="content mt-4">
        <div class="container mx-n2 mx-sm-auto w-auto">
            <!-- Start - Master Navbar -->
            <div class="master-tabs card text-center">
                <div class="card-header border-bottom-0">
                    <ul class="nav nav-tabs card-header-tabs">
                        <!-- PJ SHEET -->
                        @component('backend._base.nav_item')
                            @slot( 'label', __('master.base.tabs.sheet'))
                        @endcomponent

                        <!-- MASTER SETTING -->
                        @component('backend._base.nav_item')
                            @slot( 'label', __('master.base.tabs.setting'))
                        @endcomponent

                        <!-- MASTER BASIC -->
                        @component('backend._base.nav_item')
                            @slot( 'label', __('master.base.tabs.basic'))
                        @endcomponent

                        <!-- EXPENSE -->
                        @component('backend._base.nav_item')
                            @slot( 'label', __('master.base.tabs.expense'))
                        @endcomponent

                        <!-- MASTER FINANCE -->
                        @component('backend._base.nav_item')
                            @slot( 'url', route('master.finance', $project_id ))
                            @slot( 'label', __('master.base.tabs.finance'))
                        @endcomponent

                        <!-- MASTER SECTION -->
                        @component('backend._base.nav_item')
                            @slot( 'label', __('master.base.tabs.section'))
                        @endcomponent
                    </ul>
                </div>
            </div>
            <!-- End - Master Navbar -->

            <!-- Start - Master Content -->
            <div class="my-4">
                @yield('content')
            </div>
            <!-- End - Master Content -->

        </div>
    </section>
@endsection

@push('css')
    <link rel="stylesheet" href="{{asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css')}}">
@endpush

@push('scripts')
    <script src="{{asset('plugins/parsley/parsley.min.js')}}"></script>
    <script src="{{asset('plugins/parsley/i18n/ja.js')}}"></script>
    <script src="{{asset('plugins/moment/moment.min.js')}}"></script>
    <script src="{{asset('js/plugins/parsley/parsley.validators.js')}}"></script>
    <script>
        (function( $, io, document, window, undefined ){
            $(document).ready( function(){

                var options = {
                    uiEnabled: true,
                    errorClass: 'is-invalid',
                    successClass: false
                };

                @stack('extend-parsley')

                $('[data-parsley]').parsley( options );

                // $('.collapse').collapse();

                // add active class navbar project
                $('.master-tabs .nav-item a').filter(function(){
                    return this.href === location.href;
                }).addClass('active');

            });
        }( jQuery, _, document, window ));
    </script>
@endpush
