@component( "{$component->expense}.row" )
    @slot('title')

        <!-- Row label - Start -->
        @component("{$component->expense}.label.default")
            @slot( 'for', "prefix + 'other-referral'" )
            @slot( 'label', __( "{$lang->label}.other.referral" ))
        @endcomponent
        <!-- Row label - End -->

    @endslot
    @slot('input')
        @component("{$component->expense}.column.default")
            @slot('left')

                <!-- Other - Referral - Start -->
                @component("{$component->preset}.money")
                    @slot( 'disabled', 'isDisabled' )
                    @slot( 'model', 'entry.referral_fee' )
                    @slot( 'name', "prefix + 'other-referral'" )
                @endcomponent
                <!-- Other - Referral - End -->
                
            @endslot
            @slot('right')
                
                <!-- Other - Referral memo - Start -->
                @component("{$component->preset}.text")
                    @slot( 'disabled', 'isDisabled' )
                    @slot( 'model', 'entry.referral_fee_memo' )
                    @slot( 'name', "prefix + 'other-referral-memo'" )
                @endcomponent
                <!-- Other - Referral memo - End -->

            @endslot
        @endcomponent
    @endslot
    @slot('total')
        <div class="px-1 col-12">

            <!-- Other - Referal total - Start -->
            @component("{$component->preset}.money")
                @slot( 'disabled', true )
                @slot( 'model', 'totalReferralFee' )
                @slot( 'name', "prefix + 'other-referral-total'" )
            @endcomponent
            <!-- Other - Referal total - End -->

        </div>
    @endslot
@endcomponent