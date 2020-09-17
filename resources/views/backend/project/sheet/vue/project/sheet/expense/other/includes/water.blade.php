@component( "{$component->expense}.row" )
    @slot('title')

        <!-- Row label - Start -->
        @component("{$component->expense}.label.default")
            @slot( 'for', "prefix + 'other-water'" )
            @slot( 'label', __( "{$lang->label}.other.water" ))
        @endcomponent
        <!-- Row label - End -->
        
    @endslot
    @slot('input')
    @component("{$component->expense}.column.default")
            @slot('left')

                <!-- Other - Water subscription - Start -->
                @component("{$component->preset}.money")
                    @slot( 'disabled', 'isDisabled' )
                    @slot( 'model', 'entry.water_supply_subscription' )
                    @slot( 'name', "prefix + 'other-water'" )
                @endcomponent
                <!-- Other - Water subscription - End -->
                
            @endslot
            @slot('right')
                
                <!-- Other - Water subscription memo - Start -->
                @component("{$component->preset}.text")
                    @slot( 'disabled', 'isDisabled' )
                    @slot( 'model', 'entry.water_supply_subscription_memo' )
                    @slot( 'name', "prefix + 'other-water-memo'" )
                @endcomponent
                <!-- Other - Water subscription memo - End -->

            @endslot
        @endcomponent
    @endslot
    @slot('total')
        <div class="px-1 col-12">

            <!-- Other - Water subscription total - Start -->
            @component("{$component->preset}.money")
                @slot( 'disabled', true )
                @slot( 'model', 'totalWaterSubscription' )
                @slot( 'name', "prefix + 'other-water-total'" )
            @endcomponent
            <!-- Other - Water subscription total - End -->

        </div>
    @endslot
@endcomponent