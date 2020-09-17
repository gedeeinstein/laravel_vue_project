<div class="collapsible purchase-survey mb-3 mb-md-4">
    <div class="card">

        <div class="card-header p-1" id="purchase-survey">
            <button type="button" class="btn btn-accordion" data-toggle="collapse" data-target="#collapse-purchase-survey" aria-expanded="true"
                aria-controls="collapse-purchase-survey">
                <span>@lang("{$lang->heading}.survey")</span>
            </button>
        </div>

        <div id="collapse-purchase-survey" class="collapse show" aria-labelledby="purchase-survey" data-parent=".purchase-survey">
            <div class="card-body p-0">
                
                <ul class="list-group list-group-flush">
                    @component("{$purchasing->components}.heading") @endcomponent

                    <!-- Total - Start -->
                    @include("{$purchasing->survey}.total")
                    <!-- Total - End -->

                    <!-- Survey - Fixed survey - Start -->
                    @include("{$purchasing->survey}.fixed")
                    <!-- Survey - Fixed survey - End -->

                    <!-- Survey - Divisional registration - Start -->
                    @include("{$purchasing->survey}.divisional")
                    <!-- Survey - Divisional registration - End -->

                    <!-- Survey - Boundry restoration - Start -->
                    @include("{$purchasing->survey}.boundry")
                    <!-- Survey - Boundry restoration - End -->

                    <template v-if="survey.additional && survey.additional.entries && survey.additional.entries.length">
                        <template v-for="( additional, additionalIndex ) in survey.additional.entries">

                            <!-- Additional cost entry - Start -->
                            @include("{$purchasing->survey}.additional")
                            <!-- Additional cost entry - End -->

                        </template>
                    </template>
                
                </ul>

                <div class="p-3 text-left">

                    <!-- New row button - Start -->
                    @component("{$component->common}.button")
                        @slot( 'click', 'addAdditional( survey )' )
                        @slot( 'label', __("{$lang->label}.additional"))
                    @endcomponent
                    <!-- New row button - End -->

                </div>
            </div>
        </div>
    </div>
</div>