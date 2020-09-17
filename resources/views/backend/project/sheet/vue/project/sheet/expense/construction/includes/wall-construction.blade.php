@component( "{$component->expense}.row" )
    @slot('title')

        <!-- Row label - Start -->
        @component("{$component->expense}.label.default")
            @slot( 'for', "prefix + 'construction-retaining-wall'" )
            @slot( 'label', __( "{$lang->label}.construction.retaining_wall" ))
        @endcomponent
        <!-- Row label - End -->

    @endslot
    @slot('button')

        <!-- Auto-calculate button - Start -->
        @component("{$component->expense}.button.calculate")
            @slot( 'click', 'calculateRetainingWall' )
            @slot( 'disabled', 'isWallConstructionDisabled' )
        @endcomponent
        <!-- Auto-calculate button - End -->

    @endslot
    @slot('input')
        @component("{$component->expense}.column.default")
            @slot('left')

                <!-- Construction - Retaining wall construction - Start -->
                @component("{$component->preset}.money")
                    @slot( 'disabled', 'isDisabled' )
                    @slot( 'model', 'entry.retaining_wall_construction' )
                    @slot( 'name', "prefix + 'construction-retaining-wall'" )
                @endcomponent
                <!-- Construction - Retaining wall construction - End -->
                
            @endslot
            @slot('right')
                
                <!-- Construction - Retaining wall construction memo - Start -->
                @component("{$component->preset}.text")
                    @slot( 'disabled', 'isDisabled' )
                    @slot( 'model', 'entry.retaining_wall_construction_memo' )
                    @slot( 'name', "prefix + 'construction-retaining-wall-memo'" )
                @endcomponent
                <!-- Construction - Retaining wall construction memo - End -->

            @endslot
        @endcomponent
    @endslot
    @slot('total')
        <div class="px-1 col-12">

            @component("{$component->preset}.money")
                @slot( 'disabled', true )
                @slot( 'model', 'totalWallConstruction' )
                @slot( 'name', "prefix + 'construction-retaining-wall-total'" )
            @endcomponent
            
        </div>
    @endslot
@endcomponent