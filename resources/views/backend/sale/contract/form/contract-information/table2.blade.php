<div>
    <table class="table table-bordered table-small">
        <thead>
            <tr>
                <th class="bg-light-gray">購入者氏名</th>
                <th class="bg-light-gray">購入者住所</th>
                <th class="bg-light-gray">建築HM</th>
                <th class="bg-light-gray">登記</th>
                <th class="bg-light-gray">外構</th>
                <th class="bg-light-gray">本人確認</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <!-- start - sale_contract.customer_name -->
                <td>
                    <div class="form-group">
                        <div class="input-group input-group-small">
                            <input v-model="sale_contract.customer_name"
                                class="form-control form-control-w-lg" data-id="C23-7"
                                type="text" :disabled="!initial.editable"
                            >
                        </div>
                    </div>
                </td>
                <!-- end - sale_contract.customer_name -->

                <!-- start - sale_contract.customer_address -->
                <td>
                    <div class="form-group">
                        <div class="input-group input-group-small">
                            <input v-model="sale_contract.customer_address"
                                class="form-control form-control-w-xxxl" data-id="C23-8"
                                type="text" :disabled="!initial.editable"
                            >
                        </div>
                    </div>
                </td>
                <!-- end - sale_contract.customer_address -->

                <!-- start - sale_contract.house_maker -->
                <td>
                    <div class="form-group">
                        <div class="input-group input-group-small">
                            <multiselect v-model="sale_contract.house_maker" :options="house_makers"
                                data-id="C23-9" style="width: 175px;" placeholder="選択できる業者がありません。"
                                :close-on-select="true" select-label="" deselect-label label="name"
                                selected-label="選択中" track-by="name" :disabled="!initial.editable"
                                data-parsley-no-focus data-parsley-trigger="change focusout">
                                <template slot="clear" slot-scope="props">
                                    <div v-if="sale_contract.house_maker"
                                        @mousedown.prevent.stop="clear_select('house_maker')"
                                        class="multiselect__clear"></div>
                                </template>
                            </multiselect>
                            <div class="form-result"></div>
                        </div>
                    </div>
                </td>
                <!-- end - sale_contract.house_maker -->

                <!-- start - sale_contract.register -->
                <td>
                    <div class="form-group">
                        <div class="input-group input-group-small">
                            <multiselect v-model="sale_contract.register" :options="professions"
                                data-id="C23-10" style="width: 175px;" placeholder="選択できる業者がありません。"
                                :close-on-select="true" select-label="" deselect-label label="name"
                                selected-label="選択中" track-by="name" :disabled="!initial.editable"
                                data-parsley-no-focus data-parsley-trigger="change focusout">
                                <template slot="clear" slot-scope="props">
                                    <div v-if="sale_contract.register"
                                        @mousedown.prevent.stop="clear_select('register')"
                                        class="multiselect__clear"></div>
                                </template>
                            </multiselect>
                            <div class="form-result"></div>
                        </div>
                    </div>
                </td>
                <!-- end - sale_contract.register -->

                <!-- start - sale_contract.outdoor_facility_manufacturer -->
                <td>
                    <div class="form-group">
                        <div class="input-group input-group-small">
                            <multiselect v-model="sale_contract.outdoor_facility_manufacturer" :options="contractors"
                                data-id="C23-11" style="width: 175px;" placeholder="選択できる業者がありません。"
                                :close-on-select="true" select-label="" deselect-label label="name"
                                selected-label="選択中" track-by="name" :disabled="!initial.editable"
                                data-parsley-no-focus data-parsley-trigger="change focusout">
                                <template slot="clear" slot-scope="props">
                                    <div v-if="sale_contract.outdoor_facility_manufacturer"
                                        @mousedown.prevent.stop="clear_select('outdoor_facility_manufacturer')"
                                        class="multiselect__clear"></div>
                                </template>
                            </multiselect>
                            <div class="form-result"></div>
                        </div>
                    </div>
                </td>
                <!-- end - sale_contract.outdoor_facility_manufacturer -->

                <td>
                    <div class="form-group">
                        <div class="input-group input-group-small">
                            <!-- start - sale_contract.person_himself -->
                            <select v-model="sale_contract.person_himself"
                                class="form-control" data-id="C23-25"
                                :disabled="!initial.editable">
                                <option selected="selected"></option>
                                <option value="1">運</option>
                                <option value="2">パ</option>
                                <option value="3">健</option>
                                <option value="4">年</option>
                                <option value="5">謄</option>
                                <option value="6">不</option>
                            </select>
                            <!-- end - sale_contract.person_himself -->

                            <!-- start - sale_contract.person_himself_attachment -->
                            <div class="form-check icheck-cyan pl-2">
                                <input v-model="sale_contract.person_himself_attachment"
                                    class="form-check-input" type="checkbox"
                                    id="identity_verification" value="1" data-id="C23-26"
                                    :disabled="!initial.editable"
                                >
                                <label class="form-check-label" for="identity_verification">添付</label>
                            </div>
                            <!-- end - sale_contract.person_himself_attachment -->
                        </div>
                    </div>
                </td>
            </tr>
        </tbody>
    </table>
</div>
