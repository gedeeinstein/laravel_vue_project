<script type="text/x-template" id="group-registration">
    <div class="form-group row mb-2 mb-md-3">
        <label :for="name" class="col-md-3 col-form-label">
            <span class="fw-n" :class="{ 'text-grey': isCompleted }">6条 所有権の移転、登記</span>
        </label>
        <div class="col-md">
            
            <!-- Heading - Start -->
            <div class="heading rounded bg-grey p-2 mb-2">
                <span class="fw-n" :class="{ 'text-grey': isCompleted }">区画整理事業完了時清算金</span>
            </div>
            <!-- Heading - End -->

            <!-- Liquidation - Start -->
            <div v-if="2 === landType" class="row mb-2" v-for="name in [ prefix+ 'liquidation' ]">
                <div class="col-auto">

                    <div class="icheck-cyan" v-for="id in [ name+ '-seller' ]">
                        <input type="radio" :id="id" :name="name" data-parsley-checkmin="1"
                            :disabled="isDisabled" :value="1" v-model="article61Contract" />
                        <label :for="id" class="fs-12 fw-n noselect w-100">
                            <span :class="{ 'bg-warning p-1': 1 == defaultLiquidation, 'text-black-50': isCompleted }">売主負担頼</span>
                        </label>
                    </div>

                </div>
                <div class="col-auto">

                    <div class="icheck-cyan" v-for="id in [ name+ '-buyer' ]">
                        <input type="radio" :id="id" :name="name" data-parsley-checkmin="1"
                            :disabled="isDisabled" :value="2" v-model="article61Contract" />
                        <label :for="id" class="fs-12 fw-n noselect w-100">
                            <span :class="{ 'bg-warning p-1': 2 == defaultLiquidation, 'text-black-50': isCompleted }">買主負担頼</span>
                        </label>
                    </div>

                </div>
            </div>
            <!-- Liquidation - End -->

            <!-- Not required label - Start -->
            <div v-else class="fs-14 my-2" :class="isCompleted ? 'text-grey': 'text-danger'">この項目は　区画整理地内：仮換地　の場合以外は入力の必要ありません。</div>
            <!-- Not required label - End -->
    
            <!-- Heading - Start -->
            <div class="heading rounded bg-grey p-2 mb-2">
                <span class="fw-n" :class="{ 'text-grey': isCompleted }">区画整理事業における賦課金</span>
            </div>
            <!-- Heading - End -->

            <!-- Retribution - Start -->
            <div v-if="2 === landType" class="row mb-2" v-for="name in [ prefix+ 'retribution' ]">
                <div class="col-auto">

                    <div class="icheck-cyan" v-for="id in [ name+ '-seller' ]">
                        <input type="radio" :id="id" :name="name" data-parsley-checkmin="1"
                            :disabled="isDisabled" :value="1" v-model="article62Contract" />
                        <label :for="id" class="fs-12 fw-n noselect w-100">
                            <span :class="{ 'bg-warning p-1': 1 == defaultRetribution, 'text-black-50': isCompleted }">売主負担頼</span>
                        </label>
                    </div>

                </div>
                <div class="col-auto">

                    <div class="icheck-cyan" v-for="id in [ name+ '-buyer' ]">
                        <input type="radio" :id="id" :name="name" data-parsley-checkmin="1"
                            :disabled="isDisabled" :value="2" v-model="article62Contract" />
                        <label :for="id" class="fs-12 fw-n noselect w-100">
                            <span :class="{ 'bg-warning p-1': 2 == defaultRetribution, 'text-black-50': isCompleted }">買主負担頼</span>
                        </label>
                    </div>

                </div>
            </div>
            <!-- Retribution - End -->

            <!-- Not required label - Start -->
            <div v-else class="fs-14 my-2" :class="isCompleted ? 'text-grey': 'text-danger'">この項目は　区画整理地内：仮換地　の場合以外は入力の必要ありません。</div>
            <!-- Not required label - End -->

        </div>
    </div>
</script>
