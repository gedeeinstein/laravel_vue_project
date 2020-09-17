<script type="text/x-template" id="important-note-drainage-remark">
    <div class="form-group row">
        <label for="" class="col-3 col-form-label" style="font-weight: normal;">給排水施設の備考</label>
        <div class="col-9">
            <textarea v-model="entry.water_supply_and_drainage_remarks" :disabled="isDisabled || isCompleted"
            data-parsley-trigger="keyup" data-parsley-maxlength="4096"
            class="form-control" id="" data-id="A1311-56"></textarea>
        </div>
    </div>
    <hr>
</script>
