@component( "{$component->expense}.row" )
    @slot('title')

        <!-- Row label - Start -->
        @component("{$component->expense}.label.default")
            @slot( 'for', "prefix + 'survey-divisional'" )
            @slot( 'label', __( "{$lang->label}.survey.divisional" ))
        @endcomponent
        <!-- Row label - End -->

    @endslot
    @slot('input')
        @component("{$component->expense}.column.default")
            @slot('left')

                <!-- Survey - Divisional registration - Start -->
                @component("{$component->preset}.money")
                    @slot( 'disabled', 'isDisabled' )
                    @slot( 'model', 'entry.divisional_registration' )
                    @slot( 'name', "prefix + 'survey-divisional'" )
                @endcomponent
                <!-- Survey - Divisional registration - End -->
                
            @endslot
            @slot('right')
                
                <!-- Survey - Divisional registration memo - Start -->
                @component("{$component->preset}.text")
                    @slot( 'disabled', 'isDisabled' )
                    @slot( 'model', 'entry.divisional_registration_memo' )
                    @slot( 'name', "prefix + 'survey-divisional-memo'" )
                @endcomponent
                <!-- Survey - Divisional registration memo - End -->

            @endslot
        @endcomponent
    @endslot
    @slot('total')
        <div class="px-1 col-12">

            @component("{$component->preset}.money")
                @slot( 'disabled', true )
                @slot( 'model', 'totalDivisionalRegistration' )
                @slot( 'name', "prefix + 'survey-divisional-total'" )
            @endcomponent
            
        </div>
    @endslot
@endcomponent