<script type="text/x-template" id="important-note-drinking-water">
    <div class="form-group row">
        <label for="" class="col-3 col-form-label" style="font-weight: normal;">飲用水</label>
        <div class="col-9">
            <div class="row">
                <div class="col-4">直ちに利用可能な施設</div>
                <div class="col-8">
                    <div class="form-check form-check-inline icheck-cyan">
                        <input v-model="entry.potable_water_facilities" :disabled="isDisabled || isCompleted"
                        class="form-check-input" type="radio" id="potable_water_facilities_1" value="1" data-id="A1311-1">
                        <label class="form-check-label" for="potable_water_facilities_1">公営水道</label>
                    </div>
                    <div class="form-check form-check-inline icheck-cyan">
                        <input v-model="entry.potable_water_facilities" :disabled="isDisabled || isCompleted"
                        class="form-check-input" type="radio" id="potable_water_facilities_2" value="2">
                        <label class="form-check-label" for="potable_water_facilities_2">市営水道</label>
                    </div>
                    <div class="form-check form-check-inline icheck-cyan">
                        <input v-model="entry.potable_water_facilities" :disabled="isDisabled || isCompleted"
                        class="form-check-input" type="radio" id="potable_water_facilities_3" value="3">
                        <label class="form-check-label" for="potable_water_facilities_3">井戸</label>
                    </div>
                </div>
            </div>

            <div class="row mt-2">
                <div class="col-4">前面道路配管</div>
                <div class="col-8 form-inline">
                    <div class="form-check form-check-inline icheck-cyan">
                        <input v-model="entry.potable_water_front_road_piping" :disabled="isDisabled || isCompleted"
                        class="form-check-input" type="radio" id="potable_water_front_road_piping_1" value="1" data-id="A1311-2">
                        <label class="form-check-label" for="potable_water_front_road_piping_1">有　口径</label>
                    </div>
                    <div class="input-group">
                        <input v-model="entry.potable_water_front_road_piping_text"
                        :disabled="isDisabled || isCompleted || entry.potable_water_front_road_piping != 1"
                        class="form-control input-decimal col-2 ml-2" type="number" value="" data-id="A1311-3">
                        <div class="input-group-append">
                            <div class="input-group-text">mm</div>
                        </div>
                    </div>
                    <div class="form-check form-check-inline icheck-cyan">
                        <input v-model="entry.potable_water_front_road_piping" :disabled="isDisabled || isCompleted"
                        class="form-check-input" type="radio" id="potable_water_front_road_piping_2" value="2">
                        <label class="form-check-label" for="potable_water_front_road_piping_2">無</label>
                    </div>
                </div>
            </div>
            <div class="row mt-2">
                <div class="col-4">敷地内引込管</div>
                <div class="col-8 form-inline">
                    <div class="form-check form-check-inline icheck-cyan">
                        <input v-model="entry.potable_water_on_site_service_pipe" :disabled="isDisabled || isCompleted"
                        class="form-check-input" type="radio" id="potable_water_on_site_service_pipe_1" value="1" data-id="A1311-4">
                        <label class="form-check-label" for="potable_water_on_site_service_pipe_1">有　口径</label>
                    </div>
                    <div class="input-group">
                        <input v-model="entry.potable_water_on_site_service_pipe_text"
                        :disabled="isDisabled || isCompleted || entry.potable_water_on_site_service_pipe != 1"
                        class="form-control input-decimal col-2 ml-2" type="number" value="" data-id="A1311-5">
                        <div class="input-group-append">
                            <div class="input-group-text">mm</div>
                        </div>
                    </div>
                    <div class="form-check form-check-inline icheck-cyan">
                        <input v-model="entry.potable_water_on_site_service_pipe" :disabled="isDisabled || isCompleted"
                        class="form-check-input" type="radio" id="potable_water_on_site_service_pipe_2" value="2">
                        <label class="form-check-label" for="potable_water_on_site_service_pipe_2">無</label>
                    </div>
                </div>
            </div>

            <div class="row mt-2">
                <div class="col-4">私設管</div>
                <div class="col-8">
                    <div class="form-check form-check-inline icheck-cyan">
                        <input v-model="entry.potable_water_private_pipe" :disabled="isDisabled || isCompleted"
                        class="form-check-input" type="radio" id="potable_water_private_pipe_1" value="1" data-id="A1311-6">
                        <label class="form-check-label" for="potable_water_private_pipe_1">有</label>
                    </div>
                    <div class="form-check form-check-inline icheck-cyan">
                        <input v-model="entry.potable_water_private_pipe" :disabled="isDisabled || isCompleted"
                        class="form-check-input" type="radio" id="potable_water_private_pipe_2" value="2">
                        <label class="form-check-label" for="potable_water_private_pipe_2">無</label>
                    </div>
                </div>
            </div>
            <div class="row mt-2">
                <div class="col-4">施設整備予定</div>
                <div class="col-8 form-inline">
                    <div class="form-check form-check-inline icheck-cyan">
                        <input v-model="entry.potable_water_schedule" :disabled="isDisabled || isCompleted"
                        class="form-check-input" type="radio" id="potable_water_schedule_1" value="1" data-id="A1311-7">
                        <label class="form-check-label" for="potable_water_schedule_1">無</label>
                    </div>
                    <div class="form-check form-check-inline icheck-cyan">
                        <input v-model="entry.potable_water_schedule" :disabled="isDisabled || isCompleted"
                        class="form-check-input" type="radio" id="potable_water_schedule_2" value="2">
                        <label class="form-check-label" for="potable_water_schedule_2">有</label>
                    </div>
                    <input v-model="entry.potable_water_schedule_year"
                    :disabled="isDisabled || isCompleted || entry.potable_water_schedule != 2"
                    class="form-control input-integer col-2 ml-2" type="number" value="" data-id="A1311-8" placeholder="西暦">
                    <div class="">年</div>
                    <input v-model="entry.potable_water_schedule_month"
                    :disabled="isDisabled || isCompleted || entry.potable_water_schedule != 2"
                    class="form-control input-integer col-1 ml-2" type="number" value="" data-id="A1311-9">
                    <div class="">月頃</div>
                </div>
            </div>
            <div class="row mt-2">
                <div class="col-4">加入金</div>
                <div class="col-8 form-inline">
                    <div class="input-group">
                        <!-- <input v-model="entry.potable_water_participation_fee"
                        :disabled="isDisabled || isCompleted"
                        class="form-control col-5" type="number" value="" data-id="A1311-10">
                        <div class="input-group-append">
                            <div class="input-group-text">円</div>
                        </div> -->
                        <template>
                          <currency-input v-model="entry.potable_water_participation_fee"
                            :disabled="isDisabled || isCompleted"
                            :currency="null" :precision="{min: 0, max: 9}" :allow-negative="false"
                            class="form-control input-money col-5" value="" data-id="A1311-10"
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
