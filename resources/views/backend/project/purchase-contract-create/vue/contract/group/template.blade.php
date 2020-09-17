<script type="text/x-template" id="contract-group">
    <div class="collapsible" v-for="group in ['contract-group']" :class="group">
        <div class="card">

            <!-- Group header - Start -->
            <div class="card-header p-2" :id="group+ '-heading'">
                <button type="button" class="btn btn-accordion" data-toggle="collapse" :data-target="'#' +group+ '-collapse'" aria-expanded="true"
                    :aria-controls="group+ '-collapse'">
                    <span class="fw-b">契約書</span>
                </button>
            </div>
            <!-- Group header - End -->

            <div :id="group+ '-collapse'" class="collapse show" :aria-labelledby="group+ '-heading'" data-parent=".collapsible">
                <div class="card-body">
                    <div class="px-md-2">

                        <!-- Article 4 - Area group - Start -->
                        <group-area v-model="entry" :project="project" :contract="contract" :target="target" 
                            :completed="isCompleted" :disabled="isDisabled">
                        </group-area>
                        <!-- Article 4 - Area group - End -->

                        <hr/>

                        <!-- Article 5 - Boundary group - Start -->
                        <group-boundary v-model="entry" :project="project" :contract="contract" :target="target" 
                            :completed="isCompleted" :disabled="isDisabled">
                        </group-boundary>
                        <!-- Article 5 - Coundary group - End -->

                        <hr/>

                        <!-- Article 6 - Registration group - Start -->
                        <group-registration v-model="entry" :project="project" :contract="contract" :target="target" 
                            :completed="isCompleted" :disabled="isDisabled">
                        </group-registration>
                        <!-- Article 6 - Registration group - End -->

                        <hr/>

                        <!-- Article 8 - Delivery - Start -->
                        <group-delivery v-model="entry" :project="project" :contract="contract" :target="target" 
                            :completed="isCompleted" :disabled="isDisabled">
                        </group-delivery>
                        <!-- Article 8 - Delivery - End -->

                        <hr/>

                        <!-- Article 12 - Penalty - Start -->
                        <group-penalty v-model="entry" :project="project" :contract="contract" :target="target" 
                            :completed="isCompleted" :disabled="isDisabled">
                        </group-penalty>
                        <!-- Article 12 - Penalty - End -->

                        <hr/>

                        <!-- Article 15 - Finance - Start -->
                        <group-finance v-model="entry" :project="project" :contract="contract" :target="target" 
                            :completed="isCompleted" :disabled="isDisabled">
                        </group-finance>
                        <!-- Article 15 - Finance - End -->

                        <hr/>

                        <!-- Article 16 - Stamp - Start -->
                        <group-stamp v-model="entry" :project="project" :contract="contract" :target="target" 
                            :completed="isCompleted" :disabled="isDisabled">
                        </group-stamp>
                        <!-- Article 16 - Stamp - End -->

                        <hr/>

                        <!-- Article 23 - Confirmation  - Start -->
                        <group-confirmation v-model="entry" :project="project" :contract="contract" :target="target" 
                            :completed="isCompleted" :disabled="isDisabled">
                        </group-confirmation>
                        <!-- Article 23 - Confirmation - End -->

                        <hr/>

                        <!-- Remarks  - Start -->
                        <group-remark v-model="entry" :project="project" :contract="contract" :target="target" 
                            :completed="isCompleted" :disabled="isDisabled">
                        </group-remark>
                        <!-- Remarks - End -->

                        <hr/>
                        
                    </div>

                    <!-- Group status - Start -->
                    <div class="group-status bg-light p-3 p-md-2">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="row h-100 px-0 px-md-2" v-for="name in [ prefix+ 'completed' ]">
                                    <div class="col-auto d-flex align-items-center">
                                        
                                        <div class="icheck-cyan">
                                            <input type="radio" :id="name + '-yes'" :name="name" data-parsley-checkmin="1"
                                                :value="1" v-model.number="entry.contract_status" />
                                            <label :for="name + '-yes'" class="fs-12 fw-n noselect w-100">
                                                <span>完</span>
                                            </label>
                                        </div>
    
                                    </div>
                                    <div class="col-auto d-flex align-items-center">
    
                                        <div class="icheck-cyan">
                                            <input type="radio" :id="name + '-no'" :name="name" data-parsley-checkmin="1"
                                                :value="2" v-model.number="entry.contract_status" />
                                            <label :for="name + '-no'" class="fs-12 fw-n noselect w-100">
                                                <span>未</span>
                                            </label>
                                        </div>
    
                                    </div>
                                    <div class="col d-none d-lg-flex align-items-center justify-content-end">
                                        <label class="m-0 fw-n" :for="prefix+ 'memo'">未完メモ：</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md">
                                <div class="row">
                                    <div class="col-lg-8">
                                        <div class="row">
                                            <div class="col-md-auto d-flex d-lg-none align-items-center mt-2 mb-2">
                                                <label class="m-0 fw-n" :for="prefix+ 'memo'">未完メモ：</label>
                                            </div>
                                            <div class="col-md">
                                                <template v-for="name in [ prefix+ 'memo' ]">

                                                    @component("{$component->preset}.text")
                                                        @slot( 'disabled', 'isDisabled' )
                                                        @slot( 'model', 'entry.contract_memo' )
                                                        @slot( 'placeholder', "'未完となっている項目や理由を記入してください'")
                                                    @endcomponent
                                                    
                                                </template>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Group status - End -->

                </div>
            </div>
        </div>
    </div>
</script>
