@php
    $href = '#';
    if( !empty( $url )) $href = $url;
@endphp
<div class="px-2 col-12 col-sm-auto d-flex justify-content-center align-items-center mt-2 mt-sm-0">
    <a href="{{ $href }}" class="btn btn-page btn-default ellipsis">
        <div class="row mx-n1 flex-nowrap">
            {{ $slot }}
        </div>
    </a>
</div>