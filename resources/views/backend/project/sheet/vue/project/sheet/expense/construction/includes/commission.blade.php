@component( "{$component->expense}.row" )
    @slot('title')
    
        <!-- Row label - Start -->
        @component("{$component->expense}.label.default")
            @slot( 'label', __( "{$lang->label}.construction.commission" ))
            @slot( 'for', "prefix + 'construction-commission-fee'" )
        @endcomponent
        <!-- Row label - End -->

    @endslot
    @slot('button')

        <!-- Auto-calculate button - Start -->
        @component("{$component->expense}.button.calculate")
            @slot( 'disabled', 'isDisabled || isCommissionDisabled')
            @slot( 'click', 'calculateCommission' )
        @endcomponent
        <!-- Auto-calculate button - End -->

    @endslot
    @slot('input')
        @component("{$component->expense}.column.default")
            @slot('left')

                <!-- Construction - Development commission fee - Start -->
                @component("{$component->preset}.money")
                    @slot( 'disabled', 'isDisabled || isCommissionDisabled' )
                    @slot( 'model', 'entry.development_commissions_fee' )
                    @slot( 'name', "prefix + 'construction-commission-fee'" )
                    @slot( 'attrs' )
                        :class="isCommissionDisabled ? 'text-black-50': 'input-money'"
                    @endslot
                @endcomponent
                <!-- Construction - Development commission fee - End -->
                
            @endslot
            @slot('right')
                
                <!-- Construction - Development commission memo - Start -->
                @component("{$component->preset}.text")
                    @slot( 'disabled', 'isDisabled || isCommissionDisabled' )
                    @slot( 'model', 'entry.development_commissions_fee_memo' )
                    @slot( 'name', "prefix + 'construction-commission-fee-memo'" )
                    @slot( 'attrs' )
                        :class="isCommissionDisabled ? 'text-black-50': 'input-text'"
                    @endslot
                @endcomponent
                <!-- Construction - Development commission memo - End -->

            @endslot
        @endcomponent
    @endslot
    @slot('total')
        <div class="px-1 col-12">

            @component("{$component->preset}.money")
                @slot( 'disabled', true )
                @slot( 'model', 'totalCommission' )
                @slot( 'name', "prefix + 'construction-commission-total'" )
                @slot( 'attrs' )
                    :class="isCommissionDisabled ? 'text-black-50': ''"
                @endslot
            @endcomponent
            
        </div>
    @endslot
@endcomponent