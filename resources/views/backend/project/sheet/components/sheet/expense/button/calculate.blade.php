@php
    // ----------------------------------------------------------------------
    $locale = app()->getLocale();
    // ----------------------------------------------------------------------
    $color = $color ?? 'gray';
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
        <span>自</span>
    @else
        <span class="fs-14 text-gray">
            <i class="far fa-calculator"></i>
        </span>
    @endif
</button>