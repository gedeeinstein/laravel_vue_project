@component( "{$component->expense}.row" )
    @slot('title')

        <!-- Row label - Start -->
        @component("{$component->expense}.label.default")
            @slot( 'for', "prefix + 'purchase-fee'" )
            @slot( 'label', __( "{$lang->label}.purchase.commission" ))
        @endcomponent
        <!-- Row label - End -->

    @endslot
    @slot('button')

        <!-- Auto-calculate button - Start -->
        @component("{$component->expense}.button.calculate")
            @slot( 'click', 'calculateBrokerageFee' )
        @endcomponent
        <!-- Auto-calculate button - End -->

    @endslot
    @slot('input')
        
        <!-- Purchase fee (absolute value) - Start -->
        <div class="px-1 col-sm-4 pt-2 pt-sm-0">
            @component("{$component->preset}.money")
                @slot( 'negative', true )
                @slot( 'disabled', 'isDisabled' )
                @slot( 'model', 'entry.brokerage_fee' )
                @slot( 'name', "prefix + 'purchase-fee'" )
                @slot( 'attrs' ) @blur="updateBrokerageFee" @endslot
            @endcomponent
        </div>
        <!-- Purchase fee (absolute value) - End -->

        <!-- Purchase fee type - Start -->
        <div class="px-1 col-sm-3 pt-2 pt-sm-0">
            @component("{$component->preset}.select")
                @slot( 'disabled', 'isDisabled' )
                @slot( 'name', "prefix + 'purchase-fee-type'" )
                @slot( 'model', [ 'type' => 'number', 'name' => 'entry.brokerage_fee_type' ])
                @slot( 'options')
                    <option value="1">@lang( "{$lang->option}.purchase.commission.income" )</option>
                    <option value="2">@lang( "{$lang->option}.purchase.commission.expense" )</option>
                    <option value="3">@lang( "{$lang->option}.purchase.commission.none" )</option>
                @endslot
            @endcomponent
        </div>
        <!-- Purchase fee type - End -->

        <!-- Purchase fee memo - Start -->
        <div class="px-1 col-sm-5 pt-2 pt-sm-0">
            @component("{$component->preset}.text")
                @slot( 'disabled', 'isDisabled' )
                @slot( 'model', 'entry.brokerage_fee_memo' )
                @slot( 'name', "prefix + 'purchase-fee-memo'" )
            @endcomponent
        </div>
        <!-- Purchase fee memo - End -->

    @endslot
    @slot('total')

        <!-- Purchase fee total - Start -->
        <div class="px-1 col-12">
            @component("{$component->preset}.money")
                @slot( 'negative', true )
                @slot( 'disabled', true )
                @slot( 'model', 'totalBrokerageFee' )
                @slot( 'name', "prefix + 'purchase-fee-total'" )
            @endcomponent
        </div>
        <!-- Purchase fee total - End -->
        
    @endslot
@endcomponent