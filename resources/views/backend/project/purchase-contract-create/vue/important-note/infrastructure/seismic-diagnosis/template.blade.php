<script type="text/x-template" id="important-note-seismic-diagnosis">
    <div v-if="building_kind">
        <div class="form-group row">
            <label for="" class="col-3 col-form-label" style="font-weight: normal;">耐震診断</label>
            <div class="col-9">
                <div class="sub-label">耐震診断の有無</div>
                <div class="form-check form-check-inline icheck-cyan">
                    <input v-model="entry.seismic_diagnosis_presence" :disabled="isDisabled || isCompleted"
                    class="form-check-input" type="radio" id="seismic_diagnosis_presence_1" value="1" data-id="A1311-85">
                    <label class="form-check-label" for="seismic_diagnosis_presence_1">有</label>
                </div>
                <div class="form-check form-check-inline icheck-cyan">
                    <input v-model="entry.seismic_diagnosis_presence" :disabled="isDisabled || isCompleted"
                    class="form-check-input" type="radio" id="seismic_diagnosis_presence_2" value="2">
                    <label class="form-check-label" for="seismic_diagnosis_presence_2">無</label>
                </div>
                <div class="sub-label">耐震診断の書類</div>
                <div class="form-check form-check-inline icheck-cyan">
                    <input v-model="entry.seismic_standard_certification"
                    :disabled="isDisabled || isCompleted || entry.seismic_diagnosis_presence != 1"
                    class="form-check-input" type="checkbox" id="seismic_standard_certification" value="1" data-id="A1311-86">
                    <label class="form-check-label" for="seismic_standard_certification">耐震基準適合証明書</label>
                </div>
                <div class="form-check form-check-inline icheck-cyan">
                    <input v-model="entry.seismic_diagnosis_performance_evaluation"
                    :disabled="isDisabled || isCompleted || entry.seismic_diagnosis_presence != 1"
                    class="form-check-input" type="checkbox" id="seismic_diagnosis_performance_evaluation" value="1" data-id="A1311-87">
                    <label class="form-check-label" for="seismic_diagnosis_performance_evaluation">住宅性能評価書</label>
                </div>
                <div class="form-check form-check-inline icheck-cyan">
                    <input v-model="entry.seismic_diagnosis_result"
                    :disabled="isDisabled || isCompleted || entry.seismic_diagnosis_presence != 1"
                    class="form-check-input" type="checkbox" id="seismic_diagnosis_result" value="1" data-id="A1311-88">
                    <label class="form-check-label" for="seismic_diagnosis_result">耐震診断結果</label>
                </div>
                <div class="sub-label">備考</div>
                <textarea v-model="entry.seismic_diagnosis_remarks" :disabled="isDisabled || isCompleted"
                data-parsley-trigger="keyup" data-parsley-maxlength="4096"
                class="form-control" data-id="A1311-89"></textarea>
            </div>
        </div>
        <hr>
    </div>
</script>
