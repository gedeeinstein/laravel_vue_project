<div class="row mx-n1">
    <div class="px-1 col-sm-112px">

        <div class="icheck-cyan icheck-sm" v-for="name in [ group + 'purchase-contract' ]">
            <input type="checkbox" :id="name" :name="name" data-parsley-checkmin="1"
                :disabled="isDisabled" :true-value="true" :false-value="false" v-model="check.contract" />
            <label :for="name" class="fs-12 fw-n noselect w-100">
                <span>仕入契約年月</span>
            </label>
        </div>

    </div>
    <div class="px-1 col-sm d-flex align-items-center" ref="contract" @click="enableContract">
        <div class="row mx-n1 flex-grow-1">
            <div class="px-1 col-7" v-for="name in [ group + 'purchase-contract-year' ]">

                <!-- Contract date - Start -->
                <select :name="name" :id="name" class="form-control fs-14" :disabled="isDisabled || !check.contract"
                    v-model="filter.contract_year" @change="submitFilter">
                    <option v-for="option in preset.years" :value="option">@{{ option }}年</option>
                </select>
                <!-- Contract date - End -->

            </div>
            <div class="px-1 col-5" v-for="name in [ group + 'purchase-contract-month' ]">

                <!-- Contract month - Start -->
                <select :name="name" :id="name" class="form-control fs-14" :disabled="isDisabled || !check.contract || !filter.contract_year"
                    v-model="filter.contract_month" @change="submitFilter">
                    <option :value="null"></option>
                    <option v-for="option in 12" :value="option">@{{ option }}月</option>
                </select>
                <!-- Contract month - End -->

            </div>
        </div>
    </div>
</div>