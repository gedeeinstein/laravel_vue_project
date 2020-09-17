@component( "{$component->expense}.row" )
    @slot('title')

        <!-- Row label - Start -->
        @component("{$component->expense}.label.default")
            @slot( 'label', __( "{$lang->label}.construction.cultural" ))
            @slot( 'for', "prefix + 'construction-cultural-fee'" )
        @endcomponent
        <!-- Row label - End -->

    @endslot
    @slot('button')

        <!-- Auto-calculate button - Start -->
        @component("{$component->expense}.button.calculate")
            @slot( 'click', 'calculateCulturalFee' )
            @slot( 'disabled', 'isCulturalPropertyDisabled' )
        @endcomponent
        <!-- Auto-calculate button - End -->

    @endslot
    @slot('input')
        @component("{$component->expense}.column.default")
            @slot('left')

                @component("{$component->preset}.money")
                    @slot( 'disabled', 'isDisabled || isCulturalPropertyDisabled' )
                    @slot( 'model', 'entry.cultural_property_research_fee' )
                    @slot( 'name', "prefix + 'construction-cultural-fee'" )
                    @slot( 'attrs' )
                        :class="isCulturalPropertyDisabled ? 'text-black-50': 'input-money'"
                    @endslot
                @endcomponent
                
            @endslot
            @slot('right')

                @component("{$component->preset}.text")
                    @slot( 'disabled', 'isDisabled || isCulturalPropertyDisabled' )
                    @slot( 'model', 'entry.cultural_property_research_fee_memo' )
                    @slot( 'name', "prefix + 'construction-cultural-fee-memo'" )
                    @slot( 'attrs' )
                        :class="isCulturalPropertyDisabled ? 'text-black-50': 'input-text'"
                    @endslot
                @endcomponent

            @endslot
        @endcomponent
    @endslot
    @slot('total')
        <div class="px-1 col-12">

            @component("{$component->preset}.money")
                @slot( 'disabled', true )
                @slot( 'model', 'totalCultural' )
                @slot( 'name', "prefix + 'construction-cultural-total'" )
                @slot( 'attrs' )
                    :class="isCulturalPropertyDisabled ? 'text-black-50': ''"
                @endslot
            @endcomponent
            
        </div>
    @endslot
@endcomponent