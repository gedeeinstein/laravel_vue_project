<script type="text/x-template" id="important-note-electrical">
    <div class="form-group row">
        <label for="" class="col-3 col-form-label" style="font-weight: normal;">電気</label>
        <div class="col-9">
            <div class="row">
                <div class="col-4">小売電気事業者</div>
                <div class="col-8">
                    <div class="form-check form-check-inline icheck-cyan">
                        <input v-model="entry.electrical_retail_company" :disabled="isDisabled || isCompleted"
                        class="form-check-input" type="radio" id="electrical_retail_company_1" value="1" data-id="A1311-11">
                        <label class="form-check-label" for="electrical_retail_company_1">東北電力</label>
                    </div>
                    <div class="form-check form-check-inline icheck-cyan">
                        <input v-model="entry.electrical_retail_company" :disabled="isDisabled || isCompleted"
                        class="form-check-input" type="radio" id="electrical_retail_company_2" value="2">
                        <label class="form-check-label" for="electrical_retail_company_2">その他</label>
                    </div>
                    <div class="row">
                        <div class="col-4 col-form-label mt-2">電気事業者の名称</div>
                        <input v-model="entry.electrical_retail_company_name"
                        :disabled="isDisabled || isCompleted || entry.electrical_retail_company != 2"
                        data-parsley-trigger="keyup" data-parsley-maxlength="128"
                        class="form-control col-7" type="text" value="" data-id="A1311-12">
                    </div>
                    <div class="row">
                        <div class="col-4 col-form-label mt-2">電気事業者の住所</div>
                        <input v-model="entry.electrical_retail_company_address"
                        :disabled="isDisabled || isCompleted || entry.electrical_retail_company != 2"
                        data-parsley-trigger="keyup" data-parsley-maxlength="128"
                        class="form-control col-7" type="text" value="" data-id="A1311-13">
                    </div>
                    <div class="row">
                        <div class="col-4 col-form-label mt-2">電気事業者の連絡先</div>
                        <input v-model="entry.electrical_retail_company_contact"
                        :disabled="isDisabled || isCompleted || entry.electrical_retail_company != 2"
                        data-parsley-trigger="keyup" data-parsley-maxlength="128"
                        class="form-control col-7" type="text" value="" data-id="A1311-14">
                    </div>
                </div>
            </div>
            <div class="row mt-2">
                <div class="col-4">施設整備予定</div>
                <div class="col-8 form-inline">
                    <div class="form-check form-check-inline icheck-cyan">
                        <input v-model="entry.electrical_schedule" :disabled="isDisabled || isCompleted"
                        class="form-check-input" type="radio" id="electrical_schedule_1" value="1" data-id="A1311-15">
                        <label class="form-check-label" for="electrical_schedule_1">無</label>
                    </div>
                    <div class="form-check form-check-inline icheck-cyan">
                        <input v-model="entry.electrical_schedule" :disabled="isDisabled || isCompleted"
                        class="form-check-input" type="radio" id="electrical_schedule_2" value="2">
                        <label class="form-check-label" for="electrical_schedule_2">有</label>
                    </div>
                    <input v-model="entry.electrical_schedule_year"
                    :disabled="isDisabled || isCompleted || entry.electrical_schedule != 2"
                    class="form-control input-integer col-2 ml-2" type="number" value="" data-id="A1311-16" placeholder="西暦">
                    <div class="">年</div>
                    <input v-model="entry.electrical_schedule_month"
                     :disabled="isDisabled || isCompleted || entry.electrical_schedule != 2"
                    class="form-control input-integer col-1 ml-2" type="number" value="" data-id="A1311-17">
                    <div class="">月頃</div>

                </div>
            </div>
            <div class="row mt-2">
                <div class="col-4">負担金</div>
                <div class="col-8 form-inline">
                    <div class="input-group">
                        <!-- <input v-model="entry.electrical_charge"
                        :disabled="isDisabled || isCompleted"
                        class="form-control col-5" type="number" value="" data-id="A1311-18">
                        <div class="input-group-append">
                            <div class="input-group-text">円</div>
                        </div> -->
                        <template>
                          <currency-input v-model="entry.electrical_charge"
                            :disabled="isDisabled || isCompleted"
                            :currency="null" :precision="{min: 0, max: 9}" :allow-negative="false"
                            class="form-control input-money col-5" value="" data-id="A1311-18"
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
    <hr>
</script>
