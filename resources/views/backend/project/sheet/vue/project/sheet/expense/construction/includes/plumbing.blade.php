@component( "{$component->expense}.row" )
    @slot('title')

        <!-- Row label - Start -->
        @component("{$component->expense}.label.default")
            @slot( 'for', "prefix + 'construction-plumbing'" )
            @slot( 'label', __( "{$lang->label}.construction.plumbing" ))
        @endcomponent
        <!-- Row label - End -->

    @endslot
    @slot('button')

        <!-- Auto-calculate button - Start -->
        @component("{$component->expense}.button.calculate")
            @slot( 'disabled', '!checklist.water_draw_count')
            @slot( 'click', 'calculatePlumbing' )
        @endcomponent
        <!-- Auto-calculate button - End -->

    @endslot
    @slot('input')
        @component("{$component->expense}.column.default")
            @slot('left')

                <!-- Construction - Plumbing - Start -->
                @component("{$component->preset}.money")
                    @slot( 'disabled', 'isDisabled' )
                    @slot( 'model', 'entry.waterwork_construction' )
                    @slot( 'name', "prefix + 'construction-plumbing'" )
                @endcomponent
                <!-- Construction - Plumbing - End -->
                
            @endslot
            @slot('right')
                
                <!-- Construction - Plumbing memo - Start -->
                @component("{$component->preset}.text")
                    @slot( 'disabled', 'isDisabled' )
                    @slot( 'model', 'entry.waterwork_construction_memo' )
                    @slot( 'name', "prefix + 'construction-plumbing-memo'" )
                @endcomponent
                <!-- Construction - Plumbing memo - End -->

            @endslot
        @endcomponent
    @endslot
    @slot('total')
        <div class="px-1 col-12">

            @component("{$component->preset}.money")
                @slot( 'disabled', true )
                @slot( 'model', 'totalPlumbing' )
                @slot( 'name', "prefix + 'construction-plumbing-total'" )
            @endcomponent
            
        </div>
    @endslot
@endcomponent