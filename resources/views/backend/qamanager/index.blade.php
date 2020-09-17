@extends('backend._base.content_datatables')

@section('breadcrumbs')
    <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item"><a href="{{route('dashboard')}}"><i class="fas fa-tachometer-alt"></i> @lang('label.dashboard')</a></li>
        <li class="breadcrumb-item active">{{ $page_title }}</li>
    </ol>
@endsection

@section('top_buttons')
    @if ($user_role == 'global_admin')
        <a href="{{ route('qamanager-category.index') }}" class="btn btn-sm btn-info"><i class="fas fa-folder mr-1"></i> @lang('label.qamanager.categorymm')</a>
        <a href="{{ route('qamanager.create') }}" class="btn btn-sm btn-info"><i class="fas fa-plus-circle mr-1"></i> @lang('label.createNew')</a>
    @endif
@endsection

@section('intro')
    <p class="mt-2 mb-4">PJシート&チェックリストに表示される追加チェックリストの管理を行うことができます。</p>
@endsection

@section('content')
    <th data-col="id" data-order="false" width="30">ID</th>
    <th data-hidden data-col="category.name">@lang('label.qamanager.category')</th>
    <th data-col="question" data-order="false">@lang('label.qamanager.question')</th>
    <th data-col="type_name" data-order="false">@lang('label.qamanager.input_type')</th>
    <th data-hidden data-col="order">@lang('label.qamanager.order')</th>
    <th data-hidden data-col="category.order">@lang('label.qamanager.order')</th>
@endsection

<script>
    @section('extend-datatable')
        var groupColumn = 1;
        var $table = $('#datatable');
        var page = '{{ url()->current() }}';

        $table.removeClass('table-striped');

        // disble server side rendering
        options.serverSide = false;

        // custom order nested table
        @if ($user_role == 'global_admin')
        options.columnDefs.push({
            render: function ( data, type, row ){
                var $container = $('<div/>');
                var $nameContainer = $('<div/>').addClass('row mx-n1').appendTo( $container );
                var $nameColumn = $('<div/>').addClass('px-1 col-auto').html( data ).appendTo( $nameContainer );

                var status = parseInt( row.status );
                if( !status ){
                    var $column = $('<div/>').addClass('px-1 col-auto');
                    var $badge = $('<span/>').addClass('badge badge-secondary fs-11 p-1').html('無効');
                    $nameColumn.addClass('row mx-n1').html( $column.clone().addClass('d-flex align-items-center').append( $badge ));
                    $nameColumn.append( $column.clone().addClass('text-black-50').html( data ));
                }

                var editURL = page + '/' + row.id + '/edit';
                var voidURL = 'JavaScript:void(0);';

                var editLabel = '<i class="fas fa-edit"></i> 編集';
                var topLabel = '<i class="fas fa-angle-double-up"></i> 最上段へ';
                var upLabel = '<i class="fas fa-angle-up"></i> 上へ';
                var downLabel = '<i class="fas fa-angle-down"></i> 下へ';
                var bottomLabel = '<i class="fas fa-angle-double-down"></i> 最下段へ';

                var bindData = JSON.stringify({id: row.id, category_id: row.category_id, order: row.order});

                var $controls = $('<div/>').addClass('row row-controls separtor mx-n1 mt-1 fs-13').appendTo( $container );
                var $btnEdit = $('<a/>').addClass('px-1 col-auto').attr( 'href', editURL ).html( editLabel ).appendTo( $controls );
                var $btnTop = $('<a/>').addClass('px-1 col-auto order-btn').attr({ 'data-pos': 'top', 'data-row':bindData, 'href': voidURL }).html( topLabel ).appendTo( $controls );
                var $btnUp = $('<a/>').addClass('px-1 col-auto order-btn').attr({ 'data-pos': 'up', 'data-row':bindData, 'href': voidURL }).html( upLabel ).appendTo( $controls );
                var $btnDown = $('<a/>').addClass('px-1 col-auto order-btn').attr({ 'data-pos': 'down', 'data-row':bindData, 'href': voidURL }).html( downLabel ).appendTo( $controls );
                var $bntBottom = $('<a/>').addClass('px-1 col-auto order-btn').attr({ 'data-pos': 'bottom', 'data-row':bindData, 'href': voidURL }).html( bottomLabel ).appendTo( $controls );

                return $container.get(0).outerHTML;
            },
            targets: 2
        });
        @endif

        options.columnDefs.push({
            render: function ( data, type, row ){
                var $container = $('<div/>').html( data );
                var status = parseInt( row.status );
                if( !status ) $container.addClass('text-black-50');
                return $container.get(0).outerHTML;
            },
            targets: [ 0, 3 ]
        });

        // custom order on datatable
        options.order = [[ 5, 'asc' ], [ 4, 'asc' ]];

        // group by category on drawCallback
        options.drawCallback = function( settings ){
            var api = this.api();
            var rows = api.rows( {page:'current'} ).nodes();
            var last = null;
            api.column(groupColumn, {page:'current'} ).data().each( function ( group, i ) {
                if ( last !== group ) {
                    $(rows).eq( i ).before(
                        '<tr class="group-table"><td colspan="3">'+group+'</td></tr>'
                    );
                    last = group;
                }
            });
        }
    @endsection

    @section('onload-datatable')
        // handle ordering function
        $datatable.find('tbody').on('click', '.order-btn', function() {
            var api = "{{ route('qamanager.order') }}";
            var $row = $(this).closest('tr');
            var postData = $(this).data('row');
            var position = $(this).data('pos');

            postData.position = position;

            axios.post(api, postData).then(function (response) {
                if(response.data.status == 'success'){
                    table.ajax.reload( function (loaded){}, false );
                }
            })
            .catch(function (error) {
                console.log(error);
                $.toast({
                    heading: '失敗', icon: 'error',
                    position: 'top-right', stack: false, hideAfter: 3000,
                    text: [error.response.data.message, error.response.data.error],
                    position: { right: 18, top: 68 }
                });
            });
        });

    @endsection
</script>