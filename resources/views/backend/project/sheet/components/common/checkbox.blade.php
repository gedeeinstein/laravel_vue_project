@if( !empty( $name ))
    @php
        $disabled = $disabled ?? 'status.loading';
        $value = $value ?? array( 1, 0 );
    @endphp
    <template v-for="name in [{!! $name !!}]">
        <div class="icheck-cyan">
            <input type="checkbox" :id="name" :name="name" data-parsley-checkmin="1" :disabled="{{ $disabled }}" 
                @if( isset( $value[0] )) :true-value="{{ $value[0] }}" @endif 
                @if( isset( $value[1] )) :false-value="{{ $value[1] }}" @endif
                @if( !empty( $model )) v-model.number="{{ $model }}" @endif />
            @if( !empty( $label ))
                <label :for="name" class="fs-12 noselect w-100">
                    <span>{{ $label }}</span>
                </label>
            @endif
        </div>
    </template>
@endif