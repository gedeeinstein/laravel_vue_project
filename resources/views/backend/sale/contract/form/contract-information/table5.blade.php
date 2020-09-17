<div class="table-responsive">
    <table class="table table-bordered table-small buypurchase_table mt-0">
        <thead>
            <tr>
                <th class="bg-light-gray">引渡金</th>
                <th class="bg-light-gray">支払日</th>
                <th class="bg-light-gray auto">状況</th>
                <th class="bg-light-gray">入金口座</th>
                <th class="bg-light-gray auto">備考</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <!-- start - sale_contract.delivery_price -->
                <td>
                    <template>
                        <currency-input v-model="sale_contract.delivery_price"
                            class="form-control form-control-w-xl input-decimal"
                            :currency="null" :precision="{min: 0, max: 0}" :allow-negative="false"
                            data-parsley-decimal-maxlength="[12,0]" data-parsley-trigger="change focusout"
                            data-parsley-no-focus data-id="C23-20" :disabled="!initial.editable || !initial.is_active"
                        />
                    </template>
                    <span v-if="initial.editable">
                        <i @click="is_active"
                            class="add_xxxx_button fa fa-edit cur-pointer text-secondary" data-toggle="tooltip"
                            title="編集" data-original-title="編集">
                        </i>
                    </span>
                </td>
                <!-- end - sale_contract.delivery_price -->

                <!-- start - sale_contract.delivery_date -->
                <td>
                    <date-picker v-model="sale_contract.delivery_date"
                        type="date" class="form-control-w-xl"
                        input-class="form-control form-control-w-xl input-date"
                        format="YYYY/MM/DD" value-type="format" data-id="C23-21"
                        :disabled="!initial.editable">
                    </date-picker>
                    <span v-if="initial.editable">
                        <i @click="copy_value(sale_contract, 'contract')"
                            class="copy_xxxx_button far fa-copy cur-pointer text-secondary" data-toggle="tooltip"
                            title="決済日からコピーする" data-original-title="決済日からコピーする">
                        </i>
                    </span>
                </td>
                <!-- end - sale_contract.delivery_date -->

                <!-- start - sale_contract.delivery_status -->
                <td class="auto">
                    <select v-model="sale_contract.delivery_status" :disabled="!initial.editable"
                        class="form-control" data-id="C23-22">
                        <option value="1">予</option>
                        <option value="2">確</option>
                        <option value="3">済</option>
                    </select>
                </td>
                <!-- end - sale_contract.delivery_status -->

                <!-- start - sale_contract.delivery_account -->
                <td>
                    <select v-model="sale_contract.delivery_account" :disabled="!initial.editable"
                        class="form-control form-control-w-xl" data-id="C23-23">
                        <option value="0"></option>
                        <option v-for="company_bank_account in banks" :value="company_bank_account.id">
                            @{{ company_bank_account.name }}
                        </option>
                    </select>
                </td>
                <!-- end - sale_contract.delivery_account -->

                <!-- start - sale_contract.delivery_note -->
                <td class="auto">
                    <input v-model="sale_contract.delivery_note" :disabled="!initial.editable"
                        class="form-control" type="text" data-id="C23-24">
                </td>
                <!-- end - sale_contract.delivery_note -->
            </tr>
        </tbody>
    </table>
    <hr>
</div>
