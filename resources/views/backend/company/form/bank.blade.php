
<transition name="paste-in">

    <div v-if="item.kind_bank">
        <hr class="my-4"/>

        <div class="form-section">
            <h4 class="mb-4">@lang('label.$company.form.header.bank')</h4>

            <!-- Company abbreviation name - Start -->
            <div class="row form-group py-2 border-0">
                <div class="col-md-3 col-header">
                    <strong class="field-title fs-15">@lang('label.$company.form.label.name_abbr')</strong>
                </div>
                <div class="col-md-9 col-content">
                    <input type="text" id="company-name-abbr" name="company-name-abbr" class="form-control" v-model.trim="item.name_abbreviation"
                        :disabled="status.loading" data-parsley-maxlength="128" data-parsley-trigger="change focusout" data-parsley-no-focus />
                </div>
            </div>
            <!-- Company abbreviation name - Start -->

            <!-- Store branches - Start -->
            <div class="row form-group py-2 border-0 align-items-start">

                <div class="col-md-3 col-header">
                    <span class="bg-danger label-required">@lang('label.required')</span>
                    <strong class="field-title fs-15">@lang('label.$company.form.label.store')</strong>
                </div>

                <div class="col-md-9 col-content py-2 py-md-0 my-md-n1">

                    <draggable v-model="item.banks" group="banks" class="sortable bank-branches" handle=".drag-handle"
                        animation="300" easing="cubic-bezier(0.165, 0.84, 0.44, 1)" :disabled="status.loading">

                        <!-- Store branches - Start -->
                        <div v-for="( bank, index ) in item.banks" class="sortable-item mx-n2 px-2">
                            <div class="border-top">
                                <div class="row mx-n2 py-3 py-md-2">
                                    <div class="px-2 col-md-8">
                                        <div class="row mx-n1">
                                            <div class="px-1 col-md mb-2 mb-md-0">
                                                <input type="text" :name="'branch-' +index+ '-name'" class="form-control" v-model.trim="bank.name_branch"
                                                    :required="isBankBranchRequired( index, 'name' )" data-parsley-trigger="change focusout" data-parsley-no-focus
                                                    placeholder="@lang('label.$company.form.label.store')" :disabled="status.loading" data-parsley-maxlength="128" />
                                            </div>
                                            <div class="px-1 col-auto d-flex align-items-center drag-handle draggable">
                                                <span>@lang('label.$company.form.label.abbr')</span>
                                            </div>
                                            <div class="px-1 col col-md">
                                                <input type="text" :name="'branch-' +index+ '-abbr'" class="form-control" v-model.trim="bank.name_branch_abbreviation"
                                                    :required="isBankBranchRequired( index, 'abbr' )" data-parsley-trigger="change focusout" data-parsley-no-focus
                                                    placeholder="@lang('label.$company.form.label.abbr')" :disabled="status.loading" data-parsley-maxlength="128" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="px-2 col-md-4 mt-2 mt-md-0 drag-handle draggable">
                                        <div class="row mx-n1 h-100">
                                            <div class="px-1 col-md-6 d-flex align-items-center">
                                                <button type="button" class="btn btn-block btn-outline-danger btn-sm" @click="removeBankBranch( index )" :disabled="status.loading">
                                                    <i class="fas fa-minus-circle fs-13"></i>
                                                    <span>@lang('label.$company.form.button.delete')</span>
                                                </button>
                                            </div>
                                            <div v-if="!index" class="px-1 col-md-6 d-flex align-items-center mt-2 mt-md-0">
                                                <button type="button" class="btn btn-block btn-outline-info btn-sm" @click="addBankBranch" :disabled="status.loading">
                                                    <i class="fas fa-plus-circle fs-13"></i>
                                                    <span>@lang('label.$company.form.button.add')</span>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Store branches - End -->

                    </draggable>

                </div>
            </div>
            <!-- Store branches - End -->

        </div>
    </div>
</transition>
