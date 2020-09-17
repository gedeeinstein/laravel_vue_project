<script type="text/x-template" id="expense-other">
    <div class="expense-other collapsible mb-3 mb-md-4">
        <div class="card">

            @php 
                $component->expense = 'backend.project.sheet.components.sheet.expense';
                $lang = (object) array(
                    'label'   => 'project.sheet.expense.label',
                    'option'  => 'project.sheet.expense.option',
                    'heading' => 'project.sheet.expense.heading'
                );
            @endphp
    
            <div class="card-header p-1" id="expense-other">
                <button type="button" class="btn btn-accordion" data-toggle="collapse" data-target="#collapse-expense-other" aria-expanded="true"
                    aria-controls="collapse-expense-other">
                    <span>@lang("{$lang->heading}.other")</span>
                </button>
            </div>
    
            <div id="collapse-expense-other" class="collapse show" aria-labelledby="expense-other" data-parent=".expense-other">
                <div class="card-body p-0">
                    
                    <ul class="list-group list-group-flush">

                        <!-- Group heading - Start -->
                        @component("{$component->expense}.heading") @endcomponent
                        <!-- Group heading - End -->
    
                        <!-- Total - Start -->
                        @relativeInclude('includes.total')
                        <!-- Total - End -->
    
                        <!-- Other - Feferral fee - Start -->
                        @relativeInclude('includes.referral')
                        <!-- Other - Feferral fee  - End -->
    
                        <!-- Other - Eviction fee - Start -->
                        @relativeInclude('includes.eviction')
                        <!-- Other - Eviction fee - End -->
    
                        <!-- Other - Water supply subscription - Start -->
                        @relativeInclude('includes.water')
                        <!-- Other - Water supply subscription - End -->
    
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