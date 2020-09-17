<script type="text/x-template" id="important-note-miscellaneous-water">
    <div class="form-group row">
        <label for="" class="col-3 col-form-label" style="font-weight: normal;">雑配水</label>
        <div class="col-9">
            <div class="row mb-1">
                <div class="col-4">直ちに利用可能な施設</div>
                <div class="col-8">
                    <div class="form-check form-check-inline icheck-cyan">
                        <input v-model="entry.miscellaneous_water_facilities" :disabled="isDisabled || isCompleted"
                        class="form-check-input" type="radio" id="miscellaneous_water_facilities_1" value="1" data-id="A1311-40">
                        <label class="form-check-label" for="miscellaneous_water_facilities_1">公共下水</label>
                    </div>
                    <div class="form-check form-check-inline icheck-cyan">
                        <input v-model="entry.miscellaneous_water_facilities" :disabled="isDisabled || isCompleted"
                        class="form-check-input" type="radio" id="miscellaneous_water_facilities_2" value="2">
                        <label class="form-check-label" for="miscellaneous_water_facilities_2">個別浄化槽</label>
                    </div>
                    <div class="form-check form-check-inline icheck-cyan">
                        <input v-model="entry.miscellaneous_water_facilities" :disabled="isDisabled || isCompleted"
                        class="form-check-input" type="radio" id="miscellaneous_water_facilities_3" value="3">
                        <label class="form-check-label" for="miscellaneous_water_facilities_3">集中浄化槽</label>
                    </div>
                    <div class="form-check form-check-inline icheck-cyan">
                        <input v-model="entry.miscellaneous_water_facilities" :disabled="isDisabled || isCompleted"
                        class="form-check-input" type="radio" id="miscellaneous_water_facilities_4" value="4">
                        <label class="form-check-label" for="miscellaneous_water_facilities_4">側溝等</label>
                    </div>
                    <div class="form-check form-check-inline icheck-cyan">
                        <input v-model="entry.miscellaneous_water_facilities" :disabled="isDisabled || isCompleted"
                        class="form-check-input" type="radio" id="miscellaneous_water_facilities_5" value="5">
                        <label class="form-check-label" for="miscellaneous_water_facilities_5">浸透式</label>
                    </div>
                </div>
            </div>
            <div class="row mt-2">
                <div class="col-4">前面道路配管</div>
                <div class="col-8 form-inline">
                    <div class="form-check form-check-inline icheck-cyan">
                        <input v-model="entry.miscellaneous_water_front_road_piping" :disabled="isDisabled || isCompleted"
                        class="form-check-input" type="radio" id="miscellaneous_water_front_road_piping_1" value="1" data-id="A1311-2">
                        <label class="form-check-label" for="miscellaneous_water_front_road_piping_1">有　口径</label>
                    </div>
                    <div class="input-group">
                        <input v-model="entry.miscellaneous_water_front_road_piping_text"
                        :disabled="isDisabled || isCompleted || entry.miscellaneous_water_front_road_piping != 1"
                        data-parsley-trigger="keyup" data-parsley-maxlength="128"
                        class="form-control col-2 ml-2" type="text" value="" data-id="A1311-3">
                        <div class="input-group-append">
                            <div class="input-group-text">mm</div>
                        </div>
                    </div>
                    <div class="form-check form-check-inline icheck-cyan">
                        <input v-model="entry.miscellaneous_water_front_road_piping" :disabled="isDisabled || isCompleted"
                        class="form-check-input" type="radio" id="miscellaneous_water_front_road_piping_2" value="2">
                        <label class="form-check-label" for="miscellaneous_water_front_road_piping_2">無</label>
                    </div>
                </div>
            </div>
            <div class="row mt-2">
                <div class="col-4">敷地内引込管</div>
                <div class="col-8 form-inline">
                    <div class="form-check form-check-inline icheck-cyan">
                        <input v-model="entry.miscellaneous_water_on_site_service_pipe" :disabled="isDisabled || isCompleted"
                        class="form-check-input" type="radio" id="miscellaneous_water_on_site_service_pipe_1" value="1" data-id="A1311-4">
                        <label class="form-check-label" for="miscellaneous_water_on_site_service_pipe_1">有　口径</label>
                    </div>
                    <div class="input-group">
                        <input v-model="entry.miscellaneous_water_on_site_service_pipe_text"
                        :disabled="isDisabled || isCompleted || entry.miscellaneous_water_on_site_service_pipe != 1"
                        data-parsley-trigger="keyup" data-parsley-maxlength="128"
                        class="form-control col-2 ml-2" type="text" value="" data-id="A1311-5">
                        <div class="input-group-append">
                            <div class="input-group-text">mm</div>
                        </div>
                    </div>
                    <div class="form-check form-check-inline icheck-cyan">
                        <input v-model="entry.miscellaneous_water_on_site_service_pipe" :disabled="isDisabled || isCompleted"
                        class="form-check-input" type="radio" id="miscellaneous_water_on_site_service_pipe_2" value="2">
                        <label class="form-check-label" for="miscellaneous_water_on_site_service_pipe_2">無</label>
                    </div>
                </div>
            </div>
            <div class="row mt-2">
                <div class="col-4">施設整備予定</div>
                <div class="col-8 form-inline">
                    <div class="form-check form-check-inline icheck-cyan">
                        <input v-model="entry.miscellaneous_water_schedule" :disabled="isDisabled || isCompleted"
                        class="form-check-input" type="radio" id="miscellaneous_water_schedule_1" value="1" data-id="A1311-45">
                        <label class="form-check-label" for="miscellaneous_water_schedule_1">無</label>
                    </div>
                    <div class="form-check form-check-inline icheck-cyan">
                        <input v-model="entry.miscellaneous_water_schedule" :disabled="isDisabled || isCompleted"
                        class="form-check-input" type="radio" id="miscellaneous_water_schedule_2" value="2">
                        <label class="form-check-label" for="miscellaneous_water_schedule_2">有</label>
                    </div>
                    <input v-model="entry.miscellaneous_water_schedule_year"
                    :disabled="isDisabled || isCompleted || entry.miscellaneous_water_schedule != 2"
                    class="form-control input-integer col-2 ml-2" type="number" value="" data-id="A1311-46" placeholder="西暦">
                    <div class="">年</div>
                    <input v-model="entry.miscellaneous_water_schedule_month"
                    :disabled="isDisabled || isCompleted || entry.miscellaneous_water_schedule != 2"
                    class="form-control input-integer col-1 ml-2" type="number" value="" data-id="A1311-47">
                    <div class="">月頃</div>
                </div>
            </div>
            <div class="row mt-2">
                <div class="col-4">負担金</div>
                <div class="col-8 form-inline">
                    <div class="input-group">
                        <!-- <input v-model="entry.miscellaneous_water_charge"
                        :disabled="isDisabled || isCompleted"
                        class="form-control col-5" type="number" data-id="A1311-48">
                        <div class="input-group-append">
                            <div class="input-group-text">円</div>
                        </div> -->
                        <template>
                          <currency-input v-model="entry.miscellaneous_water_charge"
                            :disabled="isDisabled || isCompleted"
                            :currency="null" :precision="{min: 0, max: 9}" :allow-negative="false"
                            class="form-control input-money col-5" value="" data-id="A1311-48"
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
