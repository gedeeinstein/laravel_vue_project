<div class="collapsible purchase-construction mb-3 mb-md-4">
    <div class="card">

        <div class="card-header p-1" id="purchase-construction">
            <button type="button" class="btn btn-accordion" data-toggle="collapse" data-target="#collapse-purchase-construction" aria-expanded="true"
                aria-controls="collapse-purchase-construction">
                <span>@lang("{$lang->heading}.construction")</span>
            </button>
        </div>

        <div id="collapse-purchase-construction" class="collapse show" aria-labelledby="purchase-construction" data-parent=".purchase-construction">
            <div class="card-body p-0">
                
                <ul class="list-group list-group-flush">
                    @component("{$purchasing->components}.heading") @endcomponent

                    <!-- Total - Start -->
                    @include("{$purchasing->construction}.total")
                    <!-- Total - End -->

                    <!-- Construction - Building Demolition - Start -->
                    @include("{$purchasing->construction}.building-demolition")
                    <!-- Construction - Building Demolition - End -->


                    <!-- Construction - Retaining Wall Demolition - Start -->
                    @include("{$purchasing->construction}.wall-demolition")
                    <!-- Construction - Retaining Wall Demolition - End -->


                    <!-- Construction - Electric Pole Moving / Transfer - Start -->
                    @include("{$purchasing->construction}.pole-relocation")
                    <!-- Construction - Electric Pole Moving / Transfer - End -->


                    <!-- Construction - Water / Plumbing Construction - Start -->
                    @include("{$purchasing->construction}.plumbing")
                    <!-- Construction - Water / Plumbing Construction - End -->


                    <!-- Construction - Embankment Construction - Start -->
                    @include("{$purchasing->construction}.embankment")
                    <!-- Construction - Embankment Construction - End -->


                    <!-- Construction - Retaining Wall Construction - Start -->
                    @include("{$purchasing->construction}.wall-construction")
                    <!-- Construction - Retaining Wall Construction - End -->


                    <!-- Construction - Road Construction - Start -->
                    @include("{$purchasing->construction}.road")
                    <!-- Construction - Road Construction - End -->


                    <!-- Construction - Gutter Construction - Start -->
                    @include("{$purchasing->construction}.gutter")
                    <!-- Construction - Gutter Construction - End -->


                    <!-- Construction - Construction Work Set - Start -->
                    @include("{$purchasing->construction}.work-set")
                    <!-- Construction - Construction Work Set - End -->


                    <!-- Construction - Location Designation Fee - Start -->
                    @include("{$purchasing->construction}.location-fee")
                    <!-- Construction - Location Designation Fee - End -->


                    <!-- Construction - Development Commission - Start -->
                    @include("{$purchasing->construction}.commission")
                    <!-- Construction - Development Commission - End -->


                    <!-- Construction - Cultural Property Fee - Start -->
                    @include("{$purchasing->construction}.cultural-fee")
                    <!-- Construction - Cultural Property Fee - End -->


                    <template v-if="construction.additional && construction.additional.entries && construction.additional.entries.length">
                        <template v-for="( additional, additionalIndex ) in construction.additional.entries">

                            <!-- Additional cost entry - Start -->
                            @include("{$purchasing->construction}.additional")
                            <!-- Additional cost entry - End -->

                        </template>
                    </template>
                
                </ul>

                <div class="p-3 text-left">

                    <!-- New row button - Start -->
                    @component("{$component->common}.button")
                        @slot( 'click', 'addAdditional( construction )' )
                        @slot( 'label', __("{$lang->label}.additional"))
                    @endcomponent
                    <!-- New row button - End -->

                </div>
            </div>
        </div>
    </div>
</div>