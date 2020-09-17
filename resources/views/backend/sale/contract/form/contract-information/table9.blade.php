<div class="table-responsive">
    <table class="table table-bordered table-small mt-0 w-auto">
        <tbody>
            <tr>
                <th class="bg-light-gray" style="width: 150px;">フィー・その他収支</th>
                <!-- start - sale_fee.enable -->
                <td class="auto">
                    <div class="form-group">
                        <div class="form-check form-check-inline icheck-cyan pl-2">
                            <input v-model="fee.enable" id="fee_enable_1"
                                class="form-check-input" type="radio"
                                value="1" :disabled="!initial.editable" data-id="C26-1"
                            >
                            <label class="form-check-label" for="fee_enable_1">無</label>
                        </div>
                        <div class="form-check form-check-inline icheck-cyan pl-2">
                            <input v-model="fee.enable" id="fee_enable_2"
                                class="form-check-input" type="radio" value="2"
                                :disabled="!initial.editable"
                            >
                            <label class="form-check-label" for="fee_enable_2">有</label>
                        </div>
                    </div>
                </td>
                <!-- start - sale_fee.enable -->
            </tr>
        </tbody>
    </table>
</div>

<template v-if="fee.enable == 2">
    <div class="bg-success text-center">フィー・その他収支</div>
    <div class="table-responsive">
        <table class="table table-bordered table-small w-auto">
            <tbody>
                <tr>
                    <th class="bg-light-gray">対象者</th>
                    <!-- start - sale_fee.customer -->
                    <td>
                        <div class="form-group">
                            <div class="input-group input-group-small">
                                <input v-model="fee.customer"
                                    class="form-control form-control-w-xl"
                                    :disabled="!initial.editable" data-id="C26-2" type="text"
                                >
                            </div>
                        </div>
                    </td>
                    <!-- start - sale_fee.customer -->
                    <th class="bg-light-gray">備考</th>
                    <!-- start - sale_fee.note -->
                    <td>
                        <div class="form-group">
                            <div class="input-group input-group-small">
                                <input v-model="fee.note"
                                    class="form-control form-control-w-xxxl"
                                    :disabled="!initial.editable" data-id="C26-3" type="text"
                                >
                            </div>
                        </div>
                    </td>
                    <!-- start - sale_fee.note -->
                </tr>
                <tr>
                    <th class="bg-light-gray">収支</th>
                    <!-- start - sale_fee.balance -->
                    <td>
                        <div class="form-group">
                            <div class="input-group input-group-small">
                                <select v-model="fee.balance" :style="{ background: fee_background }"
                                    :disabled="!initial.editable" :required="fee.enable == 2"
                                    class="form-control" data-id="C26-4">
                                    <option value="1"></option>
                                    <option value="2" style="background-color: #ADD8E6;">収</option>
                                    <option value="3" style="background-color: #FF0000;">支</option>
                                    <option value="4" style="background-color: #7CFC00;">無</option>
                                </select>
                            </div>
                        </div>
                    </td>
                    <!-- start - sale_fee.balance -->
                    <th class="bg-light-gray">入金先</th>
                    <!-- start - sale_fee.receipt_company -->
                    <td>
                        <div class="form-group">
                            <div class="input-group input-group-small">
                                <select v-model="fee.receipt_company" :disabled="!initial.editable || fee.balance == 3"
                                    class="form-control col-4" data-id="C26-5">
                                    <option value="0"></option>
                                    <option v-for="company in in_houses" :value="company.id">
                                        @{{ company.name }}
                                    </option>
                                </select>
                            </div>
                        </div>
                    </td>
                    <!-- start - sale_fee.receipt_company -->
                </tr>
                <tr v-for="(price, index) in prices">
                    <th class="bg-light-gray">フィー価格</th>
                    <td colspan="3">
                        <div class="form-group">
                            <!-- start - sale_fee_price.price -->
                            <div class="input-group input-group-small">
                                <template>
                                    <currency-input v-model="price.price" placeholder="金額"
                                        class="form-control form-control-w-lg mr-2 input-decimal"
                                        :currency="null" :precision="{min: 0, max: 0}" :allow-negative="false"
                                        data-parsley-decimal-maxlength="[12,0]" data-parsley-trigger="change focusout"
                                        data-parsley-no-focus data-id="C26-6" :disabled="!initial.editable"
                                    />
                                </template>
                                <!-- start - sale_fee_price.price -->

                                <!-- start - sale_fee_price.status -->
                                <select v-model="price.status" :disabled="!initial.editable"
                                    class="form-control mr-2" data-id="C26-7"
                                    :required="price.price">
                                    <option value="1">予</option>
                                    <option value="2">確</option>
                                    <option value="3">済</option>
                                </select>
                                <!-- start - sale_fee_price.status -->

                                <!-- start - sale_fee_price.date -->
                                <date-picker v-model="price.date" placeholder="日付"
                                    type="date" class="form-control form-control-w-xl mr-2"
                                    input-class="form-control form-control-w-xl input-date"
                                    format="YYYY/MM/DD" value-type="format" data-id="C26-8"
                                    :disabled="!initial.editable" :required="price.price">
                                </date-picker>
                                <!-- start - sale_fee_price.date -->

                                <!-- start - sale_fee_price.account -->
                                {{-- <select v-model="price.account" :disabled="!initial.editable || fee.balance == 4"
                                    class="form-control form-control-w-xl mr-2" data-id="C26-9"
                                    :required="price.price">
                                    <option value="0"></option>
                                    <option v-if="fee.balance == 2" v-for="bank_account in bank_accounts"
                                        :value="bank_account.id">@{{ bank_account.company.name_abbreviation }} @{{ bank_account.bank.name_branch_abbreviation }} @{{ bank_account.account_number }}
                                    </option>
                                    <option v-if="fee.balance == 3" v-for="bank_account in banks"
                                        :value="bank_account.id">@{{ bank_account.name }}
                                    </option>
                                </select> --}}
                                <select v-model="price.account" :disabled="!initial.editable"
                                    class="form-control form-control-w-xl mr-2" data-id="C26-9"
                                    :required="price.price">
                                    <option value="0"></option>
                                    <option v-for="bank_account in kind_bank_accounts"
                                        :value="bank_account.id">@{{ bank_account.company.name_abbreviation }} @{{ bank_account.bank.name_branch_abbreviation }} @{{ bank_account.account_number }}
                                    </option>
                                </select>
                                <!-- start - sale_fee_price.account -->

                                <!-- start - add and remove button -->
                                <span v-if="index == 0 && initial.editable" style="align-self: center;">
                                    <i @click="add_data('prices')"
                                        class="add_xxxx_button fa fa-plus-circle cur-pointer text-primary"
                                        data-toggle="tooltip" title="追加" data-original-title="追加">
                                    </i>
                                </span>
                                <span v-else-if="index > 0 && initial.editable" style="align-self: center;">
                                    <i @click="remove_data(index, 'prices')"
                                        class="add_xxxx_button fa fa-minus-circle cur-pointer text-danger"
                                        data-toggle="tooltip" title="削除" data-original-title="削除">
                                    </i>
                                </span>
                                <!-- start - add and remove button -->
                            </div>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>
