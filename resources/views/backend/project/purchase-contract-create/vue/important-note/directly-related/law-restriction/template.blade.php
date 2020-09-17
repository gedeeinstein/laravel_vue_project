<script type="text/x-template" id="important-note-law-restriction">
    <div class="form-group row">
        <label for="" class="col-3 col-form-label" style="font-weight: normal;">都市計画法、建築基準法以外の<br>法令に基づく制限</label>
        <div class="col-9">
            <template v-if="purchase_sale.project_urbanization_area_sub == 2">
                <div class="row mt-1">
                    <label class="col-3 col-form-label" style="font-weight: normal;" for="">仮換地指定</label>
                    <div class="col-9">
                        <div class="form-check form-check-inline icheck-cyan">
                            <input v-model="entry.provisional_land_change" :disabled="isDisabled || isCompleted"
                            class="form-check-input" type="radio" id="provisional_land_change_1" value="1" data-id="A1310-83">
                            <label class="form-check-label" for="provisional_land_change_1">未</label>
                        </div>
                        <div class="form-check form-check-inline icheck-cyan">
                            <input v-model="entry.provisional_land_change" :disabled="isDisabled || isCompleted"
                            class="form-check-input" type="radio" id="provisional_land_change_2" value="2">
                            <label class="form-check-label" for="provisional_land_change_2">済</label>
                            <input v-model="entry.provisional_land_change_text"
                            :disabled="isDisabled || isCompleted || entry.provisional_land_change != 2"
                            data-parsley-trigger="keyup" data-parsley-maxlength="128"
                            class="form-control ml-2" type="text" value="" data-id="A1310-84">
                        </div>

                    </div>
                </div>
                <div class="row mt-1">
                    <label class="col-3 col-form-label" style="font-weight: normal;" for="">換地処分の公告</label>
                    <div class="col-9 form-inline">
                        <input v-model="entry.provisional_land_change_notice" :disabled="isDisabled || isCompleted"
                        data-parsley-trigger="keyup" data-parsley-maxlength="128"
                        class="form-control" type="text" value="" data-id="A1310-85">（予定）
                    </div>
                </div>
                <div class="row mt-1">
                    <label class="col-3 col-form-label" style="font-weight: normal;" for="">仮換地図等</label>
                    <div class="col-9">
                        <div class="form-check form-check-inline icheck-cyan">
                            <input v-model="entry.provisional_land_change_map" :disabled="isDisabled || isCompleted"
                            class="form-check-input" type="radio" id="provisional_land_change_map_1" value="1" data-id="A1310-86">
                            <label class="form-check-label" for="provisional_land_change_map_1">有</label>
                        </div>
                        <div class="form-check form-check-inline icheck-cyan">
                            <input v-model="entry.provisional_land_change_map" :disabled="isDisabled || isCompleted"
                            class="form-check-input" type="radio" id="provisional_land_change_map_2" value="2">
                            <label class="form-check-label" for="provisional_land_change_map_2">無</label>
                        </div>

                    </div>
                </div>
                <div class="row mt-1">
                    <label class="col-3 col-form-label" style="font-weight: normal;" for="">清算金の徴収・交付</label>
                    <div class="col-9">
                        <div class="form-check form-check-inline icheck-cyan">
                            <input v-model="entry.liquidation" :disabled="isDisabled || isCompleted"
                            class="form-check-input" type="radio" id="liquidation_1" value="1" data-id="A1310-87">
                            <label class="form-check-label" for="liquidation_1">有：徴収</label>
                        </div>
                        <div class="form-check form-check-inline icheck-cyan">
                            <input v-model="entry.liquidation" :disabled="isDisabled || isCompleted"
                            class="form-check-input" type="radio" id="liquidation_2" value="2">
                            <label class="form-check-label" for="liquidation_2">有：交付</label>
                        </div>
                        <div class="form-check form-check-inline icheck-cyan">
                            <input v-model="entry.liquidation" :disabled="isDisabled || isCompleted"
                            class="form-check-input" type="radio" id="liquidation_3" value="3">
                            <label class="form-check-label" for="liquidation_3">なし</label>
                        </div>
                        <div class="form-check form-check-inline icheck-cyan">
                            <input v-model="entry.liquidation" :disabled="isDisabled || isCompleted"
                            class="form-check-input" type="radio" id="liquidation_4" value="4">
                            <label class="form-check-label" for="liquidation_4">未定</label>
                        </div>
                        <div class="input-group">
                            <div class="form-check form-check-inline icheck-cyan">
                                金額 <input v-model="entry.liquidation_money" :disabled="isDisabled || isCompleted"
                                class="form-check-input ml-2" type="radio" id="liquidation_money_1" value="1" data-id="A1310-88">
                                <label class="form-check-label ml-2" for="liquidation_money_1">未定</label>
                            </div>
                            <div class="form-check form-check-inline icheck-cyan">
                                <input v-model="entry.liquidation_money" :disabled="isDisabled || isCompleted"
                                class="form-check-input" type="radio" id="liquidation_money_2" value="2">
                                <label class="form-check-label" for="liquidation_money_2">確定金</label>
                                <div class="form-inline ml-2">
                                    <div class=" input-group">
                                        <!-- <input v-model="entry.liquidation_money_text"
                                        :disabled="isDisabled || isCompleted || entry.liquidation_money != 2"
                                        class="form-control col-5" type="number" value="" data-id="A1310-89">
                                        <div class="input-group-append">
                                            <div class="input-group-text">円</div>
                                        </div> -->
                                        <template>
                                          <currency-input v-model="entry.liquidation_money_text"
                                            :disabled="isDisabled || isCompleted || entry.liquidation_money != 2"
                                            :currency="null" :precision="{min: 0, max: 9}" :allow-negative="false"
                                            class="form-control input-money col-5" value="" data-id="A1310-89"
                                            data-parsley-decimal-maxlength="[12,0]" data-parsley-trigger="change focusout" data-parsley-no-focus/>
                                        </template>
                                        <div class="input-group-append">
                                            <div class="input-group-text">円</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <label class="col-3 col-form-label" style="font-weight: normal;" for="">賦課金の徴収・交付</label>
                    <div class="col-9">
                        <div class="form-check form-check-inline icheck-cyan">
                            <input v-model="entry.levy" :disabled="isDisabled || isCompleted"
                            class="form-check-input" type="radio" id="levy_1" value="1" data-id="A1310-90">
                            <label class="form-check-label" for="levy_1">有：徴収</label>
                        </div>
                        <div class="form-check form-check-inline icheck-cyan">
                            <input v-model="entry.levy" :disabled="isDisabled || isCompleted"
                            class="form-check-input" type="radio" id="levy_2" value="2">
                            <label class="form-check-label" for="levy_2">有：交付</label>
                        </div>
                        <div class="form-check form-check-inline icheck-cyan">
                            <input v-model="entry.levy" :disabled="isDisabled || isCompleted"
                            class="form-check-input" type="radio" id="levy_3" value="3">
                            <label class="form-check-label" for="levy_3">なし</label>
                        </div>
                        <div class="form-check form-check-inline icheck-cyan">
                            <input v-model="entry.levy" :disabled="isDisabled || isCompleted"
                            class="form-check-input" type="radio" id="levy_4" value="4">
                            <label class="form-check-label" for="levy_4">未定</label>
                        </div>
                        <div class="input-group">
                            <div class="form-check form-check-inline icheck-cyan">
                                金額 <input v-model="entry.levy_money" :disabled="isDisabled || isCompleted"
                                class="form-check-input ml-2" type="radio" id="levy_money_1" value="1" data-id="A1310-91">
                                <label class="form-check-label ml-2" for="levy_money_1">未定</label>
                            </div>
                            <div class="form-check form-check-inline icheck-cyan">
                                <input v-model="entry.levy_money" :disabled="isDisabled || isCompleted"
                                class="form-check-input" type="radio" id="levy_money_2" value="2">
                                <label class="form-check-label" for="levy_money_2">確定金</label>
                                <div class="form-inline ml-2">
                                    <!-- <div class=" input-group">
                                        <input v-model="entry.levy_money_text"
                                        :disabled="isDisabled || isCompleted || entry.levy_money != 2"
                                        class="form-control col-5" type="number" value="" data-id="A1310-92">
                                        <div class="input-group-append">
                                            <div class="input-group-text">円</div>
                                        </div>
                                    </div> -->
                                    <template>
                                      <currency-input v-model="entry.levy_money_text"
                                        :disabled="isDisabled || isCompleted || entry.levy_money != 2"
                                        :currency="null" :precision="{min: 0, max: 9}" :allow-negative="false"
                                        class="form-control input-money col-5" value="" data-id="A1310-92"
                                        data-parsley-decimal-maxlength="[12,0]" data-parsley-trigger="change focusout" data-parsley-no-focus/>
                                    </template>
                                    <div class="input-group-append">
                                        <div class="input-group-text">円</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mt-1">
                    <label class="col-3 col-form-label" style="font-weight: normal;" for="">建築等の制限</label>
                    <div class="col-9">
                        <div class="form-check form-check-inline icheck-cyan">
                            <input v-model="entry.architectural_restrictions" :disabled="isDisabled || isCompleted"
                            class="form-check-input" type="radio" id="architectural_restrictions_1" value="1" data-id="A1310-93">
                            <label class="form-check-label" for="architectural_restrictions_1">有</label>
                        </div>
                        <div class="form-check form-check-inline icheck-cyan">
                            <input v-model="entry.architectural_restrictions" :disabled="isDisabled || isCompleted"
                            class="form-check-input" type="radio" id="architectural_restrictions_2" value="2">
                            <label class="form-check-label" for="architectural_restrictions_2">無</label>
                        </div>

                    </div>
                </div>
                <div class="sub-label">備考</div>
                <textarea v-model="entry.other_legal_restrictions_text_a" :disabled="isDisabled || isCompleted"
                data-parsley-trigger="keyup" data-parsley-maxlength="4096"
                class="form-control" id="" data-id="A1310-94"></textarea>
            </template>

            <div class="sub-label mt-2">制限対象法律</div>
            <div class="row form-group mt-1" style="margin-bottom: 0px;">
                <div class="col-4">
                    <div class="form-check icheck-cyan">
                        <input v-model="entry.restricted_law_9" :disabled="isDisabled || isCompleted"
                        class="form-check-input" type="checkbox" id="restricted_law_9" value="1" data-id="A1310-95">
                        <label class="form-check-label" for="restricted_law_9">9.被災地街区復興特別措置法</label>
                    </div>
                </div>
                <div class="col-4">
                    <div class="form-check icheck-cyan">
                        <input v-model="entry.restricted_law_16" :disabled="isDisabled || isCompleted"
                        class="form-check-input" type="checkbox" id="restricted_law_16" value="2" data-id="A1310-96" checked="checked">
                        <label class="form-check-label" for="restricted_law_16">16.都市開発法</label>
                    </div>
                </div>
                <div class="col-4">
                    <div class="form-check icheck-cyan">
                        <input v-model="entry.restricted_law_21" :disabled="isDisabled || isCompleted"
                        class="form-check-input" type="checkbox" id="restricted_law_21" value="3" data-id="A1310-97">
                        <label class="form-check-label" for="restricted_law_21">21.港湾法</label>
                    </div>
                </div>
            </div>
            <div class="row form-group" style="margin-bottom: 0px;">
                <div class="col-4">
                    <div class="form-check icheck-cyan">
                        <input v-model="entry.restricted_law_33" :disabled="isDisabled || isCompleted"
                        class="form-check-input" type="checkbox" id="restricted_law_33" value="1" data-id="A1310-98">
                        <label class="form-check-label" for="restricted_law_33">33.河川法</label>
                    </div>
                </div>
                <div class="col-4">
                    <div class="form-check icheck-cyan">
                        <input v-model="entry.restricted_law_35" :disabled="isDisabled || isCompleted"
                        class="form-check-input" type="checkbox" id="restricted_law_35" value="2" data-id="A1310-99" checked="checked">
                        <label class="form-check-label" for="restricted_law_35">35.海岸法</label>
                    </div>
                </div>
                <div class="col-4">
                    <div class="form-check icheck-cyan">
                        <input v-model="entry.restricted_law_36" :disabled="isDisabled || isCompleted"
                        class="form-check-input" type="checkbox" id="restricted_law_36" value="3" data-id="A1310-100">
                        <label class="form-check-label" for="restricted_law_36">36.津波防災地域づくりに関する法律</label>
                    </div>
                </div>
            </div>
            <div class="row form-group" style="margin-bottom: 0px;">
                <div class="col-4">
                    <div class="form-check icheck-cyan">
                        <input v-model="entry.restricted_law_42" :disabled="isDisabled || isCompleted"
                        class="form-check-input" type="checkbox" id="restricted_law_42" value="1" data-id="A1310-101">
                        <label class="form-check-label" for="restricted_law_42">42.道路法</label>
                    </div>
                </div>
                <div class="col-4">
                    <div class="form-check icheck-cyan">
                        <input v-model="entry.restricted_law_46" :disabled="isDisabled || isCompleted"
                        class="form-check-input" type="checkbox" id="restricted_law_46" value="2" data-id="A1310-102" checked="checked">
                        <label class="form-check-label" for="restricted_law_46">46.航空法</label>
                    </div>
                </div>
                <div class="col-4">
                    <div class="form-check icheck-cyan">
                        <input v-model="entry.restricted_law_47" :disabled="isDisabled || isCompleted"
                        class="form-check-input" type="checkbox" id="restricted_law_47" value="3" data-id="A1310-103">
                        <label class="form-check-label" for="restricted_law_47">47.国土利用計画法</label>
                    </div>
                </div>
            </div>
            <div class="row form-group" style="margin-bottom: 0px;">
                <div class="col-4">
                    <div class="form-check icheck-cyan">
                        <input v-model="entry.restricted_law_49" :disabled="isDisabled || isCompleted"
                        class="form-check-input" type="checkbox" id="restricted_law_49" value="1" data-id="A1310-104">
                        <label class="form-check-label" for="restricted_law_49">49.土壌汚染対策法</label>
                    </div>
                </div>
                <div class="col-4">
                    <div class="form-check icheck-cyan">
                        <input v-model="entry.restricted_law_50" :disabled="isDisabled || isCompleted"
                        class="form-check-input" type="checkbox" id="restricted_law_50" value="2" data-id="A1310-105" checked="checked">
                        <label class="form-check-label" for="restricted_law_50">50.都市再生特別措置法</label>
                    </div>
                </div>
                <div class="col-4">
                    <div class="form-check icheck-cyan">
                        <input v-model="entry.restricted_law_51" :disabled="isDisabled || isCompleted"
                        class="form-check-input" type="checkbox" id="restricted_law_51" value="3" data-id="A1310-106">
                        <label class="form-check-label" for="restricted_law_51">51.地域再生法</label>
                    </div>
                </div>
            </div>
            <div class="row form-group" style="margin-bottom: 0px;">
                <div class="col-4">
                    <div class="form-check icheck-cyan">
                        <input v-model="entry.restricted_law_54" :disabled="isDisabled || isCompleted"
                        class="form-check-input" type="checkbox" id="restricted_law_54" value="1" data-id="A1310-107">
                        <label class="form-check-label" for="restricted_law_54">54.東日本大震災復興特別区域法</label>
                    </div>
                </div>
                <div class="col-4">
                    <div class="form-check icheck-cyan">
                        <input v-model="entry.restricted_law_55" :disabled="isDisabled || isCompleted"
                        class="form-check-input" type="checkbox" id="restricted_law_55" value="1" data-id="A1310-108">
                        <label class="form-check-label" for="restricted_law_55">55.大規模災害からの復興に関する法律</label>
                    </div>
                </div>
                <div class="col-4">
                </div>
            </div>
            <div class="sub-label">備考2</div>
            <textarea v-model="entry.other_legal_restrictions_text_b" :disabled="isDisabled || isCompleted"
            data-parsley-trigger="keyup" data-parsley-maxlength="4096"
            class="form-control" id="" data-id="A1310-109">※別添説明資料参照</textarea>
        </div>
    </div>
</script>
