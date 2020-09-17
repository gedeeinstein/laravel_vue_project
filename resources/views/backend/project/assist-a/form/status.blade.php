<!-- start - status check -->
<div class="col-auto form-text">
    <fieldset :disabled="!initial.editable">
        <div class="form-check icheck-cyan d-inline">
            <input v-model="stat_check.status" class="form-check-input" type="radio" name="check_status"
                id="check_status1" :value="1">
            <label class="form-check-label" for="check_status1">完</label>
        </div>
        <div class="form-check icheck-cyan d-inline">
            <input v-model="stat_check.status" class="form-check-input" type="radio" name="check_status"
                id="check_status2" :value="2">
            <label class="form-check-label" for="check_status2">未</label>
        </div>
    </fieldset>
</div>
<!-- end - status check -->

<!-- start - status memo -->
<div class="col-auto form-text">
    <span class="">未完メモ：</span>
</div>
<div class="col-6">
    <input v-model="stat_check.memo" class="form-control form-control-sm" type="text"
           placeholder="未完となっている項目や理由を記入してください"
           :disabled="!initial.editable">
</div>
<!-- start - status memo -->