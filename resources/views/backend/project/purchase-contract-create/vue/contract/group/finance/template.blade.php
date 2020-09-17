<script type="text/x-template" id="group-finance">
    <div class="form-group row mb-2 mb-md-3">
        <label :for="name" class="col-md-3 col-form-label">
            <span class="fw-n" :class="{ 'text-grey': isCompleted }">15条 融資利用</span>
        </label>
        <div class="col-md">
            
            <!-- Finance - Start -->
            <div class="row mb-1" v-for="name in [ prefix+ 'option' ]">
                <div class="col-auto d-flex align-items-center">
                                            
                    <div class="icheck-cyan" v-for="id in [ name+ '-yes' ]">
                        <input type="radio" :id="id" :name="name" data-parsley-checkmin="1"
                            :disabled="isDisabled" :value="1" v-model="entry.c_article15_contract" />
                        <label :for="id" class="fs-12 fw-n noselect w-100">
                            <span :class="{ 'text-black-50': isCompleted }">有</span>
                        </label>
                    </div>

                </div>
                <div class="col-auto d-flex align-items-center">
                    
                    <div class="icheck-cyan" v-for="id in [ name+ '-no' ]">
                        <input type="radio" :id="id" :name="name" data-parsley-checkmin="1"
                            :disabled="isDisabled" :value="2" v-model="entry.c_article15_contract" />
                        <label :for="id" class="fs-12 fw-n noselect w-100">
                            <span :class="{ 'text-black-50': isCompleted }">無</span>
                        </label>
                    </div>

                </div>
            </div>
            <!-- Penalty - End -->

            <!-- Loan 1 - Start -->
            <div class="form-group row mb-2" v-for="group in [ prefix+ 'loan-1' ]">
                <label :for="group+ '-contract'" class="col-md-75px col-form-label">
                    <span class="fw-n" :class="{ 'text-grey': isCompleted }">申込先1</span>
                </label>
                <div class="col col-lg-10 col-xl-9">
                    <div class="row">
                        <div class="col-md mb-2 mb-md-0" v-for="name in [ group+ '-contract' ]">
                            
                            @component("{$component->preset}.text")
                                @slot( 'disabled', 'isFinanceDisabled' )
                                @slot( 'model', 'entry.c_article15_loan_contract_0' )
                            @endcomponent

                        </div>
                        <div class="col-md" v-for="name in [ group+ '-amount' ]">

                            @component("{$component->preset}.text")
                                @slot( 'group', true )
                                @slot( 'append', '円' )
                                @slot( 'disabled', 'isFinanceDisabled' )
                                @slot( 'model', 'entry.c_article15_loan_amount_contract_0' )
                            @endcomponent

                        </div>
                        <div class="col-md-auto">

                            <div class="icheck-cyan" v-for="name in [ group+ '-issue' ]">
                                <input type="checkbox" :id="name" :name="name" data-parsley-checkmin="1"
                                    :disabled="isFinanceDisabled" :true-value="true" :false-value="false" 
                                    v-model="entry.c_article15_loan_issue_contract_0" />
                                <label :for="name" class="fs-12 fw-n noselect w-100">
                                    <span :class="{ 'text-black-50': isCompleted }">あっせん有</span>
                                </label>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <!-- Loan 1 - End -->

            <!-- Loan 2 - Start -->
            <div class="form-group row mb-2" v-for="group in [ prefix+ 'loan-2' ]">
                <label :for="group+ '-contract'" class="col-md-75px col-form-label">
                    <span class="fw-n" :class="{ 'text-grey': isCompleted }">申込先2</span>
                </label>
                <div class="col col-lg-10 col-xl-9">
                    <div class="row">
                        <div class="col-md mb-2 mb-md-0" v-for="name in [ group+ '-contract' ]">
                            
                            @component("{$component->preset}.text")
                                @slot( 'disabled', 'isFinanceDisabled' )
                                @slot( 'model', 'entry.c_article15_loan_contract_1' )
                            @endcomponent

                        </div>
                        <div class="col-md" v-for="name in [ group+ '-amount' ]">

                            @component("{$component->preset}.text")
                                @slot( 'group', true )
                                @slot( 'append', '円' )
                                @slot( 'disabled', 'isFinanceDisabled' )
                                @slot( 'model', 'entry.c_article15_loan_amount_contract_1' )
                            @endcomponent

                        </div>
                        <div class="col-md-auto">

                            <div class="icheck-cyan" v-for="name in [ group+ '-issue' ]">
                                <input type="checkbox" :id="name" :name="name" data-parsley-checkmin="1"
                                    :disabled="isFinanceDisabled" :true-value="true" :false-value="false" 
                                    v-model="entry.c_article15_loan_issue_contract_1" />
                                <label :for="name" class="fs-12 fw-n noselect w-100">
                                    <span :class="{ 'text-black-50': isCompleted }">あっせん有</span>
                                </label>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <!-- Loan 2 - End -->

            <!-- Loan release date - Start -->
            <div class="form-group row mb-2" v-for="name in [ prefix+ 'loan-release-date' ]">
                <label :for="name" class="col-md-auto col-form-label">
                    <span class="fw-n" :class="{ 'text-grey': isCompleted }">融資利用の特約に基づく契約解除期日</span>
                </label>
                <div class="col">
                    <div class="row">
                        <div class="col-md col-lg-8">

                            @component("{$component->preset}.date")
                                @slot( 'block', true )
                                @slot( 'disabled', 'isFinanceDisabled' )
                                @slot( 'model', 'entry.c_article15_loan_release_date_contract')
                            @endcomponent

                        </div>
                    </div>
                </div>
            </div>
            <!-- Loan release date - End -->

        </div>
    </div>
</script>
