<script type="text/x-template" id="important-note-performance-evaluation">
    <div v-if="building_kind">
        <div class="form-group row">            
            <label for="" class="col-3 col-form-label" style="font-weight: normal;">住宅性能評価</label>
            <div class="col-9">
                <div class="form-check form-check-inline icheck-cyan">
                    <input v-model="entry.performance_evaluation" :disabled="isDisabled || isCompleted"
                    class="form-check-input" type="radio" id="performance_evaluation_1" value="1" data-id="A1311-60">
                    <label class="form-check-label" for="performance_evaluation_1">なし</label>
                </div>
                <div class="form-check form-check-inline icheck-cyan">
                    <input v-model="entry.performance_evaluation" :disabled="isDisabled || isCompleted"
                    class="form-check-input" type="radio" id="performance_evaluation_2" value="2">
                    <label class="form-check-label" for="performance_evaluation_2">設計住宅性能評価書のみ</label>
                </div>
                <div class="form-check form-check-inline icheck-cyan">
                    <input v-model="entry.performance_evaluation" :disabled="isDisabled || isCompleted"
                    class="form-check-input" type="radio" id="performance_evaluation_3" value="3">
                    <label class="form-check-label" for="performance_evaluation_3">設計住宅性能評価書と建設住宅性能評価書</label>
                </div>
            </div>
        </div>
        <hr>
    </div>
</script>
