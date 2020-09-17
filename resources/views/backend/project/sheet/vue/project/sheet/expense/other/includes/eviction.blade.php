@component( "{$component->expense}.row" )
    @slot('title')

        <!-- Row label - Start -->
        @component("{$component->expense}.label.default")
            @slot( 'for', "prefix + 'other-eviction'" )
            @slot( 'label', __( "{$lang->label}.other.eviction" ))
        @endcomponent
        <!-- Row label - End -->

    @endslot
    @slot('input')
        @component("{$component->expense}.column.default")
            @slot('left')

                <!-- Other - Eviction - Start -->
                @component("{$component->preset}.money")
                    @slot( 'disabled', 'isDisabled' )
                    @slot( 'model', 'entry.eviction_fee' )
                    @slot( 'name', "prefix + 'other-eviction'" )
                @endcomponent
                <!-- Other - Eviction - End -->
                
            @endslot
            @slot('right')
                
                <!-- Other - Eviction memo - Start -->
                @component("{$component->preset}.text")
                    @slot( 'disabled', 'isDisabled' )
                    @slot( 'model', 'entry.eviction_fee_memo' )
                    @slot( 'name', "prefix + 'other-eviction-memo'" )
                @endcomponent
                <!-- Other - Eviction memo - End -->

            @endslot
        @endcomponent
    @endslot
    @slot('total')
        <div class="px-1 col-12">

            <!-- Other - Eviction total - Start -->
            @component("{$component->preset}.money")
                @slot( 'disabled', true )
                @slot( 'model', 'totalEvictionFee' )
                @slot( 'name', "prefix + 'other-eviction-total'" )
            @endcomponent
            <!-- Other - Eviction total - End -->

        </div>
    @endslot
@endcomponent