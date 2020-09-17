<template v-if="!isWallDemolitionHidden">
    @component( "{$component->expense}.row" )
        @slot('title')

            <!-- Row label - Start -->
            @component("{$component->expense}.label.default")
                @slot( 'for', "prefix + 'construction-wall-demolition'" )
                @slot( 'label', __( "{$lang->label}.construction.demolition.wall" ))
            @endcomponent
            <!-- Row label - End -->

        @endslot
        @slot('input')
            @component("{$component->expense}.column.default")
                @slot('left')

                    <!-- Construction - Retaining wall demolition - Start -->
                    @component("{$component->preset}.money")
                        @slot( 'disabled', 'isDisabled' )
                        @slot( 'model', 'entry.retaining_wall_demolition' )
                        @slot( 'name', "prefix + 'construction-wall-demolition'" )
                    @endcomponent
                    <!-- Construction - Retaining wall demolition - End -->
                    
                @endslot
                @slot('right')
                    
                    <!-- Construction - Retaining wall demolition memo - Start -->
                    @component("{$component->preset}.text")
                        @slot( 'disabled', 'isDisabled' )
                        @slot( 'model', 'entry.retaining_wall_demolition_memo' )
                        @slot( 'name', "prefix + 'construction-wall-demolition-memo'" )
                    @endcomponent
                    <!-- Construction - Retaining wall demolition memo - End -->

                @endslot
            @endcomponent
        @endslot
        @slot('total')
            <div class="px-1 col-12">

                @component("{$component->preset}.money")
                    @slot( 'disabled', true )
                    @slot( 'model', 'totalWallDemolition' )
                    @slot( 'name', "prefix + 'construction-wall-demolition-total'" )
                @endcomponent
                
            </div>
        @endslot
    @endcomponent
</template>