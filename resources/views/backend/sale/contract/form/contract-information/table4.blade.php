<div class="table-responsive">
    <table class="table table-bordered table-small buypurchase_table mt-0 mb-0">
        <thead>
            <tr>
                <th class="bg-light-gray">契約手付金</th>
                <th class="bg-light-gray">支払日</th>
                <th class="bg-light-gray auto">状況</th>
                <th class="bg-light-gray">入金口座</th>
                <th class="bg-light-gray auto">備考</th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="(deposit, index) in deposits">
                <!-- start - sale_contract_deposit.price -->
                <td>
                    <template>
                        <currency-input v-model="deposit.price"
                            class="form-control form-control-w-xl input-decimal" :readonly="index == 0"
                            :currency="null" :precision="{min: 0, max: 0}" :allow-negative="false"
                            data-parsley-decimal-maxlength="[12,0]" data-parsley-trigger="change focusout"
                            data-parsley-no-focus data-id="C23-15" :disabled="!initial.editable || index == 0"
                        />
                    </template>
                    <span v-if="index == 0 && initial.editable">
                        <i @click="add_data('deposits')"
                            class="add_xxxx_button fa fa-plus-circle cur-pointer text-primary"
                            data-toggle="tooltip" title="追加" data-original-title="追加">
                        </i>
                    </span>
                    <span v-else-if="index > 0 && initial.editable">
                        <i @click="remove_data(index, 'deposits')"
                            class="add_xxxx_button fa fa-minus-circle cur-pointer text-danger"
                            data-toggle="tooltip" title="削除" data-original-title="削除">
                        </i>
                    </span>
                </td>
                <!-- end - sale_contract_deposit.price -->

                <!-- start - sale_contract_deposit.date -->
                <td>
                    <date-picker v-model="deposit.date"
                        type="date" class="form-control-w-xl"
                        input-class="form-control form-control-w-xl input-date"
                        format="YYYY/MM/DD" value-type="format" data-id="C23-16"
                        :disabled="!initial.editable">
                    </date-picker>
                    <span v-if="initial.editable">
                        <i @click="copy_value(deposit, 'deposits')"
                            class="copy_xxxx_button far fa-copy cur-pointer text-secondary"
                            data-toggle="tooltip" title="" data-original-title="契約日からコピーする">
                        </i>
                    </span>
                </td>
                <!-- end - sale_contract_deposit.date -->

                <!-- start - sale_contract_deposit.status -->
                <td class="auto">
                    <select v-model="deposit.status" :disabled="!initial.editable"
                        class="form-control" data-id="C23-17">
                        <option value="1">予</option>
                        <option value="2">確</option>
                        <option value="3">済</option>
                    </select>
                </td>
                <!-- end - sale_contract_deposit.status -->

                <!-- start - sale_contract_deposit.account -->
                <td>
                    <select v-model="deposit.account" :disabled="!initial.editable"
                        class="form-control form-control-w-xl" data-id="C23-18">
                        <option value="0"></option>
                        <option v-for="company_bank_account in banks" :value="company_bank_account.id">
                            @{{ company_bank_account.name }}
                        </option>
                    </select>
                </td>
                <!-- end - sale_contract_deposit.account -->

                <!-- start - sale_contract_deposit.note -->
                <td class="auto">
                    <input v-model="deposit.note" :disabled="!initial.editable"
                        class="form-control" type="text" data-id="C23-19"
                    >
                </td>
                <!-- end - sale_contract_deposit.note -->
            </tr>
        </tbody>
    </table>
</div>
