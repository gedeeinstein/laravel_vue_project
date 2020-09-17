@component( "{$component->expense}.row" )
    @slot('title')

        <!-- Row label - Start -->
        @component("{$component->expense}.label.default")
            @slot( 'for', "prefix + 'tax-annual'" )
            @slot( 'label', __( "{$lang->label}.tax.annual" ))
        @endcomponent
        <!-- Row label - End -->

    @endslot
    @slot('button')

        <!-- Auto-calculate button - Start -->
        @component("{$component->expense}.button.calculate")
            @slot( 'click', 'calculateAnnualTax' )
        @endcomponent
        <!-- Auto-calculate button - End -->

    @endslot
    @slot('input')
        @component("{$component->expense}.column.default")
            @slot('left')

                <!-- Tax - The following year tax - Start -->
                @component("{$component->preset}.money")
                    @slot( 'disabled', 'isDisabled' )
                    @slot( 'name', "prefix + 'tax-annual'" )
                    @slot( 'model', 'entry.the_following_year_the_city_tax' )
                @endcomponent
                <!-- Tax - The following year tax - End -->
                
            @endslot
            @slot('right')
                
                <!-- Tax - The following year tax memo - Start -->
                @component("{$component->preset}.text")
                    @slot( 'disabled', 'isDisabled' )
                    @slot( 'name', "prefix + 'tax-annual-memo'" )
                    @slot( 'model', 'entry.the_following_year_the_city_tax_memo' )
                @endcomponent
                <!-- Tax - The following year tax memo - End -->

            @endslot
        @endcomponent
    @endslot
    @slot('total')
        <div class="px-1 col-sm">
            @component("{$component->preset}.money")
                @slot( 'disabled', true )
                @slot( 'name', "prefix + 'tax-annual-total'" )
                @slot( 'model', 'totalAnnual' )
            @endcomponent
        </div>
    @endslot
@endcomponent