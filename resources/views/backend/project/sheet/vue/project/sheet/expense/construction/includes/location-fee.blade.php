@component( "{$component->expense}.row" )
    @slot('title')

        <!-- Row label - Start -->
        @component("{$component->expense}.label.default")
            @slot( 'label', __( "{$lang->label}.construction.location" ))
            @slot( 'for', "prefix + 'construction-location-fee'" )
        @endcomponent
        <!-- Row label - End -->

    @endslot
    @slot('button')

        <!-- Auto-calculate button - Start -->
        @component("{$component->expense}.button.calculate")
            @slot( 'disabled', 'isDisabled || isLocationFeeDisabled')
            @slot( 'click', 'calculateLocationFee' )
        @endcomponent
        <!-- Auto-calculate button - End -->

    @endslot
    @slot('input')
        @component("{$component->expense}.column.default")
            @slot('left')

                <!-- Construction - Location fee - Start -->
                @component("{$component->preset}.money")
                    @slot( 'disabled', 'isDisabled || isLocationFeeDisabled' )
                    @slot( 'name', "prefix + 'construction-location-fee'" )
                    @slot( 'model', 'entry.location_designation_application_fee' )
                    @slot( 'attrs' )
                        :class="isLocationFeeDisabled ? 'text-black-50': 'input-money'"
                    @endslot
                @endcomponent
                <!-- Construction - Location fee - End -->
                
            @endslot
            @slot('right')
                
                <!-- Construction - Location fee memo - Start -->
                @component("{$component->preset}.text")
                    @slot( 'disabled', 'isDisabled || isLocationFeeDisabled' )
                    @slot( 'model', 'entry.location_designation_application_fee_memo' )
                    @slot( 'name', "prefix + 'construction-location-fee-memo'" )
                    @slot( 'attrs' )
                        :class="isLocationFeeDisabled ? 'text-black-50': 'input-text'"
                    @endslot
                @endcomponent
                <!-- Construction - Location fee memo - End -->

            @endslot
        @endcomponent
    @endslot
    @slot('total')
        <div class="px-1 col-12">

            @component("{$component->preset}.money")
                @slot( 'disabled', true )
                @slot( 'model', 'totalLocationFee' )
                @slot( 'name', "prefix + 'construction-location-fee-total'" )
                @slot( 'attrs' )
                    :class="isLocationFeeDisabled ? 'text-black-50': ''"
                @endslot
            @endcomponent
            
        </div>
    @endslot
@endcomponent