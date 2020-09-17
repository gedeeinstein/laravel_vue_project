@extends('backend._base.content_datatables')

@section('page_title')
    <div class="row mx-n2">
        <div class="px-2 col-auto">
            <div class="d-block d-md-none">
                <a href="{{ route('qamanager.index') }}" class="btn btn-sm btn-default">
                    <i class="fas fa-chevron-left"></i>
                    <span>チェックリスト一覧</span>
                </a>
            </div>
            <div class="d-none d-md-block">
                <a href="{{ route('qamanager.index') }}" class="btn btn-default">
                    <i class="fas fa-chevron-left"></i>
                    <span>チェックリスト一覧</span>
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
        <li class="breadcrumb-item"><a href="{{route('dashboard')}}"><i class="fas fa-tachometer-alt"></i> @lang('label.dashboard')</a></li>
        <li class="breadcrumb-item active">{{ $page_title }}</li>
    </ol>
@endsection

@section('top_buttons')
    @if ($user_role == 'global_admin')
        <div class="input-group input-group-sm px-1 w-75 float-right">
            <input type="text" class="form-control input-add-category" placeholder="新しいカテゴリ名を入力">
            <span class="input-group-append">
                <button type="button" class="btn btn-info btn-sm btn-flat btn-add-category"><i class="fas fa-plus-circle mr-1"></i> 追加</button>
            </span>
        </div>
    @endif
@endsection

@section('content')
    <th data-col="id" data-order="false" width="30">ID</th>
    <th data-col="name" data-order="false">@lang('label.qamanager.name')</th>
    <th data-hidden data-col="order" data-order="false">@lang('label.qamanager.order')</th>
    <th data-col="created_at" data-order="false">@lang('label.created_at')</th>
    <th data-col="updated_at" data-order="false">@lang('label.last_update')</th>
@endsection

<script>
    @section('extend-datatable')
        var $table = $('#datatable');
        $table.removeClass('table-striped');

        // Disble server side rendering
        options.serverSide = false;
        options.order = [ 2, 'asc' ];

        // Custom order nested table
        @if ($user_role == 'global_admin')
        options.columnDefs.push({
            render: function ( data, type, row ){
                var $container = $('<div/>');
                var $nameContainer = $('<div/>').addClass('row mx-n1').appendTo( $container );
                var $nameColumn = $('<div/>').addClass('px-1 col-auto editable'+row.id).html( data ).appendTo( $nameContainer );
                var $inputColumn = $('<div/>').addClass('px-1 col-sm editable'+row.id).css('display','none').html( '<div class="input-group input-group-sm"> <input type="text" class="form-control input-edit-category'+row.id+'" value="'+data+'" data-origin="'+data+'"> <span class="input-group-append"> <button type="button" data-id="'+row.id+'" class="btn btn-info btn-flat btn-edit-category"><i class="fas fa-edit mr-1"></i> 更新</button> </span> </div>' ).appendTo( $nameContainer );

                var voidURL = 'JavaScript:void(0);';
                var editLabel = '<i class="fas fa-edit"></i> 編集';
                var topLabel = '<i class="fas fa-angle-double-up"></i> 最上段へ';
                var upLabel = '<i class="fas fa-angle-up"></i> 上へ';
                var downLabel = '<i class="fas fa-angle-down"></i> 下へ';
                var bottomLabel = '<i class="fas fa-angle-double-down"></i> 最下段へ';
                var deleteLabel = '<i class="fas fa-trash"></i> 削除';

                var bindData = JSON.stringify({id: row.id, order: row.order});

                var $controls = $('<div/>').addClass('row row-controls separtor mx-n1 mt-1 fs-13').appendTo( $container );
                var $btnEdit = $('<a/>').addClass('px-1 col-auto toggle-edit').attr({ 'data-id': row.id, 'href': voidURL }).html( editLabel ).appendTo( $controls );
                var $btnTop = $('<a/>').addClass('px-1 col-auto order-btn').attr({ 'data-pos': 'top', 'data-row':bindData, 'href': voidURL }).html( topLabel ).appendTo( $controls );
                var $btnUp = $('<a/>').addClass('px-1 col-auto order-btn').attr({ 'data-pos': 'up', 'data-row':bindData, 'href': voidURL }).html( upLabel ).appendTo( $controls );
                var $btnDown = $('<a/>').addClass('px-1 col-auto order-btn').attr({ 'data-pos': 'down', 'data-row':bindData, 'href': voidURL }).html( downLabel ).appendTo( $controls );
                var $bntBottom = $('<a/>').addClass('px-1 col-auto order-btn').attr({ 'data-pos': 'bottom', 'data-row':bindData, 'href': voidURL }).html( bottomLabel ).appendTo( $controls );
                var $bntDelete = $('<a/>').addClass('px-1 col-auto text-danger btn-delete-category').attr({ 'data-id': row.id, 'href': voidURL }).html( deleteLabel ).appendTo( $controls );

                return $container.get(0).outerHTML;
            },
            targets: 1
        });
        @endif
    @endsection
    @section('onload-datatable')

        // Toggle editable collumn
        $datatable.find('tbody').on('click', '.toggle-edit', function(e) {
            e.preventDefault();
            var row = $(this).data('id');
            $('.editable'+row).toggle();
        });

        // Update Category
        $datatable.find('tbody').on('click', '.btn-edit-category', function(e) {
            e.preventDefault();
            var $button = $(this);
            var id = $button.data('id');
            var $input = $('.input-edit-category'+id);
            var origin = $input.data('origin');
            var updateName = $.trim($input.val());

            // on confirm if input is change and not null
            var confirmed = false;
            if(updateName && $input.val() != origin){
                confirmed = confirm('@lang('label.confirm_edit')')
            }

            // if confirmed and input has value
            if (confirmed) {
                $button.prop('disabled', true);
                $input.prop('disabled', true);

                var updateAPI = "{{ url('/qamanager-category') }}";
                var request = axios.patch(updateAPI+'/'+id, {name: updateName});

                request.then(function (response) {
                    if (response.data.status == 'success') {
                        $('.editable'+id).toggle();
                        table.ajax.reload( function (loaded){
                            $.toast({
                                heading: '成功', icon: 'success',
                                position: 'top-right', stack: false, hideAfter: 3000,
                                text: response.data.message,
                                position: { right: 18, top: 68 }
                            });
                        }, false );
                    }
                })
                request.catch(function (error) {
                    console.log(error.response)
                    $.toast({
                        heading: '失敗', icon: 'error',
                        position: 'top-right', stack: false, hideAfter: 3000,
                        text: [error.response.data.message, error.response.data.error],
                        position: { right: 18, top: 68 }
                    });
                    $button.prop('disabled', false);
                    $input.prop('disabled', false);
                });
                request.then(function () {
                    $button.prop('disabled', false);
                    $input.prop('disabled', false);
                });
            }
            else{
                $input.focus();
            }

        });

        // Add Category
        $('.btn-add-category').on('click', function(e) {
            e.preventDefault();
            var $button = $(this);
            var $input = $('.input-add-category');
            var inputName = $.trim($input.val());

            // only confirm if input has value
            var confirmed =  false;
            if(inputName){
                confirmed = confirm('@lang('label.confirm_create')');
            }else{
                $input.focus();
            }

            // if confirmed and input has value
            if (confirmed) {
                $button.prop('disabled', true);
                $input.prop('disabled', true);

                var addAPI = "{{ route('qamanager-category.store') }}";
                var request = axios.post(addAPI, {name: inputName});

                request.then(function (response) {
                    if (response.data.status == 'success') {
                        table.ajax.reload( function (loaded){
                            $.toast({
                                heading: '成功', icon: 'success',
                                position: 'top-right', stack: false, hideAfter: 3000,
                                text: response.data.message,
                                position: { right: 18, top: 68 }
                            });
                        }, false );
                    }
                })
                request.catch(function (error) {
                    console.log(error.response)
                    $.toast({
                        heading: '失敗', icon: 'error',
                        position: 'top-right', stack: false, hideAfter: 3000,
                        text: [error.response.data.message, error.response.data.error],
                        position: { right: 18, top: 68 }
                    });
                    $button.prop('disabled', false);
                    $input.prop('disabled', false);
                });
                request.then(function () {
                    $button.prop('disabled', false);
                    $input.prop('disabled', false);
                    $input.val('');
                });

            }

        });

        // Delete Category
        $datatable.find('tbody').on('click', '.btn-delete-category', function(e) {
            e.preventDefault();
            var $button = $(this);
            var id = $button.data('id');

            var confirmed = confirm('@lang('label.confirm_delete')');

            // if confirmed and input has value
            if (confirmed) {
                $button.prop('disabled', true);

                var deleteAPI = "{{ url('/qamanager-category') }}";
                var request = axios.delete(deleteAPI+'/'+id);

                request.then(function (response) {
                    if (response.data.status == 'success') {
                        table.ajax.reload( function (loaded){
                            $.toast({
                                heading: '成功', icon: 'success',
                                position: 'top-right', stack: false, hideAfter: 3000,
                                text: response.data.message,
                                position: { right: 18, top: 68 }
                            });
                        }, false );
                    }
                })
                request.catch(function (error) {
                    console.log(error)
                    $.toast({
                        heading: '失敗', icon: 'error',
                        position: 'top-right', stack: false, hideAfter: 3000,
                        text: [error.response.data.message, error.response.data.error],
                        position: { right: 18, top: 68 }
                    });
                    $button.prop('disabled', false);
                });
                request.then(function () {
                    $button.prop('disabled', false);
                });
            }
        });


        // Order Category
        $datatable.find('tbody').on('click', '.order-btn', function() {
            var api = "{{ route('qamanager-category.order') }}";
            var $row = $(this).closest('tr');
            var postData = $(this).data('row');
            var position = $(this).data('pos');
            postData.position = position;

            axios.post(api, postData).then(function (response) {
                console.log(response);
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