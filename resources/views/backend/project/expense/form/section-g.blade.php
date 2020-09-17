<div class="expense-caption">
    <strong>G. 測量関連費用</strong>
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
                <th class="bg-gray-expense expense_ctl"></th>
            </tr>
        </thead>
        <tbody>
        <!-- Start - Dynamic Row Input -->
        <template v-for="data in sections.g.data">
            <tr v-for="(row, index) in data">
                <td v-if="index == 0" :rowspan="data.length" class="align-text-top">
                    <div v-if="!row.other" class="d-flex align-items-center">
                        <span class="mr-1">@{{ row.screen_index }}. @{{ row.name }}</span>
                    </div>
                    <div v-else class="d-flex align-items-center">
                        <span class="mr-1">@{{ row.screen_index }}.</span>
                        <input v-model="row.name" class="form-control w-100" type="text" placeholder="×××××">
                    </div>
                </td>
                <td>
                    <!-- expense_budget -->
                    @component('backend.project.expense.form.components.input_budget', [
                        "model" => "row.budget"
                    ])
                    @endcomponent
                </td>
                <td>
                    <!-- expense_decided -->
                    @component('backend.project.expense.form.components.input_decided', [
                        "model" => "row.decided"
                    ])
                    @endcomponent
                </td>
                <td>
                    <!-- expense_period -->
                    @component('backend.project.expense.form.components.input_period', [
                        "model" => "row.payperiod"
                    ])
                    @endcomponent
                </td>
                <td>
                    <!-- expense_payee -->
                    @component('backend.project.expense.form.components.input_payee', [
                        "model" => "row.payee"
                    ])
                    @endcomponent
                </td>
                <td>
                    <!-- expense_note -->
                    @component('backend.project.expense.form.components.input_note', [
                        "model" => "row.note"
                    ])
                    @endcomponent
                </td>
                <td>
                    <!-- expense_paid -->
                    @component('backend.project.expense.form.components.input_paid', [
                        "model" => "row.paid"
                    ])
                    @endcomponent
                </td>
                <td>
                    <!-- expense_date -->
                    @component('backend.project.expense.form.components.input_date', [
                        "model" => "row.date"
                    ])
                    @endcomponent
                </td>
                <td>
                    <!-- expense_bank -->
                    @component('backend.project.expense.form.components.input_bank', [
                        "model" => "row.bank"
                    ])
                    @endcomponent
                </td>
                <td>
                    <!-- expense_tax -->
                    @component('backend.project.expense.form.components.input_tax', [
                        "id"    => "'tax_'+row.screen_name+'_'+row.screen_index+'_'+index",
                        "model" => "row.taxfree",
                    ])
                    @endcomponent
                </td>
                <td :class="{'bg-orange-expense': (row.status == 2), 'bg-pink-expense': (row.status == 1 && row.paid || row.status == 1 && row.decided)}" class="position-relative">
                    <!-- expense_status -->
                    @component('backend.project.expense.form.components.input_status', [
                        "id"    => "'status_'+row.screen_name+'_'+row.screen_index+'_'+index",
                        "model" => "row.status"
                    ])
                    @endcomponent
                    <template v-if="initial.editable">
                        <i v-if="index == 0" @click="addExpenseRow(data)"
                            class="btn_overflow fa fa-plus-circle cur-pointer text-primary"
                            data-toggle="tooltip" title="" data-original-title="行追加">
                        </i>
                        <i v-else @click="removeExpenseRow(data, index)"
                            class="btn_overflow fa fa-minus-circle cur-pointer text-danger"
                            data-toggle="tooltip" title="" data-original-title="行削除">
                        </i>
                    </template>
                </td>
            </tr>
        </template>
        <!-- End - Dynamic Row Input -->
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
                    <input :value="total_g.budget | numeralFormat" class="form-control form-control-sm" type="text" readonly="readonly">
                </td>
                <td>
                    <!-- total_decided -->
                    <input :value="total_g.decided | numeralFormat" class="form-control form-control-sm" type="text" readonly="readonly">
                </td>
                <td>
                    <!-- total_paid -->
                    <input :value="total_g.paid | numeralFormat" class="form-control form-control-sm" type="text" readonly="readonly">
                </td>
                <td>
                    <!-- total_balance -->
                    <input :value="total_g.balance | numeralFormat" class="form-control form-control-sm" :class="{'bg-orange-expense': (total_g.balance != 0)}" type="text" readonly="readonly">
                </td>
            </tr>
            <tr>
                <td>消費税小計</td>
                <td>
                    <!-- total_budget_tax -->
                    <input :value="total_g.budget_tax | numeralFormat" class="form-control form-control-sm" type="text" readonly="readonly">
                </td>
                <td>
                    <!-- total_decided_tax -->
                    <input :value="total_g.decided_tax | numeralFormat" class="form-control form-control-sm" type="text" readonly="readonly">
                </td>
                <td>
                    <!-- total_paid_tax -->
                    <input :value="total_g.paid_tax | numeralFormat" class="form-control form-control-sm" type="text" readonly="readonly">
                </td>
                <td>
                    <!-- total_balance_tax -->
                    <input :value="total_g.balance_tax | numeralFormat" class="form-control form-control-sm" :class="{'bg-orange-expense': (total_g.balance_tax != 0)}" type="text" readonly="readonly">
                </td>
            </tr>
        </tbody>
    </table>
    <!-- End - Tax Calculation Table -->
</div>