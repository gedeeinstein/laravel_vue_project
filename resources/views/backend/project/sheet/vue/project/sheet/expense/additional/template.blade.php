<script type="text/x-template" id="expense-additional">
    @component( "{$component->expense}.additional" )
        @slot('title')
            <div class="w-100">
                <div class="row mx-n1">
    
                    <!-- Loading indicator - Start -->
                    <template v-if="status && status.loading">
                        <div class="px-1 col-auto d-flex align-items-center">
                            <i class="fs-16 text-muted far fa-spinner fa-spin"></i>
                        </div>
                    </template>
                    <!-- Loading indicator - End -->
    
                    <div class="px-1 col">
    
                        <!-- Additional registration name - Start -->
                        @component("{$component->preset}.text")
                            @slot( 'enter', 'focusNextRow' )
                            @slot( 'model', 'entry.name' )
                            @slot( 'disabled', 'isDisabled' )
                            @slot( 'classes', 'text-left text-lg-right' )
                            @slot( 'name', "prefix + 'additional-name'" )
                        @endcomponent
                        <!-- Additional registration name - End -->
    
                    </div>
                </div>
            </div>

        @endslot
        @slot('input')
            @component("{$component->expense}.column.default")
                @slot('left')

                    <!-- Additional registration cost - Start -->
                    @component("{$component->preset}.money")
                        @slot( 'enter', 'focusNextRow' )
                        @slot( 'disabled', 'isDisabled' )
                        @slot( 'model', 'entry.value' )
                        @slot( 'name', "prefix + 'additional-cost'" )
                    @endcomponent
                    <!-- Additional registration cost - End -->
                    
                @endslot
                @slot('right')
                    
                    <!-- Additional registration memo - Start -->
                    @component("{$component->preset}.text")
                        @slot( 'enter', 'focusNextRow' )
                        @slot( 'disabled', 'isDisabled' )
                        @slot( 'model', 'entry.memo' )
                        @slot( 'name', "prefix + 'additional-memo'" )
                    @endcomponent
                    <!-- Additional registration memo - End -->

                @endslot
            @endcomponent
        @endslot
        @slot('total')

            <!-- Additional registration total - Start -->
            <div class="px-1 col-12">
                @component("{$component->preset}.money")
                    @slot( 'disabled', true )
                    @slot( 'model', 'entry.total' )
                    @slot( 'name', "prefix + 'additional-total'" )
                @endcomponent
            </div>
            <!-- Additional registration total - End -->

        @endslot
        @slot('delete')

            <!-- Delete button - Start -->
            @component("{$component->expense}.button.delete")
                @slot( 'click', 'remove' )
            @endcomponent
            <!-- Delete button - End -->

        @endslot
    @endcomponent
</script>