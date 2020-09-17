@if( !empty( $name ))
    @php
        $disabled = $disabled ?? 'false';
        $value = $value ?? array( 'true', 'false' );
    @endphp
    <template v-for="name in [{!! $name !!}]">
        <div class="icheck-cyan">
            <input type="checkbox" :id="name" :name="name" data-parsley-checkmin="1" @if( $disabled ) :disabled="{{ $disabled }}" @endif
                @if( array_key_exists( 0, $value )) :true-value="{{ $value[0] }}" @endif 
                @if( array_key_exists( 1, $value )) :false-value="{{ $value[1] }}" @endif
                @if( !empty( $model )) v-model.number="{{ $model }}" @endif 
                @if( !empty( $change )) @change="{{ $change }}" @endif 
                @if( !empty( $attrs )) {{ $attrs }} @endif />
            @if( !empty( $label ))
                <label :for="name" class="fs-12 noselect w-100">
                    <span>{{ $label }}</span>
                </label>
            @endif
        </div>
    </template>
@endif