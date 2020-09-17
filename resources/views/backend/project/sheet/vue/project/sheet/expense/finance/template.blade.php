<script type="text/x-template" id="expense-finance">
    <div class="expense-finance collapsible mb-3 mb-md-4">
        <div class="card">

            @php 
                $component->expense = 'backend.project.sheet.components.sheet.expense';
                $lang = (object) array(
                    'label'   => 'project.sheet.expense.label',
                    'option'  => 'project.sheet.expense.option',
                    'heading' => 'project.sheet.expense.heading'
                );
            @endphp
    
            <div class="card-header p-1" id="expense-finance">
                <button type="button" class="btn btn-accordion" data-toggle="collapse" data-target="#collapse-expense-finance" aria-expanded="true"
                    aria-controls="collapse-expense-finance">
                    <span>@lang("{$lang->heading}.finance")</span>
                </button>
            </div>
    
            <div id="collapse-expense-finance" class="collapse show" aria-labelledby="expense-finance" data-parent=".expense-finance">
                <div class="card-body p-0">
                    
                    <ul class="list-group list-group-flush">

                        <!-- Group heading - Start -->
                        @component("{$component->expense}.heading") @endcomponent
                        <!-- Group heading - End -->
    
                        <!-- Total - Start -->
                        @relativeInclude('includes.total')
                        <!-- Total - End -->
                    
                        <!-- Finance - Intereset Rate - Start -->
                        @relativeInclude('includes.interest')
                        <!-- Finance - Intereset Rate - End -->
    
                        <!-- Finance - Banking Fee - Start -->
                        @relativeInclude('includes.banking')
                        <!-- Finance - Banking Fee - End -->
    
                        <!-- Finance - Stamp - Start -->
                        @relativeInclude('includes.stamp')
                        <!-- Finance - Stamp - End -->
    
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