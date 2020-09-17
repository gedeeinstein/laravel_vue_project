<script type="text/x-template" id="important-note-survey-status">
    <div v-if="building_kind">
        <div class="form-group row">
            <label for="" class="col-3 col-form-label" style="font-weight: normal;">建物状況調査</label>
            <div class="col-9">
                <div class="sub-label">建物状況調査の実施の有無（１年以内に実施している場合）</div>
                <div class="form-check form-check-inline icheck-cyan">
                    <input v-model="entry.survey_status_implementation" :disabled="isDisabled || isCompleted"
                    class="form-check-input" type="radio" id="survey_status_implementation_1" value="1" data-id="A1311-61">
                    <label class="form-check-label" for="survey_status_implementation_1">有</label>
                </div>
                <div class="form-check form-check-inline icheck-cyan">
                    <input v-model="entry.survey_status_implementation" :disabled="isDisabled || isCompleted"
                    class="form-check-input" type="radio" id="survey_status_implementation_2" value="2">
                    <label class="form-check-label" for="survey_status_implementation_2">無</label>
                </div>

                <div class="sub-label">建物状況調査の結果の概要</div>
                <textarea v-model="entry.survey_status_results"
                :disabled="isDisabled || isCompleted || entry.survey_status_implementation != 1"
                data-parsley-trigger="keyup" data-parsley-maxlength="4096"
                class="form-control" id="" data-id="A1311-62"></textarea>
            </div>
        </div>
        <hr>
    </div>
</script>
