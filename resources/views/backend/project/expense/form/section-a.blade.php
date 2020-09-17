<div class="expense-caption">
    <strong>A. 物件原価</strong>
</div>
<ul class="px-4">
    <li>物件原価は、<a href="{{ route('project.sheet', $project->id ) }}" target="_blank">PJシート</a> で予算を、
        <a href="{{ route('project.purchase.contract', $project->id ) }}" target="_blank">仕入契約</a> の売買価格で決定総額を、手付金・引渡金で支払額をそれぞれ入力できます。
    </li>
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
            <template v-for="data in sections.a.data">
                <tr v-for="(row, index) in data">
                    <td v-if="index == 0" :rowspan="data.length" class="align-text-top">
                        物件原価
                    </td>
                    <td v-if="index == 0" :rowspan="data.length" class="align-text-top">
                        <!-- A161-1 expense_budget -->
                        <span>1.</span>
                        <input :value="sections.a.procurement.price | numeralFormat" class="form-control form-control-1btn" type="text" readonly="readonly">
                    </td>
                    <td v-if="index == 0" :rowspan="data.length" class="align-text-top">
                        <!-- A161-2 expense_decided -->
                        <input :value="row.decided | numeralFormat" class="form-control form-control-sm" type="text" readonly="readonly">
                    </td>
                    <td>
                        <!-- A161-3 expense_payee -->
                        <input :value="row.payee" class="form-control form-control-w-xl" type="text" readonly="readonly">
                    </td>
                    <td>
                        <!-- A161-4 expense_note -->
                        <input :value="row.note" class="form-control form-control-w-xl" type="text" readonly="readonly">
                    </td>
                    <td>
                        <!-- A161-5 expense_paid -->
                        <input :value="row.paid | numeralFormat" class="form-control form-control-sm" type="text" readonly="readonly">
                    </td>
                    <td>
                        <!-- A161-6 expense_date -->
                        <input :value="row.date" class="form-control form-control-sm" type="text" readonly="readonly">
                    </td>
                    <td>
                        <!-- A161-7 expense_bank -->
                        <select v-model="row.bank" class="form-control form-control-w-xl" disabled="disabled">
                            <option value="0"></option>
                            <option v-for="bank in master.bank" :value="bank.id">
                                @{{ bank.name }}
                            </option>
                        </select>
                    </td>
                    <td>
                        <!-- A161-8 expense_tax -->
                        <div class="form-check icheck-cyan icheck-sm d-inline">
                            <input class="form-check-input" name="check" name="" type="checkbox" id="" value="1" checked="checked" disabled="disabled">
                            <label class="form-check-label" for="">非</label>
                        </div>
                    </td>
                    <td :class="{'bg-orange-expense': (row.status == 2), 'bg-pink-expense': (row.status == 1 && row.paid || row.status == 1 && row.decided)}">
                        <div class="form-check icheck-cyan icheck-sm d-inline">
                            <input v-model="row.status" class="form-check-input" :name="'status_section_a_'+index" type="radio"
                                    :id="'status_section_a_'+index" value="1" disabled="disabled">
                            <label class="form-check-label" :for="'status_section_a_'+index">予</label>
                        </div>
                        <div class="form-check icheck-cyan icheck-sm d-inline">
                            <input v-model="row.status" class="form-check-input" :name="'status_section_a_'+index" type="radio"
                                    :id="'status_section_a_'+index" value="2" disabled="disabled">
                            <label class="form-check-label" :for="'status_section_a_'+index">確</label>
                        </div>
                        <div class="form-check icheck-cyan icheck-sm d-inline">
                            <input v-model="row.status" class="form-check-input" :name="'status_section_a_'+index" type="radio"
                                    :id="'status_section_a_'+index" value="3" disabled="disabled">
                            <label class="form-check-label" :for="'status_section_a_'+index">済</label>
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
                    <input :value="total_a.budget | numeralFormat" class="form-control form-control-sm" type="text" readonly="readonly">
                </td>
                <td>
                    <!-- total_decided -->
                    <input :value="total_a.decided | numeralFormat" class="form-control form-control-sm" type="text" readonly="readonly">
                </td>
                <td>
                    <!-- total_paid -->
                    <input :value="total_a.paid | numeralFormat" class="form-control form-control-sm" type="text" readonly="readonly">
                </td>
                <td>
                    <!-- total_balance -->
                    <input :value="total_a.balance | numeralFormat" :class="{'bg-orange-expense': (total_a.balance != 0)}" class="form-control form-control-sm" type="text" readonly="readonly">
                </td>
            </tr>
            <tr>
                <td>消費税小計</td>
                <td>
                    <!-- total_budget_tax -->
                    <input :value="total_a.budget_tax | numeralFormat" class="form-control form-control-sm" type="text" readonly="readonly">
                </td>
                <td>
                    <!-- total_decided_tax -->
                    <input :value="total_a.decided_tax | numeralFormat" class="form-control form-control-sm" type="text" readonly="readonly">
                </td>
                <td>
                    <!-- total_paid_tax -->
                    <input :value="total_a.paid_tax | numeralFormat" class="form-control form-control-sm" type="text" readonly="readonly">
                </td>
                <td>
                    <!-- total_balance_tax -->
                    <input :value="total_a.balance_tax | numeralFormat" :class="{'bg-orange-expense': (total_a.balance_tax != 0)}" class="form-control form-control-sm" type="text" readonly="readonly">
                </td>
            </tr>
        </tbody>
    </table>
    <!-- End - Tax Calculation Table -->

</div>