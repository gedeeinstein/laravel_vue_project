<div class="expense-caption">
    <strong>J. 総費用</strong>
</div>
<div class="form-group">
    <table class="table table-bordered table-small-x mt-0 w-auto">
        <thead>
            <tr>
                <th class="bg-gray-expense"></th>
                <th class="bg-gray-expense expense_budget">予算</th>
                <th class="bg-gray-expense expense_decided">決定総額</th>
                <th class="bg-gray-expense expense_note">備考</th>
                <th class="bg-gray-expense expense_payee">支払額（済）</th>
                <th class="bg-gray-expense expense_payee">残額</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="text-right">物件購入総費用（税込）</td>
                <td>
                    <input :value="total_j.expense_budget | numeralFormat" class="form-control" type="text" readonly="readonly" data-id="A169-1">
                </td>
                <td>
                    <input :value="total_j.expense_decided | numeralFormat" class="form-control" type="text" readonly="readonly" data-id="A169-2">
                </td>
                <td>
                    <input v-model="expense.total_note" class="form-control" type="text" data-id="A169-3">
                </td>
                <td>
                    <input :value="total_j.expense_paid | numeralFormat" class="form-control" type="text" readonly="readonly" data-id="A169-4">
                </td>
                <td>
                    <input :value="total_j.expense_balance | numeralFormat" class="form-control" :class="{'bg-orange-expense': (total_j.expense_balance != 0)}" type="text" readonly="readonly" data-id="A169-5">
                </td>
            </tr>
            <tr>
                <td class="text-right">（税抜）</td>
                <td>
                    <input :value="total_j.expense_tax_exlude_budget | numeralFormat" class="form-control" type="text" readonly="readonly" data-id="A169-6">
                </td>
                <td>
                    <input :value="total_j.expense_tax_exlude_decided | numeralFormat" class="form-control" type="text" readonly="readonly" data-id="A169-7">
                </td>
                <td></td>
                <td>
                    <input :value="total_j.expense_tax_exlude_paid | numeralFormat" class="form-control" type="text" readonly="readonly" data-id="A169-8">
                </td>
                <td></td>
            </tr>
            <tr>
                <td class="text-right">（消費税）</td>
                <td>
                    <input :value="total_j.expense_budget_tax | numeralFormat" class="form-control" type="text" readonly="readonly" data-id="A169-9">
                </td>
                <td>
                    <input :value="total_j.expense_decided_tax | numeralFormat" class="form-control" type="text" readonly="readonly" data-id="A169-10">
                </td>
                <td></td>
                <td>
                    <input :value="total_j.expense_paid_tax | numeralFormat" class="form-control" type="text" readonly="readonly" data-id="A169-11">
                </td>
                <td></td>
            </tr>
        </tbody>
        <thead>
            <tr>
                <th class="bg-gray-expense"></th>
                <th class="bg-gray-expense expense_budget">予算</th>
                <th class="bg-gray-expense expense_decided">決定総額</th>
                <th class="bg-gray-expense expense_note">備考</th>
                <th class="bg-gray-expense expense_payee">有効面積(m2)</th>
                <th class="bg-gray-expense expense_payee">有効面積(坪)</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="text-right">総費用有効宅坪単価（税込）</td>
                <td>
                    <input :value="total_j.expense_budget_tsubo | numeralFormat(4)" class="form-control" type="text" readonly="readonly" data-id="A169-12">
                </td>
                <td>
                    <input :value="total_j.expense_decided_tsubo | numeralFormat(4)" class="form-control" type="text" readonly="readonly" data-id="A169-13">
                </td>
                <td>
                    <input v-model="expense.total_note_tsubo" class="form-control" type="text" data-id="A169-14">
                </td>
                <td>
                    <input :value="total_j.expense_paid_tsubo | numeralFormat(4)" class="form-control" type="text" readonly="readonly" data-id="A169-15">
                </td>
                <td>
                    <input :value="total_j.expense_balance_tsubo | numeralFormat(4)" class="form-control" type="text" readonly="readonly" data-id="A169-16">
                </td>
            </tr>
        </tbody>
    </table>
</div>