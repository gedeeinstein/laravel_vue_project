<div
    v-if="purchase_targets[{{ $index }}].purchase_contract.mediation == 2"
    class="card-subheader01"
    style=" background: #005AA0;
    color: #fff;
    text-align: center;
    padding: 0.3em 0.6em;
    margin: 0.5em 0;" >@lang('project_purchase_contract.intermediary_information')</div>
<table v-if="purchase_targets[{{ $index }}].purchase_contract.mediation == 2" class="table table-bordered table-small table-parcel-list mt-3">
    <thead>
        <tr>
            <th class="mediation_dealtype">@lang('project_purchase_contract.transaction_type')</th>
            <th class="mediation_balance"><span class="text-danger">※</span>@lang('project_purchase_contract.balance')</th>
            <th class="mediation_reward">@lang('project_purchase_contract.remuneration')</th>
            <th class="mediation_reward_reference">@lang('project_purchase_contract.reference_value')</th>
            <th class="mediation_date">@lang('project_purchase_contract.payment')</th>
            <th class="mediation_status">@lang('project_purchase_contract.status')</th>
            <th class="mediation_bank">@lang('project_purchase_contract.deposit')</th>
            <th class="mediation_trader">@lang('project_purchase_contract.intermediary')</th>
            <th class="parcel_ctl"></th>
        </tr>
    </thead>
    <tbody>
        <tr v-for="(purchase_contract_mediation, index_mediation) in purchase_targets[{{ $index }}].purchase_contract.purchase_contract_mediations">
            <td>
                <div class="form-group">
                    <select v-model="purchase_contract_mediation.dealtype" class="form-control form-control-w-lg form-control-sm" name=""
                        :disabled="!default_value.editable" :required="purchase_targets[{{ $index }}].purchase_contract.mediation == 2">
                        <option></option>
                        <option value="1">土地</option>
                        <option value="2">建売</option>
                        <option value="3">中古住宅</option>
                    </select>
                </div>
            </td>
            <td>
                <div class="form-group">
                    <select v-model="purchase_contract_mediation.balance" class="form-control form-control-w-lg form-control-sm" name="" required=""
                        :disabled="!default_value.editable" :style="{ background: purchase_contract_mediation.background }">
                        <option></option>
                        <option value="1" style="background-color: #ADD8E6;">収</option>
                        <option value="2" style="background-color: #FF0000;">支</option>
                        <option value="3" style="background-color: #7CFC00;">無</option>
                    </select>
                </div>
            </td>
            <td>
                <div class="form-group">
                    <template>
                        <currency-input class="form-control form-control-w-md form-control-sm input-decimal" v-model="purchase_contract_mediation.reward"
                            :currency="null" :precision="{min: 0, max: 9}" :allow-negative="false" :disabled="!default_value.editable"
                            data-parsley-decimal-maxlength="[12,4]" data-parsley-trigger="change focusout" data-parsley-no-focus />
                    </template>
                    <span><i @click="copyFromPurchaseContractMediationCalculation(purchase_targets[{{ $index }}], {{ $index }}, index_mediation)" class="copy_xxxx_button far fa-copy cur-pointer text-secondary" data-toggle="tooltip" title="参考値コピー"></i></span>
                </div>
            </td>
            <td>
                <div class="form-group">
                    <currency-input class="form-control form-control-w-lg form-control-sm" v-model="default_value.data[{{ $index }}].purchase_contract_mediation_calculation"
                        :currency="null" :precision="{min: 0, max: 9}" :allow-negative="false" :disabled="!default_value.editable"
                        data-parsley-decimal-maxlength="[12,4]" data-parsley-trigger="change focusout" data-parsley-no-focus readonly="readonly"/>
                </div>
            </td>
            <td>
                <div class="form-group">
                    <date-picker v-model="purchase_contract_mediation.date" type="date" class="w-100" input-class="form-control form-control-sm input-date" format="YYYY/MM/DD" value-type="format"
                        :disabled="!default_value.editable"></date-picker>
                </div>
            </td>
            <td>
                <div class="form-group">
                    <select v-model="purchase_contract_mediation.status" class="form-control form-control-w-lg form-control-sm" name=""
                        :disabled="!default_value.editable">
                        <option value="0"></option>
                        <option value="1">予</option>
                        <option value="2" :disabled="option_is_disabled">済</option>
                    </select>
                </div>
            </td>
            <td>
                <div class="form-group">
                    <select v-model="purchase_contract_mediation.bank" class="form-control form-control-w-xl form-control-sm"
                        :disabled="!default_value.editable || ( purchase_contract_mediation.balance != 1 && purchase_contract_mediation.balance != 2 ) || !sale_mediation_inputed || purchase_contract_mediation.trader_company_id == null">
                        <option value="0"></option>
                        <option v-if="purchase_contract_mediation.balance == 1 && sale_mediation_inputed && purchase_contract_mediation.trader_company_id != null"
                            v-for="company_bank_account in company_bank_accounts" :value="company_bank_account.id">
                            @{{ company_bank_account.company.name_abbreviation }} @{{ company_bank_account.bank.name_branch_abbreviation }} @{{ company_bank_account.account_number }}
                        </option>
                        <option v-if="purchase_contract_mediation.balance == 2 && sale_mediation_inputed && purchase_contract_mediation.trader_company_id != null"
                            v-for="company_bank_account in banks" :value="company_bank_account.id">
                            @{{ company_bank_account.name }}
                        </option>
                    </select>
                </div>
            </td>
            <td>
                <div class="form-group">
                    <v-select v-if="purchase_contract_mediation.balance == 1" :options="in_house_n_real_estates"
                        class="w-100 input-suggest"
                        style="border-color: #bfb3da; background-color: #e2dcee;"
                        :reduce="name => name.id"
                        label="name" :disabled="!default_value.editable"
                        v-model="purchase_contract_mediation.trader_company_id"
                        required="">
                        <span slot="no-options">選択できる業者はありません</span>
                    </v-select>
                    <v-select v-if="purchase_contract_mediation.balance == 2" :options="real_estates"
                        class="w-100 input-suggest"
                        style="border-color: #bfb3da; background-color: #e2dcee;"
                        :reduce="name => name.id"
                        label="name" :disabled="!default_value.editable"
                        v-model="purchase_contract_mediation.trader_company_id"
                        required="">
                        <span slot="no-options">選択できる業者はありません</span>
                    </v-select>
                    <v-select v-if="purchase_contract_mediation.balance == 3 || purchase_contract_mediation.balance == 0" :options="[]"
                        class="w-100 input-suggest"
                        style="border-color: #bfb3da; background-color: #e2dcee;"
                        label="" :disabled="!default_value.editable">
                        <span slot="no-options">選択できる業者はありません</span>
                    </v-select>
                </div>
            </td>
            <td>
                <div class="form-group" align="center">
                    <template v-if="default_value.editable">
                        <i v-if="index_mediation == 0" @click="addProjectPurchaseMediation(purchase_targets[{{ $index }}])" class="add_row_button fa fa-plus-circle cur-pointer text-primary align-bottom form-control-sm" data-toggle="tooltip" title="行を追加"></i>
                        <i v-else @click="removeProjectPurchaseMediation(purchase_targets[{{ $index }}], index_mediation)" class="add_row_button fa fa-minus-circle cur-pointer text-danger align-bottom form-control-sm" data-toggle="tooltip" title="行を追加"></i>
                    </template>
                </div>
            </td>
        </tr>
    </tbody>
</table>
