<script type="text/x-template" id="expense-tax">
    <div class="expense-tax collapsible mb-3 mb-md-4">
        <div class="card">

            @php 
                $component->expense = 'backend.project.sheet.components.sheet.expense';
                $lang = (object) array(
                    'label'   => 'project.sheet.expense.label',
                    'option'  => 'project.sheet.expense.option',
                    'heading' => 'project.sheet.expense.heading'
                );
            @endphp
    
            <div class="card-header p-1" id="expense-tax">
                <button type="button" class="btn btn-accordion" data-toggle="collapse" data-target="#collapse-expense-tax" aria-expanded="true"
                    aria-controls="collapse-expense-tax">
                    <span>@lang("{$lang->heading}.tax")</span>
                </button>
            </div>
    
            <div id="collapse-expense-tax" class="collapse show" aria-labelledby="expense-tax" data-parent=".expense-tax">
                <div class="card-body p-0">
                    
                    <ul class="list-group list-group-flush">

                        <!-- Group heading - Start -->
                        @component("{$component->expense}.heading") @endcomponent
                        <!-- Group heading - End -->
    
                        <!-- Total - Start -->
                        @relativeInclude('includes.total')
                        <!-- Total - End -->
                    
                        <!-- Tax - Property Acquisition - Start -->
                        @relativeInclude('includes.acquisition')
                        <!-- Tax - roperty Acquisition - End -->
    
                        <!-- Tax - The Following Year Tax - Start -->
                        @relativeInclude('includes.annual')
                        <!-- Tax - The Following Year Tax - End -->

                        <template v-if="entry.additional && entry.additional.entries">
                            <li class="expense-row expense-additional list-group-item py-0" 
                                v-for="( additional, additionalIndex ) in entry.additional.entries" 
                                :key="additionalKey" :class="{ 'highlighted': !additional.id }">
    
                                <!-- Additional cost entry - Start -->
                                <expense-additional v-model="additional" :section="entry" :expense-rows="rows" 
                                    :disabled="isDisabled" @remove="removeAdditional( additionalIndex )">
                                </expense-additional>
                                <!-- Additional cost entry - End -->
    
                            </li>
                        </template>
                    
                    </ul>
    
                    <div class="p-3 text-left">
    
                        <!-- New row button - Start -->
                        @component("{$component->preset}.button")
                            @slot( 'click', 'addAdditional' )
                            @slot( 'label', __("{$lang->label}.additional"))
                        @endcomponent
                        <!-- New row button - End -->
    
                    </div>
                </div>
            </div>
        </div>
    </div>
</script>