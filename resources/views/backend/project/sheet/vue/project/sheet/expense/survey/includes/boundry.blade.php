@component( "{$component->expense}.row" )
    @slot('title')

        <!-- Row label - Start -->
        @component("{$component->expense}.label.default")
            @slot( 'for', "prefix + 'survey-survey'" )
            @slot( 'label', __( "{$lang->label}.survey.boundry" ))
        @endcomponent
        <!-- Row label - End -->

    @endslot
    @slot('input')
        @component("{$component->expense}.column.default")
            @slot('left')

                <!-- Survey - Boundry restoration - Start -->
                @component("{$component->preset}.money")
                    @slot( 'disabled', 'isDisabled' )
                    @slot( 'model', 'entry.boundary_pile_restoration' )
                    @slot( 'name', "prefix + 'survey-survey'" )
                @endcomponent
                <!-- Survey - Boundry restoration - End -->
                
            @endslot
            @slot('right')
                
                <!-- Survey - Boundry restoration memo - Start -->
                @component("{$component->preset}.text")
                    @slot( 'disabled', 'isDisabled' )
                    @slot( 'model', 'entry.boundary_pile_restoration_memo' )
                    @slot( 'name', "prefix + 'survey-boundry-memo'" )
                @endcomponent
                <!-- Survey - Boundry restoration memo - End -->

            @endslot
        @endcomponent
    @endslot
    @slot('total')
        <div class="px-1 col-12">

            @component("{$component->preset}.money")
                @slot( 'disabled', true )
                @slot( 'model', 'totalBoundryRestoration' )
                @slot( 'name', "prefix + 'survey-boundry-total'" )
            @endcomponent
            
        </div>
    @endslot
@endcomponent