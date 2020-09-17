<script type="text/x-template" id="contract-real-estate">
    <div class="collapsible" v-for="group in ['contract-real-estate']" :class="group">
        <div class="card">

            <!-- Group header - Start -->
            <div class="card-header p-2" :id="group+ '-heading'">
                <button type="button" class="btn btn-accordion" data-toggle="collapse" :data-target="'#' +group+ '-collapse'" aria-expanded="true"
                    :aria-controls="group+ '-collapse'">
                    <span class="fw-b">不動産の表示</span>
                </button>
            </div>
            <!-- Group header - End -->

            <div :id="group+ '-collapse'" class="collapse show" :aria-labelledby="group+ '-heading'" data-parent=".collapsible">
                <div class="card-body">

                    <!-- Product building component - Start -->
                    <template v-if="buildings" v-for="( building, buildingIndex ) in buildings">

                        <!-- Building - Start -->
                        <contract-building v-model="building.product_building" :parent="building" :index="buildingIndex" 
                            :disabled="isDisabled" :completed="isCompleted">
                        </contract-building>
                        <!-- Building - End -->

                    </template>
                    <!-- Product building component - End -->
                    

                    <!-- Heading - Start -->
                    <div class="heading rounded bg-grey p-2 mt-3 mb-2 mb-md-3">
                        <span class="fw-b" :class="{ 'text-grey': isCompleted }">特記事項</span>
                    </div>
                    <!-- Heading - End -->

                    <div class="px-md-2">

                        <!-- Residential special notes - Start -->
                        <template v-if="hasResidentials" v-for="name in [ prefix+ 'residential-special-note' ]">
                            <div class="form-group row mb-2 mb-md-3">
                                <label :for="name" class="col-md-3 col-form-label">
                                    <span class="fw-n" :class="{ 'text-grey': isCompleted }">宅地の特記事項</span>
                                </label>
                                <div class="col-md">
                                    
                                    @component("{$component->preset}.textarea")
                                        @slot( 'disabled', 'isDisabled' )
                                        @slot( 'model', 'entry.notices_residential_contract' )
                                    @endcomponent

                                </div>
                            </div>
                        </template>
                        <!-- Residential special notes - End -->

                        <!-- Special roads - Start -->
                        <template v-if="hasRoads" v-for="name in [ prefix+ 'special-road' ]">
                            <div class="form-group row mb-2 mb-md-3">
                                <label :for="name" class="col-md-3 col-form-label">
                                    <span class="fw-n" :class="{ 'text-grey': isCompleted }">道路の特記事項</span>
                                </label>
                                <div class="col-md">

                                    @component("{$component->preset}.textarea")
                                        @slot( 'disabled', 'isDisabled' )
                                        @slot( 'model', 'entry.notices_road_contract' )
                                    @endcomponent

                                </div>
                            </div>
                        </template>
                        <!-- Special roads - End -->

                        <!-- Building special note - Start -->
                        <template v-if="hasBuildings" v-for="name in [ prefix+ 'building-special-note' ]">
                            <div class="form-group row mb-2 mb-md-3">
                                <label :for="name" class="col-md-3 col-form-label">
                                    <span class="fw-n" :class="{ 'text-grey': isCompleted }">建物の特記事項</span>
                                </label>
                                <div class="col-md">

                                    @component("{$component->preset}.textarea")
                                        @slot( 'disabled', 'isDisabled' )
                                        @slot( 'model', 'entry.notices_building_contract' )
                                    @endcomponent

                                </div>
                            </div>
                        </template>
                        <!-- Building special note - End -->

                    </div>

                    <!-- Group status - Start -->
                    <div class="group-status bg-light p-3 p-md-2">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="row h-100 px-0 px-md-2" v-for="name in [ prefix+ 'completed' ]">
                                    <div class="col-auto d-flex align-items-center">
                                        
                                        <div class="icheck-cyan">
                                            <input type="radio" :id="name + '-yes'" :name="name" data-parsley-checkmin="1"
                                                :value="1" v-model.number="entry.notices_status" />
                                            <label :for="name + '-yes'" class="fs-12 fw-n noselect w-100">
                                                <span>完</span>
                                            </label>
                                        </div>
    
                                    </div>
                                    <div class="col-auto d-flex align-items-center">
    
                                        <div class="icheck-cyan">
                                            <input type="radio" :id="name + '-no'" :name="name" data-parsley-checkmin="1"
                                                :value="2" v-model.number="entry.notices_status" />
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
                                                        @slot( 'model', 'entry.notices_memo' )
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
