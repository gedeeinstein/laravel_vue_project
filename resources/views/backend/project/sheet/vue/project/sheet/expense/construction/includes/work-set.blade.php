@component( "{$component->expense}.row" )
    @slot('title')

        <!-- Row label - Start -->
        @component("{$component->expense}.label.default")
            @slot( 'for', "prefix + 'construction-workset'" )
            @slot( 'label', __( "{$lang->label}.construction.workset" ))
            @slot( 'alt', "'(' +tsuboWorkset+ '円/坪)'" )
        @endcomponent
        <!-- Row label - End -->

    @endslot
    @slot('button')

        <!-- Auto-calculate button - Start -->
        @component("{$component->expense}.button.calculate")
            @slot( 'disabled', '!checklist.development_cost' )
            @slot( 'click', 'calculateWorkset' )
        @endcomponent
        <!-- Auto-calculate button - End -->

    @endslot
    @slot('input')
        @component("{$component->expense}.column.default")
            @slot('left')

                <!-- Construction - Construction work set - Start -->
                @component("{$component->preset}.money")
                    @slot( 'disabled', 'isDisabled' )
                    @slot( 'model', 'entry.construction_work_set' )
                    @slot( 'name', "prefix + 'construction-workset'" )
                @endcomponent
                <!-- Construction - Construction work set - End -->
                
            @endslot
            @slot('right')
                
                <!-- Construction - Construction work set memo - Start -->
                @component("{$component->preset}.text")
                    @slot( 'disabled', 'isDisabled' )
                    @slot( 'model', 'entry.construction_work_set_memo' )
                    @slot( 'name', "prefix + 'construction-workset-memo'" )
                @endcomponent
                <!-- Construction - Construction work set memo - End -->

            @endslot
        @endcomponent
    @endslot
    @slot('total')
        <div class="px-1 col-12">

            @component("{$component->preset}.money")
                @slot( 'disabled', true )
                @slot( 'model', 'totalConstructionWorkset' )
                @slot( 'name', "prefix + 'construction-workset-total'" )
            @endcomponent
            
        </div>
    @endslot
@endcomponent