@php
    // ----------------------------------------------------------------------
    $for = $for ?? false;
    $bold = $bold ?? false;
    $label = $label ?? false;
    // ----------------------------------------------------------------------
    $classes = collect([ 'm-0' ]);
    if( !$bold ) $classes->push( 'fw-n' );
    // ----------------------------------------------------------------------
@endphp
<div class="expense-label text-center row mx-n2">
    <div class="px-2 col-auto col-lg-12">
        <label class="{{ $classes->join(' ')}}" @if( $for ) :for="{!! $for !!}" @endif>{{ $label }}</label>
    </div>
    @if( !empty( $alt ))
        <div class="px-2 col-auto col-lg-12">
            <div class="text-black-50" v-html="{!! $alt !!}"></div>
        </div>
    @endif
</div>