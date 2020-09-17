<div class="collapsible purchase-tax mb-3 mb-md-4">
    <div class="card">

        <div class="card-header p-1" id="purchase-tax">
            <button type="button" class="btn btn-accordion" data-toggle="collapse" data-target="#collapse-purchase-tax" aria-expanded="true"
                aria-controls="collapse-purchase-tax">
                <span>@lang("{$lang->heading}.tax")</span>
            </button>
        </div>

        <div id="collapse-purchase-tax" class="collapse show" aria-labelledby="purchase-tax" data-parent=".purchase-tax">
            <div class="card-body p-0">
                
                <ul class="list-group list-group-flush">
                    @component("{$purchasing->components}.heading") @endcomponent

                    <!-- Total - Start -->
                    @include("{$purchasing->tax}.total")
                    <!-- Total - End -->
                
                    <!-- Tax - Property Acquisition - Start -->
                    @include("{$purchasing->tax}.acquisition")
                    <!-- Tax - roperty Acquisition - End -->

                    <!-- Tax - The Following Year Tax - Start -->
                    @include("{$purchasing->tax}.annual")
                    <!-- Tax - The Following Year Tax - End -->

                    <template v-if="tax.additional && tax.additional.entries && tax.additional.entries.length">
                        <template v-for="( additional, additionalIndex ) in tax.additional.entries">

                            <!-- Additional cost entry - Start -->
                            @include("{$purchasing->tax}.additional")
                            <!-- Additional cost entry - End -->

                        </template>
                    </template>
                
                </ul>

                <div class="p-3 text-left">

                    <!-- New row button - Start -->
                    @component("{$component->common}.button")
                        @slot( 'click', 'addAdditional( tax )' )
                        @slot( 'label', __("{$lang->label}.additional"))
                    @endcomponent
                    <!-- New row button - End -->

                </div>
            </div>
        </div>
    </div>
</div>