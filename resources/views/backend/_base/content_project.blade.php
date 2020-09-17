@extends("backend._base.app")

@section("content-wrapper")

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

    @include("backend._includes.alert")
    <section id="project-form" class="content mt-4">
        <div class="container mx-n2 mx-sm-auto w-auto">

            <!-- Start - Project Navbar -->
            @php
                $project_id = request()->project;
                $currentURL = url()->full();
            @endphp
            <div class="project-tabs card text-center">
                <div class="card-header border-bottom-0">
                    <ul class="nav nav-tabs card-header-tabs">

                        @component('backend._base.nav_item')
                            @slot( 'url', route('project.sheet', $project_id ))
                            @slot( 'label', __('project.base.tabs.sheet'))
                        @endcomponent

                        @component('backend._base.nav_item')
                            @slot( 'url', route('project.assist.a', $project_id ))
                            @slot( 'label', __('project.base.tabs.assist.a'))
                        @endcomponent

                        @component('backend._base.nav_item')
                            @slot( 'url', route('project.assist.b', $project_id ))
                            @slot( 'label', __('project.base.tabs.assist.b'))
                        @endcomponent

                        @component('backend._base.nav_item')
                            @slot( 'url', route('project.purchases-sales', $project_id ))
                            @slot( 'label', __('project.base.tabs.purchase.sales'))
                        @endcomponent

                        @component('backend._base.nav_item')
                            @slot( 'url', route( 'project.purchase', $project_id ))
                            @slot( 'label', __('project.base.tabs.purchase.index'))
                        @endcomponent

                        @component('backend._base.nav_item')
                            @slot( 'url', route('project.purchase.contract', $project_id ))
                            {{-- @slot( 'url', route('project.sheet', $project_id )) --}}
                            @slot( 'label', __('project.base.tabs.purchase.contract'))
                        @endcomponent

                        @component('backend._base.nav_item')
                            @slot( 'url', route('project.expense', $project_id ))
                            @slot( 'label', __('project.base.tabs.expense'))
                        @endcomponent

                        @component('backend._base.nav_item')
                            {{-- @slot( 'url', route('project.sheet', $project_id )) --}}
                            @slot( 'label', __('project.base.tabs.ledger'))
                        @endcomponent
                    </ul>
                </div>
            </div>
            <!-- End - Project Navbar -->

            <!-- Start - Project Content -->
            <div class="my-4">
                @yield('content')
            </div>
            <!-- End - Project Content -->

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
                $('.project-tabs .nav-item a').filter(function(){
                    return this.href === location.href;
                }).addClass('active');

            });
        }( jQuery, _, document, window ));
    </script>
@endpush
