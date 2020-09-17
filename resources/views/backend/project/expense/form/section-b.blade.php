<div class="expense-caption">
    <strong>B. 仕入時仲介（支出）</strong>
</div>
<ul class="px-4">
    <li>仕入時仲介手数料は、<a href="{{ route('project.purchase.contract', $project->id ) }}" target="_blank">仕入契約</a> で入力できます。</li>
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
            </tr>
        </thead>
        <tbody>
            <template v-for="data in sections.b.data">
                <tr v-for="(row, index) in data">
                    <td v-if="index == 0" :rowspan="data.length" class="align-text-top">
                        仕入時仲介手数料
                    </td>
                    <td v-if="index == 0" :rowspan="data.length" class="align-text-top">
                        <!-- expense_budget -->
                        <span>1.</span>
                        <input :value="sections.b.procurement.brokerage_fee | numeralFormat" class="form-control form-control-1btn" type="text" readonly="readonly">
                    </td>
                    <td class="align-text-top">
                        <!-- expense_decided -->
                        <input :value="row.decided | numeralFormat" class="form-control form-control-sm" type="text" readonly="readonly">
                    </td>
                    <td>
                        <!-- expense_payee -->
                        <select v-model="row.payee" class="form-control form-control-w-xl" disabled="disabled">
                            <option v-for="estate in master.real_estates" :value="estate.id">
                                @{{ estate.name }}
                            </option>
                        </select>
                    </td>
                    <td>
                        <!-- expense_note -->
                        <input v-model="expense.mediation_note" class="form-control form-control-w-xl" type="text">
                    </td>
                    <td>
                        <!-- expense_paid -->
                        <input :value="row.paid | numeralFormat" class="form-control form-control-sm" type="text" readonly="readonly">
                    </td>
                    <td>
                        <!-- expense_date -->
                        <input :value="row.date" class="form-control form-control-sm" type="text" readonly="readonly">
                    </td>
                    <td>
                        <!-- expense_bank -->
                        <select v-model="row.bank" class="form-control form-control-w-xl" disabled="disabled">
                            <option value="0"></option>
                            <option v-for="company_bank in master.all_bank" :value="company_bank.id">
                                @{{ company_bank.bank.company.name_abbreviation }} @{{ company_bank.bank.name_branch_abbreviation }} @{{ company_bank.company_bank_account }}
                            </option>
                        </select>
                    </td>
                    <td>
                        <!-- expense_tax -->
                        <div class="form-check icheck-cyan icheck-sm d-inline">
                            <input class="form-check-input" name="check" name="" type="checkbox" id="" value="1" disabled="disabled">
                            <label class="form-check-label" for="">非</label>
                        </div>
                    </td>
                    <td :class="{'bg-pink-expense': (row.status == 1)}">
                        <div class="form-check icheck-cyan icheck-sm d-inline">
                            <input v-model="row.status" class="form-check-input" :name="'status_sections_b_'+index" type="radio"
                                    :id="'status_sections_b_'+index" value="1" disabled="disabled">
                            <label class="form-check-label" :for="'status_sections_b_'+index">予</label>
                        </div>
                        <div class="form-check icheck-cyan icheck-sm d-inline">
                            <input v-model="row.status" class="form-check-input" :name="'status_sections_b_'+index" type="radio"
                                    :id="'status_sections_b_'+index" value="2" disabled="disabled">
                            <label class="form-check-label" :for="'status_sections_b_'+index">済</label>
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
                    <input :value="total_b.budget | numeralFormat" class="form-control form-control-sm" type="text" readonly="readonly">
                </td>
                <td>
                    <!-- total_decided -->
                    <input :value="total_b.decided | numeralFormat" class="form-control form-control-sm" type="text" readonly="readonly">
                </td>
                <td>
                    <!-- total_paid -->
                    <input :value="total_b.paid | numeralFormat" class="form-control form-control-sm" type="text" readonly="readonly">
                </td>
                <td>
                    <!-- total_balance -->
                    <input :value="total_b.balance | numeralFormat" :class="{'bg-orange-expense': (total_b.balance != 0)}" class="form-control form-control-sm" type="text" readonly="readonly">
                </td>
            </tr>
            <tr>
                <td>消費税小計</td>
                <td>
                    <!-- total_budget_tax -->
                    <input :value="total_b.budget_tax | numeralFormat" class="form-control form-control-sm" type="text" readonly="readonly">
                </td>
                <td>
                    <!-- total_decided_tax -->
                    <input :value="total_b.decided_tax | numeralFormat" class="form-control form-control-sm" type="text" readonly="readonly">
                </td>
                <td>
                    <!-- total_paid_tax -->
                    <input :value="total_b.paid_tax | numeralFormat" class="form-control form-control-sm" type="text" readonly="readonly">
                </td>
                <td>
                    <!-- total_balance_tax -->
                    <input :value="total_b.balance_tax | numeralFormat" :class="{'bg-orange-expense': (total_b.balance_tax != 0)}" class="form-control form-control-sm" type="text" readonly="readonly">
                </td>
            </tr>
        </tbody>
    </table>
    <!-- End - Tax Calculation Table -->
</div>