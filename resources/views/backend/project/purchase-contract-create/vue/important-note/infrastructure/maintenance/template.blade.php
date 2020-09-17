<script type="text/x-template" id="important-note-maintenance">
    <div v-if="building_kind">
        <div class="form-group row">
            <label for="" class="col-3 col-form-label" style="font-weight: normal;">建物の建築及び維持保全の状況</label>
            <div class="col-9">
                <div class="sub-label">確認の申請書および添付図書並びに確認済証</div>
                <div class="form-check form-check-inline icheck-cyan">
                    <input v-model="entry.maintenance_confirmed_certificat" :disabled="isDisabled || isCompleted"
                    class="form-check-input" type="radio" id="maintenance_confirmed_certificat_1" value="1" data-id="A1311-63">
                    <label class="form-check-label" for="maintenance_confirmed_certificat_1">有</label>
                </div>
                <div class="form-check form-check-inline icheck-cyan">
                    <input v-model="entry.maintenance_confirmed_certificat" :disabled="isDisabled || isCompleted"
                    class="form-check-input" type="radio" id="maintenance_confirmed_certificat_2" value="2">
                    <label class="form-check-label" for="maintenance_confirmed_certificat_2">無</label>
                </div>

                <div class="sub-label">検査済証（新築時のもの）</div>
                <div class="form-check form-check-inline icheck-cyan">
                    <input v-model="entry.maintenance_inspection_certificate" :disabled="isDisabled || isCompleted"
                    class="form-check-input" type="radio" id="maintenance_inspection_certificate_1" value="1" data-id="A1311-64">
                    <label class="form-check-label" for="maintenance_inspection_certificate_1">有</label>
                </div>
                <div class="form-check form-check-inline icheck-cyan">
                    <input v-model="entry.maintenance_inspection_certificate" :disabled="isDisabled || isCompleted"
                    class="form-check-input" type="radio" id="maintenance_inspection_certificate_2" value="2">
                    <label class="form-check-label" for="maintenance_inspection_certificate_2">無</label>
                </div>

                <div class="sub-label">増改築等を行った建物である場合</div>
                <div class="form-check form-check-inline icheck-cyan">
                    <input v-model="entry.maintenance_renovation" :disabled="isDisabled || isCompleted"
                    class="form-check-input" type="radio" id="maintenance_renovation_1" value="1" data-id="A1311-65">
                    <label class="form-check-label" for="maintenance_renovation_1">該当する</label>
                </div>
                <div class="form-check form-check-inline icheck-cyan">
                    <input v-model="entry.maintenance_renovation" :disabled="isDisabled || isCompleted"
                    class="form-check-input" type="radio" id="maintenance_renovation_2" value="2">
                    <label class="form-check-label" for="maintenance_renovation_2">該当しない</label>
                </div>
                <div v-if="entry.maintenance_renovation == 1"
                class="indent1 ml-4">
                    <div class="sub-label">確認の申請書および添付図書並びに確認済証（増改築等のときのもの）</div>
                    <div class="form-check form-check-inline icheck-cyan">
                        <input v-model="entry.maintenance_renovation_confirmed_certificat" :disabled="isDisabled || isCompleted"
                        class="form-check-input" type="radio" id="maintenance_renovation_confirmed_certificat_1" value="1" data-id="A1311-66">
                        <label class="form-check-label" for="maintenance_renovation_confirmed_certificat_1">有</label>
                    </div>
                    <div class="form-check form-check-inline icheck-cyan">
                        <input v-model="entry.maintenance_renovation_confirmed_certificat" :disabled="isDisabled || isCompleted"
                        class="form-check-input" type="radio" id="maintenance_renovation_confirmed_certificat_2" value="2">
                        <label class="form-check-label" for="maintenance_renovation_confirmed_certificat_2">無</label>
                    </div>

                    <div class="sub-label">検査済証（増改築時のもの）</div>
                    <div class="form-check form-check-inline icheck-cyan">
                        <input v-model="entry.maintenance_renovation_inspection_certificate" :disabled="isDisabled || isCompleted"
                        class="form-check-input" type="radio" id="maintenance_renovation_inspection_certificate_1" value="1" data-id="A1311-67">
                        <label class="form-check-label" for="maintenance_renovation_inspection_certificate_1">有</label>
                    </div>
                    <div class="form-check form-check-inline icheck-cyan">
                        <input v-model="entry.maintenance_renovation_inspection_certificate" :disabled="isDisabled || isCompleted"
                        class="form-check-input" type="radio" id="maintenance_renovation_inspection_certificate_2" value="2">
                        <label class="form-check-label" for="maintenance_renovation_inspection_certificate_2">無</label>
                    </div>
                </div>
                <div class="sub-label">※建物状況調査を実施した住宅である場合</div>
                <div class="form-check form-check-inline icheck-cyan">
                    <input v-model="entry.maintenance_building_situation_survey" :disabled="isDisabled || isCompleted"
                    class="form-check-input" type="radio" id="maintenance_building_situation_survey_1" value="1" data-id="A1311-68">
                    <label class="form-check-label" for="maintenance_building_situation_survey_1">該当する</label>
                </div>
                <div class="form-check form-check-inline icheck-cyan">
                    <input v-model="entry.maintenance_building_situation_survey" :disabled="isDisabled || isCompleted"
                    class="form-check-input" type="radio" id="maintenance_building_situation_survey_2" value="2">
                    <label class="form-check-label" for="maintenance_building_situation_survey_2">該当しない</label>
                </div>
                <div v-if="entry.maintenance_building_situation_survey == 1"
                class="indent1 ml-4">
                    <div class="sub-label">建物状況調査報告書</div>
                    <div class="form-check form-check-inline icheck-cyan">
                        <input v-model="entry.maintenance_building_situation_survey_report" :disabled="isDisabled || isCompleted"
                        class="form-check-input" type="radio" id="maintenance_building_situation_survey_report_1" value="1" data-id="A1311-69">
                        <label class="form-check-label" for="maintenance_building_situation_survey_report_1">有</label>
                    </div>
                    <div class="form-check form-check-inline icheck-cyan">
                        <input v-model="entry.maintenance_building_situation_survey_report" :disabled="isDisabled || isCompleted"
                        class="form-check-input" type="radio" id="maintenance_building_situation_survey_report_2" value="2">
                        <label class="form-check-label" for="maintenance_building_situation_survey_report_2">無</label>
                    </div>
                </div>
                <div class="sub-label">※建物住宅性能評価書（建設）を受けた住宅である場合</div>
                <div class="form-check form-check-inline icheck-cyan">
                    <input v-model="entry.maintenance_building_housing_performance_evaluation" :disabled="isDisabled || isCompleted"
                    class="form-check-input" type="radio" id="maintenance_building_housing_performance_evaluation_1" value="1" data-id="A1311-70">
                    <label class="form-check-label" for="maintenance_building_housing_performance_evaluation_1">該当する</label>
                </div>
                <div class="form-check form-check-inline icheck-cyan">
                    <input v-model="entry.maintenance_building_housing_performance_evaluation" :disabled="isDisabled || isCompleted"
                    class="form-check-input" type="radio" id="maintenance_building_housing_performance_evaluation_2" value="2">
                    <label class="form-check-label" for="maintenance_building_housing_performance_evaluation_2">該当しない</label>
                </div>
                <div v-if="entry.maintenance_building_housing_performance_evaluation == 1"
                class="indent1 ml-4">
                    <div class="sub-label">既存住宅性能評価書（現況調査・評価書）</div>
                    <div class="form-check form-check-inline icheck-cyan">
                        <input v-model="entry.maintenance_building_housing_performance_evaluation_report" :disabled="isDisabled || isCompleted"
                        class="form-check-input" type="radio" id="maintenance_building_housing_performance_evaluation_report_1" value="1" data-id="A1311-71">
                        <label class="form-check-label" for="maintenance_building_housing_performance_evaluation_report_1">有</label>
                    </div>
                    <div class="form-check form-check-inline icheck-cyan">
                        <input v-model="entry.maintenance_building_housing_performance_evaluation_report" :disabled="isDisabled || isCompleted"
                        class="form-check-input" type="radio" id="maintenance_building_housing_performance_evaluation_report_2" value="2">
                        <label class="form-check-label" for="maintenance_building_housing_performance_evaluation_report_2">無</label>
                    </div>
                </div>
                <div class="sub-label">※建築基準法第12条の規定による定期調査報告の対象である住宅の場合（不特定多数が利用する特殊建築物）</div>
                <div class="form-check form-check-inline icheck-cyan">
                    <input v-model="entry.maintenance_regular_survey_report" :disabled="isDisabled || isCompleted"
                    class="form-check-input" type="radio" id="maintenance_regular_survey_report_1" value="1" data-id="A1311-72">
                    <label class="form-check-label" for="maintenance_regular_survey_report_1">概要する</label>
                </div>
                <div class="form-check form-check-inline icheck-cyan">
                    <input v-model="entry.maintenance_regular_survey_report" :disabled="isDisabled || isCompleted"
                    class="form-check-input" type="radio" id="maintenance_regular_survey_report_2" value="2">
                    <label class="form-check-label" for="maintenance_regular_survey_report_2">該当しない</label>
                </div>
                <div v-if="entry.maintenance_regular_survey_report == 1"
                class="indent1 ml-4">
                    <div class="sub-label">①定期調査報告書（特定建築物）</div>
                    <div class="form-check form-check-inline icheck-cyan">
                        <input v-model="entry.maintenance_periodic_survey_report_a" :disabled="isDisabled || isCompleted"
                        class="form-check-input" type="radio" id="maintenance_periodic_survey_report_a_1" value="1" data-id="A1311-73">
                        <label class="form-check-label" for="maintenance_periodic_survey_report_a_1">有</label>
                    </div>
                    <div class="form-check form-check-inline icheck-cyan">
                        <input v-model="entry.maintenance_periodic_survey_report_a" :disabled="isDisabled || isCompleted"
                        class="form-check-input" type="radio" id="maintenance_periodic_survey_report_a_2" value="2">
                        <label class="form-check-label" for="maintenance_periodic_survey_report_a_2">無</label>
                    </div>

                    <div class="sub-label">②定期検索報告書（昇降機等）</div>
                    <div class="form-check form-check-inline icheck-cyan">
                        <input v-model="entry.maintenance_periodic_survey_report_b" :disabled="isDisabled || isCompleted"
                        class="form-check-input" type="radio" id="maintenance_periodic_survey_report_b_1" value="1" data-id="A1311-74">
                        <label class="form-check-label" for="maintenance_periodic_survey_report_b_1">有</label>
                    </div>
                    <div class="form-check form-check-inline icheck-cyan">
                        <input v-model="entry.maintenance_periodic_survey_report_b" :disabled="isDisabled || isCompleted"
                        class="form-check-input" type="radio" id="maintenance_periodic_survey_report_b_2" value="2">
                        <label class="form-check-label" for="maintenance_periodic_survey_report_b_2">無</label>
                    </div>

                    <div class="sub-label">③定期検索報告書（建築設備）</div>
                    <div class="form-check form-check-inline icheck-cyan">
                        <input v-model="entry.maintenance_periodic_survey_report_c" :disabled="isDisabled || isCompleted"
                        class="form-check-input" type="radio" id="maintenance_periodic_survey_report_c_1" value="1" data-id="A1311-75">
                        <label class="form-check-label" for="maintenance_periodic_survey_report_c_1">有</label>
                    </div>
                    <div class="form-check form-check-inline icheck-cyan">
                        <input v-model="entry.maintenance_periodic_survey_report_c" :disabled="isDisabled || isCompleted"
                        class="form-check-input" type="radio" id="maintenance_periodic_survey_report_c_2" value="2">
                        <label class="form-check-label" for="maintenance_periodic_survey_report_c_2">無</label>
                    </div>

                    <div class="sub-label">④定期検索報告書（防火設備）</div>
                    <div class="form-check form-check-inline icheck-cyan">
                        <input v-model="entry.maintenance_periodic_survey_report_d" :disabled="isDisabled || isCompleted"
                        class="form-check-input" type="radio" id="maintenance_periodic_survey_report_d_1" value="1" data-id="A1311-76">
                        <label class="form-check-label" for="maintenance_periodic_survey_report_d_1">有</label>
                    </div>
                    <div class="form-check form-check-inline icheck-cyan">
                        <input v-model="entry.maintenance_periodic_survey_report_d" :disabled="isDisabled || isCompleted"
                        class="form-check-input" type="radio" id="maintenance_periodic_survey_report_d_2" value="2">
                        <label class="form-check-label" for="maintenance_periodic_survey_report_d_2">無</label>
                    </div>
                </div>

                <div class="sub-label">昭和56年5月31日以前に新築の工事に着手した建物</div>
                <div class="form-check form-check-inline icheck-cyan">
                    <input v-model="entry.maintenance_construction_started_before" :disabled="isDisabled || isCompleted"
                    class="form-check-input" type="radio" id="maintenance_construction_started_before_1" value="1" data-id="A1311-77">
                    <label class="form-check-label" for="maintenance_construction_started_before_1">該当する</label>
                </div>
                <div class="form-check form-check-inline icheck-cyan">
                    <input v-model="entry.maintenance_construction_started_before" :disabled="isDisabled || isCompleted"
                    class="form-check-input" type="radio" id="maintenance_construction_started_before_2" value="2">
                    <label class="form-check-label" for="maintenance_construction_started_before_2">該当しない</label>
                </div>
                <div class="indent1 ml-4">
                    <template v-if="entry.maintenance_construction_started_before == 1">
                        <div class="sub-label">※耐震基準適合証明書</div>
                        <div class="form-check form-check-inline icheck-cyan">
                            <input v-model="entry.maintenance_construction_started_before_seismic_standard_certification" :disabled="isDisabled || isCompleted"
                            class="form-check-input" type="radio" id="maintenance_construction_started_before_seismic_standard_certification_1" value="1" data-id="A1311-78">
                            <label class="form-check-label" for="maintenance_construction_started_before_seismic_standard_certification_1">有</label>
                        </div>
                        <div class="form-check form-check-inline icheck-cyan">
                            <input v-model="entry.maintenance_construction_started_before_seismic_standard_certification" :disabled="isDisabled || isCompleted"
                            class="form-check-input" type="radio" id="maintenance_construction_started_before_seismic_standard_certification_2" value="2">
                            <label class="form-check-label" for="maintenance_construction_started_before_seismic_standard_certification_2">無</label>
                        </div>
                    </template>
                    <div class="sub-label">その他</div>
                    <div class="form-inline">
                        <div class="form-check form-check-inline icheck-cyan">
                            <input v-model="entry.maintenance_construction_started_before_sub" :disabled="isDisabled || isCompleted"
                            class="form-check-input" type="radio" id="maintenance_construction_started_before_sub_1" value="1" data-id="A1311-79">
                            <label class="form-check-label" for="maintenance_construction_started_before_sub_1">有：</label>
                        </div>
                        <div class="input-group mr-3">
                            <input v-model="entry.maintenance_construction_started_before_sub_text"
                            :disabled="isDisabled || isCompleted || entry.maintenance_construction_started_before_sub != 1"
                            data-parsley-trigger="keyup" data-parsley-maxlength="128"
                            class="form-control" type="text" value="" data-id="A1311-80">
                        </div>
                        <div class="form-check form-check-inline icheck-cyan">
                            <input v-model="entry.maintenance_construction_started_before_sub" :disabled="isDisabled || isCompleted"
                            class="form-check-input" type="radio" id="maintenance_construction_started_before_sub_2" value="2">
                            <label class="form-check-label" for="maintenance_construction_started_before_sub_2">無</label>
                        </div>
                    </div>

                </div>
                <div class="sub-label">備考</div>
                <textarea v-model="entry.maintenance_remarks" :disabled="isDisabled || isCompleted"
                data-parsley-trigger="keyup" data-parsley-maxlength="4096"
                class="form-control" data-id="A1311-81"></textarea>
            </div>
        </div>
        <hr>
    </div>
</script>
