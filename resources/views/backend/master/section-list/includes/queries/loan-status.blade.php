<div class="row mx-n2">
    <div class="px-2 col-auto d-flex align-items-center">
        <span>融資状況</span>
    </div>
    <div class="px-2 col-auto">
        <div class="row mx-n1">
            <div class="px-1 col-auto">

                <!-- Loan undecided - Start -->
                <div class="icheck-cyan icheck-sm" v-for="name in [ group+ 'loan-status-1' ]">
                    <input type="checkbox" :id="name" :name="name" data-parsley-checkmin="1"
                        :disabled="isDisabled" :true-value="true" :false-value="false" v-model="filter.loan_undecided" />
                    <label :for="name" class="fs-12 fw-n noselect w-100">
                        <span>未</span>
                    </label>
                </div>
                <!-- Loan undecided - End -->

            </div>
            <div class="px-1 col-auto">

                <!-- Loan expected - Start -->
                <div class="icheck-cyan icheck-sm" v-for="name in [ group+ 'loan-status-2' ]">
                    <input type="checkbox" :id="name" :name="name" data-parsley-checkmin="1"
                        :disabled="isDisabled" :true-value="true" :false-value="false" v-model="filter.loan_expected" />
                    <label :for="name" class="fs-12 fw-n noselect w-100">
                        <span>予</span>
                    </label>
                </div>
                <!-- Loan expected - Start -->

            </div>
            <div class="px-1 col-auto">

                <!-- Loan confirmed - Start -->
                <div class="icheck-cyan icheck-sm" v-for="name in [ group+ 'loan-status-3' ]">
                    <input type="checkbox" :id="name" :name="name" data-parsley-checkmin="1"
                        :disabled="isDisabled" :true-value="true" :false-value="false" v-model="filter.loan_confirmed" />
                    <label :for="name" class="fs-12 fw-n noselect w-100">
                        <span>確</span>
                    </label>
                </div>
                <!-- Loan confirmed - End -->

            </div>
            <div class="px-1 col-auto">

                <!-- Loan applied - Start -->
                <div class="icheck-cyan icheck-sm" v-for="name in [ group+ 'loan-status-4' ]">
                    <input type="checkbox" :id="name" :name="name" data-parsley-checkmin="1"
                        :disabled="isDisabled" :true-value="true" :false-value="false" v-model="filter.loan_applied" />
                    <label :for="name" class="fs-12 fw-n noselect w-100">
                        <span>済</span>
                    </label>
                </div>
                <!-- Loan applied - End -->

            </div>
            <div class="px-1 col-auto">

                <!-- Loan completed - Start -->
                <div class="icheck-cyan icheck-sm" v-for="name in [ group+ 'loan-status-5' ]">
                    <input type="checkbox" :id="name" :name="name" data-parsley-checkmin="1"
                        :disabled="isDisabled" :true-value="true" :false-value="false" v-model="filter.loan_completed" />
                    <label :for="name" class="fs-12 fw-n noselect w-100">
                        <span>完</span>
                    </label>
                </div>
                <!-- Loan completed - End -->

            </div>
        </div>
    </div>
</div>
