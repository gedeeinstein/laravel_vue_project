<div class="row">
    <div class="col-6">
        <div class="form-group row">
            <label for="transportation" class="col-3 col-form-label">交通機関</label>
            <div class="col-9">
                <!-- start - transportation -->
                <div class="row">
                    <select v-model="property.transportation"
                            id="transportation"
                            class="form-control col-8"
                            :disabled="!initial.editable">
                        <option value="0" selected="selected"></option>
                        <option value="1">バス</option>
                        <option value="2">JR</option>
                        <option value="3">地下鉄東西線</option>
                        <option value="4">地下鉄南北線</option>
                    </select>
                </div>
                <!-- end - transportation -->

                <!-- start - transportation_station -->
                <div class="row">
                    <div class="form-inline row mt-2 input-group">
                        <div class="col-3">
                            <label for="transportation_station" class="col-form-label">駅</label>
                        </div>
                        <input v-model="property.transportation_station"
                               id="transportation_station"
                               class="form-control col-5" type="text"
                               :disabled="!initial.editable">
                    </div>
                </div>
                <!-- end - transportation_station -->

                <!-- start - transportation_time -->
                <div class="row">
                    <div class="form-inline row mt-2 input-group input-decimal">
                        <label for="transportation_time" class="col-3 col-form-label">距離</label>
                        <template>
                            <currency-input v-model="property.transportation_time"
                                id="transportation_time"
                                class="form-control col-5"
                                :currency="null"
                                :precision="{ min: 0, max: 4 }"
                                :allow-negative="false"
                                :disabled="!initial.editable" />
                        </template>
                        <div class="input-group-append">
                            <div class="input-group-text">m</div>
                        </div>
                    </div>
                </div>
                <!-- end - transportation_time -->

                <!-- start - transportation_time_calc -->
                <div class="row">
                    <div class="form-inline row mt-2 input-group">
                        <label for="transportation_time_calc" class="col-3 col-form-label">時間(徒歩)</label>
                        <input :value="transportation_time_calc"
                               id="transportation_time_calc"
                               class="form-control col-5" type="text"
                               readonly="readonly" :disabled="!initial.editable">
                        <div class="input-group-append">
                            <div class="input-group-text">分</div>
                        </div>
                    </div>
                </div>
                <!-- end - transportation_time_calc -->
            </div>
        </div>

        <!-- start - basic_fire_protection -->
        <div class="form-group row">
            <label for="basic_fire_protection" class="col-3 col-form-label">基本防火指定</label>
            <select v-model="property.basic_fire_protection"
                    id="basic_fire_protection"
                    class="form-control col-5"
                    :disabled="!initial.editable">
                <option value="0" selected="selected"></option>
                <option value="1">防火地域</option>
                <option value="2">準防火地域</option>
                <option value="3">法22条区域</option>
                <option value="4">指定なし</option>
            </select>
        </div>
        <!-- end - basic_fire_protection -->

        <!-- start - height_district -->
        <div class="form-group row">
            <label for="height_district" class="col-3 col-form-label">高度地域</label>
            <select v-model="property.height_district"
                    id="height_district"
                    class="form-control col-5"
                    :disabled="!initial.editable">
                <option value="0" selected="selected"></option>
                <option value="1">第１種高度地区</option>
                <option value="2">第２種高度地区</option>
                <option value="3">第３種高度地区</option>
                <option value="4">第４種高度地区</option>
                <option value="5">最低限高度地区</option>
                <option value="6">無</option>
            </select>
        </div>
        <!-- start - height_district -->

        <!-- start - height_district_use -->
        <div class="form-group row">
            <label for="height_district_use" class="col-3 col-form-label">高度利用地区</label>
            <input v-model="property.height_district_use"
                   id="height_district_use"
                   class="form-control col-5" type="text"
                   :disabled="!initial.editable">
        </div>
        <!-- start - height_district_use -->

        <div class="form-group row">
            <label for="restriction_extra" class="col-3 col-form-label">その他制限</label>
            <div class="col-9">
                <!-- start - property_restriction -->
                <template v-for="(restriction, index) in property_restrictions">
                    <div v-if="!restriction.deleted" class="row d-flex align-items-center">
                        <select v-model="restriction.restriction_id"
                                class="form-control col-11 mb-2"
                                :disabled="!initial.editable">
                            <option value="0" selected="selected"></option>
                            <option value="1">屋外広告物条例　第二種許可地域</option>
                            <option value="2">景観地区および景観計画</option>
                            <option value="3">駐車場附置義務条例</option>
                        </select>
                        <template v-if="initial.editable">
                            <i v-if="index == 0" @click="addPropertyRestrictions" class="fa fa-plus-circle cur-pointer text-primary ml-2 align-bottom"
                            data-toggle="tooltip" title="行を追加" data-original-title="行を追加"></i>
                            <i v-else @click="removePropertyRestrictions(index)" class="fa fa-minus-circle cur-pointer text-danger ml-2 align-bottom"
                            data-toggle="tooltip" title="" data-original-title=""></i>
                        </template>
                    </div>
                </template>
                <!-- end - property_restriction -->

                <!-- start - restriction_extra -->
                <div class="row">
                    <input v-model="property.restriction_extra"
                           id="restriction_extra"
                           class="form-control col-11" type="text"
                           placeholder="自由入力"
                           :disabled="!initial.editable">
                </div>
                <!-- start - restriction_extra -->
            </div>
        </div>
    </div>

    <div class="col-6 px-5">
        <div class="form-group row">
            <label for="property_basic" class="col-3 col-form-label">基本危機管理</label>
            <div class="col-9">
                <!-- start - basic_cultural_property_reserves -->
                <div class="row">
                    <div class="col-2">
                        <label for="basic_cultural_property_reserves" class="col-form-label">
                            <span class="sticon-haniwa s9" title="文化財埋蔵（基本）"></span>
                        </label>
                    </div>
                    <select v-model="property.basic_cultural_property_reserves"
                            class="form-control col-4"
                            :disabled="!initial.editable">
                        <option value="3">未確認</option>
                        <option value="2">NO</option>
                        <option value="1">YES</option>
                    </select>
                    <input v-model="property.basic_cultural_property_reserves_name"
                           v-if="property.basic_cultural_property_reserves == 1"
                           class="form-control col-5 ml-1" type="text"
                           placeholder="遺跡名"
                           :disabled="!initial.editable">
                </div>
                <!-- end - basic_cultural_property_reserves -->

                <!-- start - basic_district_planning -->
                <div class="row">
                    <div class="col-2">
                        <label for="basic_district_planning" class="col-form-label">
                            <span class="sticon-district_planning s9" title="地区計画（基本）"></span>
                        </label>
                    </div>
                    <select v-model="property.basic_district_planning"
                            class="form-control col-4"
                            :disabled="!initial.editable">
                        <option value="3">未確認</option>
                        <option value="2">NO</option>
                        <option value="1">YES</option>
                    </select>
                    <input v-model="property.basic_district_planning_name"
                           v-if="property.basic_district_planning == 1"
                           class="form-control col-5 ml-1" type="text"
                           placeholder="地区名"
                           :disabled="!initial.editable">
                </div>
                <!-- end - basic_district_planning -->

                <!-- start - basic_scenic_area -->
                <div class="row">
                    <div class="col-2">
                        <label for="basic_scenic_area" class="col-form-label">
                            <span class="sticon-wind s9" title="風致地区（基本）"></span>
                        </label>
                    </div>
                    <select v-model="property.basic_scenic_area"
                            class="form-control col-4"
                            :disabled="!initial.editable">
                        <option value="3">未確認</option>
                        <option value="2">NO</option>
                        <option value="1">YES</option>
                    </select>
                </div>
                <!-- end - basic_scenic_area -->

                <!-- start - basic_landslide -->
                <div class="row">
                    <div class="col-2">
                        <label for="basic_landslide" class="col-form-label">
                            <span class="sticon-landslide s9" title="地滑り（基本）"></span>
                        </label>
                    </div>
                    <select v-model="property.basic_landslide"
                            class="form-control col-4"
                            :disabled="!initial.editable">
                        <option value="3">未確認</option>
                        <option value="2">NO</option>
                        <option value="1">YES</option>
                    </select>
                </div>
                <!-- end - basic_landslide -->

                <!-- start - basic_residential_land_development -->
                <div class="row">
                    <div class="col-2">
                        <label for="basic_residential_land_development" class="col-form-label">
                            <span class="sticon-residential_land_development s9" title="宅地造成区域法（基本）"></span>
                        </label>
                    </div>
                    <select v-model="property.basic_residential_land_development"
                            class="form-control col-4"
                            :disabled="!initial.editable">
                        <option value="3">未確認</option>
                        <option value="2">NO</option>
                        <option value="1">YES</option>
                    </select>
                </div>
                <!-- end - basic_residential_land_development -->
            </div>
        </div>
    </div>
</div>
