<script type="text/x-template" id="important-note-gas">
    <div class="form-group row">
        <label for="" class="col-3 col-form-label" style="font-weight: normal;">ガス</label>
        <div class="col-9">
            <div class="row">
                <div class="col-4">直ちに利用可能な施設</div>
                <div class="col-8">
                    <div class="form-check form-check-inline icheck-cyan">
                        <input v-model="entry.gas_facilities" :disabled="isDisabled || isCompleted"
                        class="form-check-input" type="radio" id="gas_facilities_1" value="1" data-id="A1311-19">
                        <label class="form-check-label" for="gas_facilities_1">都市ガス</label>
                    </div>
                    <div class="form-check form-check-inline icheck-cyan">
                        <input v-model="entry.gas_facilities" :disabled="isDisabled || isCompleted"
                        class="form-check-input" type="radio" id="gas_facilities_2" value="2">
                        <label class="form-check-label" for="gas_facilities_2">個別プロパン</label>
                    </div>
                    <div class="form-check form-check-inline icheck-cyan">
                        <input v-model="entry.gas_facilities" :disabled="isDisabled || isCompleted"
                        class="form-check-input" type="radio"  id="gas_facilities_3"value="3">
                        <label class="form-check-label" for="gas_facilities_3">集中プロパン</label>
                    </div>
                </div>
            </div>

            <div class="row mt-2">
                <div class="col-4">前面道路配管</div>
                <div class="col-8 form-inline">
                    <div class="form-check form-check-inline icheck-cyan">
                        <input v-model="entry.gas_front_road_piping" :disabled="isDisabled || isCompleted"
                        class="form-check-input" type="radio" id="gas_front_road_piping_1" value="1" data-id="A1311-2">
                        <label class="form-check-label" for="gas_front_road_piping_1">有　口径</label>
                    </div>
                    <div class="input-group">
                        <input v-model="entry.gas_front_road_piping_text"
                        :disabled="isDisabled || isCompleted || entry.gas_front_road_piping != 1"
                        data-parsley-trigger="keyup" data-parsley-maxlength="128"
                        class="form-control col-2 ml-2" type="text" value="" data-id="A1311-3">
                        <div class="input-group-append">
                            <div class="input-group-text">mm</div>
                        </div>
                    </div>
                    <div class="form-check form-check-inline icheck-cyan">
                        <input v-model="entry.gas_front_road_piping" :disabled="isDisabled || isCompleted"
                        class="form-check-input" type="radio" id="gas_front_road_piping_2" value="2">
                        <label class="form-check-label" for="gas_front_road_piping_2">無</label>
                    </div>
                </div>
            </div>
            <div class="row mt-2">
                <div class="col-4">敷地内引込管</div>
                <div class="col-8 form-inline">
                    <div class="form-check form-check-inline icheck-cyan">
                        <input v-model="entry.gas_on_site_service_pipe" :disabled="isDisabled || isCompleted"
                        class="form-check-input" type="radio" id="gas_on_site_service_pipe_1"value="1" data-id="A1311-4">
                        <label class="form-check-label" for="gas_on_site_service_pipe_1">有　口径</label>
                    </div>
                    <div class="input-group">
                        <input v-model="entry.gas_on_site_service_pipe_text"
                        :disabled="isDisabled || isCompleted || entry.gas_on_site_service_pipe != 1"
                        data-parsley-trigger="keyup" data-parsley-maxlength="128"
                        class="form-control col-2 ml-2" type="text" value="" data-id="A1311-5">
                        <div class="input-group-append">
                            <div class="input-group-text">mm</div>
                        </div>
                    </div>
                    <div class="form-check form-check-inline icheck-cyan">
                        <input v-model="entry.gas_on_site_service_pipe" :disabled="isDisabled || isCompleted"
                        class="form-check-input" type="radio" id="gas_on_site_service_pipe_2"value="2">
                        <label class="form-check-label" for="gas_on_site_service_pipe_2">無</label>
                    </div>
                </div>
            </div>
            <div v-if="entry.gas_facilities == 2 || entry.gas_facilities == 3"
            class="row mt-2">
                <div class="col-4">私設管</div>
                <div class="col-8">
                    <div class="form-check form-check-inline icheck-cyan">
                        <input v-model="entry.gas_private_pipe" :disabled="isDisabled || isCompleted"
                        class="form-check-input" type="radio"  id="gas_private_pipe_1"value="1" data-id="A1311-24">
                        <label class="form-check-label" for="gas_private_pipe_1">有</label>
                    </div>
                    <div class="form-check form-check-inline icheck-cyan">
                        <input v-model="entry.gas_private_pipe" :disabled="isDisabled || isCompleted"
                        class="form-check-input" type="radio" id="gas_private_pipe_2" value="2">
                        <label class="form-check-label" for="gas_private_pipe_2">無</label>
                    </div>
                </div>
            </div>
            <div class="row mt-2">
                <div class="col-4">施設整備予定</div>
                <div class="col-8 form-inline">
                    <div class="form-check form-check-inline icheck-cyan">
                        <input v-model="entry.gas_schedule" :disabled="isDisabled || isCompleted"
                        class="form-check-input" type="radio" id="gas_schedule_1" value="0" data-id="A1311-25">
                        <label class="form-check-label" for="gas_schedule_1">無</label>
                    </div>
                    <div class="form-check form-check-inline icheck-cyan">
                        <input v-model="entry.gas_schedule" :disabled="isDisabled || isCompleted"
                        class="form-check-input" type="radio" id="gas_schedule_2" value="2">
                        <label class="form-check-label" for="gas_schedule_2">有</label>
                    </div>
                    <input v-model="entry.gas_schedule_year"
                    :disabled="isDisabled || isCompleted || entry.gas_schedule != 2"
                    class="form-control input-integer col-2 ml-2" type="number" value="" data-id="A1311-26" placeholder="西暦">
                    <div class="">年</div>
                    <input v-model="entry.gas_schedule_month"
                    :disabled="isDisabled || isCompleted || entry.gas_schedule != 2"
                    class="form-control input-integer col-1 ml-2" type="number" value="" data-id="A1311-27">
                    <div class="">月頃</div>

                </div>
            </div>
            <div class="row mt-2">
                <div class="col-4">負担金</div>
                <div class="col-8 form-inline">
                    <div class="input-group">
                        <!-- <input v-model="entry.gas_charge"
                        :disabled="isDisabled || isCompleted"
                        class="form-control col-5" type="number" value="" data-id="A1311-28">
                        <div class="input-group-append">
                            <div class="input-group-text">円</div>
                        </div> -->
                        <template>
                          <currency-input v-model="entry.gas_charge"
                            :disabled="isDisabled || isCompleted"
                            :currency="null" :precision="{min: 0, max: 9}" :allow-negative="false"
                            class="form-control input-money col-5" value="" data-id="A1311-28"
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
