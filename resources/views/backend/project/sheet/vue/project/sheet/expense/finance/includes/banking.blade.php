@component( "{$component->expense}.row" )
    @slot('title')

        <!-- Row label - Start -->
        @component("{$component->expense}.label.default")
            @slot( 'label', __( "{$lang->label}.finance.banking" ))
            @slot( 'for', "prefix + 'finance-banking-fee'" )
        @endcomponent
        <!-- Row label - End -->

    @endslot
    @slot('button')

        <!-- Auto-calculate button - Start -->
        @component("{$component->expense}.button.calculate")
            @slot( 'click', 'calculateBankingFee' )
        @endcomponent
        <!-- Auto-calculate button - End -->

    @endslot
    @slot('input')
        @component("{$component->expense}.column.default")
            @slot('left')

                <!-- Finance - Banking fee - Start -->
                @component("{$component->preset}.money")
                    @slot( 'disabled', 'isDisabled' )
                    @slot( 'model', 'entry.banking_fee' )
                    @slot( 'name', "prefix + 'finance-banking-fee'" )
                @endcomponent
                <!-- Finance - Banking fee - End -->
                
            @endslot
            @slot('right')
                
                <!-- Finance - Banking fee memo - Start -->
                @component("{$component->preset}.text")
                    @slot( 'disabled', 'isDisabled' )
                    @slot( 'model', 'entry.banking_fee_memo' )
                    @slot( 'name', "prefix + 'finance-banking-fee-memo'" )
                @endcomponent
                <!-- Finance - Banking fee memo - End -->

            @endslot
        @endcomponent
    @endslot
    @slot('total')
        <div class="px-1 col-sm">
            
            @component("{$component->preset}.money")
                @slot( 'disabled', true )
                @slot( 'model', 'totalBanking' )
                @slot( 'name', "prefix + 'finance-banking-fee-total'" )
            @endcomponent
            
        </div>
    @endslot
@endcomponent