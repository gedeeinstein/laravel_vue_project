
@php
    // ----------------------------------------------------------------------
    $name = $name ?? 'input-currency-name';
    // ----------------------------------------------------------------------

    // ----------------------------------------------------------------------
    $negative = $negative ?? 'false'; // Allow negative values
    $disabled = $disabled ?? 'status.loading'; // Disabled state
    $validation = $validation ?? true; // Add validation control or not
    $model = $model ?? false; // Vue model binding
    $muted = $muted ?? false; // Muted text color
    // ----------------------------------------------------------------------
    if( true == $disabled ) $validation = false; // If disabled, validation is skipped
    // ----------------------------------------------------------------------
    $type = $type ?? false; // The input type, integer, decimal, money, date, static, suggest
    $group = $group ?? false; // Render as input-group or not
    $append = $append ?? false; // Input-group append
    $prepend = $prepend ?? false; // Input-group prepend
    $classes = $classes ?? false; // Custom classes
    // ----------------------------------------------------------------------

    // ----------------------------------------------------------------------
    $classList = collect([ 'form-control' ]);
    $classGroup = collect([ 'input-group' ]);
    // ----------------------------------------------------------------------
    if( $classes ) $classList = $classList->merge( $classes );
    if( $muted ) $classList->push( 'text-black-50' );
    // ----------------------------------------------------------------------
    if( !$type && true === $disabled ) $type = 'static'; // If disabled type is static by default
    // ----------------------------------------------------------------------
    if( $type ){
        // ------------------------------------------------------------------
        $type = strtolower( $type );
        $context = "input-{$type}";
        // ------------------------------------------------------------------
        if( $group ) $classGroup->push( $context );
        else $classList->push( $context );
        // ------------------------------------------------------------------
        if( 'money' === $type ) $maxlength = '[12,0]';
        elseif( 'decimal' === $type ){
            $maxlength = '[12,4]';
            if( !isset( $precision )) $precision = 4;
        }
        // ------------------------------------------------------------------
    }
    // ----------------------------------------------------------------------

    // ----------------------------------------------------------------------
    $precision = $precision ?? 0; // Decimal precision. Integer or object, eg { min: 0, max: 4 }
    // ----------------------------------------------------------------------
@endphp

@if( $group )
    <div class="{{ $classGroup->join(' ') }}" v-for="name in [{!! $name !!}]">
        @if( $prepend )
            <div class="input-group-prepend">
                <label class="input-group-text input-group-text-sm" :for="name">
                    <span>{{ $prepend }}</span>
                </label>
            </div>
        @endif
        <template>
            <currency-input class="{{ $classList->join(' ') }}" :name="name" :id="name" @if( $model ) v-model="{{ $model }}" @endif
                :currency="null" :precision="{{ $precision }}" :allow-negative="{{ $negative }}" :disabled="{{ $disabled }}"
                @if( $validation ) 
                    @if( !empty( $maxlength )) data-parsley-decimal-maxlength="[12,0]" @endif
                    data-parsley-trigger="change focusout" data-parsley-no-focus 
                @endif 
                @if( !empty( $change )) @change="{{ $change }}" @endif 
                @if( !empty( $value )) :value="{!! $value !!}" @endif />
        </template>
        @if( $append )
            <div class="input-group-append">
                <label class="input-group-text input-group-text-sm" :for="name">
                    <span>{{ $append }}</span>
                </label>
            </div>
        @endif
    </div>
@else 
    <template v-for="name in [{!! $name !!}]">
        <currency-input class="{{ $classList->join(' ') }}" :name="name" :id="name" @if( $model ) v-model="{{ $model }}" @endif
            :currency="null" :precision="{{ $precision}}" :allow-negative="{{ $negative }}" :disabled="{{ $disabled }}"
            @if( $validation ) data-parsley-decimal-maxlength="[12,0]" data-parsley-trigger="change focusout" data-parsley-no-focus @endif 
            @if( !empty( $change )) @change="{{ $change }}" @endif 
            @if( !empty( $value )) :value="{!! $value !!}" @endif />
    </template>
@endif

@if( $validation )
    <div class="form-result"></div>
@endif