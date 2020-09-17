<div class="collapsible purchase-registration mb-3 mb-md-4">
    <div class="card">

        <div class="card-header p-1" id="purchase-registration">
            <button type="button" class="btn btn-accordion" data-toggle="collapse" data-target="#collapse-purchase-registration" aria-expanded="true"
                aria-controls="collapse-purchase-registration">
                <span>@lang("{$lang->heading}.registration")</span>
            </button>
        </div>

        <div id="collapse-purchase-registration" class="collapse show" aria-labelledby="purchase-registration" data-parent=".purchase-registration">
            <div class="card-body p-0">
                
                <ul class="list-group list-group-flush">
                    @component("{$purchasing->components}.heading") @endcomponent

                    <!-- Total - Start -->
                    @include("{$purchasing->registration}.total")
                    <!-- Total - End -->
                
                    <!-- Ownership Transfer Registration - Start -->
                    @include("{$purchasing->registration}.ownership")
                    <!-- Ownership Transfer Registration - End -->

                    <!-- Mortage Registration - Start -->
                    @include("{$purchasing->registration}.mortage")
                    <!-- Mortage Registration - End -->

                    <!-- Asset Tax Registration - Start -->
                    @include("{$purchasing->registration}.asset-tax")
                    <!-- Asset Tax Registration - End -->

                    <!-- Loss Registration - Start -->
                    @include("{$purchasing->registration}.loss")
                    <!-- Loss Registration - End -->

                    <template v-if="registration.additional && registration.additional.entries && registration.additional.entries.length">
                        <template v-for="( additional, additionalIndex ) in registration.additional.entries">

                            <!-- Additional cost entry - Start -->
                            @include("{$purchasing->registration}.additional")
                            <!-- Additional cost entry - End -->

                        </template>
                    </template>
                
                </ul>

                <div class="p-3 text-left">

                    <!-- New row button - Start -->
                    @component("{$component->common}.button")
                        @slot( 'click', 'addAdditional( registration )' )
                        @slot( 'label', __("{$lang->label}.additional"))
                    @endcomponent
                    <!-- New row button - End -->

                </div>
            </div>
        </div>
    </div>
</div>