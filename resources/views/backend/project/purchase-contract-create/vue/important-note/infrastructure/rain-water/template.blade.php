<script type="text/x-template" id="important-note-rain-water">
    <div class="form-group row">
        <label for="" class="col-3 col-form-label" style="font-weight: normal;">雨水</label>
        <div class="col-9">
            <div class="row mb-1">
                <div class="col-4">直ちに利用可能な施設</div>
                <div class="col-8">
                    <div class="form-check form-check-inline icheck-cyan">
                        <input v-model="entry.rain_water_facilities" :disabled="isDisabled || isCompleted"
                        class="form-check-input" type="radio" id="rain_water_facilities_1" value="1" data-id="A1311-50">
                        <label class="form-check-label" for="rain_water_facilities_1">公共下水</label>
                    </div>
                    <div class="form-check form-check-inline icheck-cyan">
                        <input v-model="entry.rain_water_facilities" :disabled="isDisabled || isCompleted"
                        class="form-check-input" type="radio" id="rain_water_facilities_2" value="2">
                        <label class="form-check-label" for="rain_water_facilities_2">側溝等</label>
                    </div>
                    <div class="form-check form-check-inline icheck-cyan">
                        <input v-model="entry.rain_water_facilities" :disabled="isDisabled || isCompleted"
                        class="form-check-input" type="radio" id="rain_water_facilities_3" value="3">
                        <label class="form-check-label" for="rain_water_facilities_3">浸透式</label>
                    </div>
                </div>
            </div>
            <div class="row mb-1">
                <div class="col-4">下水の排除方式</div>
                <div class="col-8">
                    <div class="form-check form-check-inline icheck-cyan">
                        <input v-model="entry.rain_water_exclusion" :disabled="isDisabled || isCompleted"
                        class="form-check-input" type="radio" id="rain_water_exclusion_1" value="1" data-id="A1311-51">
                        <label class="form-check-label" for="rain_water_exclusion_1">合流式</label>
                    </div>
                    <div class="form-check form-check-inline icheck-cyan">
                        <input v-model="entry.rain_water_exclusion" :disabled="isDisabled || isCompleted"
                        class="form-check-input" type="radio" id="rain_water_exclusion_2" value="2">
                        <label class="form-check-label" for="rain_water_exclusion_2">分流式</label>
                    </div>
                </div>
            </div>
            <div class="row mb-1">
                <div class="col-4">施設整備予定</div>
                <div class="col-8 form-inline">
                    <div class="form-check form-check-inline icheck-cyan">
                        <input v-model="entry.rain_water_schedule" :disabled="isDisabled || isCompleted"
                        class="form-check-input" type="radio" id="rain_water_schedule_1" value="1" data-id="A1311-52">
                        <label class="form-check-label" for="rain_water_schedule_1">無</label>
                    </div>
                    <div class="form-check form-check-inline icheck-cyan">
                        <input v-model="entry.rain_water_schedule" :disabled="isDisabled || isCompleted"
                        class="form-check-input" type="radio" id="rain_water_schedule_2" value="2">
                        <label class="form-check-label" for="rain_water_schedule_2">有</label>
                    </div>
                    <input v-model="entry.rain_water_schedule_year"
                    :disabled="isDisabled || isCompleted || entry.rain_water_schedule != 2"
                    class="form-control input-integer col-2 ml-2" type="number" value="" data-id="A1311-53" placeholder="西暦">
                    <div class="">年</div>
                    <input v-model="entry.rain_water_schedule_month"
                    :disabled="isDisabled || isCompleted || entry.rain_water_schedule != 2"
                    class="form-control input-integer col-1 ml-2"
                    type="number" value="" data-id="A1311-54">
                    <div class="">月頃</div>

                </div>
            </div>
            <div class="row mb-1">
                <div class="col-4">負担金</div>
                <div class="col-8 form-inline">
                    <div class="input-group">
                        <!-- <input v-model="entry.rain_water_charge"
                        :disabled="isDisabled || isCompleted"
                        class="form-control col-5" type="number" value="" data-id="A1311-55">
                        <div class="input-group-append">
                            <div class="input-group-text">円</div>
                        </div> -->
                        <template>
                          <currency-input v-model="entry.rain_water_charge"
                            :disabled="isDisabled || isCompleted"
                            :currency="null" :precision="{min: 0, max: 9}" :allow-negative="false"
                            class="form-control input-money col-5" value="" data-id="A1311-55"
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
