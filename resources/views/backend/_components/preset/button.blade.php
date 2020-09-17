@php
    // ----------------------------------------------------------------------
    $type = $type ?? 'button';
    $color = $color ?? 'info';
    $click = $click ?? false;
    $label = $label ?? 'Button';
    // ----------------------------------------------------------------------
    $classes = collect([ 'btn' ]);
    if( $color ) $classes->push( "btn-{$color}" );
    // ----------------------------------------------------------------------
@endphp
<button type="{{ $type }}" class="{{ $classes->join(' ')}}" @if( $click ) @click="{{ $click }}" @endif
    @if( !empty( $attrs )) {{ $attrs }} @endif>
    @if( $label ) <span>{{ $label }}</span> @endif
</button>