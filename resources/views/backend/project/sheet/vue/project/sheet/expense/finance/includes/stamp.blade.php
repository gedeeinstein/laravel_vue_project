@component( "{$component->expense}.row" )
    @slot('title')

        <!-- Row label - Start -->
        @component("{$component->expense}.label.default")
            @slot( 'for', "prefix + 'finance-stamp'" )
            @slot( 'label', __( "{$lang->label}.finance.stamp" ))
        @endcomponent
        <!-- Row label - End -->

    @endslot
    @slot('button')

        <!-- Auto-calculate button - Start -->
        @component("{$component->expense}.button.calculate")
            @slot( 'click', 'calculateStampFee' )
        @endcomponent
        <!-- Auto-calculate button - End -->

    @endslot
    @slot('input')
        @component("{$component->expense}.column.default")
            @slot('left')

                <!-- Finance - Stamp - Start -->
                @component("{$component->preset}.money")
                    @slot( 'disabled', 'isDisabled' )
                    @slot( 'model', 'entry.stamp' )
                    @slot( 'name', "prefix + 'finance-stamp'" )
                @endcomponent
                <!-- Finance - Stamp - End -->
                
            @endslot
            @slot('right')
                
                <!-- Finance - Stamp memo - Start -->
                @component("{$component->preset}.text")
                    @slot( 'disabled', 'isDisabled' )
                    @slot( 'model', 'entry.stamp_memo' )
                    @slot( 'name', "prefix + 'finance-stamp-memo'" )
                @endcomponent
                <!-- Finance - Stamp memo - End -->

            @endslot
        @endcomponent
    @endslot
    @slot('total')
        <div class="px-1 col-sm">

            @component("{$component->preset}.money")
                @slot( 'disabled', true )
                @slot( 'model', 'totalStamp' )
                @slot( 'name', "prefix + 'finance-stamp-total'" )
            @endcomponent
            
        </div>
    @endslot
@endcomponent