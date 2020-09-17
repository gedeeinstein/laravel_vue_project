<script type="text/x-template" id="contract-road">
    <div class="collapsible" v-for="group in ['contract-road']" :class="group">
        <div class="card">

            <!-- Group header - Start -->
            <div class="card-header p-2" :id="group+ '-heading'">
                <button type="button" class="btn btn-accordion" data-toggle="collapse" :data-target="'#' +group+ '-collapse'" aria-expanded="true"
                    :aria-controls="group+ '-collapse'">
                    <span class="fw-b">前面道路</span>
                </button>
            </div>
            <!-- Group header - End -->

            <div :id="group+ '-collapse'" class="collapse show" :aria-labelledby="group+ '-heading'" data-parent=".collapsible">
                <div class="card-body">
                    <div class="px-md-2">

                        <!-- Road size - Start -->
                        <template v-for="name in [ prefix+ 'road-size' ]">
                            <div class="form-group row mb-2 mb-md-3">
                                <label class="col-md-3 col-form-label">
                                    <span class="fw-n" :class="{ 'text-grey': isCompleted }">道路幅員(セットバック後)</span>
                                </label>
                                <div class="col-md">
                                    <div class="row">
                                        <div class="col-auto">
                                            
                                            <div class="icheck-cyan" v-for="name in [ name+ '-below-4m' ]">
                                                <input type="checkbox" :id="name" :name="name" data-parsley-checkmin="1"
                                                    :disabled="isDisabled" :true-value="true" :false-value="false" 
                                                    v-model="entry.road_size_contract_a" />
                                                <label :for="name" class="fs-12 fw-n noselect w-100">
                                                    <span :class="{ 'bg-warning p-1': 1 == defaults.road_size_contract_a, 'text-black-50': isCompleted }">4m未満</span>
                                                </label>
                                            </div>

                                        </div>
                                        <div class="col-auto">
                                            
                                            <div class="icheck-cyan" v-for="name in [ name+ '-4m-5m' ]">
                                                <input type="checkbox" :id="name" :name="name" data-parsley-checkmin="1"
                                                    :disabled="isDisabled" :true-value="true" :false-value="false"
                                                    v-model="entry.road_size_contract_b" />
                                                <label :for="name" class="fs-12 fw-n noselect w-100">
                                                    <span :class="{ 'bg-warning p-1': 1 == defaults.road_size_contract_b, 'text-black-50': isCompleted }">4.0 ～ 4.9m</span>
                                                </label>
                                            </div>

                                        </div>
                                        <div class="col-auto">
                                            
                                            <div class="icheck-cyan" v-for="name in [ name+ '-5m-6m' ]">
                                                <input type="checkbox" :id="name" :name="name" data-parsley-checkmin="1"
                                                    :disabled="isDisabled" :true-value="true" :false-value="false" 
                                                    v-model="entry.road_size_contract_c" />
                                                <label :for="name" class="fs-12 fw-n noselect w-100">
                                                    <span :class="{ 'bg-warning p-1': 1 == defaults.road_size_contract_c, 'text-black-50': isCompleted }">5.0 ～ 5.9m</span>
                                                </label>
                                            </div>

                                        </div>
                                        <div class="col-auto">
                                            
                                            <div class="icheck-cyan" v-for="name in [ name+ '-above-6m' ]">
                                                <input type="checkbox" :id="name" :name="name" data-parsley-checkmin="1"
                                                    :disabled="isDisabled" :true-value="true" :false-value="false" 
                                                    v-model="entry.road_size_contract_d" />
                                                <label :for="name" class="fs-12 fw-n noselect w-100">
                                                    <span :class="{ 'bg-warning p-1': 1 == defaults.road_size_contract_d, 'text-black-50': isCompleted }">6m以上</span>
                                                </label>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </template>
                        <!-- Road size - End -->

                        <!-- Road type - Start -->
                        <template v-for="name in [ prefix+ 'road-type' ]">
                            <div class="form-group row mb-2 mb-md-3">
                                <label class="col-md-3 col-form-label">
                                    <span class="fw-n" :class="{ 'text-grey': isCompleted }">道路幅員(道路種別)</span>
                                </label>
                                <div class="col-md">

                                    <div v-if="isRoadTypeEmpty" class="text-red">道路種別を選択してください</div>

                                    <div class="row">
                                        <div class="col-sm-6 col-lg-4">
                                            
                                            <div class="icheck-cyan" v-for="name in [ name+ '-a' ]">
                                                <input type="checkbox" :id="name" :name="name" data-parsley-checkmin="1"
                                                    :disabled="isDisabled" :true-value="true" :false-value="false" v-model="entry.road_type_contract_a" 
                                                    @click="updateRoadOwnership" />
                                                <label :for="name" class="fs-12 fw-n noselect w-100">
                                                    <span :class="{ 'bg-warning p-1': 1 == defaults.road_type_contract_a, 'text-black-50': isCompleted }">１項１号 国県市道</span>
                                                </label>
                                            </div>

                                        </div>
                                        <div class="col-sm-6 col-lg-4">
                                            
                                            <div class="icheck-cyan" v-for="name in [ name+ '-b' ]">
                                                <input type="checkbox" :id="name" :name="name" data-parsley-checkmin="1"
                                                    :disabled="isDisabled" :true-value="true" :false-value="false" v-model="entry.road_type_contract_b" 
                                                    @click="updateRoadOwnership" />
                                                <label :for="name" class="fs-12 fw-n noselect w-100">
                                                    <span :class="{ 'bg-warning p-1': 1 == defaults.road_type_contract_b, 'text-black-50': isCompleted }">１項２号 都市計画当事業道路</span>
                                                </label>
                                            </div>

                                        </div>
                                        <div class="col-sm-6 col-lg-4">
                                            
                                            <div class="icheck-cyan" v-for="name in [ name+ '-c' ]">
                                                <input type="checkbox" :id="name" :name="name" data-parsley-checkmin="1"
                                                    :disabled="isDisabled" :true-value="true" :false-value="false" v-model="entry.road_type_contract_c" 
                                                    @click="updateRoadOwnership" />
                                                <label :for="name" class="fs-12 fw-n noselect w-100">
                                                    <span :class="{ 'bg-warning p-1': 1 == defaults.road_type_contract_c, 'text-black-50': isCompleted }">１項３号 法施工前道路</span>
                                                </label>
                                            </div>

                                        </div>
                                        <div class="col-sm-6 col-lg-4">
                                            
                                            <div class="icheck-cyan" v-for="name in [ name+ '-d' ]">
                                                <input type="checkbox" :id="name" :name="name" data-parsley-checkmin="1"
                                                    :disabled="isDisabled" :true-value="true" :false-value="false" v-model="entry.road_type_contract_d" 
                                                    @click="updateRoadOwnership" />
                                                <label :for="name" class="fs-12 fw-n noselect w-100">
                                                    <span :class="{ 'bg-warning p-1': 1 == defaults.road_type_contract_d, 'text-black-50': isCompleted }">１項４号 事業予定道路</span>
                                                </label>
                                            </div>

                                        </div>
                                        <div class="col-sm-6 col-lg-4">
                                            
                                            <div class="icheck-cyan" v-for="name in [ name+ '-e' ]">
                                                <input type="checkbox" :id="name" :name="name" data-parsley-checkmin="1"
                                                    :disabled="isDisabled" :true-value="true" :false-value="false" v-model="entry.road_type_contract_e" 
                                                    @click="updateRoadOwnership" />
                                                <label :for="name" class="fs-12 fw-n noselect w-100">
                                                    <span :class="{ 'bg-warning p-1': 1 == defaults.road_type_contract_e, 'text-black-50': isCompleted }">１項５号 位置指定道路</span>
                                                </label>
                                            </div>

                                        </div>
                                        <div class="col-sm-6 col-lg-4">
                                            
                                            <div class="icheck-cyan" v-for="name in [ name+ '-f' ]">
                                                <input type="checkbox" :id="name" :name="name" data-parsley-checkmin="1"
                                                    :disabled="isDisabled" :true-value="true" :false-value="false" v-model="entry.road_type_contract_f" 
                                                    @click="updateRoadOwnership" />
                                                <label :for="name" class="fs-12 fw-n noselect w-100">
                                                    <span :class="{ 'bg-warning p-1': 1 == defaults.road_type_contract_f, 'text-black-50': isCompleted }">２項道路 狭あい道路</span>
                                                </label>
                                            </div>

                                        </div>
                                        <div class="col-sm-6 col-lg-4">
                                            
                                            <div class="icheck-cyan" v-for="name in [ name+ '-g' ]">
                                                <input type="checkbox" :id="name" :name="name" data-parsley-checkmin="1"
                                                    :disabled="isDisabled" :true-value="true" :false-value="false" v-model="entry.road_type_contract_g" 
                                                    @click="updateRoadOwnership" />
                                                <label :for="name" class="fs-12 fw-n noselect w-100">
                                                    <span :class="{ 'bg-warning p-1': 1 == defaults.road_type_contract_g, 'text-black-50': isCompleted }">３項道路 水平道路</span>
                                                </label>
                                            </div>

                                        </div>
                                        <div class="col-sm-6 col-lg-4">
                                            
                                            <div class="icheck-cyan" v-for="name in [ name+ '-h' ]">
                                                <input type="checkbox" :id="name" :name="name" data-parsley-checkmin="1"
                                                    :disabled="isDisabled" :true-value="true" :false-value="false" v-model="entry.road_type_contract_h" 
                                                    @click="updateRoadOwnership" />
                                                <label :for="name" class="fs-12 fw-n noselect w-100">
                                                    <span :class="{ 'bg-warning p-1': 1 == defaults.road_type_contract_h, 'text-black-50': isCompleted }">特定道路 協定道路</span>
                                                </label>
                                            </div>

                                        </div>
                                        <div class="col-sm-6 col-lg-4">
                                            
                                            <div class="icheck-cyan" v-for="name in [ name+ '-i' ]">
                                                <input type="checkbox" :id="name" :name="name" data-parsley-checkmin="1"
                                                    :disabled="isDisabled" :true-value="true" :false-value="false" v-model="entry.road_type_contract_i" 
                                                    @click="updateRoadOwnership" />
                                                <label :for="name" class="fs-12 fw-n noselect w-100">
                                                    <span :class="{ 'bg-warning p-1': 1 == defaults.road_type_contract_i, 'text-black-50': isCompleted }">表記ナシ 私道</span>
                                                </label>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </template>
                        <!-- Road type - End -->
                        
                        <!-- Road ownership - Start -->
                        <div v-if="!isRoadTypeEmpty" class="form-group row mb-2 mb-md-3">
                            <label class="col-md-3 col-form-label">
                                <span class="fw-n" :class="{ 'text-grey': isCompleted }">公道・私道</span>
                            </label>
                            <div class="col-md">

                                <!-- If empty road type - Start -->
                                <template v-if="isRoadTypeEmpty">
                                    <div :class="isCompleted ? 'text-grey': 'text-red'">道路種別を選択してください。</div>
                                </template>
                                <!-- If empty road type - End -->

                                <template v-else>

                                    <!-- Road ownership - Start -->
                                    <div class="road-ownership" v-for="name in [ prefix+ 'road-ownership' ]">

                                        <!-- Otherwise select the road type - Start -->
                                        <template v-if="!fixedPublic && !fixedPrivate">
                                            <div class="road-instruction" :class="isCompleted ? 'text-grey': 'text-red'">種別を選択して下さい。</div>
                                        </template>

                                        <div class="row">

                                            <!-- Public road - Start -->
                                            <div v-if="!fixedPrivate" class="col-auto">

                                                <!-- Fixed public road - Start -->
                                                <label v-if="fixedPublic" class="text-red fw-n"
                                                    :class="{ 'bg-warning px-1': 1 == defaults.road_type_sub2_contract_a }">
                                                    <span>公道</span>
                                                </label>
                                                <!-- Fixed public road - End -->
                                                
                                                <!-- Public road selection - Start -->
                                                <div v-else class="icheck-cyan" v-for="name in [ name+ '-public' ]">
                                                    <input type="checkbox" :id="name" :name="name" data-parsley-checkmin="1"
                                                        :disabled="isDisabled" :true-value="true" :false-value="false" 
                                                        v-model="entry.road_type_sub2_contract_a" />
                                                    <label :for="name" class="fs-12 fw-n noselect w-100">
                                                        <span :class="{ 'bg-warning p-1': 1 == defaults.road_type_sub2_contract_a, 'text-black-50': isCompleted }">公道</span>
                                                    </label>
                                                </div>
                                                <!-- Public road selection - End -->

                                            </div>
                                            <!-- Public road - End -->

                                            <!-- Private road - Start -->
                                            <div v-if="!fixedPublic" class="col-auto">

                                                <!-- Fixed private road - Start -->
                                                <label v-if="fixedPrivate" class="text-red fw-n"
                                                    :class="{ 'bg-warning px-1': 1 == defaults.road_type_sub2_contract_b }">
                                                    <span>私道</span>
                                                </label>
                                                <!-- Fixed private road - End -->
                                                
                                                <!-- Private road selection - Start -->
                                                <div v-else class="icheck-cyan" v-for="name in [ name+ '-private' ]">
                                                    <input type="checkbox" :id="name" :name="name" data-parsley-checkmin="1"
                                                        :disabled="isDisabled" :true-value="true" :false-value="false" 
                                                        v-model="entry.road_type_sub2_contract_b" />
                                                    <label :for="name" class="fs-12 fw-n noselect w-100">
                                                        <span :class="{ 'bg-warning p-1': 1 == defaults.road_type_sub2_contract_b, 'text-black-50': isCompleted }">私道</span>
                                                    </label>
                                                </div>
                                                <!-- Private road selection - End -->

                                            </div>
                                            <!-- Private road - End -->

                                        </div>
                                        <!-- Otherwise select the road type - End -->

                                    </div>
                                    <!-- Road ownership - End -->

                                    <!-- Private road form - Start -->
                                    <template v-if="entry.road_type_sub2_contract_b">

                                        <!-- Road burden - Start -->
                                        <div class="road-burden mt-2" v-for="name in [ prefix+ 'road-burden' ]">
                                            <label class="fw-n m-0" :class="{ 'text-grey': isCompleted }">私道負担</label>
                                            <div class="row">
                                                <div class="col-auto">
                                                    
                                                    <div class="icheck-cyan" v-for="id in [ name+ '-no' ]">
                                                        <input type="radio" :id="id" :name="name" data-parsley-checkmin="1"
                                                            :disabled="isDisabled" :value="1" v-model="entry.road_private_burden_contract" />
                                                        <label :for="id" class="fs-12 fw-n noselect w-100">
                                                            <span :class="{ 'text-black-50': isCompleted }">負担無</span>
                                                        </label>
                                                    </div>

                                                </div>
                                                <div class="col-auto">
                                                    
                                                    <div class="icheck-cyan" v-for="id in [ name+ '-yes' ]">
                                                        <input type="radio" :id="id" :name="name" data-parsley-checkmin="1"
                                                            :disabled="isDisabled" :value="2" v-model="entry.road_private_burden_contract" />
                                                        <label :for="id" class="fs-12 fw-n noselect w-100">
                                                            <span :class="{ 'text-black-50': isCompleted }">負担有</span>
                                                        </label>
                                                    </div>

                                                </div>
                                            </div>
                                            
                                            <!-- Burden contract - Start -->
                                            <div class="row mx-n1 mt-2">
                                                <div class="px-1 col-lg">
                                                    <div class="row mx-n1">
                                                        <div class="px-1 col-auto d-flex align-items-center">
                                                            <span :class="{ 'text-grey': isCompleted }">面積:</span>
                                                        </div>
                                                        <div class="px-1 col" @click="inputBurden">

                                                            @component("{$component->preset}.decimal")
                                                                @slot( 'disabled', 'isBurdenDisabled' )
                                                                @slot( 'name', "prefix + 'burden-area-contract'" )
                                                                @slot( 'model', 'entry.road_private_burden_area_contract' )
                                                            @endcomponent

                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="px-1 col-lg-5 mt-2 mt-lg-0">
                                                    <div class="row mx-n1">
                                                        <div class="px-1 col-6">
                                                            <div class="row mx-n1">
                                                                <div class="px-1 col-auto d-flex align-items-center">
                                                                    <span :class="{ 'text-grey': isCompleted }">持分:</span>
                                                                </div>
                                                                <div class="px-1 col" @click="inputBurden">

                                                                    @component("{$component->preset}.integer")
                                                                        @slot( 'disabled', 'isBurdenDisabled' )
                                                                        @slot( 'name', "prefix + 'burden-denom-contract'" )
                                                                        @slot( 'model', 'entry.road_private_burden_share_denom_contract' )
                                                                    @endcomponent

                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="px-1 col-6">
                                                            <div class="row mx-n1">
                                                                <div class="px-1 col-auto d-flex align-items-center">
                                                                    <span :class="{ 'text-grey': isCompleted }">分の:</span>
                                                                </div>
                                                                <div class="px-1 col" @click="inputBurden">

                                                                    @component("{$component->preset}.integer")
                                                                        @slot( 'disabled', 'isBurdenDisabled' )
                                                                        @slot( 'name', "prefix + 'burden-number-contract'" )
                                                                        @slot( 'model', 'entry.road_private_burden_share_number_contract' )
                                                                    @endcomponent

                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="px-1 col-lg mt-2 mt-lg-0">
                                                    <div class="row mx-n1">
                                                        <div class="px-1 col-auto d-flex align-items-center">
                                                            <span :class="{ 'text-grey': isCompleted }">金額:</span>
                                                        </div>
                                                        <div class="px-1 col" @click="inputBurden">

                                                            @component("{$component->preset}.money")
                                                                @slot( 'disabled', 'isBurdenDisabled' )
                                                                @slot( 'name', "prefix + 'burden-amount-contract'" )
                                                                @slot( 'model', 'entry.road_private_burden_amount_contract' )
                                                            @endcomponent

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Burden contract - End -->

                                        </div>
                                        <!-- Road burden - End -->

                                        <!-- Road setback - Start -->
                                        <div class="road-setback mt-2" v-for="group in [ prefix+ 'road-setback' ]">
                                            <label class="fw-n m-0 d-none d-sm-block" :class="{ 'text-grey': isCompleted }">セットバック</label>

                                            <div class="row">
                                                <div class="col-auto" v-for="name in [ group+ '-area' ]">
                                                    <div class="row">
                                                        <div class="col-auto d-flex d-sm-none align-items-center">
                                                            <label class="fw-n m-0" :class="{ 'text-grey': isCompleted }">セットバック</label>
                                                        </div>
                                                        <div class="col-auto">
                                                            
                                                            <div class="icheck-cyan" v-for="id in [ name+ '-no' ]">
                                                                <input type="radio" :id="id" :name="name" data-parsley-checkmin="1"
                                                                    :disabled="isDisabled" :value="2" v-model="entry.road_setback_area_contract" />
                                                                <label :for="id" class="fs-12 fw-n noselect w-100">
                                                                    <span :class="{ 'text-black-50': isCompleted }">無</span>
                                                                </label>
                                                            </div>

                                                        </div>
                                                        <div class="col-auto">
                                                            
                                                            <div class="icheck-cyan" v-for="id in [ name+ '-yes' ]">
                                                                <input type="radio" :id="id" :name="name" data-parsley-checkmin="1"
                                                                    :disabled="isDisabled" :value="1" v-model="entry.road_setback_area_contract" />
                                                                <label :for="id" class="fs-12 fw-n noselect w-100">
                                                                    <span :class="{ 'text-black-50': isCompleted }">有</span>
                                                                </label>
                                                            </div>
            
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-12 col-sm col-md-auto mt-2 mt-sm-0" 
                                                    v-for="name in [ group+ '-area-size' ]" @click="inputSetback" ref="inputSetback">

                                                    @component("{$component->preset}.decimal")
                                                        @slot( 'group', true )
                                                        @slot( 'name', "name" )
                                                        @slot( 'append' )
                                                            <span>m<sup>2</sup></span>
                                                        @endslot
                                                        @slot( 'disabled', 'isDisabled || 1 !== entry.road_setback_area_contract' )
                                                        @slot( 'model', 'entry.road_setback_area_size_contract' )
                                                    @endcomponent

                                                </div>
                                            </div>
                                        </div>
                                        <!-- Road setback - End -->

                                    </template>
                                    <!-- Private road form - End -->

                                </template>
                            </div>
                        </div>
                        <!-- Road ownership - End -->


                        <!-- Goverment donation - Start -->
                        <template v-if="!isRoadTypeEmpty" v-for="name in [ prefix+ 'goverment-donation' ]">
                            <div class="form-group row mb-2 mb-md-3">
                                <label class="col-md-3 col-form-label">
                                    <span class="fw-n" :class="{ 'text-grey': isCompleted }">役所寄付</span>
                                </label>
                                <div class="col-md">

                                    <!-- If empty road type - Start -->
                                    <template v-if="isRoadTypeEmpty">
                                        <div :class="isCompleted ? 'text-grey': 'text-red'">道路種別を選択してください。</div>
                                    </template>
                                    <!-- If empty road type - End -->

                                    <template v-else>

                                        <template v-if="!govermentDonation">
                                            <div class="py-2" :class="isCompleted ? 'text-grey': 'text-red'">
                                                <span>この道路種別では入力の必要はありません。</span>
                                            </div>
                                        </template>
                                        
                                        <template v-else>
                                            
                                            <div :class="isCompleted ? 'text-grey': 'text-red'">
                                                <span>１項２号　都市計画当事業道路の役所寄付を選択して下さい。</span>
                                            </div>

                                            <div class="row">
                                                <div class="col-auto">
                                                    
                                                    <div class="icheck-cyan" v-for="id in [ name+ '-yes' ]">
                                                        <input type="radio" :id="id" :name="name" data-parsley-checkmin="1"
                                                            :disabled="isDisabled" :value="1" v-model="entry.road_type_sub1_contract" />
                                                        <label :for="id" class="fs-12 fw-n noselect w-100">
                                                            <span :class="{ 'bg-warning p-1': 1 == defaultGovermentDonation, 'text-black-50': isCompleted }">役所寄付済</span>
                                                        </label>
                                                    </div>

                                                </div>
                                                <div class="col-auto">
                                                    
                                                    <div class="icheck-cyan" v-for="id in [ name+ '-no' ]">
                                                        <input type="radio" :id="id" :name="name" data-parsley-checkmin="1"
                                                            :disabled="isDisabled" :value="2" v-model="entry.road_type_sub1_contract" />
                                                        <label :for="id" class="fs-12 fw-n noselect w-100">
                                                            <span :class="{ 'bg-warning p-1': 2 == defaultGovermentDonation, 'text-black-50': isCompleted }">役所未寄付</span>
                                                        </label>
                                                    </div>

                                                </div>
                                            </div>

                                        </template>

                                    </template>
                                </div>
                            </div>
                        </template>
                        <!-- Goverment donation - End -->


                        <!-- Road sharing - Start -->
                        <template v-if="!isRoadTypeEmpty" v-for="name in [ prefix+ 'road-sharing' ]">
                            <div class="form-group row mb-2 mb-md-3">
                                <label class="col-md-3 col-form-label">
                                    <span class="fw-n" :class="{ 'text-grey': isCompleted }">前面道路持ち分</span>
                                </label>
                                <div class="col-md">

                                    <!-- If empty road type - Start -->
                                    <template v-if="isRoadTypeEmpty">
                                        <div :class="isCompleted ? 'text-grey': 'text-red'">道路種別を選択してください。</div>
                                    </template>
                                    <!-- If empty road type - End -->

                                    <template v-else>

                                        <template v-if="!isPrivate">
                                            <div class="py-2" :class="isCompleted ? 'text-grey': 'text-red'">
                                                <span>この道路種別では入力の必要はありません。</span>
                                            </div>
                                        </template>

                                        <template v-else>

                                            <div class="text-red">売主は前面道路持ち分を</div>

                                            <div class="row">
                                                <div class="col-auto">
                                                    
                                                    <div class="icheck-cyan" v-for="id in [ name+ '-yes' ]">
                                                        <input type="radio" :id="id" :name="name" data-parsley-checkmin="1"
                                                            :disabled="isDisabled" :value="1" v-model="entry.road_type_sub3_contract" />
                                                        <label :for="id" class="fs-12 fw-n noselect w-100">
                                                            <span :class="{ 'bg-warning p-1': 1 == defaultRoadSharing, 'text-black-50': isCompleted }">持っている</span>
                                                        </label>
                                                    </div>

                                                </div>
                                                <div class="col-auto">
                                                    
                                                    <div class="icheck-cyan" v-for="id in [ name+ '-no' ]">
                                                        <input type="radio" :id="id" :name="name" data-parsley-checkmin="1"
                                                            :disabled="isDisabled" :value="2" v-model="entry.road_type_sub3_contract" />
                                                        <label :for="id" class="fs-12 fw-n noselect w-100">
                                                            <span :class="{ 'bg-warning p-1': 2 == defaultRoadSharing, 'text-black-50': isCompleted }">持っていない</span>
                                                        </label>
                                                    </div>

                                                </div>
                                            </div>

                                        </template>

                                    </template>
                                </div>
                            </div>
                        </template>
                        <!-- Road sharing - End -->


                        <!-- Private road notes - Start -->
                        <template v-for="name in [ prefix+ 'front-road-share' ]">
                            <div class="form-group row mb-2 mb-md-3">
                                <label :for="name" class="col-md-3 col-form-label">
                                    <span class="fw-n" :class="{ 'text-grey': isCompleted }">私道時特記事項</span>
                                </label>
                                <div class="col-md">
                                    <textarea class="form-control" :disabled="isDisabled" 
                                        :name="name" :id="id" v-model="entry.remarks_contract">
                                    </textarea>
                                </div>
                            </div>
                        </template>
                        <!-- Private road notes - End -->
                        
                    </div>

                    <!-- Group status - Start -->
                    <div class="group-status bg-light p-3 p-md-2">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="row h-100 px-0 px-md-2" v-for="name in [ prefix+ 'completed' ]">
                                    <div class="col-auto d-flex align-items-center">
                                        
                                        <div class="icheck-cyan">
                                            <input type="radio" :id="name + '-yes'" :name="name" data-parsley-checkmin="1"
                                                :value="1" v-model.number="entry.road_private_status" />
                                            <label :for="name + '-yes'" class="fs-12 fw-n noselect w-100">
                                                <span>完</span>
                                            </label>
                                        </div>
    
                                    </div>
                                    <div class="col-auto d-flex align-items-center">
    
                                        <div class="icheck-cyan">
                                            <input type="radio" :id="name + '-no'" :name="name" data-parsley-checkmin="1"
                                                :value="2" v-model.number="entry.road_private_status" />
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
                                                        @slot( 'model', 'entry.road_private_memo' )
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
