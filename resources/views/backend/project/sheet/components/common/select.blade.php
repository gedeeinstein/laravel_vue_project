@php
    // ----------------------------------------------------------------------
    $name = $name ?? 'input-select-name';
    // ----------------------------------------------------------------------

    // ----------------------------------------------------------------------
    $disabled = $disabled ?? 'status.loading'; // Disabled state
    $validation = $validation ?? true; // Add validation control or not
    $model = $model ?? false; // Vue model binding
    $muted = $muted ?? false; // Muted text color
    // ----------------------------------------------------------------------
    if( true == $disabled ) $validation = false; // If disabled, validation is skipped
    // ----------------------------------------------------------------------
    $group = $group ?? false; // Render as input-group or not
    $append = $append ?? false; // Input-group append
    $prepend = $prepend ?? false; // Input-group prepend
    $classes = $classes ?? false; // Custom classes
    $change = $change ?? false;
    // ----------------------------------------------------------------------

    // ----------------------------------------------------------------------
    if( is_array( $model ) && 1 == count( $model )) $model = $model[0];
    // ----------------------------------------------------------------------

    // ----------------------------------------------------------------------
    $classList = collect([ 'form-control' ]);
    $classGroup = collect([ 'input-group' ]);
    // ----------------------------------------------------------------------
    if( $classes ) $classList = $classList->merge( $classes );
    if( $muted ) $classList->push( 'text-black-50' );
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
            <select :name="name" :id="name" class="{{ $classList->join(' ')}}"
                @if( $model )
                    @if( !is_array( $model )) v-model="{{ $model }}"
                    @else v-model.{{ $model[0] }}="{{ $model[1] }}" @endif
                @endif
                @if( !empty( $change )) @change="{{ $change }}" @endif :disabled="{{ $disabled }}">
                {{ $options }}
            </select>
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
        <select :name="name" :id="name" class="{{ $classList->join(' ')}}"
            @if( $model )
                @if( !is_array( $model )) v-model="{{ $model }}"
                @else v-model.{{ $model[0] }}="{{ $model[1] }}" @endif
            @endif
            @if( !empty( $change )) @change="{{ $change }}" @endif :disabled="{{ $disabled }}">
            {{ $options }}
        </select>
    </template>
@endif

@if( $validation )
    <div class="form-result"></div>
@endif