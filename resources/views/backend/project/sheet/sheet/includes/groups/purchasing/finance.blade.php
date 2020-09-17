<div class="collapsible purchase-finance mb-3 mb-md-4">
    <div class="card">

        <div class="card-header p-1" id="purchase-finance">
            <button type="button" class="btn btn-accordion" data-toggle="collapse" data-target="#collapse-purchase-finance" aria-expanded="true"
                aria-controls="collapse-purchase-finance">
                <span>@lang("{$lang->heading}.finance")</span>
            </button>
        </div>

        <div id="collapse-purchase-finance" class="collapse show" aria-labelledby="purchase-finance" data-parent=".purchase-finance">
            <div class="card-body p-0">
                
                <ul class="list-group list-group-flush">
                    @component("{$purchasing->components}.heading") @endcomponent

                    <!-- Total - Start -->
                    @include("{$purchasing->finance}.total")
                    <!-- Total - End -->
                
                    <!-- Finance - Intereset Rate - Start -->
                    @include("{$purchasing->finance}.interest")
                    <!-- Finance - Intereset Rate - End -->

                    <!-- Finance - Banking Fee - Start -->
                    @include("{$purchasing->finance}.banking")
                    <!-- Finance - Banking Fee - End -->

                    <!-- Finance - Stamp - Start -->
                    @include("{$purchasing->finance}.stamp")
                    <!-- Finance - Stamp - End -->

                    <template v-if="finance.additional && finance.additional.entries && finance.additional.entries.length">
                        <template v-for="( additional, additionalIndex ) in finance.additional.entries">

                            <!-- Additional cost entry - Start -->
                            @include("{$purchasing->finance}.additional")
                            <!-- Additional cost entry - End -->

                        </template>
                    </template>
                
                </ul>

                <div class="p-3 text-left">

                    <!-- New row button - Start -->
                    @component("{$component->common}.button")
                        @slot( 'click', 'addAdditional( finance )' )
                        @slot( 'label', __("{$lang->label}.additional"))
                    @endcomponent
                    <!-- New row button - End -->

                </div>
            </div>
        </div>
    </div>
</div>