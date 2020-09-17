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
                <div class="col-sm-6 text-sm">
                    @yield("breadcrumbs")
                </div>
            </div>
        </div>
    </div>
    @include("backend._includes.alert")
    <section class="content">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        @hasSection( 'panel_header' )
                            <div class="card-header py-0">
                                @yield( 'panel_header' )
                            </div>
                        @else
                            <div class="card-header">
                                <div class="row">
                                    <div class="col-sm-6 card-title">
                                        @if( View::hasSection( 'top_title' ))
                                            @yield( 'top_title' )
                                        @else
                                            <h3 class="card-title">@lang('label.list')</h3>
                                        @endif
                                    </div>
                                    <div class="col-sm-6 card-header-link">
                                        @yield('top_buttons')
                                    </div>
                                </div>
                            </div>
                        @endif
                        <div class="card-body">
                            @if( View::hasSection( 'intro' ))
                                @yield( 'intro' )
                            @endif
                            <div class="row">
                                <div class="col-sm-12">
                                    <table id="datatable" class="table table-bordered table-striped dataTable" role="grid" aria-describedby="example1_info" style="width:100%">
                                        <thead>
                                        <tr>
                                            @yield('content')
                                        </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
<script>
    (function( $, io, document, window, undefined ){
        $(document).ready( function(){
            // --------------------------------------------------------------
            var column = [];
            var $datatable = $('#datatable');
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            $("[data-col=action]").attr("rowspan", 2).addClass("text-center align-middle actionDatatables");
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Column processing
            // --------------------------------------------------------------
            $datatable.find('thead tr').clone( true ).addClass('head-filters').appendTo('#datatable thead');
            $datatable.find('thead tr:eq(1) th').each( function(i){
                // ----------------------------------------------------------
                var $column = $(this);
                var title = $column.text();
                var attr = $column.attr('rowspan');
                var id = $column.attr('data-col');
                // ----------------------------------------------------------

                // ----------------------------------------------------------
                if( id !== 'action' ){
                    // ------------------------------------------------------
                    var visible = true;
                    var hidden = $column.attr('data-hidden');
                    var orderable = $column.attr('data-order');
                    var searchable = $column.attr('data-search') || true;
                    // ------------------------------------------------------

                    // ------------------------------------------------------
                    // Hide hidden columns
                    // ------------------------------------------------------
                    if( typeof hidden !== 'undefined' && hidden !== false ){
                        visible = false;
                    }
                    if( typeof orderable !== 'undefined' && orderable !== false ){
                        orderable = false;
                    }
                    // ------------------------------------------------------

                    // ------------------------------------------------------
                    column.push({
                        data: id, name: id, visible: visible,
                        searchable: searchable, orderable: orderable,
                    });
                    // ------------------------------------------------------

                    // ------------------------------------------------------
                    // Search box
                    // ------------------------------------------------------
                    if( searchable && 'false' != searchable ){
                        // --------------------------------------------------
                        var placeholder = '@lang('label.search') ' +title;
                        // --------------------------------------------------
                        var $search = $('<input/>').addClass('form-control form-control-sm searchbox');
                        $search.attr( 'type', 'text' ).attr( 'placeholder', placeholder );
                        // --------------------------------------------------
                        $column.html( $search );
                        // --------------------------------------------------
                        $( 'input.searchbox', this ).on( 'keyup change', function(){
                            if( table.column(i).search() !== this.value ){
                                table.column(i).search( this.value ).draw();
                            }
                        });
                        // --------------------------------------------------
                    } else $column.empty();
                    // ------------------------------------------------------
                }
                // ----------------------------------------------------------

                // ----------------------------------------------------------
                if (typeof attr !== typeof undefined && attr !== false) {
                    $column.remove();
                }
                // ----------------------------------------------------------
            });
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            if( $("[data-col=action]").length ){
                column.push({data: 'action', name: 'action', orderable: false, searchable: false});
            }
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // DataTable Setup
            // --------------------------------------------------------------
            var options = {
                order: [[ 0, 'desc' ]], orderCellsTop: true,
                fixedHeader: true, paging: true,
                lengthChange: true,
                lengthMenu: [
                    [25, 50, 100, -1],
                    [25, 50, 100, "@lang('label.all')"]
                ],
                searching: true, ordering: true,
                info: true, scrollX: true,
                autoWidth: true, serverSide: true,
                processing: true, responsive: true,

                columns: column,
                ajax: "{{ url()->current() . '/json' }}",

                columnDefs: [
                    { width: '10px', targets: 0 },
                ],

                @if( 'en' != App::getLocale())
                    language: {
                        url: "{{asset('js/backend/adminlte/Japanese.json')}}"
                    }
                @endif
            };
            // --------------------------------------------------------------


            // --------------------------------------------------------------
            // Datatable extended options
            // --------------------------------------------------------------
            @hasSection( 'extend-datatable' )
                @yield( 'extend-datatable' )
            @endif
            // --------------------------------------------------------------


            // --------------------------------------------------------------
            // DataTable initiation
            // --------------------------------------------------------------
            let table = $datatable.DataTable( options );
            // --------------------------------------------------------------


            // --------------------------------------------------------------
            // Datatable after load script
            // --------------------------------------------------------------
            @hasSection( 'onload-datatable' )
                @yield( 'onload-datatable' )
            @endif
            // --------------------------------------------------------------


            // --------------------------------------------------------------
            // Entry deletion
            // --------------------------------------------------------------
            $datatable.on( 'click', '.deleteData[data-remote]', function (e){
                e.preventDefault();
                // ----------------------------------------------------------
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                // ----------------------------------------------------------

                // ----------------------------------------------------------
                let url = $(this).data('remote');
                if( confirm( '@lang('label.jsConfirmDeleteData')' )){
                    $.ajax({
                        url: url,
                        type: 'DELETE',
                        dataType: 'json',
                        data: {method: 'DELETE', submit: true}
                    }).always( function( data ){
                        console.log(data);
                        $datatable.DataTable().draw(false);
                        toastr.success('@lang('label.jsInfoDeletedData')');
                    });
                }
                // ----------------------------------------------------------
                else toastr.error('@lang('label.jsSorry')');
                // ----------------------------------------------------------
            });
            // --------------------------------------------------------------

        });
    }( jQuery, _, document, window ));
</script>
@endpush
