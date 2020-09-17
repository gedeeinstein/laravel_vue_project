@component( "{$component->expense}.row" )
    @slot('title')

        <!-- Row label - Start -->
        @component("{$component->expense}.label.default")
            @slot( 'for', "prefix + 'finance-interest'" )
            @slot( 'label', __( "{$lang->label}.finance.interest" ))
        @endcomponent
        <!-- Row label - End -->

    @endslot
    @slot('button')

        <!-- Auto-calculate button - Start -->
        @component("{$component->expense}.button.calculate")
            @slot( 'click', 'calculateInterest' )
        @endcomponent
        <!-- Auto-calculate button - End -->

    @endslot
    @slot('input')

        <!-- Finance - Interest rate - Start -->
        <div class="px-1 col-sm col-lg-5 mt-2 mt-sm-0">
            @component("{$component->preset}.money")
                @slot( 'disabled', 'isDisabled' )
                @slot( 'model', 'entry.total_interest_rate' )
                @slot( 'name', "prefix + 'finance-interest'" )
            @endcomponent
        </div>
        <!-- Finance - Interest rate - End -->
    
        <!-- Finance - Expected interest rate - Start -->
        <div class="px-1 col-sm col-lg-7 mt-2 mt-sm-0">
            @component("{$component->preset}.decimal")
                @slot( 'group', true )
                @slot( 'append', '%' )
                @slot( 'disabled', 'isDisabled' )
                @slot( 'model', 'entry.expected_interest_rate' )
                @slot( 'prepend', __( "{$lang->label}.finance.rate" ))
                @slot( 'name', "prefix + 'finance-interest-expected'" )
            @endcomponent
        </div>
        <!-- Finance - Expected interest rate - End -->

    @endslot
    @slot('total')

        <!-- Finance - Interest rate decision - Start -->
        <div class="px-1 col-sm col-lg-5 mt-2 mt-sm-0">
            @component("{$component->preset}.money")
                @slot( 'disabled', true )
                @slot( 'model', 'totalInterest' )
                @slot( 'name', "prefix + 'finance-interest-total'" )
            @endcomponent
        </div>
        <!-- Finance - Interest rate decision - End -->

        <!-- Finance - mas-finance-unit loan ratio - Start -->
        <div class="px-1 col-sm col-lg-7 mt-2 mt-sm-0">
            @component("{$component->preset}.money")
                @slot( 'group', true )
                @slot( 'append', '%' )
                @slot( 'disabled', true )
                @slot( 'model', 'preset.loanRatio' )
                @slot( 'prepend', __( "{$lang->label}.finance.rate_alt" ))
                @slot( 'name', "prefix + 'finance-interest-loan-ratio'" )
            @endcomponent
        </div>
        <!-- Finance - mas-finance-unit loan ratio - End -->

    @endslot
@endcomponent