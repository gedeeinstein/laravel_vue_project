@component( "{$component->expense}.row" )
    @slot('title')

        <!-- Row label - Start -->
        @component("{$component->expense}.label.default")
            @slot( 'for', "prefix + 'survey-fixed'" )
            @slot( 'label', __( "{$lang->label}.survey.fixed" ))
        @endcomponent
        <!-- Row label - End -->

    @endslot
    @slot('input')
        @component("{$component->expense}.column.default")
            @slot('left')

                <!-- Survey - Fixed survey - Start -->
                @component("{$component->preset}.money")
                    @slot( 'disabled', 'isDisabled' )
                    @slot( 'model', 'entry.fixed_survey' )
                    @slot( 'name', "prefix + 'survey-fixed'" )
                @endcomponent
                <!-- Survey - Fixed survey - End -->
                
            @endslot
            @slot('right')
                
                <!-- Survey - Fixed survey memo - Start -->
                @component("{$component->preset}.text")
                    @slot( 'disabled', 'isDisabled' )
                    @slot( 'model', 'entry.fixed_survey_memo' )
                    @slot( 'name', "prefix + 'survey-fixed-memo'" )
                @endcomponent
                <!-- Survey - Fixed survey memo - End -->

            @endslot
        @endcomponent
    @endslot
    @slot('total')
        <div class="px-1 col-12">

            @component("{$component->preset}.money")
                @slot( 'disabled', true )
                @slot( 'model', 'totalFixedSurvey' )
                @slot( 'name', "prefix + 'survey-fixed-total'" )
            @endcomponent
            
        </div>
    @endslot
@endcomponent