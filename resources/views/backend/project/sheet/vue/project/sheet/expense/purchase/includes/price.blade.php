@component( "{$component->expense}.row" )
    @slot('title')

        <!-- Row label - Start -->
        @component("{$component->expense}.label.default")
            @slot( 'for', "prefix + 'purchase-price'" )
            @slot( 'label', __( "{$lang->label}.purchase.price" ))
        @endcomponent
        <!-- Row label - End -->

    @endslot
    @slot('input')

        <!-- Purchase - Price - Start -->
        <div class="px-1 col-sm col-lg-6">
            @component("{$component->preset}.money")
                @slot( 'model', 'entry.price' )
                @slot( 'name', "prefix + 'purchase-price'" )
            @endcomponent
        </div>
        <!-- Purchase - Price - End -->
        
        <!-- Purchase - Tsubo unit price - Start -->
        <div class="px-1 col-sm col-lg-6 mt-2 mt-sm-0">
            @component("{$component->preset}.text")
                @slot( 'group', true )
                @slot( 'append', '円' )
                @slot( 'disabled', true )
                @slot( 'prepend', __( "{$lang->label}.tsubo_price" ))
                @slot( 'name', "prefix + 'purchase-tsubo-price'" )
                @slot( 'value', 'tsuboPrice | numeralFormat(0)' )
            @endcomponent
        </div>
        <!-- Purchase - Tsubo unit price - End -->

    @endslot
    @slot('total')
        <div class="px-1 col-sm col-lg-5">
            @component("{$component->preset}.money")
                @slot( 'disabled', true )
                @slot( 'model', 'totalPrice' )
                @slot( 'name', "prefix + 'purchase-price-total'" )
            @endcomponent
        </div>
        
        <div class="px-1 col-sm col-lg-7 mt-2 mt-sm-0">
            @component("{$component->preset}.text")
                @slot( 'group', true )
                @slot( 'append', '円' )
                @slot( 'disabled', true )
                @slot( 'prepend', __( "{$lang->label}.tsubo_price" ))
                @slot( 'name', "prefix + 'purchase-tsubo-price-total'" )
                @slot( 'value', 'tsuboTotalPrice | numeralFormat(0)' )
            @endcomponent
        </div>
    @endslot
@endcomponent