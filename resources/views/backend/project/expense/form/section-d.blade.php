<div class="expense-caption">
    <strong>D. 融資関連費用</strong>
</div>
<div class="form-group">
    <table class="table table-bordered table-small-x table-expense-list mt-0">
        <thead>
            <tr>
                <th class="bg-gray-expense expense_name"></th>
                <th class="bg-gray-expense expense_budget">予算</th>
                <th class="bg-gray-expense expense_decided">決定総額</th>
                <th class="bg-gray-expense expense_period">支払時期</th>
                <th class="bg-gray-expense expense_payee">支払先</th>
                <th class="bg-gray-expense expense_note">備考</th>
                <th class="bg-gray-expense expense_paid">支払額</th>
                <th class="bg-gray-expense expense_date">日付</th>
                <th class="bg-gray-expense expense_bank">出金口座</th>
                <th class="bg-gray-expense expense_tax">非課税</th>
                <th class="bg-gray-expense expense_status">状況</th>
            </tr>
        </thead>
        <tbody>
        <template v-for="data in sections.d.data">
            <tr v-for="(row, index) in data">
                <td v-if="index == 0" :rowspan="data.length" class="align-text-top">
                    <div v-if="!row.other" class="d-flex align-items-center">
                        <span class="mr-1">@{{ row.category_index }}. @{{ row.display_name }}</span>
                    </div>
                    <div v-else class="d-flex align-items-center">
                        <span class="mr-1">@{{ row.category_index }}.</span>
                        <input v-model="row.display_name" class="form-control w-100" type="text" readonly="readonly">
                    </div>
                </td>
                <td>
                    <span>@{{ index+1 }}.</span>
                    <input :value="row.budget | numeralFormat" class="form-control form-control-w-lg" type="text" readonly="readonly">
                </td>
                <td>
                    <input :value="row.decided | numeralFormat" class="form-control form-control-w-lg" type="text" readonly="readonly">
                </td>
                <td>
                    <input :value="row.payperiod" class="form-control form-control-w-sm" type="text" readonly="readonly">
                </td>
                <td>
                    <select v-model="row.payee" class="form-control form-control-w-xl" disabled="disabled">
                        <option v-for="payee in master.payee" :value="payee.id">
                            @{{ payee.name }}
                        </option>
                    </select>
                </td>
                <td>
                    <input :value="row.note" class="form-control form-control-w-xl" type="text" readonly="readonly">
                </td>
                <td>
                    <input :value="row.paid | numeralFormat" class="form-control form-control-w-lg" type="text" readonly="readonly">
                </td>
                <td>
                    <input :value="row.date" class="form-control form-control-w-lg" type="text" readonly="readonly">
                </td>
                <td>
                    <select v-model="row.bank" class="form-control form-control-w-xl" disabled="disabled">
                        <option value="0"></option>
                        <option v-for="company_bank in master.all_bank" :value="company_bank.id">
                            @{{ company_bank.bank.company.name_abbreviation }} @{{ company_bank.bank.name_branch_abbreviation }} @{{ company_bank.company_bank_account }}
                        </option>
                    </select>
                </td>
                <td>
                    <div class="form-check icheck-cyan icheck-sm d-inline">
                        <input class="form-check-input" name="check" type="checkbox" id="" value="1"
                            :checked="row.taxfree" disabled="disabled">
                        <label class="form-check-label" for="">非</label>
                    </div>
                </td>
                <td :class="{'bg-orange-expense': (row.status == 2), 'bg-pink-expense': (row.status == 1 && row.paid || row.status == 1 && row.decided)}">
                    <div class="form-check icheck-cyan icheck-sm d-inline">
                        <input v-model="row.status" class="form-check-input" :name="'status_'+row.display_name+'_'+row.category_index+'_'+index" type="radio"
                                :id="'status_'+row.display_name+'_'+row.category_index+'_'+index" value="1" disabled="disabled">
                        <label class="form-check-label" :for="'status_'+row.display_name+'_'+row.category_index+'_'+index">無</label>
                    </div>
                    <div class="form-check icheck-cyan icheck-sm d-inline">
                        <input v-model="row.status" class="form-check-input" :name="'status_'+row.display_name+'_'+row.category_index+'_'+index" type="radio"
                                :id="'status_'+row.display_name+'_'+row.category_index+'_'+index" value="2" disabled="disabled">
                        <label class="form-check-label" :for="'status_'+row.display_name+'_'+row.category_index+'_'+index">保</label>
                    </div>
                    <div class="form-check icheck-cyan icheck-sm d-inline">
                        <input v-model="row.status" class="form-check-input" :name="'status_'+row.display_name+'_'+row.category_index+'_'+index" type="radio"
                                :id="'status_'+row.display_name+'_'+row.category_index+'_'+index" value="3" disabled="disabled">
                        <label class="form-check-label" :for="'status_'+row.display_name+'_'+row.category_index+'_'+index">済</label>
                    </div>
                </td>
            </tr>
        </template>
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
                    <input :value="total_d.budget | numeralFormat" class="form-control form-control-sm" type="text" readonly="readonly">
                </td>
                <td>
                    <!-- total_decided -->
                    <input :value="total_d.decided | numeralFormat" class="form-control form-control-sm" type="text" readonly="readonly">
                </td>
                <td>
                    <!-- total_paid -->
                    <input :value="total_d.paid | numeralFormat" class="form-control form-control-sm" type="text" readonly="readonly">
                </td>
                <td>
                    <!-- total_balance -->
                    <input :value="total_d.balance | numeralFormat" class="form-control form-control-sm" :class="{'bg-orange-expense': (total_d.balance != 0)}" type="text" readonly="readonly">
                </td>
            </tr>
            <tr>
                <td>消費税小計</td>
                <td>
                    <!-- total_budget_tax -->
                    <input :value="total_d.budget_tax | numeralFormat" class="form-control form-control-sm" type="text" readonly="readonly">
                </td>
                <td>
                    <!-- total_decided_tax -->
                    <input :value="total_d.decided_tax | numeralFormat" class="form-control form-control-sm" type="text" readonly="readonly">
                </td>
                <td>
                    <!-- total_paid_tax -->
                    <input :value="total_d.paid_tax | numeralFormat" class="form-control form-control-sm" type="text" readonly="readonly">
                </td>
                <td>
                    <!-- total_balance_tax -->
                    <input :value="total_d.balance_tax | numeralFormat" class="form-control form-control-sm" :class="{'bg-orange-expense': (total_d.balance_tax != 0)}" type="text" readonly="readonly">
                </td>
            </tr>
        </tbody>
    </table>
    <!-- End - Tax Calculation Table -->
</div>