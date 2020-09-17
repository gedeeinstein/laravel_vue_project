<div class="table-responsive">
    <table class="table table-bordered table-small mt-0 buypurchase_table">
        <tbody>
            <tr>
                <th class="bg-light-gray" style="width: 150px;">仲介</th>
                <!-- start - sale_mediation.enable -->
                <td class="auto">
                    <div class="form-group">
                        <div class="form-check form-check-inline icheck-cyan pl-2">
                            <input v-model="mediation_enable" id="mediation_enable_1"
                                class="form-check-input" type="radio"
                                value="1" data-id="C25-1" :disabled="!initial.editable"
                            >
                            <label class="form-check-label" for="mediation_enable_1">無</label>
                        </div>
                        <div class="form-check form-check-inline icheck-cyan pl-2">
                            <input v-model="mediation_enable" id="mediation_enable_2"
                                class="form-check-input" type="radio" value="2"
                                :disabled="!initial.editable"
                            >
                            <label class="form-check-label" for="mediation_enable_2">有</label>
                        </div>
                    </div>
                </td>
                <!-- end - sale_mediation.enable -->
            </tr>
        </tbody>
    </table>
</div>

<template v-if="mediation_enable == 2">
    <div class="bg-success text-center">仲介情報</div>
    <div>
        <table class="table table-bordered table-small table-parcel-list mt-0">
            <thead>
                <tr>
                    <th class="mediation_dealtype">取引形態</th>
                    <th class="mediation_balance" style="width: 75px;"><span class="text-danger">※</span>収支</th>
                    <th class="mediation_reward">報酬額</th>
                    <th class="mediation_reward_reference">参考値</th>
                    <th class="mediation_date">支払/受領日</th>
                    <th class="mediation_status" style="width: 75px;">状況</th>
                    <th class="mediation_bank">入出金口座</th>
                    <th class="mediation_trader">仲介業者</th>
                    <th class="parcel_ctl" style="width: 50px;"></th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(mediation, index) in mediations">
                    <!-- start - sale_mediation.dealtype -->
                    <td>
                        <select v-model="mediation.dealtype" :disabled="!initial.editable"
                            class="form-control" data-id="C25-2">
                            <option value="1"></option>
                            <option value="2">土地</option>
                            <option value="3">建売</option>
                            <option value="4">中古住宅</option>
                        </select>
                    </td>
                    <!-- end - sale_mediation.dealtype -->

                    <!-- start - sale_mediation.balance -->
                    <td>
                        <select v-model="mediation.balance" :disabled="!initial.editable"
                            :required="mediation_enable == 2" class="form-control" data-id="C25-3"
                            :style="{ background: mediation_background[index] }">
                            <option value="1"></option>
                            <option value="2" style="background-color: #ADD8E6;">収</option>
                            <option value="3" style="background-color: #FF0000;">支</option>
                            <option value="4" style="background-color: #7CFC00;">無</option>
                        </select>
                    </td>
                    <!-- end - sale_mediation.balance -->

                    <!-- start - sale_mediation.reward -->
                    <td>
                        <template>
                            <currency-input v-model="mediation.reward"
                                class="form-control form-control-w-lg input-decimal"
                                :currency="null" :precision="{min: 0, max: 9}" :allow-negative="false"
                                data-parsley-decimal-maxlength="[16,4]" data-parsley-trigger="change focusout"
                                data-parsley-no-focus data-id="C25-4" :disabled="!initial.editable"
                            />
                        </template>
                        <span v-if="initial.editable">
                            <i @click="copy_value(mediation, 'mediations')"
                                class="copy_xxxx_button far fa-copy cur-pointer text-secondary"
                                data-toggle="tooltip" title="参考値コピー">
                            </i>
                        </span>
                    </td>
                    <!-- end - sale_mediation.reward -->

                    <!-- start - contract_price -->
                    <td>
                        <template>
                            <currency-input v-model="contract_price"
                                class="form-control form-control-w-lg input-decimal" readonly="readonly"
                                :currency="null" :precision="{min: 0, max: 9}" :allow-negative="false"
                                data-parsley-decimal-maxlength="[16,4]" data-parsley-trigger="change focusout"
                                data-parsley-no-focus data-id="C25-5" :disabled="!initial.editable"
                            />
                        </template>
                    </td>
                    <!-- end - contract_price -->

                    <!-- start - sale_mediation.date -->
                    <td>
                        <date-picker v-model="mediation.date"
                            type="date" class="form-control-w-xl"
                            input-class="form-control form-control-w-xl input-date"
                            format="YYYY/MM/DD" value-type="format" data-id="C25-6"
                            :disabled="!initial.editable">
                        </date-picker>
                    </td>
                    <!-- end - sale_mediation.date -->

                    <!-- start - sale_mediation.status -->
                    <td>
                        <select v-model="mediation.status" :disabled="!initial.editable"
                            class="form-control" data-id="C25-7">
                            <option value="1"></option>
                            <option value="2">予</option>
                            <option value="3"
                                :disabled="role != 'global_admin' && role != 'accontant'">済
                            </option>
                        </select>
                    </td>
                    <!-- end - sale_mediation.status -->

                    <!-- start - sale_mediation.bank -->
                    <td>
                        <select v-model="mediation.bank"
                            :disabled="!initial.editable || !mediation.trader || mediation.balance == 4"
                            class="form-control w-100" data-id="C25-8">
                            <option value="0"></option>
                            <option v-if="mediation.balance == 2" v-for="bank_account in trader_bank_accounts[index]"
                                :value="bank_account.id">@{{ bank_account.company.name_abbreviation }} @{{ bank_account.bank.name_branch_abbreviation }} @{{ bank_account.account_number }}
                            </option>
                            <option v-if="mediation.balance == 3" v-for="bank_account in banks"
                                :value="bank_account.id">@{{ bank_account.name }}
                            </option>
                        </select>
                    </td>
                    <!-- end - sale_mediation.bank -->

                    <!-- start - sale_mediation.trader -->
                    <td>
                        <multiselect v-model="mediation.trader" :options="in_house_n_real_estates"
                            v-if="mediation.balance == 2" data-id="C25-9" placeholder="選択できる業者がありません。"
                            :close-on-select="true" select-label="" deselect-label label="name"
                            selected-label="選択中" track-by="name" :disabled="!initial.editable"
                            data-parsley-no-focus data-parsley-trigger="change focusout">
                            <template slot="clear" slot-scope="props">
                                <div v-if="mediation.trader"
                                    @mousedown.prevent.stop="clear_select('trader', index)"
                                    class="multiselect__clear"></div>
                            </template>
                        </multiselect>
                        <multiselect v-model="mediation.trader" :options="real_estates"
                            v-if="mediation.balance == 3" data-id="C25-9" placeholder="選択できる業者がありません。"
                            :close-on-select="true" select-label="" deselect-label label="name"
                            selected-label="選択中" track-by="name" :disabled="!initial.editable"
                            data-parsley-no-focus data-parsley-trigger="change focusout">
                            <template slot="clear" slot-scope="props">
                                <div v-if="mediation.trader"
                                    @mousedown.prevent.stop="clear_select('trader', index)"
                                    class="multiselect__clear"></div>
                            </template>
                        </multiselect>
                        <div class="form-result"></div>
                    </td>
                    <!-- end - sale_mediation.trader -->

                    <!-- start - add remove button -->
                    <td class="text-center">
                        <span v-if="index == 0 && initial.editable">
                            <i @click="add_data('mediations')"
                                class="add_row_button fa fa-plus-circle cur-pointer text-primary align-bottom"
                                data-toggle="tooltip" title="追加" data-original-title="追加">
                            </i>
                        </span>
                        <span v-else-if="index > 0 && initial.editable">
                            <i @click="remove_data(index, 'mediations')"
                                class="add_row_button fa fa-minus-circle cur-pointer text-danger align-bottom"
                                data-toggle="tooltip" title="削除" data-original-title="削除">
                            </i>
                        </span>
                    </td>
                    <!-- end - add remove button -->
                </tr>
            </tbody>
        </table>
    </div>
</template>
