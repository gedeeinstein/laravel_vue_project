@component( "{$component->expense}.row" )
    @slot('title')

        <!-- Row label - Start -->
        @component("{$component->expense}.label.default")
            @slot( 'for', "prefix + 'registration-ownership'" )
            @slot( 'label', __( "{$lang->label}.registration.ownership" ))
        @endcomponent
        <!-- Row label - End -->

    @endslot
    @slot('button')

        <!-- Auto-calculate button - Start -->
        @component("{$component->expense}.button.calculate")
            @slot( 'click', 'calculateOwnershipTransfer' )
        @endcomponent
        <!-- Auto-calculate button - End -->

    @endslot
    @slot('input')
        @component("{$component->expense}.column.default")
            @slot('left')

                <!-- Registration - Ownership transfer amount - Start -->
                @component("{$component->preset}.money")
                    @slot( 'disabled', 'isDisabled' )
                    @slot( 'model', 'entry.transfer_of_ownership' )
                    @slot( 'name', "prefix + 'registration-ownership'" )
                @endcomponent
                <!-- Registration - Ownership transfer amount - End -->
                
            @endslot
            @slot('right')
                
                <!-- Registration - Ownership transfer memo - Start -->
                @component("{$component->preset}.text")
                    @slot( 'disabled', 'isDisabled' )
                    @slot( 'model', 'entry.transfer_of_ownership_memo' )
                    @slot( 'name', "prefix + 'registration-ownership-memo'" )
                @endcomponent
                <!-- Registration - Ownership transfer memo - End -->

            @endslot
        @endcomponent
    @endslot
    @slot('total')
        <div class="px-1 col-12">

            <!-- Ownership transfer total - Start -->
            @component("{$component->preset}.money")
                @slot( 'disabled', true )
                @slot( 'model', 'totalOwnership' )
                @slot( 'name', "prefix + 'registration-ownership-total'" )
            @endcomponent
            <!-- Ownership transfer total - End -->

        </div>
    @endslot
@endcomponent