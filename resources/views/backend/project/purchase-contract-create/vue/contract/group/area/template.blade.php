<script type="text/x-template" id="group-area">
    <div class="form-group row mb-2 mb-md-3">
        <label :for="name" class="col-md-3 col-form-label">
            <span class="fw-n" :class="{ 'text-grey': isCompleted }">4条 面積</span>
        </label>
        <div class="col-md">

            <!-- Contract - Start -->
            <div class="row" v-for="name in [ prefix+ 'contract' ]">
                <div class="col-auto">

                    <div class="icheck-cyan" v-for="id in [ name+ '-public' ]">
                        <input type="radio" :id="id" :name="name" data-parsley-checkmin="1"
                            :disabled="isDisabled" :value="1" v-model="article4Contract" />
                        <label :for="id" class="fs-12 fw-n noselect w-100">
                            <span :class="{ 'bg-warning p-1': 1 == defaultTransaction, 'text-black-50': isCompleted }">公簿取引</span>
                        </label>
                    </div>

                </div>
                <div class="col-auto">

                    <div class="icheck-cyan" v-for="id in [ name+ '-clearing' ]">
                        <input type="radio" :id="id" :name="name" data-parsley-checkmin="1"
                            :disabled="isDisabled" :value="2" v-model="article4Contract" />
                        <label :for="id" class="fs-12 fw-n noselect w-100">
                            <span :class="{ 'bg-warning p-1': 2 == defaultTransaction, 'text-black-50': isCompleted }">実測清算取引</span>
                        </label>
                    </div>

                </div>
            </div>
            <!-- Contract - End -->

            <!-- Contract option 2 - Start -->
            <transition name="paste-in">
                <div class="actual-clearing-transaction" v-if="2 === entry.c_article4_contract">

                    <hr/>

                    <!-- Subcontract - Start -->
                    <div class="area-burden" v-for="name in [ prefix+ 'subcontract' ]">

                        <!-- Small screen label - Start -->
                        <label class="fs-14 fw-n noselect w-100 d-block d-md-none">
                            <span :class="{ 'text-black-50': isCompleted }">負担</span>
                        </label>
                        <!-- Small screen label - End -->

                        <!-- Option 1 - Start -->
                        <div class="row option">
                            <div class="col-12">
                                <div class="icheck-cyan" v-for="id in [ name+ '-no' ]">
                                    <input type="radio" :id="id" :name="name" data-parsley-checkmin="1"
                                    :disabled="isDisabled" :value="1" v-model="subContract" />
                                    <label :for="id" class="fs-14 fw-n noselect w-100">
                                        <span :class="{ 'bg-warning p-1': 2 == defaultTransaction, 'text-black-50': isCompleted }">
                                            1.私道負担（道路境界線後退部分を含む）のない場合、登記簿（公募）面積
                                        </span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <!-- Option 1 - End -->

                        <!-- Option 2 - Start -->
                        <div class="row option">
                            <div class="col-12">
                                <div class="icheck-cyan" v-for="id in [ name+ '-yes' ]">
                                    <input type="radio" :id="id" :name="name" data-parsley-checkmin="1"
                                        :disabled="isDisabled" :value="2" v-model="subContract" />
                                    <label :for="id" class="fs-14 fw-n noselect w-100">
                                        <span :class="{ 'bg-warning p-1': 2 == defaultTransaction, 'text-black-50': isCompleted }">
                                            2.私道負担（道路境界線後退部分を含む）のある場合、その部分を除く有効宅地部分
                                        </span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <!-- Option 1 - End -->

                        <!-- Custom option - Start -->
                        <div class="row option mx-0 py-1" v-for="id in [ name+ '-custom' ]" @click="customSubContract">
                            <div class="px-0 col-auto">
                                <div class="icheck-cyan">
                                    <input type="radio" :id="id" :name="name" data-parsley-checkmin="1"
                                        :disabled="isDisabled" :value="3" v-model="subContract" />
                                    <label :for="id" class="fs-14 fw-n noselect w-100"></label>
                                </div>
                            </div>
                            <div class="px-0 col col-lg-9" ref="customInput">
                                <input class="form-control" :name="id" v-model="entry.c_article4_sub_text_contract"
                                    :disabled="3 !== subContract" />
                            </div>
                        </div>
                        <!-- Custom option - End -->

                    </div>
                    <!-- Subcontract - Start -->

                    <hr/>

                    <!-- Clearing area - Start -->
                    <template v-for="name in [ prefix+ 'clearing-area' ]">
                        <div class="form-group row mb-2 mb-md-3">
                            <label :for="name" class="col-md-4 col-lg-3 col-xl-2 col-form-label">
                                <span class="fw-n" :class="{ 'text-grey': isCompleted }">精算基準面積</span>
                            </label>
                            <div class="col col-lg-6 col-xl-7">

                                @component("{$component->preset}.decimal")
                                    @slot( 'group', true )
                                    @slot( 'name', 'name' )
                                    @slot( 'disabled', 'isDisabled' )
                                    @slot( 'model', 'entry.c_article4_clearing_standard_area' )
                                    @slot( 'append' )
                                        <span style="width:1.25rem">m<sup>2</sup></span>
                                    @endslot
                                @endcomponent

                            </div>
                        </div>
                    </template>
                    <!-- Clearing area - End -->

                    <!-- Clearing area cost - Start -->
                    <template v-for="name in [ prefix+ 'clearing-cost' ]">
                        <div class="form-group row mb-2 mb-md-3">
                            <label :for="name" class="col-md-4 col-lg-3 col-xl-2 col-form-label">
                                <span class="fw-n" :class="{ 'text-grey': isCompleted }">清算単価1㎡あたり</span>
                            </label>
                            <div class="col col-lg-6 col-xl-7">

                                @component("{$component->preset}.money")
                                    @slot( 'group', true )
                                    @slot( 'name', 'name' )
                                    @slot( 'disabled', 'isDisabled' )
                                    @slot( 'model', 'entry.c_article4_clearing_standard_area_cost' )
                                    @slot( 'append' )
                                        <span style="width:1.25rem">円</span>
                                    @endslot
                                @endcomponent

                            </div>
                        </div>
                    </template>
                    <!-- Clearing area cost - End -->

                    <!-- Clearing area note - Start -->
                    <template v-for="name in [ prefix+ 'clearing-note' ]">
                        <div class="form-group row mb-1">
                            <label :for="name" class="col-md-4 col-lg-3 col-xl-2 col-form-label">
                                <span class="fw-n" :class="{ 'text-grey': isCompleted }">備考</span>
                            </label>
                            <div class="col">
                                <textarea class="form-control" :name="name" :disabled="isDisabled"
                                    v-model="entry.c_article4_clearing_standard_area_remarks">
                                </textarea>
                            </div>
                        </div>
                    </template>
                    <!-- Clearing area note - End -->

                </div>
            </transition>
            <!-- Contract option 2 - End -->

        </div>
    </div>
</script>
