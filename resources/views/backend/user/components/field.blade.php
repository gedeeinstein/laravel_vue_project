@php
    $optional = true; $items = null;
    if( !empty( $required )) $optional = !$required;
    if( !empty( $align )){
        if( 'top' == $align ) $items = 'align-items-start';
        elseif( 'bottom' == $align ) $items = 'align-items-end';
        elseif( 'center' == $align ) $items = 'align-items-center';
    }
@endphp
<div class="row form-group py-3 {{ $items ? $items : '' }}">
    <div class="col-md-4 col-lg-3 col-header">
        @if( $optional )
            <span class="bg-success label-required">@lang('label.optional')</span>
        @else 
            <span class="bg-danger label-required">@lang('label.required')</span>
        @endif
        @if( !empty( $label ))
            <strong class="field-title">{{ $label }}</strong>
        @endif
    </div>
    <div class="col-md-8 col-lg-9 col-content">
        <div class="row">
            {{ $slot }}
        </div>
    </div>
</div>