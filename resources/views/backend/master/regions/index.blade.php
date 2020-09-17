@extends('backend._base.content_datatables')

@section('breadcrumbs')
    <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="fas fa-tachometer-alt"></i> @lang('label.dashboard')</a></li>
        <li class="breadcrumb-item active">{{ $page_title }}</li>
    </ol>
@endsection

@section('top_buttons')
    <div class="row mx-n2 justify-content-end flex-nowrap">
        <div class="col-auto px-2">
            <select class="form-control" id="select-prefecture">
                @foreach( $prefectures as $entry )
                    <option value="{{ $entry->url }}" {{ $entry->selected ? 'selected' : '' }}>{{ $entry->label }}</option>
                @endforeach
            </select>
        </div>
    </div>
@endsection

@section('content')
    <th data-col="prefecture_code" width="80">@lang('label.prefecture_code')</th>
    <th data-col="group_code" width="74">@lang('label.group_code')</th>
    <th data-col="name">@lang('label.name_alt')</th>
    <th data-col="kana">@lang('label.name_kana')</th>
    <th data-col="order" width="50">@lang('label.order')</th>
    <th data-col="government_designated_city" width="90">@lang('label.designated_city')</th>
    <th data-col="is_enable" width="50">@lang('label.enabled')</th>
@endsection

@push('css')
    <link rel="stylesheet" href="{{ asset('components/jquery-nice-select/css/nice-select.css') }}">
@endpush

@push('scripts')
    <script src="{{ asset('components/jquery-nice-select/js/jquery.nice-select.min.js') }}"></script>
    <script>
        (function( $, window, document, undefined ){
            $(document).ready( function(){
                // ----------------------------------------------------------
                var $select = $('#select-prefecture');
                // ----------------------------------------------------------

                // ----------------------------------------------------------
                if( 'function' === typeof $.fn.niceSelect ){
                    $select.niceSelect();
                }
                // ----------------------------------------------------------

                // ----------------------------------------------------------
                // Redirect to the selected prefecture
                // ----------------------------------------------------------
                $select.on('change', function(){
                    window.location.href = $select.val();
                });
                // ----------------------------------------------------------
            });
        }( jQuery, window, document ));
    </script>
@endpush
