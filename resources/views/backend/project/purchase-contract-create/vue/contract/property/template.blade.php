<script type="text/x-template" id="contract-property">
    <div class="collapsible" v-for="group in ['contract-property']" :class="group">
        <div class="card">

            <!-- Group header - Start -->
            <div class="card-header p-2" :id="group+ '-heading'">
                <button type="button" class="btn btn-accordion" data-toggle="collapse" :data-target="'#' +group+ '-collapse'" aria-expanded="true"
                    :aria-controls="group+ '-collapse'">
                    <span class="fw-b">物件概要</span>
                </button>
            </div>
            <!-- Group header - End -->

            <div :id="group+ '-collapse'" class="collapse show" :aria-labelledby="group+ '-heading'" data-parent=".collapsible">
                <div class="card-body">

                    <div class="px-md-2">

                        <!-- Shipping - Start -->
                        <template v-for="name in [ prefix+ 'building-number' ]">
                            <div class="form-group row mb-2 mb-md-3">
                                <label :for="name" class="col-md-3 col-form-label">
                                    <span class="fw-n" :class="{ 'text-grey': isCompleted }">対象商品</span>
                                </label>
                                <div class="col-md">

                                    <!-- Heading - Start -->
                                    <div class="heading rounded bg-grey p-2 mb-2">
                                        <div class="fw-n" :class="{ 'text-grey': isCompleted }">
                                            <span>種類</span>
                                        </div>
                                    </div>
                                    <!-- Heading - End -->

                                    <!-- Property type - Start -->
                                    <div class="row mb-2 mx-n1">
                                        <div class="px-1 col-auto d-flex align-items-center">
                                            <span v-if="propertyType" class="fw-b my-2" :class="isCompleted ? 'text-grey': 'text-danger'">
                                                <span v-if="1 === propertyType">土地（更地）</span>
                                                <span v-else-if="2 === propertyType">土地（商品用建物有）</span>
                                                <span v-else-if="3 === propertyType">土地（解体建物付）</span>
                                                <span v-else-if="4 === propertyType">土地（解建＋商建付）</span>
                                            </span>
                                        </div>
                                        <div class="px-1 col-auto d-flex align-items-center">
                                            <button type="button" class="btn btn-sm px-2" :class="noteDisplay ? 'btn-outline-primary': 'btn-primary'"
                                                :disabled="isDisabled" @click="noteDisplay = !noteDisplay">
                                                <span>種類を変更</span>
                                            </button>
                                        </div>
                                    </div>
                                    <!-- Property type - End -->

                                    <!-- Display note - Start -->
                                    <transition name="paste-in">
                                        <div v-if="noteDisplay" class="p-2 rounded" :class="{ 'bg-light text-light-50': isCompleted, 'bg-warning': !isCompleted }">
                                            <span>商品の種類を変更する際は、アシストAで建物の情報を正確に入力するか、仕入契約画面にて商品用建物or解体用建物の区別を正確に選択してください。</span>
                                            <span :class="{ 'text-danger': !isCompleted }">※アシストAで建物情報が入力されず、かつ未登記建物もない場合は、更地として判断されます。</span>
                                        </div>
                                    </transition>
                                    <!-- Display note - End -->

                                </div>
                            </div>
                        </template>
                        <!-- Shipping - End -->


                        <!-- Demolition - Start -->
                        <template v-if="2 === propertyType || 4 === propertyType" v-for="name in [ prefix+ 'demolition' ]">

                            <!-- Demolition selection - Start -->
                            <div class="form-group row mb-2 mb-md-3">
                                <label :for="name" class="col-md-3 col-form-label">
                                    <span class="fw-n" :class="{ 'text-grey': isCompleted }">古屋解体</span>
                                </label>
                                <div class="col-md">
                                    <div class="row">
                                        <div class="col-auto">

                                            <div class="icheck-cyan">
                                                <input type="radio" :id="name + '-seller'" :name="name" data-parsley-checkmin="1"
                                                    :disabled="isDisabled" :value="1" v-model.number="propertyDescriptionDismantling" />
                                                <label :for="name + '-seller'" class="fs-12 fw-n noselect w-100">
                                                    <span :class="{ 'bg-warning p-1': 1 == defaultDemolition, 'text-black-50': isCompleted }">売主</span>
                                                </label>
                                            </div>

                                        </div>
                                        <div class="col-auto">

                                            <div class="icheck-cyan">
                                                <input type="radio" :id="name + '-buyer'" :name="name" data-parsley-checkmin="1"
                                                    :disabled="isDisabled" :value="2" v-model.number="propertyDescriptionDismantling" />
                                                <label :for="name + '-buyer'" class="fs-12 fw-n noselect w-100">
                                                    <span :class="{ 'bg-warning p-1': 2 == defaultDemolition, 'text-black-50': isCompleted }">買主</span>
                                                </label>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Demolition selection - End -->

                            <!-- Ownership transfer - Start -->
                            <transition name="fade-in">
                                <template v-if="2 === entry.property_description_dismantling" v-for="name in [ prefix+ 'ownership-transfer' ]">
                                    <div class="form-group row mb-2 mb-md-3">
                                        <label :for="name" class="col-md-3 col-form-label">
                                            <span class="fw-n" :class="{ 'text-grey': isCompleted }">建物所有権移転</span>
                                        </label>
                                        <div class="col-md">

                                            <!-- Ownership transfer - Start -->
                                            <div class="row">
                                                <div class="col-auto">

                                                    <div class="icheck-cyan">
                                                        <input type="radio" :id="name + '-yes'" :name="name" data-parsley-checkmin="1"
                                                            :disabled="isDisabled" :value="1" v-model="entry.property_description_transfer" />
                                                        <label :for="name + '-yes'" class="fs-12 fw-n noselect w-100">
                                                            <span :class="{ 'text-black-50': isCompleted }">する</span>
                                                        </label>
                                                    </div>

                                                </div>
                                                <div class="col-auto">

                                                    <div class="icheck-cyan">
                                                        <input type="radio" :id="name + '-no'" :name="name" data-parsley-checkmin="1"
                                                            :disabled="isDisabled" :value="2" v-model="entry.property_description_transfer" />
                                                        <label :for="name + '-no'" class="fs-12 fw-n noselect w-100">
                                                            <span :class="{ 'text-black-50': isCompleted }">しない</span>
                                                        </label>
                                                    </div>

                                                </div>
                                            </div>
                                            <!-- Ownership transfer - End -->

                                            <!-- Removal by buyer - Start -->
                                            <div class="row mt-2">
                                                <div class="col-auto">

                                                    <div class="icheck-cyan" v-for="name in [ prefix+ 'remnant-removal' ]">
                                                        <input type="checkbox" :id="name" :name="name" data-parsley-checkmin="1"
                                                            :disabled="isDisabled" :true-value="1" :false-value="0"
                                                            v-model="entry.property_description_removal_by_buyer" />
                                                        <label :for="name" class="fs-12 fw-n noselect w-100">
                                                            <span :class="{ 'text-black-50': isCompleted }">残置物も買主にて撤去</span>
                                                        </label>
                                                    </div>

                                                </div>
                                                <div class="col-auto">

                                                    <div class="icheck-cyan" v-for="name in [ prefix+ 'aircond-removal' ]">
                                                        <input type="checkbox" :id="name" :name="name" data-parsley-checkmin="1"
                                                            :disabled="isDisabled" :true-value="1" :false-value="0"
                                                            v-model="entry.property_description_cooler_removal_by_buyer" />
                                                        <label :for="name" class="fs-12 fw-n noselect w-100">
                                                            <span :class="{ 'text-black-50': isCompleted }">エアコンも買主にて撤去</span>
                                                        </label>
                                                    </div>

                                                </div>
                                            </div>
                                            <!-- Removal by buyer - End -->

                                        </div>
                                    </div>
                                </template>
                            </transition>
                            <!-- Ownership transfer - End -->

                        </template>
                        <!-- Demolition - End -->


                        <!-- Building type - Start -->
                        <template v-if="3 === propertyType || 4 === propertyType" v-for="name in [ prefix+ 'building-type' ]">
                            <div class="form-group row mb-2 mb-md-3">
                                <label :for="name" class="col-md-3 col-form-label">
                                    <span class="fw-n" :class="{ 'text-grey': isCompleted }">商品用建物の種類</span>
                                </label>
                                <div class="col-md">
                                    <div class="row">
                                        <div class="col-auto">

                                            <div class="icheck-cyan">
                                                <input type="radio" :id="name + '-used-house'" :name="name" data-parsley-checkmin="1"
                                                    :disabled="isDisabled" :value="1" v-model="entry.property_description_kind" />
                                                <label :for="name + '-used-house'" class="fs-12 fw-n noselect w-100">
                                                    <span :class="{ 'text-black-50': isCompleted }">中古住宅</span>
                                                </label>
                                            </div>

                                        </div>
                                        <div class="col-auto">

                                            <div class="icheck-cyan">
                                                <input type="radio" :id="name + '-commerical-house'" :name="name" data-parsley-checkmin="1"
                                                    :disabled="isDisabled" :value="2" v-model="entry.property_description_kind" />
                                                <label :for="name + '-commerical-house'" class="fs-12 fw-n noselect w-100">
                                                    <span :class="{ 'text-black-50': isCompleted }">収益物件（住居）</span>
                                                </label>
                                            </div>

                                        </div>
                                        <div class="col-auto">

                                            <div class="icheck-cyan">
                                                <input type="radio" :id="name + '-commerical-store'" :name="name" data-parsley-checkmin="1"
                                                    :disabled="isDisabled" :value="3" v-model="entry.property_description_kind" />
                                                <label :for="name + '-commerical-store'" class="fs-12 fw-n noselect w-100">
                                                    <span :class="{ 'text-black-50': isCompleted }">収益物件（店舗のみ）</span>
                                                </label>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </template>
                        <!-- Building type - End -->

                    </div>

                    <!-- Group status - Start -->
                    <div class="group-status bg-light p-3 p-md-2">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="row h-100 px-0 px-md-2" v-for="name in [ prefix+ 'completed' ]">
                                    <div class="col-auto d-flex align-items-center">

                                        <div class="icheck-cyan">
                                            <input type="radio" :id="name + '-yes'" :name="name" data-parsley-checkmin="1"
                                                :value="1" v-model.number="entry.property_description_status" />
                                            <label :for="name + '-yes'" class="fs-12 fw-n noselect w-100">
                                                <span>完</span>
                                            </label>
                                        </div>

                                    </div>
                                    <div class="col-auto d-flex align-items-center">

                                        <div class="icheck-cyan">
                                            <input type="radio" :id="name + '-no'" :name="name" data-parsley-checkmin="1"
                                                :value="2" v-model.number="entry.property_description_status" />
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
                                                        @slot( 'model', 'entry.property_description_memo' )
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
