@component( "{$component->expense}.row" )
    @slot('title')

        <!-- Row label - Start -->
        @component("{$component->expense}.label.default")
            @slot( 'for', "prefix + 'construction-building-demolition'" )
            @slot( 'label', __( "{$lang->label}.construction.demolition.building" ))
            @slot( 'alt', "'(' +tsuboDemolition+ '円/坪)'" )
        @endcomponent
        <!-- Row label - End -->

    @endslot
    @slot('button')
    
        <!-- Auto-calculate button - Start -->
        @component("{$component->expense}.button.calculate")
            @slot( 'disabled', 'isDisabled || isBuildingDemolitionDisabled' )
            @slot( 'click', 'calculateBuildingDemolition' )
        @endcomponent
        <!-- Auto-calculate button - End -->

    @endslot
    @slot('input')
        @component("{$component->expense}.column.default")
            @slot('left')

                <!-- Construction - Building demolition - Start -->
                @component("{$component->preset}.money")
                    @slot( 'disabled', 'isDisabled || isBuildingDemolitionDisabled' )
                    @slot( 'model', 'entry.building_demolition' )
                    @slot( 'name', "prefix + 'construction-building-demolition'" )
                    @slot( 'attrs' )
                        :class="isBuildingDemolitionDisabled ? 'text-black-50': 'input-money'"
                    @endslot
                @endcomponent
                <!-- Construction - Building demolition - End -->
                
            @endslot
            @slot('right')
                
                <!-- Construction - Building demolition memo - Start -->
                @component("{$component->preset}.text")
                    @slot( 'disabled', 'isDisabled || isBuildingDemolitionDisabled' )
                    @slot( 'model', 'entry.building_demolition_memo' )
                    @slot( 'name', "prefix + 'construction-building-demolition-memo'" )
                    @slot( 'attrs' )
                        :class="isBuildingDemolitionDisabled ? 'text-black-50': 'input-text'"
                    @endslot
                @endcomponent
                <!-- Construction - Building demolition memo - End -->

            @endslot
        @endcomponent
    @endslot
    @slot('total')
        <div class="px-1 col-12">

            @component("{$component->preset}.money")
                @slot( 'disabled', true )
                @slot( 'model', 'totalBuildingDemolition' )
                @slot( 'name', "prefix + 'construction-building-demolition-total'" )
                @slot( 'attrs' )
                    :class="isBuildingDemolitionDisabled ? 'text-black-50': ''"
                @endslot
            @endcomponent
            
        </div>
    @endslot
@endcomponent