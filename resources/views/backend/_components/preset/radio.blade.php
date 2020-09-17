@php
    $disabled = $disabled ?? 'false';
    $readOnly = $readOnly ?? false;
    $name = $name ?? 'name';
    $id = $id ?? "{$name} + '-{$value}'";
@endphp
<template v-for="id in [{!! $id !!}]">
    <div class="icheck-cyan">
        <input type="radio" :id="id" :name="{{ $name }}" data-parsley-checkmin="1"
            @if( $disabled ) :disabled="{{ $disabled }}" @endif @if( !empty( $value )) :value="{{ $value }}" @endif 
            @if( !empty( $model )) v-model="{{ $model }}" @endif @if( $readOnly ) readonly @endif 
            @if( !empty( $attrs )) {{ $attrs }} @endif />
        @if( !empty( $label ))
            <label :for="id" class="fs-12 noselect w-100">
                <span>{{ $label }}</span>
            </label>
        @endif
    </div>
</template>