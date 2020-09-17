@php
    // ----------------------------------------------------------------------
    $locale = app()->getLocale();
    // ----------------------------------------------------------------------
    $color = $color ?? 'secondary';
    $click = $click ?? false;
    // ----------------------------------------------------------------------
    $disabled = $disabled ?? 'false';
    // ----------------------------------------------------------------------
    $classes = collect([ 'btn', 'btn-slim' ]);
    if( $color ) $classes->push( "btn-{$color}" );
    // ----------------------------------------------------------------------
@endphp

<button type="button" class="{{ $classes->join(' ')}}" @if( $click ) @click="{{ $click }}" @endif
    @if( $disabled ) :disabled="{{ $disabled }} @endif">
    @if( 'jp' == $locale )
        <span>消</span>
    @else
        <span class="fs-14 text-white">
            <i class="far fa-trash-alt"></i>
        </span>
    @endif
</button>