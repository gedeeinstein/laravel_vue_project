@php
    // ----------------------------------------------------------------------
    $locale = app()->getLocale();
    // ----------------------------------------------------------------------
    $color = $color ?? 'gray';
    $click = $click ?? false;
    // ----------------------------------------------------------------------
    $loading = 'status.loading';
    $disabled = $disabled ?? false;
    $disabled = $disabled ? "( {$disabled} ) || {$loading}" : $loading;
    // ----------------------------------------------------------------------
    $classes = collect([ 'btn', 'btn-slim' ]);
    if( $color ) $classes->push( "btn-{$color}" );
    // ----------------------------------------------------------------------
@endphp

<button type="button" class="{{ $classes->join(' ')}}" @if( $click ) @click="{{ $click }}" @endif
    :disabled="{{ $disabled }}">
    @if( 'jp' == $locale )
        <span>自</span>
    @else
        <span class="fs-14 text-gray">
            <i class="far fa-calculator"></i>
        </span>
    @endif
</button>