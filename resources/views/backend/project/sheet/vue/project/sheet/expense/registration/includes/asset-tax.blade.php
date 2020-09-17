@component( "{$component->expense}.row" )
    @slot('title')

        <!-- Row label - Start -->
        @component("{$component->expense}.label.default")
            @slot( 'for', "prefix + 'registration-tax'" )
            @slot( 'label', __( "{$lang->label}.registration.asset_tax" ))
        @endcomponent
        <!-- Row label - End -->

    @endslot
    @slot('button')

        <!-- Auto-calculate button - Start -->
        @component("{$component->expense}.button.calculate")
            @slot( 'click', 'calculateAssetTax' )
            @slot( 'disabled', '!entry.fixed_assets_tax_date' )
        @endcomponent
        <!-- Auto-calculate button - End -->

    @endslot
    @slot('input')

        <!-- Registration asset tax - Start -->
        <div class="px-1 col-sm col-lg-5 mt-2 mt-sm-0">
            @component("{$component->preset}.money")
                @slot( 'disabled', 'isDisabled' )
                @slot( 'model', 'entry.fixed_assets_tax' )
                @slot( 'name', "prefix + 'registration-tax'" )
            @endcomponent
        </div>
        <!-- Registration asset tax - End -->

        <!-- Registration asset tax date - Start -->
        <div class="px-1 col-sm col-lg-7 mt-2 mt-sm-0">
            @component("{$component->preset}.date")
                @slot( 'group', true )
                @slot( 'disabled', 'isDisabled' )
                @slot( 'model', 'entry.fixed_assets_tax_date' )
                @slot( 'name', "prefix + 'registration-tax-date'" )
                @slot( 'prepend', __( "{$lang->label}.settlement.schedule" ))
            @endcomponent
        </div>
        <!-- Registration asset tax date - End -->

    @endslot
    @slot('total')

        <div class="px-1 col-sm mt-2 mt-sm-0">
            @component("{$component->preset}.text")
                @slot( 'group', true )
                @slot( 'disabled', true )
                @slot( 'model', 'preset.contract' )
                @slot( 'prepend', __( "{$lang->label}.settlement.date" ))
                @slot( 'name', "prefix + 'registration-tax-contract'" )
            @endcomponent
        </div>
        
    @endslot
@endcomponent