<div class="collapsible purchase-other mb-3 mb-md-4">
    <div class="card">

        <div class="card-header p-1" id="purchase-other">
            <button type="button" class="btn btn-accordion" data-toggle="collapse" data-target="#collapse-purchase-other" aria-expanded="true"
                aria-controls="collapse-purchase-other">
                <span>@lang("{$lang->heading}.other")</span>
            </button>
        </div>

        <div id="collapse-purchase-other" class="collapse show" aria-labelledby="purchase-other" data-parent=".purchase-other">
            <div class="card-body p-0">
                
                <ul class="list-group list-group-flush">
                    @component("{$purchasing->components}.heading") @endcomponent

                    <!-- Total - Start -->
                    @include("{$purchasing->other}.total")
                    <!-- Total - End -->

                    <!-- Other - Feferral fee - Start -->
                    @include("{$purchasing->other}.referral")
                    <!-- Other - Feferral fee  - End -->

                    <!-- Other - Eviction fee - Start -->
                    @include("{$purchasing->other}.eviction")
                    <!-- Other - Eviction fee - End -->

                    <!-- Other - Water supply subscription - Start -->
                    @include("{$purchasing->other}.water")
                    <!-- Other - Water supply subscription - End -->

                    <template v-if="other.additional && other.additional.entries && other.additional.entries.length">
                        <template v-for="( additional, additionalIndex ) in other.additional.entries">

                            <!-- Additional cost entry - Start -->
                            @include("{$purchasing->other}.additional")
                            <!-- Additional cost entry - End -->

                        </template>
                    </template>
                
                </ul>

                <div class="p-3 text-left">

                    <!-- New row button - Start -->
                    @component("{$component->common}.button")
                        @slot( 'click', 'addAdditional( other )' )
                        @slot( 'label', __("{$lang->label}.additional"))
                    @endcomponent
                    <!-- New row button - End -->

                </div>
            </div>
        </div>
    </div>
</div>