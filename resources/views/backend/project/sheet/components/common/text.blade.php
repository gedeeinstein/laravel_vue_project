@php
    // ----------------------------------------------------------------------
    $name = $name ?? 'input-text-name';
    // ----------------------------------------------------------------------

    // ----------------------------------------------------------------------
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
        $context = "input-{$type}";
        if( $group ) $classGroup->push( $context );
        else $classList->push( $context );
    }
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
            <input type="text" class="{{ $classList->join(' ')}}" :name="name" :id="name" 
            @if( $model ) v-model="{{ $model }}" @endif :disabled="{{ $disabled }}" 
            @if( $validation ) data-parsley-maxlength="128" data-parsley-trigger="change focusout" data-parsley-no-focus @endif 
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
        <input type="text" class="{{ $classList->join(' ')}}" :name="name" :id="name" 
        @if( $model ) v-model="{{ $model }}" @endif :disabled="{{ $disabled }}"
        @if( $validation ) data-parsley-maxlength="128" data-parsley-trigger="change focusout" data-parsley-no-focus @endif 
        @if( !empty( $change )) @change="{{ $change }}" @endif 
        @if( !empty( $value )) :value="{!! $value !!}" @endif />
    </template>
@endif

@if( $validation )
    <div class="form-result"></div>
@endif