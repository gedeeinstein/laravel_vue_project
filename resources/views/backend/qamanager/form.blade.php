@extends('backend._base.content_form')

@section('page_title')
    <div class="row mx-n2">
        <div class="px-2 col-auto">
            <div class="d-block d-md-none">
                <a href="{{ route('qamanager.index') }}" class="btn btn-sm btn-default">
                    <i class="fas fa-chevron-left"></i>
                    <span>@lang('label.list')</span>
                </a>
            </div>
            <div class="d-none d-md-block">
                <a href="{{ route('qamanager.index') }}" class="btn btn-default">
                    <i class="fas fa-chevron-left"></i>
                    <span>@lang('label.list')</span>
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
    @if( $page_type <> "create" && auth()->user()->user_role->name == 'global_admin')
        <a href="{{route('qamanager.create')}}" class="btn btn-sm btn-info float-sm-right"><i class="fas fa-plus-circle mr-1"></i> @lang('label.createNew')</a>
    @endif
@endsection

@section('content')
    @component('backend._components.form_container', [ 'action' => $form_action, 'page_type' => $page_type, 'files' => false ])
        <!-- Input Category -->
        @component('backend._components.input_select', [
            'name' => 'category_id',
            'options' => $categories,
            'label' => __('label.qamanager.category'),
            'required' => 1,
            'value' => $item->category_id])
        @endcomponent
        <!-- Input Question -->
        @component('backend._components.input_textarea', [
            'name' => 'question',
            'label' => __('label.qamanager.question'),
            'required' => 1,
            'rows' => 3,
            'value' => $item->question])
        @endcomponent
        <!-- Input Types -->
        @component('backend._components.input_radio', [
            'name' => 'input_type',
            'options' => $input_types,
            'label' => __('label.qamanager.input_type'),
            'required' => 1,
            'value_key' => true,
            'value' => $item->input_type])
        @endcomponent
        <!-- Input Choices -->
        @component('backend._components.input_textarea', [
            'name' => 'choices',
            'label' => __('label.qamanager.choices'),
            'required' => 0,
            'rows' => 2,
            'class' => 'tagify',
            'value' => $item->choices])
            <p class="mt-1">ラジオボタン・チェックボックスの選択肢を改行区切りで入力してください。</p>
        @endcomponent
        <!-- Input Status -->
        @component('backend._components.input_radio', [
            'name' => 'status',
            'options' => $input_status,
            'label' => __('label.qamanager.status'),
            'required' => 1,
            'value_key' => true,
            'value' => $item->status])
            <p class="mt-2">無効にすると、PJシート&チェックリスト画面に非表示になります。</p>
        @endcomponent
        <!-- Button Submit and Delete -->
        @component('backend._components.input_buttons', [
            'page_type' => $page_type ])

            @if ($page_type <> 'create')
                <button data-id="{{ $item->id }}" class="btn btn-danger ml-1" id="input-delete">
                    <i class="fas fa-trash"></i> {{ __('label.delete')  }}
                </button>
            @endif

        @endcomponent
    @endcomponent
@endsection

@push('css')
    <link rel="stylesheet" href="{{ asset('components/@yaireo/tagify/dist/tagify.css') }}">
@endpush

@push('scripts')
    <script src="{{ asset('components/@yaireo/tagify/dist/tagify.min.js') }}"></script>
    <script>
        (function( $, window, document, undefined ){
            $(document).ready( function(){
                // init option tagify input
                var $option = document.querySelector('textarea[name=choices]'),
                tagify = new Tagify($option);

                // hide option input if type textarea
                // hide option on ready
                var type = $('input[type=radio][name=input_type]:checked').val();
                if(type == 3) $('#form-group--choices').hide();

                // hide option on change
                $('input[type=radio][name=input_type]').change(function() {
                    if(this.value == 3) {
                        $('#form-group--choices').slideUp('fast');
                        tagify.removeAllTags();
                    }
                    else $('#form-group--choices').slideDown('fast');
                });

                $("#input-delete").click(function(e) {
                    e.preventDefault();
                    var confirmed = confirm('@lang('label.confirm_delete')');
                    var id = $(this).data('id');
                    var delete_url = "{{ url('/qamanager') }}";

                    if (confirmed) {
                        var request = axios.delete(delete_url+'/'+id);

                        request.then(function (response) {
                            if (response.data.status == 'success') {
                                window.location = '{{ url('/qamanager') }}';
                            }
                        })
                        request.catch(function (error) {
                            console.log(error)
                            window.location = '{{ url('/qamanager') }}';
                        });
                    }
                });
            });
        }( jQuery, window, document ));
    </script>
@endpush