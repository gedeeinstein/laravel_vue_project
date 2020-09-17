@extends("backend._base.app")

@section("content-wrapper")

    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    @hasSection( 'page_title' )
                        @yield( 'page_title' )
                    @else
                        @if( !empty( $page_title ))
                            <h1 class="m-0 text-dark h1title">{{ $page_title }}</h1>
                        @endif
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

            <!-- Content - Start -->
            <div class="my-4">
                @yield('content')
            </div>
            <!-- Content - End -->

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

                $('[data-toggle="tooltip"]').tooltip({
                    container: 'body'
                });

                var options = {
                    uiEnabled: true,
                    errorClass: 'is-invalid',
                    successClass: false
                };

                @stack('extend-parsley')

                $('[data-parsley]').parsley( options );

            });
        }( jQuery, _, document, window ));
    </script>
@endpush
