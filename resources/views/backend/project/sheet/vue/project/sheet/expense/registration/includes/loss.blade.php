@component( "{$component->expense}.row" )
    @slot('title')

        <!-- Row label - Start -->
        @component("{$component->expense}.label.default")
            @slot( 'for', "prefix + 'registration-loss'" )
            @slot( 'label', __( "{$lang->label}.registration.loss" ))
        @endcomponent
        <!-- Row label - End -->

    @endslot
    @slot('button')

        <!-- Auto-calculate button - Start -->
        @component("{$component->expense}.button.calculate")
            @slot( 'click', 'calculateLoss' )
        @endcomponent
        <!-- Auto-calculate button - End -->

    @endslot
    @slot('input')
        @component("{$component->expense}.column.default")
            @slot('left')

                <!-- Registration - Loss - Start -->
                @component("{$component->preset}.money")
                    @slot( 'disabled', 'isDisabled' )
                    @slot( 'model', 'entry.loss' )
                    @slot( 'name', "prefix + 'registration-loss'" )
                @endcomponent
                <!-- Registration - Loss - End -->
                
            @endslot
            @slot('right')
                
                <!-- Registration - Loss memo - Start -->
                @component("{$component->preset}.text")
                    @slot( 'disabled', 'isDisabled' )
                    @slot( 'model', 'entry.loss_memo' )
                    @slot( 'name', "prefix + 'registration-loss-memo'" )
                @endcomponent
                <!-- Registration - Loss memo - End -->

            @endslot
        @endcomponent
    @endslot
    @slot('total')
        <div class="px-1 col-12">

            @component("{$component->preset}.money")
                @slot( 'disabled', true )
                @slot( 'model', 'totalLoss' )
                @slot( 'name', "prefix + 'registration-loss-total'" )
            @endcomponent
            
        </div>
    @endslot
@endcomponent