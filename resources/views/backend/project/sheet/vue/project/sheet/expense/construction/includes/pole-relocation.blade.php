<template v-if="!isPoleRelocationHidden">
    @component( "{$component->expense}.row" )
        @slot('title')

            <!-- Row label - Start -->
            @component("{$component->expense}.label.default")
                @slot( 'for', "prefix + 'construction-pole-relocation'" )
                @slot( 'label', __( "{$lang->label}.construction.pole_relocation" ))
            @endcomponent
            <!-- Row label - End -->

        @endslot
        @slot('input')
            @component("{$component->expense}.column.default")
                @slot('left')

                    <!-- Construction - Obstructive pole relocation - Start -->
                    @component("{$component->preset}.money")
                        @slot( 'disabled', 'isDisabled' )
                        @slot( 'model', 'entry.transfer_electric_pole' )
                        @slot( 'name', "prefix + 'construction-pole-relocation'" )
                    @endcomponent
                    <!-- Construction - Obstructive pole relocation - End -->
                    
                @endslot
                @slot('right')
                    
                    <!-- Construction - Obstructive pole relocation memo - Start -->
                    @component("{$component->preset}.text")
                        @slot( 'disabled', 'isDisabled' )
                        @slot( 'model', 'entry.transfer_electric_pole_memo' )
                        @slot( 'name', "prefix + 'construction-pole-relocation-memo'" )
                    @endcomponent
                    <!-- Construction - Obstructive pole relocation memo - End -->

                @endslot
            @endcomponent
        @endslot
        @slot('total')
            <div class="px-1 col-12">

                @component("{$component->preset}.money")
                    @slot( 'disabled', true )
                    @slot( 'model', 'totalPoleRelocation' )
                    @slot( 'name', "prefix + 'construction-pole-relocation-total'" )
                @endcomponent
                
            </div>
        @endslot
    @endcomponent
</template>