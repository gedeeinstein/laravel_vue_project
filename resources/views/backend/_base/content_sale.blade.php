@extends("backend._base.app")

@section("content-wrapper")
    <!-- Start - Sale Title -->
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
    <!-- End - Sale Title -->

    @php
        $project_id = request()->project;
        $section_id = request()->section;
        $currentURL = url()->full();
    @endphp
    @include("backend._includes.alert")
    <section id="project-form" class="content mt-4">
        <div class="container mx-n2 mx-sm-auto w-auto">
            <!-- Start - Sale Navbar -->
            <div class="sale-tabs card text-center">
                <div class="card-header border-bottom-0">
                    <ul class="nav nav-tabs card-header-tabs">
                        <!-- SALE ACTIVITY -->
                        @component('backend._base.nav_item')
                            @slot( 'label', __('sale.base.tabs.sale-activity'))
                        @endcomponent
                        <!-- SALE ACTIVITY -->

                        <!-- SALE CONTRACT -->
                        @component('backend._base.nav_item')
                            @slot( 'url', route('sale.contract', [
                                    'project' => $project_id,
                                    'section' => $section_id,
                                ] ))
                            @slot( 'label', __('sale.base.tabs.sale-contract'))
                        @endcomponent
                        <!-- SALE CONTRACT -->

                        <!-- SPENDING SECTION -->
                        @component('backend._base.nav_item')
                            @slot( 'label', __('sale.base.tabs.spending-section'))
                        @endcomponent
                        <!-- SPENDING SECTION -->

                        <!-- TRADING LEDGER -->
                        @component('backend._base.nav_item')
                            @slot( 'label', __('sale.base.tabs.trading-ledger'))
                        @endcomponent
                        <!-- TRADING LEDGER -->
                    </ul>
                </div>
            </div>
            <!-- End - Sale Navbar -->

            <!-- Start - Sale Content -->
            <div class="my-4">
                @yield('content')
            </div>
            <!-- End - Sale Content -->

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
                $('.sale-tabs .nav-item a').filter(function(){
                    return this.href === location.href;
                }).addClass('active');

            });
        }( jQuery, _, document, window ));
    </script>
@endpush
