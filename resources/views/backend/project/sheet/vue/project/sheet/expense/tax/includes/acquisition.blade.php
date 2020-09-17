@component( "{$component->expense}.row" )
    @slot('title')

        <!-- Row label - Start -->
        @component("{$component->expense}.label.default")
            @slot( 'for', "prefix + 'tax-acquisition'" )
            @slot( 'label', __( "{$lang->label}.tax.acquisition" ))
        @endcomponent
        <!-- Row label - End -->

    @endslot
    @slot('button')

        <!-- Auto-calculate button - Start -->
        @component("{$component->expense}.button.calculate")
            @slot( 'click', 'calculateAcquisitionTax' )
        @endcomponent
        <!-- Auto-calculate button - End -->

    @endslot
    @slot('input')
        @component("{$component->expense}.column.default")
            @slot('left')

                <!-- Tax - Property acquisition tax - Start -->
                @component("{$component->preset}.money")
                    @slot( 'disabled', 'isDisabled' )
                    @slot( 'model', 'entry.property_acquisition_tax' )
                    @slot( 'name', "prefix + 'tax-acquisition'" )
                @endcomponent
                <!-- Tax - Property acquisition tax - End -->
                
            @endslot
            @slot('right')
                
                <!-- Tax - Property acquisition tax memo - Start -->
                @component("{$component->preset}.text")
                    @slot( 'disabled', 'isDisabled' )
                    @slot( 'model', 'entry.property_acquisition_tax_memo' )
                    @slot( 'name', "prefix + 'tax-acquisition-memo'" )
                @endcomponent
                <!-- Tax - Property acquisition tax memo - End -->

            @endslot
        @endcomponent
    @endslot
    @slot('total')
        <div class="px-1 col-sm d-flex align-items-center">
            <span>@lang("{$lang->label}.tax.acquisition_note")</span>
        </div>
    @endslot
@endcomponent