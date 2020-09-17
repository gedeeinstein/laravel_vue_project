@component( "{$component->expense}.row" )
    @slot('title')

        <!-- Row label - Start -->
        @component("{$component->expense}.label.default")
            @slot( 'for', "prefix + 'registration-mortgage'" )
            @slot( 'label', __( "{$lang->label}.registration.mortage" ))
        @endcomponent
        <!-- Row label - End -->

    @endslot
    @slot('button')

        <!-- Auto-calculate button - Start -->
        @component("{$component->expense}.button.calculate")
            @slot( 'click', 'calculateMortgage' )
        @endcomponent
        <!-- Auto-calculate button - End -->

    @endslot
    @slot('input')

        <!-- Registration mortgage setting - Start -->
        <div class="px-1 col-sm col-lg-5 mt-2 mt-sm-0">
            @component("{$component->preset}.money")
                @slot( 'group', true )
                @slot( 'disabled', 'isDisabled' )
                @slot( 'model', 'entry.mortgage_setting' )
                @slot( 'name', "prefix + 'registration-mortgage'" )
            @endcomponent
        </div>
        <!-- Registration mortgage setting - End -->
        
        <!-- Registration mortgage setting plan - Start -->
        <div class="px-1 col-sm col-lg-7 mt-2 mt-sm-0">
            @component("{$component->preset}.money")
                @slot( 'group', true )
                @slot( 'disabled', 'isDisabled' )
                @slot( 'model', 'entry.mortgage_setting_plan' )
                @slot( 'prepend', __( "{$lang->label}.loan.expected" ))
                @slot( 'name', "prefix + 'registration-mortgage-plan'" )
            @endcomponent
        </div>
        <!-- Registration mortgage setting plan - End -->

    @endslot
    @slot('total')
        <div class="px-1 col-sm col-lg-5">
            
            @component("{$component->preset}.money")
                @slot( 'disabled', true )
                @slot( 'model', 'totalMortgage' )
                @slot( 'name', "prefix + 'registration-mortgage-total'" )
            @endcomponent

        </div>
        <div class="px-1 col-sm col-lg-7 mt-2 mt-sm-0">

            @component("{$component->preset}.money")
                @slot( 'group', true )
                @slot( 'disabled', true )
                @slot( 'model', 'preset.loanMoney' )
                @slot( 'prepend', __( "{$lang->label}.loan.amount" ))
                @slot( 'name', "prefix + 'registration-mortgage-loan-money'" )
            @endcomponent

        </div>
    @endslot
@endcomponent