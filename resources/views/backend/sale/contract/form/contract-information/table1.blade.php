<div class="table-responsive">
    <table class="table table-bordered table-small">
        <thead>
            <tr>
                <th class="bg-light-gray">販売価格</th>
                <th class="bg-light-gray">販売確定価格</th>
                <th class="bg-light-gray">内、建物価格</th>
                <th class="bg-light-gray">確定利益額</th>
                <th class="bg-light-gray">確定利益率</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <!-- start - mas_section.price_budget -->
                <td>
                    <div class="form-group">
                        <div class="input-group input-group-small">
                            <template>
                                <currency-input v-model="mas_section.price_budget"
                                    class="form-control form-control-w-xl input-money" readonly="readonly"
                                    :currency="null" :precision="{min: 0, max: 0}" :allow-negative="false"
                                    data-parsley-decimal-maxlength="[12,0]" data-parsley-trigger="change focusout"
                                    data-parsley-no-focus data-id="C23-1" :disabled="true"
                                />
                            </template>
                            <div class="input-group-append">
                                <div class="input-group-text input-group-text-xs">円</div>
                            </div>
                        </div>
                    </div>
                </td>
                <!-- end - mas_section.price_budget -->

                <!-- start - sale_contract.contract_price -->
                <td>
                    <div class="form-group">
                        <div class="input-group input-group-small">
                            <template>
                                <currency-input v-model="sale_contract.contract_price"
                                    class="form-control form-control-w-xl input-money"
                                    :currency="null" :precision="{min: 0, max: 0}" :allow-negative="false"
                                    data-parsley-decimal-maxlength="[12,0]" data-parsley-trigger="change focusout"
                                    data-parsley-no-focus data-id="C23-2" :disabled="!initial.editable"
                                />
                            </template>
                            <div class="input-group-append">
                                <div class="input-group-text input-group-text-xs">円</div>
                            </div>
                        </div>
                    </div>
                </td>
                <!-- end - sale_contract.contract_price -->

                <td>
                    <div class="form-group">
                        <div class="input-group input-group-small">
                            <!-- start - sale_contract.contract_price_building -->
                            <template>
                                <currency-input v-model="sale_contract.contract_price_building"
                                    class="form-control form-control-w-xl input-money"
                                    :currency="null" :precision="{min: 0, max: 0}" :allow-negative="false"
                                    data-parsley-decimal-maxlength="[12,0]" data-parsley-trigger="change focusout"
                                    data-parsley-no-focus data-id="C23-3" :disabled="!initial.editable"
                                />
                            </template>
                            <div class="input-group-append">
                                <div class="input-group-text input-group-text-xs">円</div>
                            </div>
                            <!-- end - sale_contract.contract_price_building -->

                            <!-- start - sale_contract.contract_price_building_no_tax -->
                            <div class="form-check icheck-cyan ml-3">
                                <input v-model="sale_contract.contract_price_building_no_tax"
                                    type="checkbox" class="ml-2" :disabled="!initial.editable"
                                    id="contract_price_building_no_tax" data-id="C23-4"
                                >
                                <label class="form-check-label" for="contract_price_building_no_tax">非</label>
                            </div>
                            <!-- end - sale_contract.contract_price_building_no_tax -->
                        </div>
                    </div>
                </td>

                <!-- start - income -->
                <td>
                    <div class="form-group">
                        <div class="input-group input-group-small">
                            <template>
                                <currency-input v-model="income"
                                    class="form-control form-control-w-xl input-money" readonly="readonly"
                                    :currency="null" :precision="{min: 0, max: 0}" :allow-negative="false"
                                    data-parsley-decimal-maxlength="[12,0]" data-parsley-trigger="change focusout"
                                    data-parsley-no-focus data-id="C23-5" :disabled="true"
                                />
                            </template>
                            <div class="input-group-append">
                                <div class="input-group-text input-group-text-xs">円</div>
                            </div>
                        </div>
                    </div>
                </td>
                <!-- end - income -->

                <!-- start - mas_section.profit_decided -->
                <td>
                    <div class="form-group">
                        <div class="input-group input-group-small">
                            <template>
                                <currency-input v-model="profit_decided"
                                    class="form-control form-control-w-md input-decimal" readonly="readonly"
                                    :currency="null" :precision="{min: 0, max: 9}" :allow-negative="false"
                                    data-parsley-decimal-maxlength="[16,4]" data-parsley-trigger="change focusout"
                                    data-parsley-no-focus data-id="C23-6" :disabled="true"
                                />
                            </template>
                            <div class="input-group-append">
                                <div class="input-group-text input-group-text-xs">%</div>
                            </div>
                        </div>
                    </div>
                </td>
                <!-- end - mas_section.profit_decided -->
            </tr>
        </tbody>
    </table>
</div>
