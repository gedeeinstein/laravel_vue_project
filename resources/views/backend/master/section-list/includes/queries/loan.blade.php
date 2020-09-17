<div class="row mx-n1">
    <div class="px-1 col-sm-112px">

        <div class="icheck-cyan icheck-sm" v-for="name in [ group + 'repayment' ]">
            <input type="checkbox" :id="name" :name="name" data-parsley-checkmin="1"
                :disabled="isDisabled" :true-value="true" :false-value="false" v-model="check.loan"/>
            <label :for="name" class="fs-12 fw-n noselect w-100">
                <span>返済期日</span>
            </label>
        </div>

    </div>
    <div class="px-1 col-sm d-flex align-items-center" ref="loan" @click="enableLoan">
        <div class="row mx-n1 flex-grow-1">
            <div class="px-1 col-7" v-for="name in [ group + 'repayment-year' ]">

                <!-- Loan period year - Start -->
                <select :name="name" :id="name" class="form-control fs-14" :disabled="isDisabled || !check.loan"
                    v-model="filter.loan_year" @change="submitFilter">
                    <option v-for="option in preset.years" :value="option">@{{ option }}年</option>
                </select>
                <!-- Loan period year - End -->

            </div>
            <div class="px-1 col-5" v-for="name in [ group + 'repayment-month' ]">

                <!-- Loan period month - Start -->
                <select :name="name" :id="name" class="form-control fs-14" :disabled="isDisabled || !check.loan || !filter.loan_year"
                    v-model="filter.loan_month" @change="submitFilter">
                    <option :value="null"></option>
                    <option v-for="option in 12" :value="option">@{{ option }}月</option>
                </select>
                <!-- Loan period month - End -->

            </div>
        </div>
    </div>
</div>