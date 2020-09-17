<div class="table-responsive">
    <table class="table table-bordered table-small buypurchase_table mt-0">
        <thead>
            <tr>
                <th class="bg-light-gray">固定資産税日割清算金</th>
                <th class="bg-light-gray">入金日</th>
                <th class="bg-light-gray auto">状況</th>
                <th class="bg-light-gray">入金口座</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <!-- start - sale_contract.real_estate_tax_income -->
                <td>
                    <template>
                        <currency-input v-model="sale_contract.real_estate_tax_income"
                            class="form-control form-control-w-xl input-decimal"
                            :currency="null" :precision="{min: 0, max: 0}" :allow-negative="false"
                            data-parsley-decimal-maxlength="[12,0]" data-parsley-trigger="change focusout"
                            data-parsley-no-focus data-id="C24-1" :disabled="!initial.editable"
                        />
                    </template>
                </td>
                <!-- end - sale_contract.real_estate_tax_income -->

                <!-- start - sale_contract.real_estate_tax_income_date -->
                <td>
                    <date-picker v-model="sale_contract.real_estate_tax_income_date"
                        type="date" class="form-control-w-xl"
                        input-class="form-control form-control-w-xl input-date"
                        format="YYYY/MM/DD" value-type="format" data-id="C24-2"
                        :disabled="!initial.editable">
                    </date-picker>
                </td>
                <!-- end - sale_contract.real_estate_tax_income_date -->

                <!-- start - sale_contract.real_estate_tax_income_status -->
                <td class="auto">
                    <select v-model="sale_contract.real_estate_tax_income_status"
                        :disabled="!initial.editable"
                        class="form-control" data-id="C24-3">
                        <option value="1">予</option>
                        <option value="2">確</option>
                        <option value="3">済</option>
                    </select>
                </td>
                <!-- end - sale_contract.real_estate_tax_income_status -->

                <!-- start - sale_contract.real_estate_tax_income_account -->
                <td>
                    <select v-model="sale_contract.real_estate_tax_income_account"
                        :disabled="!initial.editable"
                        class="form-control form-control-w-xl" data-id="C24-4">
                        <option value="0"></option>
                        <option v-for="company_bank_account in banks" :value="company_bank_account.id">
                            @{{ company_bank_account.name }}
                        </option>
                    </select>
                </td>
                <!-- end - sale_contract.real_estate_tax_income_account -->
            </tr>
        </tbody>
    </table>
    <hr>
</div>
