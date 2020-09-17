<script type="text/x-template" id="important-note-other">
    <div class="card mt-2">
        <div class="card-header" style="font-weight: bold;">III その他重要な事項・IV 添付書類</div>
        <div class="card-body">
            <div class="form-group row">
                <label for="" class="col-3 col-form-label" style="font-weight: normal;">重要な事項</label>
                <div class="col-9">

                    <div class="" data-id="A1313-0">
                        <ol class="kakko">
                            <li>買主は、その責任と負担において、引渡日までに本物件において建築確認済み証を取得することをとします。買主の責に帰さない事由により、建築確認済み証を取得できない場合は、本契約を解除出来るものとします。その場合、売主は受領済みの金員を全額無利息にて返還する事と致します。</li>
                            <li>売主は、本物件の引渡日までにその責任と負担において、本物件の前面道路の他共有者全員から通行掘削同意書の署名・捺印を取得することとします。万が一、売主が取得を完了できない場合、本契約は白紙解除になるものとし、売主はその時点で受領済みの金員を、全額無利息にて買主に速やかに返還するものとします。</li>
                            <li>売主は、その責任と負担において、引渡日までに道路部分と宅地部分の分筆登記を完了させる事とします。</li>
                        </ol>
                    </div>

                    <textarea v-model="entry.important_matters_text" :disabled="isDisabled || isCompleted"
                    data-parsley-trigger="keyup" data-parsley-maxlength="4096"
                    class="form-control" id="" data-id="A1313-1" rows="10"></textarea>
                </div>
            </div>
            <div class="form-group row">
                <label for="" class="col-3 col-form-label" style="font-weight: normal;">添付資料</label>
                <div class="col-9">
                    <div class="row form-group mt-1" style="margin-bottom: 0;">
                        <div class="col-3">
                            <div class="form-check icheck-cyan">
                                <input v-model="entry.attachment_district_planning" :disabled="isDisabled || isCompleted"
                                class="form-check-input" type="checkbox" id="attachment_district_planning" value="1" data-id="A1313-4">
                                <label class="form-check-label" for="attachment_district_planning">地区計画</label>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="form-check icheck-cyan">
                                <input v-model="entry.attachment_road" :disabled="isDisabled || isCompleted"
                                class="form-check-input" type="checkbox" id="attachment_road" value="1" data-id="A1313-5">
                                <label class="form-check-label" for="attachment_road">道路位置図</label>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="form-check icheck-cyan">
                                <input v-model="entry.attachment_land_surveying_map" :disabled="isDisabled || isCompleted"
                                class="form-check-input" type="checkbox" id="attachment_land_surveying_map" value="1" data-id="A1313-6">
                                <label class="form-check-label" for="attachment_land_surveying_map">地積測量図</label>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="form-check icheck-cyan">
                                <input v-model="entry.attachment_enomoto" :disabled="isDisabled || isCompleted"
                                class="form-check-input" type="checkbox" id="attachment_enomoto" value="1" data-id="A1313-7">
                                <label class="form-check-label" for="attachment_enomoto">謄本</label>
                            </div>
                        </div>
                    </div>
                    <div class="row form-group" style="margin-bottom: 0;">
                        <div class="col-3">
                            <div class="form-check icheck-cyan">
                                <input v-model="entry.attachment_public_map" :disabled="isDisabled || isCompleted"
                                class="form-check-input" type="checkbox" id="attachment_public_map" value="1" data-id="A1313-8">
                                <label class="form-check-label" for="attachment_public_map">公図</label>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="form-check icheck-cyan">
                                <input v-model="entry.attachment_gas_map" :disabled="isDisabled || isCompleted"
                                class="form-check-input" type="checkbox" id="attachment_gas_map" value="1" data-id="A1313-9">
                                <label class="form-check-label" for="attachment_gas_map">ガス図</label>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="form-check icheck-cyan">
                                <input v-model="entry.attachment_waterworks_diagram" :disabled="isDisabled || isCompleted"
                                class="form-check-input" type="checkbox" id="attachment_waterworks_diagram" value="1" data-id="A1313-10">
                                <label class="form-check-label" for="attachment_waterworks_diagram">上水道図</label>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="form-check icheck-cyan">
                                <input v-model="entry.attachment_sewer_diagram" :disabled="isDisabled || isCompleted"
                                class="form-check-input" type="checkbox" id="attachment_sewer_diagram" value="1" data-id="A1313-11">
                                <label class="form-check-label" for="attachment_sewer_diagram">下水道図</label>
                            </div>
                        </div>
                    </div>
                    <div class="row form-group" style="margin-bottom: 0;">
                        <div class="col-3">
                            <div class="form-check icheck-cyan">
                                <input v-model="entry.attachment_city_planning" :disabled="isDisabled || isCompleted"
                                class="form-check-input" type="checkbox" id="attachment_city_planning" value="1" data-id="A1313-12">
                                <label class="form-check-label" for="attachment_city_planning">都市計画図</label>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="form-check icheck-cyan">
                                <input v-model="entry.attachment_buried_cultural_property" :disabled="isDisabled || isCompleted"
                                class="form-check-input" type="checkbox" id="attachment_buried_cultural_property" value="1" data-id="A1313-13">
                                <label class="form-check-label" for="attachment_buried_cultural_property">埋蔵文化財</label>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="form-check icheck-cyan">
                                <input v-model="entry.attachment_road_ledger" :disabled="isDisabled || isCompleted"
                                class="form-check-input" type="checkbox" id="attachment_road_ledger" value="1" data-id="A1313-14">
                                <label class="form-check-label" for="attachment_road_ledger">道路台帳</label>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="form-check icheck-cyan">
                                <input v-model="entry.attachment_property_tax_details" :disabled="isDisabled || isCompleted"
                                class="form-check-input" type="checkbox" id="attachment_property_tax_details" value="1" data-id="A1313-15">
                                <label class="form-check-label" for="attachment_property_tax_details">固定資産税明細</label>
                            </div>
                        </div>
                    </div>
                    <div class="row form-group" style="margin-bottom: 0;">
                        <div class="col-3">
                            <div class="form-check icheck-cyan">
                                <input v-model="entry.attachment_sales_contract" :disabled="isDisabled || isCompleted"
                                class="form-check-input" type="checkbox" id="attachment_sales_contract" value="1" data-id="A1313-16">
                                <label class="form-check-label" for="attachment_sales_contract">売買契約書</label>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="form-check icheck-cyan">
                                <input v-model="entry.attachment_manual_supplementary_material" :disabled="isDisabled || isCompleted"
                                class="form-check-input" type="checkbox" id="attachment_manual_supplementary_material" value="1" data-id="A1313-17">
                                <label class="form-check-label" for="attachment_manual_supplementary_material">重要事項説明書補足資料</label>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <label class="col-form-label col-2" for="">その他資料1</label>
                        <input v-model="entry.attachment_other_document_a" :disabled="isDisabled || isCompleted"
                        data-parsley-trigger="keyup" data-parsley-maxlength="128"
                        class="form-control col-9" type="text" value="" data-id="A1313-18">
                    </div>
                    <div class="row mt-2">
                        <label class="col-form-label col-2" for="">その他資料2</label>
                        <input v-model="entry.attachment_other_document_b" :disabled="isDisabled || isCompleted"
                        data-parsley-trigger="keyup" data-parsley-maxlength="128"
                        class="form-control col-9" type="text" value="" data-id="A1313-19">
                    </div>
                    <div class="row mt-2">
                        <label class="col-form-label col-2" for="">その他資料3</label>
                        <input v-model="entry.attachment_other_document_c" :disabled="isDisabled || isCompleted"
                        data-parsley-trigger="keyup" data-parsley-maxlength="128"
                        class="form-control col-9" type="text" value="" data-id="A1313-20">
                    </div>
                    <div class="row mt-2">
                        <label class="col-form-label col-2" for="">その他資料4</label>
                        <input v-model="entry.attachment_other_document_d" :disabled="isDisabled || isCompleted"
                        data-parsley-trigger="keyup" data-parsley-maxlength="128"
                        class="form-control col-9" type="text" value="" data-id="A1313-21">
                    </div>
                </div>
            </div>
            <div class="incomplete_memo form-row p-2 bg-light">
                <div class="col-auto form-text">
                    <div class="form-check form-check-inline icheck-cyan">
                        <input v-model="entry.other_important_matters_document_status"
                        class="form-check-input" type="radio" name="" id="other_important_matters_document_status_1" value="1">
                        <label class="form-check-label" for="other_important_matters_document_status_1">完</label>
                    </div>
                    <div class="form-check form-check-inline icheck-cyan">
                        <input v-model="entry.other_important_matters_document_status"
                        class="form-check-input" type="radio" name="" id="other_important_matters_document_status_2" value="2">
                        <label class="form-check-label" for="other_important_matters_document_status_2">未</label>
                    </div>
                </div>
                <div class="col-auto form-text">
                    <span class="">未完メモ：</span>
                </div>
                <div class="col-6">
                    <input v-model="entry.other_important_matters_document_memo" :disabled="isDisabled || isCompleted"
                    data-parsley-trigger="keyup" data-parsley-maxlength="128"
                    class="form-control" name="" type="text" value="" placeholder="未完となっている項目や理由を記入してください">
                </div>
            </div>
        </div>
    </div>
</script>
