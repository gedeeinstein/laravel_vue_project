@php
    // ----------------------------------------------------------------------
    $name = $name ?? 'name';
    // ----------------------------------------------------------------------

    // ----------------------------------------------------------------------
    $negative = $negative ?? 'false'; // Allow negative values
    $disabled = $disabled ?? 'false'; // Disabled state
    $validation = $validation ?? true; // Add validation control or not
    $model = $model ?? false; // Vue model binding
    $muted = $muted ?? false; // Muted text color
    // ----------------------------------------------------------------------
    if( true === $disabled ) $validation = false; // If disabled, validation is skipped
    // ----------------------------------------------------------------------
    $group = $group ?? false; // Render as input-group or not
    $append = $append ?? false; // Input-group append
    $prepend = $prepend ?? false; // Input-group prepend
    $classes = $classes ?? false; // Custom classes
    $placeholder = $placeholder ?? false; // Custom placeholder
    // ----------------------------------------------------------------------
    $type = 'decimal'; // Default type
    $size = $size ?? false; // Default size is medium
    if( !isset( $precision )) $precision = '{min: 0, max: 4}';
    // ----------------------------------------------------------------------


    // ----------------------------------------------------------------------
    if( $group ){ // Input group
        // ------------------------------------------------------------------
        if( $prepend ){ // Prepend label or button
            if( is_string( $prepend )){
                $prepend = (object) array( 'type' => 'label', 'label' => $prepend );
            } elseif( !is_object( $prepend )){
                $prepend = (object) array( 'type' => 'html', 'label' => $prepend );
            }
        }
        // ------------------------------------------------------------------
        if( $append ){ // Append label or button
            if( is_string( $append )){
                $append = (object) array( 'type' => 'label', 'label' => $append );
            } elseif( !is_object( $append )){
                $append = (object) array( 'type' => 'html', 'label' => $prepend );
            }
        }
        // ------------------------------------------------------------------
    }
    // ----------------------------------------------------------------------

    // ----------------------------------------------------------------------
    $classList = collect([ 'form-control' ]);
    $classGroup = collect([ 'input-group' ]);
    // ----------------------------------------------------------------------
    if( $classes ) $classList = $classList->merge( $classes );
    if( $muted ) $classList->push( 'text-black-50' );
    // ----------------------------------------------------------------------

    // ----------------------------------------------------------------------
    // Size settings
    // ----------------------------------------------------------------------
    $sizes = collect([
        'sm' => array( 'sm', 'small' ),
        'lg' => array( 'lg', 'large' ),
    ]);
    // ----------------------------------------------------------------------
    if( $size ) foreach( $sizes as $index => $alias ){
        $alias = collect( $alias );
        if( $alias->contains( $size )){
            if( $group ) $classGroup->push( "input-group-{$index}" );
            else $classList->push( "form-control-{$index}" );
        }
    }
    // ----------------------------------------------------------------------
@endphp

@if( $group )

    <!-- Input group - Start -->
    <div class="{{ $classGroup->join(' ') }}" v-for="name in [{!! $name !!}]" :class="{!! $disabled !!} ? 'input-static': '{{ "input-{$type}" }}'">

        <!-- Input prepend - Start -->
        @if( $prepend )
            @if( !empty( $prepend->type ))
                @if( 'label' == $prepend->type )

                    <!-- Prepend label - Start -->
                    <div class="input-group-prepend">
                        <label class="input-group-text input-group-text-sm" :for="name">
                            @if( !empty( $prepend->label ))
                                <span>{{ $prepend->label }}</span>
                            @endif
                        </label>
                    </div>
                    <!-- Prepend label - End -->

                @elseif( 'button' == $prepend->type )

                    <!-- Prepend button - Start -->
                    <div class="input-group-prepend">
                        <button class="btn btn-input-group btn-dimmed fs-12 px-2" type="button" @if( $disabled ) :disabled="{{ $disabled }}" @endif
                            @if( !empty( $prepend->click )) @click="{{ $prepend->click }}" @endif>
                            <div class="row mx-n2">
                                @if( !empty( $prepend->icon ))
                                    <div class="px-2 col-auto"><i class="{{ $prepend->icon }}"></i></div>
                                @endif
                                @if( !empty( $prepend->label ))
                                    <div class="px-2 col-auto">{{ $prepend->label }}</div>
                                @endif
                            </div>
                        </button>
                    </div>
                    <!-- Prepend button - End -->

                @endif
            @else 

                <!-- HTML prepend slot - Start -->
                <div class="input-group-prepend">
                    <label class="input-group-text input-group-text-sm" :for="name">
                        {{ $prepend }}
                    </label>
                </div>
                <!-- HTML prepend slot - End -->

            @endif
        @endif
        <!-- Input prepend - End -->

        <!-- Input control - Start -->
        <template>
            <currency-input class="{{ $classList->join(' ') }}" :name="name" :id="name" @if( $model ) v-model="{{ $model }}" @endif
                :currency="null" :precision="{{ $precision }}" :allow-negative="{{ $negative }}" @if( $disabled ) :disabled="{{ $disabled }}" @endif
                @if( $validation ) data-parsley-decimal-maxlength="[12,4]" data-parsley-trigger="change focusout" data-parsley-no-focus @endif 
                :class="{!! $disabled !!} ? 'input-static': '{{ "input-{$type}" }}'"
                @if( $placeholder ) :placeholder="{!! $placeholder !!}" @endif
                @if( !empty( $change )) @change="{{ $change }}" @endif 
                @if( !empty( $value )) :value="{!! $value !!}" @endif
                @if( !empty( $attrs )) {{ $attrs }} @endif />
        </template>
        <!-- Input control - End -->

        <!-- Input append - Start -->
        @if( $append )
            @if( !empty( $append->type ))
                @if( 'label' == $append->type )

                    <!-- Append label - Start -->
                    <div class="input-group-append">
                        <label class="input-group-text input-group-text-sm" :for="name">
                            @if( !empty( $append->label ))
                                <span>{{ $append->label }}</span>
                            @endif
                        </label>
                    </div>
                    <!-- Append label - End -->

                @elseif( 'button' == $append->type )
                    
                    <!-- Append button - Start -->
                    <div class="input-group-append">
                        <button class="btn btn-input-group btn-dimmed fs-12 px-2" type="button" @if( $disabled ) :disabled="{{ $disabled }}" @endif
                            @if( !empty( $append->click )) @click="{{ $append->click }}" @endif>
                            <div class="row mx-n2">
                                @if( !empty( $append->icon ))
                                    <div class="px-2 col-auto"><i class="{{ $append->icon }}"></i></div>
                                @endif
                                @if( !empty( $append->label ))
                                    <div class="px-2 col-auto">{{ $append->label }}</div>
                                @endif
                            </div>
                        </button>
                    </div>
                    <!-- Append button - End -->
                    
                @endif
            @else 

                <!-- HTML append slot - Start -->
                <div class="input-group-append">
                    <label class="input-group-text input-group-text-sm" :for="name">
                        {{ $append }}
                    </label>
                </div>
                <!-- HTML append slot - End -->

            @endif
        @endif
        <!-- Input append - End -->

    </div>
    <!-- Input group - End -->

@else 

    <!-- Input control - Start -->
    <template v-for="name in [{!! $name !!}]">
        <currency-input class="{{ $classList->join(' ') }}" :name="name" :id="name" @if( $model ) v-model="{{ $model }}" @endif
            :currency="null" :precision="{{ $precision }}" :allow-negative="{{ $negative }}" @if( $disabled ) :disabled="{{ $disabled }}" @endif
            @if( $validation ) data-parsley-decimal-maxlength="[12,4]" data-parsley-trigger="change focusout" data-parsley-no-focus @endif 
            :class="{!! $disabled !!} ? 'input-static': '{{ "input-{$type}" }}'"
            @if( $placeholder ) :placeholder="{!! $placeholder !!}" @endif
            @if( !empty( $change )) @change="{{ $change }}" @endif 
            @if( !empty( $value )) :value="{!! $value !!}" @endif 
            @if( !empty( $attrs )) {{ $attrs }} @endif />
    </template>
    <!-- Input group - End -->

@endif
@if( $validation )

    <!-- Validation message - Start -->
    <div class="form-result"></div>
    <!-- Validation message - End -->

@endif