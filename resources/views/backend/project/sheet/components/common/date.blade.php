@php
    // ----------------------------------------------------------------------
    $name = $name ?? 'input-date-name';
    // ----------------------------------------------------------------------

    // ----------------------------------------------------------------------
    $disabled = $disabled ?? 'false'; // Disabled state
    $validation = $validation ?? true; // Add validation control or not
    $model = $model ?? false; // Vue model binding
    $muted = $muted ?? false; // Muted text color
    // ----------------------------------------------------------------------
    if( true === $disabled ) $validation = false; // If disabled, validation is skipped
    // ----------------------------------------------------------------------
    $type = $type ?? false; // The input type, integer, decimal, money, date, static, suggest
    $group = $group ?? false; // Render as input-group or not
    $append = $append ?? false; // Input-group append
    $prepend = $prepend ?? false; // Input-group prepend
    $classes = $classes ?? false; // Custom classes
    $change = $change ?? false;
    $format = $format ?? "'YYYY/MM/DD'";
    // ----------------------------------------------------------------------

    // ----------------------------------------------------------------------
    $classList = collect([ 'form-control' ]);
    $classGroup = collect([ 'input-group' ]);
    // ----------------------------------------------------------------------
    if( $classes ) $classList = $classList->merge( $classes );
    if( $muted ) $classList->push( 'text-black-50' );
    // ----------------------------------------------------------------------
    if( !$type ){
        $type = 'date'; // Type is date by default
        if( true === $disabled ) $type = 'static'; // If disabled set type to static
    }
    // ----------------------------------------------------------------------
    if( $type ){
        $context = "input-{$type}";
        $classList->push( $context );
        if( $group ) $classGroup->push( $context );
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
            <date-picker @if( $model ) v-model="{{ $model }}" @endif type="date" 
                input-class="{{ $classList->join(' ') }}" :name="name" :id="name" 
                @if( $disabled ) :disabled="{{ $disabled }}" @endif 
                :editable="false" @if( $format ) :format="{!! $format !!}" value-type="format" @endif
                @if( !empty( $change )) @change="{{ $change }}" @endif 
                @if( !empty( $value )) :value="{!! $value !!}" @endif>
            </date-picker>
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
        <date-picker @if( $model ) v-model="{{ $model }}" @endif type="date" 
            input-class="{{ $classList->join(' ') }}" :name="name" :id="name" 
            @if( $disabled ) :disabled="{{ $disabled }}" @endif 
            :editable="false" @if( $format ) :format="{!! $format !!}" value-type="format" @endif
            @if( !empty( $change )) @change="{{ $change }}" @endif 
            @if( !empty( $value )) :value="{!! $value !!}" @endif>
        </date-picker>
    </template>
@endif

@if( $validation )
    <div class="form-result"></div>
@endif