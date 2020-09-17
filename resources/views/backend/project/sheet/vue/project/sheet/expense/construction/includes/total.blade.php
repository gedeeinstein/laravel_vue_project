@component( "{$component->expense}.row" )
    @slot('title')

        <!-- Row label - Start -->
        @component("{$component->expense}.label.default")
            @slot( 'label', __( "{$lang->label}.construction.total" ))
        @endcomponent
        <!-- Row label - End -->

    @endslot
    @slot('input')
        <div class="px-1 col-12 pt-2 pt-sm-0">

            <!-- Total budget - Start -->
            @component("{$component->preset}.money")
                @slot( 'disabled', true )
                @slot( 'name', "prefix + 'construction-total-budget'" )
                @slot( 'model', 'totalBudget' )
            @endcomponent
            <!-- Total budget - End -->

        </div>
    @endslot
    @slot('total')
        <div class="px-1 col-12">
        
            <!-- Total decided amount - Start -->
            @component("{$component->preset}.money")
                @slot( 'disabled', true )
                @slot( 'name', "prefix + 'construction-total-amount'" )
                @slot( 'model', 'totalAmount' )
            @endcomponent
            <!-- Total decided amount - End -->

        </div>
    @endslot
@endcomponent