<div class="expense-caption">
    <strong>I. 販売時仲介・フィー・その他（支出）</strong>
</div>
<ul class="px-4">
    <li>販売時仲介・フィー・その他は、<a href="">販売の部</a> で入力できます。</li>
</ul>
<div class="form-group">
    <table class="table table-bordered table-small-x table-expense-list mt-0">
        <thead>
            <tr>
                <th class="bg-gray-expense expense_name"></th>
                <th class="bg-gray-expense expense_budget">予算</th>
                <th class="bg-gray-expense expense_decided">決定総額</th>
                <th class="bg-gray-expense expense_payee">支払先</th>
                <th class="bg-gray-expense expense_note">備考</th>
                <th class="bg-gray-expense expense_paid">支払額</th>
                <th class="bg-gray-expense expense_date">日付</th>
                <th class="bg-gray-expense expense_bank">出金口座</th>
                <th class="bg-gray-expense expense_tax">非課税</th>
                <th class="bg-gray-expense expense_status">状況</th>
                <th class="bg-gray-expense expense_ctl"></th>
            </tr>
        </thead>
        <tbody>
            <!-- Start - Sale Mediation Data -->
            <tr v-for="(row, index) in sections.i.mediations">
                <td v-if="index == 0" :rowspan="sections.i.mediations.length" class="align-text-top">販売時仲介手数料</td>
                <!-- expense_budget -->
                <td>@{{ index+1 }}.
                    <input :value="row.budget | numeralFormat" class="form-control form-control-1btn" type="text" readonly="readonly">
                </td>
                <!-- expense_decided -->
                <td>
                    <input :value="row.decided | numeralFormat" class="form-control form-control-sm" type="text" readonly="readonly">
                </td>
                <!-- expense_payee -->
                <td>
                    <input :value="row.payee" class="form-control form-control-w-xl" type="text" readonly="readonly">
                </td>
                <!-- expense_note -->
                <td v-if="index == 0" :rowspan="sections.i.mediations.length" class="align-text-top">
                    <input v-model="expense.mediation_sell_note" class="form-control form-control-w-xl" type="text">
                </td>
                <!-- expense_paid -->
                <td>
                    <input :value="row.paid | numeralFormat" class="form-control form-control-w-lg" type="text" readonly="readonly">
                </td>
                <!-- expense_date -->
                <td>
                    <input :value="row.date" class="form-control form-control-w-lg" type="text" readonly="readonly">
                </td>
                <!-- expense_bank -->
                <td>
                    <select v-model="row.bank" class="form-control form-control-w-xl" disabled="disabled">
                        <option value="0"></option>
                        <option v-for="company_bank in master.all_bank" :value="company_bank.id">
                            @{{ company_bank.bank.company.name_abbreviation }} @{{ company_bank.bank.name_branch_abbreviation }} @{{ company_bank.company_bank_account }}
                        </option>
                    </select>
                </td>
                <!-- expense_tax -->
                <td>
                    <div class="form-check icheck-cyan icheck-sm d-inline">
                        <input class="form-check-input" name="check" type="checkbox" id="" value="1" disabled="disabled">
                        <label class="form-check-label" for="">非</label>
                    </div>
                </td>
                <!-- expense_status -->
                <td :class="{'bg-pink-expense': (row.status == 1)}">
                    <div class="form-check icheck-cyan icheck-sm d-inline">
                        <input v-model="row.status" class="form-check-input" :name="'status_sections_i_mediation_'+index" type="radio"
                                :id="'status_sections_i_mediation_'+index" value="1" disabled="disabled">
                        <label class="form-check-label" :for="'status_sections_i_mediation_'+index">予</label>
                    </div>
                    <div class="form-check icheck-cyan icheck-sm d-inline">
                        <input v-model="row.status" class="form-check-input" :name="'status_sections_i_mediation_'+index" type="radio"
                                :id="'status_sections_i_mediation_'+index" value="2" disabled="disabled">
                        <label class="form-check-label" :for="'status_sections_i_mediation_'+index">済</label>
                    </div>
                </td>
                <td></td>
            </tr>
            <!-- End - Sale Mediation Data -->

            <!-- Start - Sale Fee Data -->
            <tr v-for="(row, index) in sections.i.fees">
                <td v-if="index == 0" :rowspan="sections.i.fees.length" class="align-text-top">販売時フィー・その他</td>
                <!-- expense_budget -->
                <td>@{{ index+1 }}.
                    <input :value="row.budget | numeralFormat" class="form-control form-control-1btn" type="text" readonly="readonly">
                </td>
                <!-- expense_decided -->
                <td>
                    <input :value="row.decided | numeralFormat" class="form-control form-control-sm" type="text" readonly="readonly">
                </td>
                <!-- expense_payee -->
                <td>
                    <input :value="row.payee" class="form-control form-control-w-xl" type="text" readonly="readonly">
                </td>
                <!-- expense_note -->
                <td v-if="index == 0" :rowspan="sections.i.fees.length" class="align-text-top">
                    <input v-model="expense.fee_note" class="form-control form-control-w-xl" type="text">
                </td>
                <!-- expense_paid -->
                <td>
                    <input :value="row.paid | numeralFormat" class="form-control form-control-w-lg" type="text" readonly="readonly">
                </td>
                <!-- expense_date -->
                <td>
                    <input :value="row.date" class="form-control form-control-w-lg" type="text" readonly="readonly">
                </td>
                <!-- expense_bank -->
                <td>
                    <select v-model="row.bank" class="form-control form-control-w-xl" disabled="disabled">
                        <option value="0"></option>
                        <option v-for="company_bank in master.all_bank" :value="company_bank.id">
                            @{{ company_bank.bank.company.name_abbreviation }} @{{ company_bank.bank.name_branch_abbreviation }} @{{ company_bank.company_bank_account }}
                        </option>
                    </select>
                </td>
                <!-- expense_tax -->
                <td>
                    <div class="form-check icheck-cyan icheck-sm d-inline">
                        <input class="form-check-input" name="check" type="checkbox" id="" value="1" disabled="disabled">
                        <label class="form-check-label" for="">非</label>
                    </div>
                </td>
                <!-- expense_status -->
                <td :class="{'bg-orange-expense': (row.status == 2), 'bg-pink-expense': (row.status == 1 && row.paid || row.status == 1 && row.decided)}">
                    <div class="form-check icheck-cyan icheck-sm d-inline">
                        <input v-model="row.status" class="form-check-input" :name="'status_section_i_fee_'+index" type="radio"
                                :id="'status_section_i_fee_'+index" value="1" disabled="disabled">
                        <label class="form-check-label" :for="'status_section_i_fee_'+index">予</label>
                    </div>
                    <div class="form-check icheck-cyan icheck-sm d-inline">
                        <input v-model="row.status" class="form-check-input" :name="'status_section_i_fee_'+index" type="radio"
                                :id="'status_section_i_fee_'+index" value="2" disabled="disabled">
                        <label class="form-check-label" :for="'status_section_i_fee_'+index">確</label>
                    </div>
                    <div class="form-check icheck-cyan icheck-sm d-inline">
                        <input v-model="row.status" class="form-check-input" :name="'status_section_i_fee_'+index" type="radio"
                                :id="'status_section_i_fee_'+index" value="3" disabled="disabled">
                        <label class="form-check-label" :for="'status_section_i_fee_'+index">済</label>
                    </div>
                </td>
                <td></td>
            </tr>
            <!-- End - Sale Fee Data -->

        </tbody>
    </table>

    <!-- Start - Tax Calculation Table -->
    <table class="table table-bordered table-small-x mt-0 w-auto">
        <thead>
            <tr>
                <th class="bg-gray-expense px-5"></th>
                <th class="bg-gray-expense expense_budget">予算</th>
                <th class="bg-gray-expense expense_decided">決定総額</th>
                <th class="bg-gray-expense expense_payee">支払額（済）</th>
                <th class="bg-gray-expense expense_payee">残額</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>税込小計</td>
                <td>
                    <!-- total_budget -->
                    <input :value="total_i.budget | numeralFormat" class="form-control form-control-sm" type="text" readonly="readonly">
                </td>
                <td>
                    <!-- total_decided -->
                    <input :value="total_i.decided | numeralFormat" class="form-control form-control-sm" type="text" readonly="readonly">
                </td>
                <td>
                    <!-- total_paid -->
                    <input :value="total_i.paid | numeralFormat" class="form-control form-control-sm" type="text" readonly="readonly">
                </td>
                <td>
                    <!-- total_balance -->
                    <input :value="total_i.balance | numeralFormat" class="form-control form-control-sm" :class="{'bg-orange-expense': (total_i.balance != 0)}" type="text" readonly="readonly">
                </td>
            </tr>
            <tr>
                <td>消費税小計</td>
                <td>
                    <!-- total_budget_tax -->
                    <input :value="total_i.budget_tax | numeralFormat" class="form-control form-control-sm" type="text" readonly="readonly">
                </td>
                <td>
                    <!-- total_decided_tax -->
                    <input :value="total_i.decided_tax | numeralFormat" class="form-control form-control-sm" type="text" readonly="readonly">
                </td>
                <td>
                    <!-- total_paid_tax -->
                    <input :value="total_i.paid_tax | numeralFormat" class="form-control form-control-sm" type="text" readonly="readonly">
                </td>
                <td>
                    <!-- total_balance_tax -->
                    <input :value="total_i.balance_tax | numeralFormat" class="form-control form-control-sm" :class="{'bg-orange-expense': (total_i.balance_tax != 0)}" type="text" readonly="readonly">
                </td>
            </tr>
        </tbody>
    </table>
    <!-- End - Tax Calculation Table -->
</div>