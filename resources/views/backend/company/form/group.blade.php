
<transition name="paste-in">

    <div v-if="item.kind_in_house">
        <hr class="my-4" />

        <!-- Company information - Start -->
        <div class="form-section">
            <h4 class="mb-4">@lang('label.$company.form.header.group')</h4>

            <!-- Company in-house group abbreviation name - Start -->
            <div class="row form-group py-2 border-0">
                <div class="col-md-3 col-header">
                    <strong class="field-title fs-15">@lang('label.$company.form.label.name_abbr')</strong>
                </div>
                <div class="col-md-9 col-content">
                    <input type="text" id="company-group-abbr" name="company-group-abbr" class="form-control" v-model.trim="item.kind_in_house_abbreviation" 
                        :disabled="status.loading" data-parsley-maxlength="128" data-parsley-trigger="change focusout" data-parsley-no-focus />
                </div>
            </div>
            <!-- Company in-house group abbreviation name - Start -->

        </div>
        <!-- Company information - Start -->


        <hr class="my-4" />


        <!-- Bank information - Start -->
        <div class="form-section">
            <h4 class="mb-4">@lang('label.$company.form.header.account')</h4>

            <!-- Company bank accounts - Start -->
            <div class="row form-group py-2 border-0">
                <div class="col-12 col-content">

                    <draggable v-model="item.accounts" group="accounts" class="sortable bank-accounts" handle=".drag-handle"
                        animation="300" easing="cubic-bezier(0.165, 0.84, 0.44, 1)" :disabled="status.loading">

                        <!-- Bank account - Start -->
                        <div v-for="( account, index ) in item.accounts" class="sortable-item mx-n2 px-2">
                            <div class="border-top">
                                <div class="row mx-n2 py-3 py-md-2">
                                    <div class="col-md-1 d-flex align-items-center drag-handle draggable">
                                        <span>#@{{ index +1 }}</span>
                                    </div>
                                    <div class="col-md-9 col-lg-10">
                                        <div class="row">
                                            <div class="col-md-2 col-header d-flex align-items-center drag-handle draggable">
                                                <strong class="field-title fs-15">@lang('label.$company.form.label.account.bank')</strong>
                                            </div>
                                            <div class="col-md-10">
                                                <select class="form-control" :name="'bank-account-' +index+ '-bank'" v-model.number="account.bank_id" :disabled="status.loading"
                                                    :required="isAccountBankRequired( account )" @change="applyAccountCompany( $event, account )">
                                                    <option value="" data-company=""></option>
                                                    @foreach( $banks as $bank )
                                                        <option value="{{ $bank->id }}" data-company="{{ $bank->company->id }}">{{ "{$bank->company->name} - {$bank->name_branch}" }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row mt-2">
                                            <div class="col-md-2 col-header d-flex align-items-center drag-handle draggable">
                                                <strong class="field-title fs-15">@lang('label.$company.form.label.account.type')</strong>
                                            </div>
                                            <div class="col-md-4">
                                                <select class="form-control" :name="'bank-account-' +index+ '-type'" v-model.number="account.account_kind" :disabled="status.loading">
                                                    <option value="0"></option>
                                                    <option value="1">@lang('label.$company.form.account.express')</option>
                                                    <option value="2">@lang('label.$company.form.account.regular')</option>
                                                </select>
                                            </div>
                                            <div class="col-md-2 col-header d-flex align-items-center justify-content-start justify-content-md-center mt-2 mt-md-0 drag-handle draggable">
                                                <strong class="field-title fs-15">@lang('label.$company.form.label.account.number')</strong>
                                            </div>
                                            <div class="col-md-4">
                                                <input type="text" :name="'bank-account-' +index+ '-number'" class="form-control" v-model.trim="account.account_number" :disabled="status.loading"
                                                data-parsley-maxlength="64" data-parsley-trigger="change focusout" data-parsley-no-focus />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2 col-lg-1 drag-handle draggable">
                                        <div class="row mx-n1 mt-3 mt-md-0">
                                            <div class="px-1 col-12 mb-2" v-if="!index">

                                                <!-- Add button - Start -->
                                                <button type="button" class="btn btn-block btn-outline-info btn-sm" @click="addBankAccount" :disabled="status.loading">
                                                    <i class="fas fa-plus-circle fs-13"></i>
                                                    <span>@lang('label.$company.form.button.add')</span>
                                                </button>
                                                <!-- Add button - End -->

                                            </div>
                                            <div class="px-1 col-12">

                                                <!-- Delete button - Start -->
                                                <button type="button" class="btn btn-block btn-outline-danger btn-sm" @click="removeBankAccount( index )" :disabled="status.loading">
                                                    <i class="fas fa-minus-circle fs-13"></i>
                                                    <span>@lang('label.$company.form.button.delete')</span>
                                                </button>
                                                <!-- Delete button - End -->

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Bank account - End -->

                    </draggable>

                </div>
            </div>
            <!-- Company bank accounts - Start -->

        </div>
        <!-- Bank information - End -->


        <hr class="my-4" />


        <!-- Bank borrower - Start -->
        <div class="form-section">
            <h4 class="mb-4">@lang('label.$company.form.header.borrower')</h4>

            <!-- Bank borrowers - Start -->
            <div class="row form-group py-2 border-0">
                <div class="col-12 col-content">

                    <!-- Sortable - Start -->
                    <draggable v-model="item.borrowers" group="borrowers" class="sortable borrowers" handle=".drag-handle"
                        animation="300" easing="cubic-bezier(0.165, 0.84, 0.44, 1)" :disabled="status.loading">

                        <!-- Bank borrower - Start -->
                        <div v-for="( borrower, index ) in item.borrowers" class="sortable-item mx-n2 px-2">
                            <div class="border-top">
                                <div class="row mx-n2 py-3 py-md-2">
                                    <div class="col-md-1 d-flex align-items-center drag-handle draggable">
                                        <span>#@{{ index +1 }}</span>
                                    </div>
                                    <div class="col-md-9 col-lg-10">
                                        <div class="row">
                                            <div class="col-md-2 col-header d-flex align-items-center drag-handle draggable">
                                                <strong class="field-title fs-15">@lang('label.$company.form.label.borrower.bank')</strong>
                                            </div>
                                            <div class="col-md-10">
                                                <select class="form-control" :name="'bank-borrower-' +index+ '-bank'" v-model.number="borrower.bank_id" :disabled="status.loading"
                                                    :required="isBorrowerBankRequired( borrower )" @change="applyBorrowerCompany( $event, borrower )">
                                                    <option value="" data-company=""></option>
                                                    @foreach( $banks as $bank )
                                                        <option value="{{ $bank->id }}" data-company="{{ $bank->company->id }}">{{ "{$bank->company->name} - {$bank->name_branch}" }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row mt-2">
                                            <div class="col-md-2 col-header d-flex align-items-center drag-handle draggable">
                                                <strong class="field-title fs-15">@lang('label.$company.form.label.borrower.loan')</strong>
                                            </div>
                                            <div class="col-md-10">
                                                <div class="input-group">
                                                    <template>
                                                        <currency-input v-model="borrower.loan_limit" class="form-control" :name="'bank-borrower-' +index+ '-loan'" 
                                                            :disabled="status.loading" :currency="null" :precision="0" :allow-negative="config.currency.negative" 
                                                            data-parsley-no-focus data-parsley-decimal-maxlength="[12,0]" data-parsley-trigger="change focusout" />
                                                    </template>
                                                    <div class="input-group-append">
                                                        <span class="input-group-text">円</span>
                                                    </div>
                                                </div>
                                                <div class="form-result"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2 col-lg-1 drag-handle draggable">
                                        <div class="row mx-n1 mt-3 mt-md-0">
                                            <div class="px-1 col-12 mb-2" v-if="!index">

                                                <!-- Add button - Start -->
                                                <button type="button" class="btn btn-block btn-outline-info btn-sm" @click="addBorrower" :disabled="status.loading">
                                                    <i class="fas fa-plus-circle fs-13"></i>
                                                    <span>@lang('label.$company.form.button.add')</span>
                                                </button>
                                                <!-- Add button - End -->

                                            </div>
                                            <div class="px-1 col-12">

                                                <!-- Delete button - Start -->
                                                <button type="button" class="btn btn-block btn-outline-danger btn-sm" @click="removeBorrower( index )" :disabled="status.loading">
                                                    <i class="fas fa-minus-circle fs-13"></i>
                                                    <span>@lang('label.$company.form.button.delete')</span>
                                                </button>
                                                <!-- Delete button - End -->

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Bank borrower - End -->

                    </draggable>
                    <!-- Sortable - End -->

                </div>
            </div>
            <!-- Bank borrower - Start -->

        </div>
        <!-- Bank borrower - End -->
    </div>
</transition>
