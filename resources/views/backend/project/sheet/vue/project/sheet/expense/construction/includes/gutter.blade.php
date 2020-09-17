<template v-if="!isGutterConstructionHidden">
    @component( "{$component->expense}.row" )
        @slot('title')

            <!-- Row label - Start -->
            @component("{$component->expense}.label.default")
                @slot( 'for', "prefix + 'construction-gutter'" )
                @slot( 'label', __( "{$lang->label}.construction.gutter" ))
            @endcomponent
            <!-- Row label - End -->

        @endslot
        @slot('button')

            <!-- Auto-calculate button - Start -->
            @component("{$component->expense}.button.calculate")
                @slot( 'click', 'calculateGutter' )
                @slot( 'disabled', '!( checklist.side_groove && checklist.side_groove_length )')
            @endcomponent
            <!-- Auto-calculate button - End -->

        @endslot
        @slot('input')
            @component("{$component->expense}.column.default")
                @slot('left')

                    <!-- Construction - Gutter construction - Start -->
                    @component("{$component->preset}.money")
                        @slot( 'disabled', 'isDisabled' )
                        @slot( 'model', 'entry.side_groove_construction' )
                        @slot( 'name', "prefix + 'construction-gutter'" )
                    @endcomponent
                    <!-- Construction - Gutter construction - End -->
                    
                @endslot
                @slot('right')
                    
                    <!-- Construction - Gutter construction memo - Start -->
                    @component("{$component->preset}.text")
                        @slot( 'disabled', 'isDisabled' )
                        @slot( 'model', 'entry.side_groove_construction_memo' )
                        @slot( 'name', "prefix + 'construction-gutter-memo'" )
                    @endcomponent
                    <!-- Construction - Gutter construction memo - End -->

                @endslot
            @endcomponent
        @endslot
        @slot('total')
            <div class="px-1 col-12">

                @component("{$component->preset}.money")
                    @slot( 'disabled', true )
                    @slot( 'model', 'totalGutterConstruction' )
                    @slot( 'name', "prefix + 'construction-gutter-total'" )
                @endcomponent
                
            </div>
        @endslot
    @endcomponent
</template>