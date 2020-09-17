<template v-if="!isEmbankmentConstructionHidden">
    @component( "{$component->expense}.row" )
        @slot('title')
    
            <!-- Row label - Start -->
            @component("{$component->expense}.label.default")
                @slot( 'for', "prefix + 'construction-embankment'" )
                @slot( 'label', __( "{$lang->label}.construction.embankment" ))
            @endcomponent
            <!-- Row label - End -->
    
        @endslot
        @slot('button')
    
            <!-- Auto-calculate button - Start -->
            @component("{$component->expense}.button.calculate")
                @slot( 'disabled', 'isDisabled' )
                @slot( 'click', 'calculateEmbankment' )
            @endcomponent
            <!-- Auto-calculate button - End -->
    
        @endslot
        @slot('input')
            @component("{$component->expense}.column.default")
                @slot('left')
    
                    <!-- Construction - Embankment construction - Start -->
                    @component("{$component->preset}.money")
                        @slot( 'disabled', 'isDisabled' )
                        @slot( 'model', 'entry.fill_work' )
                        @slot( 'name', "prefix + 'construction-embankment'" )
                    @endcomponent
                    <!-- Construction - Embankment construction - End -->
                    
                @endslot
                @slot('right')
                    
                    <!-- Construction - Embankment construction memo - Start -->
                    @component("{$component->preset}.text")
                        @slot( 'disabled', 'isDisabled' )
                        @slot( 'model', 'entry.fill_work_memo' )
                        @slot( 'name', "prefix + 'construction-embankment-memo'" )
                    @endcomponent
                    <!-- Construction - Embankment construction memo - End -->
    
                @endslot
            @endcomponent
        @endslot
        @slot('total')
            <div class="px-1 col-12">
    
                @component("{$component->preset}.money")
                    @slot( 'disabled', true )
                    @slot( 'model', 'totalEmbankment' )
                    @slot( 'name', "prefix + 'construction-embankment-total'" )
                @endcomponent
                
            </div>
        @endslot
    @endcomponent
</template>