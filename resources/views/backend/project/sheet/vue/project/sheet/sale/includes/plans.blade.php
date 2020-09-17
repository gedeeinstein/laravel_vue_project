<div class="card sale-plans mt-4 mb-0">
    <div class="card-header card-header-tabs compact-tabs draggable-tabs">

        <template v-for="group in [ 'sheet-' +( sheetIndex +1 )+ '-' ]">
            <draggable v-model="entry.plans" group="sale-plans" class="nav nav-tabs" id="plan-tabs" role="tablist" @end="reorderPlans"
                tag="ul" handle=".plan-drag-handle" animation="300" easing="cubic-bezier(0.165, 0.84, 0.44, 1)" :disabled="isDisabled">

                <!-- Plan tabs - Start -->
                <template v-for="( plan, planIndex ) in entry.plans" :key="planKey">
                    <plan-tab v-model="plan" :index="planIndex" :sale="entry" :sheet="sheet" :disabled="isDisabled"></plan-tab>
                </template>
                <!-- Plan tabs - End -->

                <!-- New plan button - Start -->
                <li class="nav-item">
                    <div class="nav-link cursor-pointer p-0" role="button">

                        <!-- Create button - Start -->
                        <button type="button" class="btn btn-tab btn-block" @click="createNewPlan" :disabled="isDisabled">
                            <div class="row mx-n1 justify-content-center">
                                <div class="px-1 col-auto fs-14 d-flex align-items-center">
                                    <i class="fas fa-plus-circle"></i>
                                </div>
                                <div class="px-1 col-auto">
                                    <span>@lang('project.sheet.tabs.options.new')</span>
                                </div>
                            </div>
                        </button>
                        <!-- Create button - End -->

                    </div>
                </li>
                <!-- New plan button - End -->

            </draggable>
        </template>

    </div>
    <div class="card-body overflow-hidden">
        <div class="tab-content" id="sale-plan-boards">
            
            <!-- Plan entries - Start -->
            <template v-for="( plan, planIndex ) in entry.plans">
                <plan-entry v-model="plan" :index="planIndex" :sale="entry" :sheet="sheet" :disabled="isDisabled"></plan-entry>
            </template>
            <!-- Plan entries - End -->

        </div>
    </div>
</div>