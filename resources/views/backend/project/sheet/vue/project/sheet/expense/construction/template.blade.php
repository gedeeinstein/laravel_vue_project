<script type="text/x-template" id="expense-construction">
    <div class="expense-construction collapsible mb-3 mb-md-4">
        <div class="card">

            @php 
                $component->expense = 'backend.project.sheet.components.sheet.expense';
                $lang = (object) array(
                    'label'   => 'project.sheet.expense.label',
                    'option'  => 'project.sheet.expense.option',
                    'heading' => 'project.sheet.expense.heading'
                );
            @endphp
    
            <div class="card-header p-1" id="expense-construction">
                <button type="button" class="btn btn-accordion" data-toggle="collapse" data-target="#collapse-expense-construction" aria-expanded="true"
                    aria-controls="collapse-expense-construction">
                    <span>@lang("{$lang->heading}.construction")</span>
                </button>
            </div>
    
            <div id="collapse-expense-construction" class="collapse show" aria-labelledby="expense-construction" data-parent=".expense-construction">
                <div class="card-body p-0">
                    
                    <ul class="list-group list-group-flush">

                        <!-- Group heading - Start -->
                        @component("{$component->expense}.heading") @endcomponent
                        <!-- Group heading - End -->
    
                        <!-- Total - Start -->
                        @relativeInclude('includes.total')
                        <!-- Total - End -->
    
                        <!-- Construction - Building Demolition - Start -->
                        @relativeInclude('includes.building-demolition')
                        <!-- Construction - Building Demolition - End -->
    
                        <!-- Construction - Retaining Wall Demolition - Start -->
                        @relativeInclude('includes.wall-demolition')
                        <!-- Construction - Retaining Wall Demolition - End -->
    
                        <!-- Construction - Electric Pole Moving / Transfer - Start -->
                        @relativeInclude('includes.pole-relocation')
                        <!-- Construction - Electric Pole Moving / Transfer - End -->
    
                        <!-- Construction - Water / Plumbing Construction - Start -->
                        @relativeInclude('includes.plumbing')
                        <!-- Construction - Water / Plumbing Construction - End -->
    
                        <!-- Construction - Embankment Construction - Start -->
                        @relativeInclude('includes.embankment')
                        <!-- Construction - Embankment Construction - End -->
    
                        <!-- Construction - Retaining Wall Construction - Start -->
                        @relativeInclude('includes.wall-construction')
                        <!-- Construction - Retaining Wall Construction - End -->
    
                        <!-- Construction - Road Construction - Start -->
                        @relativeInclude('includes.road')
                        <!-- Construction - Road Construction - End -->
    
                        <!-- Construction - Gutter Construction - Start -->
                        @relativeInclude('includes.gutter')
                        <!-- Construction - Gutter Construction - End -->
    
                        <!-- Construction - Construction Work Set - Start -->
                        @relativeInclude('includes.work-set')
                        <!-- Construction - Construction Work Set - End -->
    
                        <!-- Construction - Location Designation Fee - Start -->
                        @relativeInclude('includes.location-fee')
                        <!-- Construction - Location Designation Fee - End -->
    
                        <!-- Construction - Development Commission - Start -->
                        @relativeInclude('includes.commission')
                        <!-- Construction - Development Commission - End -->
    
                        <!-- Construction - Cultural Property Fee - Start -->
                        @relativeInclude('includes.cultural-fee')
                        <!-- Construction - Cultural Property Fee - End -->
    
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