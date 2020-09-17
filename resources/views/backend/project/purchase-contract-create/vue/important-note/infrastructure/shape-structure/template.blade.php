<script type="text/x-template" id="important-note-shape-structure">
    <div class="form-group row">
        <label for="" class="col-3 col-form-label" style="font-weight: normal;">宅地造成工事完了時における形状・構造等</label>
        <div class="col-9">
            <div class="form-check form-check-inline icheck-cyan">
                <input v-model="entry.shape_structure" :disabled="isDisabled || isCompleted"
                class="form-check-input" type="radio" id="shape_structure_1" value="1" data-id="A1311-57">
                <label class="form-check-label" for="shape_structure_1">未完成に該当</label>
            </div>
            <div class="form-check form-check-inline icheck-cyan">
                <input v-model="entry.shape_structure" :disabled="isDisabled || isCompleted"
                class="form-check-input" type="radio" id="shape_structure_2" value="2">
                <label class="form-check-label" for="shape_structure_2">未完成に非該当</label>
            </div>
        </div>
    </div>
    <hr>
</script>
