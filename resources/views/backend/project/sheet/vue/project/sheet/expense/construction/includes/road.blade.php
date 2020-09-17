@component( "{$component->expense}.row" )
    @slot('title')

        <!-- Row label - Start -->
        @component("{$component->expense}.label.default")
            @slot( 'label', __( "{$lang->label}.construction.road" ))
            @slot( 'for', "prefix + 'construction-road-construction'" )
        @endcomponent
        <!-- Row label - End -->

    @endslot
    @slot('button')

        <!-- Auto-calculate button - Start -->
        @component("{$component->expense}.button.calculate")
            @slot( 'click', 'calculateRoad' )
            @slot( 'disabled', 'isDisabled || isRoadConstructionDisabled || !( checklist.new_road_width && checklist.new_road_length )')
        @endcomponent
        <!-- Auto-calculate button - End -->

    @endslot
    @slot('input')
        @component("{$component->expense}.column.default")
            @slot('left')

                <!-- Construction - Road construction - Start -->
                @component("{$component->preset}.money")
                    @slot( 'model', 'entry.road_construction' )
                    @slot( 'disabled', 'isDisabled || isRoadConstructionDisabled' )
                    @slot( 'name', "prefix + 'construction-road-construction'" )
                @endcomponent
                <!-- Construction - Road construction - End -->
                
            @endslot
            @slot('right')
                
                <!-- Construction - Road construction memo - Start -->
                @component("{$component->preset}.text")
                    @slot( 'model', 'entry.road_construction_memo' )
                    @slot( 'disabled', 'isDisabled || isRoadConstructionDisabled' )
                    @slot( 'name', "prefix + 'construction-road-construction-memo'" )
                @endcomponent
                <!-- Construction - Road construction memo - End -->

            @endslot
        @endcomponent
    @endslot
    @slot('total')
        <div class="px-1 col-12">

            @component("{$component->preset}.money")
                @slot( 'disabled', true )
                @slot( 'model', 'totalRoadConstruction' )
                @slot( 'name', "prefix + 'construction-road-construction-total'" )
            @endcomponent
            
        </div>
    @endslot
@endcomponent