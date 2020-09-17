<script type="text/x-template" id="important-note-city-planning-restriction">
    <div class="form-group row">
        <label for="" class="col-3 col-form-label" style="font-weight: normal;">都市計画に基づく制限</label>
        <div class="col-9">
            <template v-if="purchase_sale.project_urbanization_area == 5">
                <div class="sub-label">①区域区分（都市計画区域外）</div>
                <div class="form-check form-check-inline icheck-cyan">
                    <input v-model="entry.area_division" :disabled="isDisabled || isCompleted"
                    class="form-check-input" type="radio" id="area_division_1" value="1" data-id="A1310-15">
                    <label class="form-check-label" for="area_division_1">準都市計画区域の指定：有</label>
                </div>
                <div class="form-check form-check-inline icheck-cyan">
                    <input v-model="entry.area_division" :disabled="isDisabled || isCompleted"
                    class="form-check-input" type="radio" id="area_division_2" value="2">
                    <label class="form-check-label" for="area_division_2">準都市計画区域の指定：無</label>
                </div>
            </template>

            <template v-if="purchase_sale.project_urbanization_area == 2">
                <div class="sub-label">②市街化調整区域の場合開発行為・旧住宅地造成事業方の許可等</div>
                <div class="row mt-2 mb-1">
                    <label class="form-label form-text col-2" for="">既存住宅番号</label>
                    <div class="col-3">
                        <input v-model="entry.residential_land_date" :disabled="isDisabled || isCompleted"
                        data-parsley-trigger="keyup" data-parsley-maxlength="128"
                        class="form-control" type="text" value="" data-id="A1310-16">
                    </div>
                    <div class="input-group col-3">
                        <input v-model="entry.residential_land_number" :disabled="isDisabled || isCompleted"
                        data-parsley-trigger="keyup" data-parsley-maxlength="128"
                        class="form-control" data-id="A136-17" type="text" value="">
                        <div class="input-group-append">
                            <div class="input-group-text">号</div>
                        </div>
                    </div>
                </div>
                <div class="row mt-2 mb-1">
                    <label class="form-label form-text col-2" for="">許可番号</label>
                    <div class="col-3">
                        <input v-model="entry.permission_date" :disabled="isDisabled || isCompleted"
                        data-parsley-trigger="keyup" data-parsley-maxlength="128"
                        class="form-control" type="text" value="" data-id="A1310-18">
                    </div>
                    <div class="input-group col-3">
                        <input v-model="entry.permission_number" :disabled="isDisabled || isCompleted"
                        data-parsley-trigger="keyup" data-parsley-maxlength="128"
                        class="form-control" data-id="A136-19" type="text" value="">
                        <div class="input-group-append">
                            <div class="input-group-text">号</div>
                        </div>
                    </div>
                </div>
                <div class="row mt-2 mb-1">
                    <label class="form-label form-text col-2" for="">検査済番号</label>
                    <div class="col-3">
                        <input v-model="entry.inspected_date" :disabled="isDisabled || isCompleted"
                        data-parsley-trigger="keyup" data-parsley-maxlength="128"
                        class="form-control" type="text" value="" data-id="A1310-20">
                    </div>
                    <div class="input-group col-3">
                        <input v-model="entry.inspected_number" :disabled="isDisabled || isCompleted"
                        data-parsley-trigger="keyup" data-parsley-maxlength="128"
                        class="form-control" data-id="A136-21" type="text" value="">
                        <div class="input-group-append">
                            <div class="input-group-text">号</div>
                        </div>
                    </div>
                </div>
                <div class="row mt-2 mb-1">
                    <label class="form-label form-text col-2" for="">完了公告</label>
                    <div class="col-3">
                        <input v-model="entry.completion_notice_date" :disabled="isDisabled || isCompleted"
                        data-parsley-trigger="keyup" data-parsley-maxlength="128"
                        class="form-control" type="text" value="" data-id="A1310-22">
                    </div>
                    <div class="input-group col-3">
                        <input v-model="entry.completion_notice_number" :disabled="isDisabled || isCompleted"
                        data-parsley-trigger="keyup" data-parsley-maxlength="128"
                        class="form-control" data-id="A136-23" type="text" value="">
                        <div class="input-group-append">
                            <div class="input-group-text">号</div>
                        </div>
                    </div>
                </div>
            </template>

            <div class="sub-label mt-2">③都市計画施設</div>
            <div class="form-check form-check-inline icheck-cyan" style="margin-top: 0px !important;">
                <input v-model="city_planning_facility" :disabled="isDisabled || isCompleted"
                class="form-check-input" type="radio" id="city_planning_facility_1" value="1" data-id="A1310-24">
                <label class="form-check-label" for="city_planning_facility_1">有</label>
            </div>
            <div class="form-check form-check-inline icheck-cyan" style="margin-top: 0px !important;">
                <input v-model="city_planning_facility" :disabled="isDisabled || isCompleted"
                class="form-check-input" type="radio" id="city_planning_facility_2" value="2">
                <label class="form-check-label" for="city_planning_facility_2">無</label>
            </div>
            <template v-if="city_planning_facility == 1">
                <div class="sub-label mt-2">③-2 都市計画施設:有</div>
                <div class="form-check form-check-inline icheck-cyan" style="margin-top: 0px !important;">
                    <input v-model="city_planning_facility_possession" :disabled="isDisabled || isCompleted"
                    class="form-check-input" type="radio" id="city_planning_facility_possession_1" value="1" data-id="A1310-25">
                    <label class="form-check-label" for="city_planning_facility_possession_1">都市計画道路</label>
                </div>
                <div class="form-check form-check-inline icheck-cyan" style="margin-top: 0px !important;">
                    <input v-model="city_planning_facility_possession" :disabled="isDisabled || isCompleted"
                    class="form-check-input" type="radio" id="city_planning_facility_possession_2" value="2">
                    <label class="form-check-label" for="city_planning_facility_possession_2">その他の都市計画施設</label>

                    <div class="form-inline ml-2">
                        <input v-model="entry.city_planning_facility_possession_memo"
                        :disabled="isDisabled || isCompleted || city_planning_facility_possession != 2"
                        data-parsley-trigger="keyup" data-parsley-maxlength="128"
                        class="form-control" type="text" value="" data-id="A1310-26">
                    </div>
                </div>
            </template>
            <template v-if="city_planning_facility_possession == 1">
                <div class="sub-label mt-2">③-3 都市計画道路</div>
                <div class="form-check form-check-inline icheck-cyan" style="margin-top: 0px !important;">
                    <input v-model="entry.city_planning_facility_possession_road" :disabled="isDisabled || isCompleted"
                    class="form-check-input" type="radio" id="city_planning_facility_possession_road_1" value="1" data-id="A1310-27">
                    <label class="form-check-label" for="city_planning_facility_possession_road_1">計画決定</label>
                </div>
                <div class="form-check form-check-inline icheck-cyan" style="margin-top: 0px !important;">
                    <input v-model="entry.city_planning_facility_possession_road" :disabled="isDisabled || isCompleted"
                    class="form-check-input" type="radio" id="city_planning_facility_possession_road_2" value="0">
                    <label class="form-check-label" for="city_planning_facility_possession_road_2">事業決定</label>
                </div>
                <div class="form-inline mt-2">
                    <label class="form-check-label" for="">名称</label>
                    <input v-model="entry.city_planning_facility_possession_road_name" :disabled="isDisabled || isCompleted"
                    data-parsley-trigger="keyup" data-parsley-maxlength="128"
                    class="form-control col-4 ml-2" type="text" value="" data-id="A1310-28">
                    <label class="form-check-label ml-2" for="">幅員</label>
                    <div class="input-group">
                        <input v-model="entry.city_planning_facility_possession_road_widht" :disabled="isDisabled || isCompleted"
                        data-parsley-trigger="keyup" data-parsley-maxlength="128"
                        class="form-control col-4 ml-2" type="text" value="" data-id="A1310-29">
                        <div class="input-group-append">
                            <div class="input-group-text">m</div>
                        </div>
                    </div>
                </div>
            </template>
            <div class="sub-label mt-3">④市街地開発事業</div>
            <div class="form-check form-check-inline icheck-cyan" style="margin-top: 0px !important;">
                <input v-model="entry.urban_development_business" :disabled="isDisabled || isCompleted"
                class="form-check-input" type="radio" id="urban_development_business_1" value="1" data-id="A1310-30">
                <label class="form-check-label" for="urban_development_business_1">有</label>
            </div>
            <div class="form-check form-check-inline icheck-cyan" style="margin-top: 0px !important;">
                <input v-model="entry.urban_development_business" :disabled="isDisabled || isCompleted"
                class="form-check-input" type="radio" id="urban_development_business_2" value="2">
                <label class="form-check-label" for="urban_development_business_2">無</label>
                <div class="form-inline ml-2">
                    <input v-model="entry.urban_development_business_memo"
                    :disabled="isDisabled || isCompleted || entry.urban_development_business != 2"
                    data-parsley-trigger="keyup" data-parsley-maxlength="128"
                    class="form-control" type="text" value="" data-id="A1310-31">
                </div>
            </div>
            <div class="mt-1">備考</div>
            <textarea v-model="entry.registration_record_building_remarks" :disabled="isDisabled || isCompleted"
            data-parsley-trigger="keyup" data-parsley-maxlength="4096"
            class="form-control" id="" data-id="A1310-32"></textarea>
        </div>
    </div>
    <hr>
</script>
