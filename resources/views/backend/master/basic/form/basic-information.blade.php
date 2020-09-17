<div class="row">
    <div class="col-6">
        <div class="form-group row">
            <label for="transportation" class="col-3 col-form-label">交通機関</label>
            <div class="col-9">
                <!-- start - transportation -->
                <div class="row">
                    <select v-model="mas_basic.transportation"
                            id="transportation"
                            class="form-control col-8"
                            :disabled="!initial.editable || !initial.is_modified">
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
                        <input v-model="mas_basic.transportation_station"
                               id="transportation_station"
                               class="form-control col-5" type="text"
                               :disabled="!initial.editable || !initial.is_modified">
                    </div>
                </div>
                <!-- end - transportation_station -->

                <!-- start - transportation_time -->
                <div class="row">
                    <div class="form-inline row mt-2 input-group input-decimal">
                        <label for="transportation_time" class="col-3 col-form-label">距離</label>
                        <template>
                            <currency-input v-model="mas_basic.transportation_time"
                                id="transportation_time"
                                class="form-control col-5"
                                :currency="null"
                                :precision="{ min: 0, max: 4 }"
                                :allow-negative="false"
                                :disabled="!initial.editable || !initial.is_modified" />
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
                               readonly="readonly">
                        <div class="input-group-append">
                            <div class="input-group-text">分</div>
                        </div>
                    </div>
                </div>
                <!-- end - transportation_time_calc -->
            </div>
        </div>

        <!-- start - height_district -->
        <div class="form-group row">
            <label for="height_district" class="col-3 col-form-label">高度地域</label>
            <select v-model="mas_basic.height_district"
                    id="height_district"
                    class="form-control col-5"
                    :disabled="!initial.editable || !initial.is_modified">
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
            <input v-model="mas_basic.height_district_use"
                   id="height_district_use"
                   class="form-control col-5" type="text"
                   :disabled="!initial.editable || !initial.is_modified">
        </div>
        <!-- start - height_district_use -->

        <div class="form-group row">
            <label for="restriction_extra" class="col-3 col-form-label">その他制限</label>
            <div class="col-9">
                <!-- start - property_restriction -->
                <template v-for="(restriction, index) in mas_basic.restrictions">
                    <div v-if="!restriction.deleted" class="row d-flex align-items-center">
                        <select v-model="restriction.restriction_id"
                                class="form-control col-11 mb-2"
                                :disabled="!initial.editable || !initial.is_modified">
                            <option value="0" selected="selected"></option>
                            <option value="1">屋外広告物条例　第二種許可地域</option>
                            <option value="2">景観地区および景観計画</option>
                            <option value="3">駐車場附置義務条例</option>
                        </select>
                        <template v-if="initial.editable && initial.is_modified">
                            <i v-if="index == 0"
                            @click="addPropertyRestrictions" class="fa fa-plus-circle cur-pointer text-primary ml-2 align-bottom"
                            data-toggle="tooltip" title="行を追加" data-original-title="行を追加"></i>
                            <i v-else
                            @click="removePropertyRestrictions(index)" class="fa fa-minus-circle cur-pointer text-danger ml-2 align-bottom"
                            data-toggle="tooltip" title="" data-original-title=""></i>
                        </template>
                    </div>

                    <!-- start - restriction_extra -->
                    <div class="row mb-2">
                        <input v-model="restriction.restriction_note"
                               id="restriction_extra"
                               class="form-control col-11" type="text"
                               placeholder="自由入力"
                               :disabled="!initial.editable || !initial.is_modified">
                    </div>
                    <!-- start - restriction_extra -->
                </template>
                <!-- end - property_restriction -->

                {{-- <!-- start - restriction_extra -->
                <div class="row">
                    <input v-model="mas_basic.restriction_extra"
                           id="restriction_extra"
                           class="form-control col-11" type="text"
                           placeholder="自由入力"
                           :disabled="!initial.editable || !initial.is_modified">
                </div>
                <!-- start - restriction_extra --> --}}

            </div>
        </div>
        <!-- -start school primary -->
        <div class="form-group row">
            <!-- -start school primary -->
            <label for="" class="col-3 col-form-label">小学校</label>
            <input v-model="mas_basic.school_primary"
            class="form-control col-6" type="text"
            :disabled="!initial.editable || !initial.is_modified">

            <!-- -start school primary distance-->
            <div class="input-group col-3">
                <template>
                    <currency-input v-model="mas_basic.school_primary_distance"
                        class="form-control input-integer"
                        :currency="null"
                        :precision="{ min: 0, max: 4 }"
                        :allow-negative="false"
                        :disabled="!initial.editable || !initial.is_modified" />
                </template>
                <div class="input-group-append">
                    <div class="input-group-text">m</div>
                </div>
            </div>
        </div>
        <!-- -end school primary -->

        <!-- -start school school juniorhigh -->
        <div class="form-group row">
            <!-- -start school school juniorhigh -->
            <label for="" class="col-3 col-form-label">中学校</label>
            <input v-model="mas_basic.school_juniorhigh"
            class="form-control col-6" type="text"
            :disabled="!initial.editable || !initial.is_modified">

            <!-- -start school school juniorhigh distance -->
            <div class="input-group col-3">
                <template>
                    <currency-input v-model="mas_basic.school_juniorhigh_distance"
                        class="form-control input-integer"
                        :currency="null"
                        :precision="{ min: 0, max: 4 }"
                        :allow-negative="false"
                        :disabled="!initial.editable || !initial.is_modified" />
                </template>
                <div class="input-group-append">
                    <div class="input-group-text">m</div>
                </div>
            </div>
        </div>
        <!-- -end school school juniorhigh -->

        <!-- -start basic parcel build ratio -->
        <div class="row form-group">
            <label for="" class="col-3 col-form-label">建ぺい率</label>
            <div class="input-group col-3 pl-0 pr-1">
                <template>
                    <currency-input v-model="mas_basic.basic_parcel_build_ratio"
                        class="form-control input-decimal"
                        :disabled="!initial.editable || !initial.is_modified" :currency="null"
                        :precision="{ min: 0, max: 4 }"
                        :allow-negative="false" />
                </template>
                <div class="input-group-append">
                    <div class="input-group-text">%</div>
                </div>
            </div>
        </div>
        <!-- -end basic parcel build ratio -->

        <!-- -start basic parcel floor ratio -->
        <div class="row form-group">
            <label for="" class="col-3 col-form-label">容積率</label>
            <div class="input-group col-3 pl-0 pr-1">
                <template>
                    <currency-input v-model="mas_basic.basic_parcel_floor_ratio"
                        class="form-control input-decimal"
                        :disabled="!initial.editable || !initial.is_modified" :currency="null"
                        :precision="{ min: 0, max: 4 }"
                        :allow-negative="false" />
                </template>
                <div class="input-group-append">
                    <div class="input-group-text">%</div>
                </div>
            </div>
        </div>
        <!-- -end basic parcel floor ratio -->
    </div>

    <div class="col-6 px-5">

        <!-- start - basic_fire_protection -->
        <div class="form-group row">
            <label for="basic_fire_protection" class="col-3 col-form-label">基本防火指定</label>
            <select v-model="mas_basic.basic_fire_protection"
                    id="basic_fire_protection"
                    class="form-control col-5"
                    :disabled="!initial.editable || !initial.is_modified">
                <option value="0" selected="selected"></option>
                <option value="1">防火地域</option>
                <option value="2">準防火地域</option>
                <option value="3">法22条区域</option>
                <option value="4">指定なし</option>
            </select>
        </div>
        <!-- end - basic_fire_protection -->

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
                    <select v-model="mas_basic.basic_cultural_property_reserves"
                            class="form-control col-4"
                            :disabled="!initial.editable || !initial.is_modified">
                        <option value="3">未確認</option>
                        <option value="2">NO</option>
                        <option value="1">YES</option>
                    </select>
                    <input v-model="mas_basic.basic_cultural_property_reserves_name"
                           v-if="mas_basic.basic_cultural_property_reserves == 1"
                           class="form-control col-4 ml-1" type="text"
                           placeholder="遺跡名"
                           :disabled="!initial.editable || !initial.is_modified">
                </div>
                <!-- end - basic_cultural_property_reserves -->

                <!-- start - basic_district_planning -->
                <div class="row">
                    <div class="col-2">
                        <label for="basic_district_planning" class="col-form-label">
                            <span class="sticon-district_planning s9" title="地区計画（基本）"></span>
                        </label>
                    </div>
                    <select v-model="mas_basic.basic_district_planning"
                            class="form-control col-4"
                            :disabled="!initial.editable || !initial.is_modified">
                        <option value="3">未確認</option>
                        <option value="2">NO</option>
                        <option value="1">YES</option>
                    </select>
                    <input v-model="mas_basic.basic_district_planning_name"
                           v-if="mas_basic.basic_district_planning == 1"
                           class="form-control col-4 ml-1" type="text"
                           placeholder="地区名"
                           :disabled="!initial.editable || !initial.is_modified">
                </div>
                <!-- end - basic_district_planning -->

                <!-- start - basic_scenic_area -->
                <div class="row">
                    <div class="col-2">
                        <label for="basic_scenic_area" class="col-form-label">
                            <span class="sticon-wind s9" title="風致地区（基本）"></span>
                        </label>
                    </div>
                    <select v-model="mas_basic.basic_scenic_area"
                            class="form-control col-4"
                            :disabled="!initial.editable || !initial.is_modified">
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
                    <select v-model="mas_basic.basic_landslide"
                            class="form-control col-4"
                            :disabled="!initial.editable || !initial.is_modified">
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
                    <select v-model="mas_basic.basic_residential_land_development"
                            class="form-control col-4"
                            :disabled="!initial.editable || !initial.is_modified">
                        <option value="3">未確認</option>
                        <option value="2">NO</option>
                        <option value="1">YES</option>
                    </select>
                </div>
                <!-- end - basic_residential_land_development -->
            </div>
        </div>

        <!-- -start project current situation -->
        <div class="form-group row">
            <!-- -start project current situation -->
            <label for="" class="col-3 col-form-label">現況</label>
            <select v-model="mas_basic.project_current_situation"
            class="form-control col-3">
                <option value="0" selected="selected"></option>
                <option value="1">更地</option>
                <option value="2">畑</option>
                <option value="3">田</option>
                <option value="4">古屋あり</option>
                <option value="5">造成前</option>
                <option value="6">造成中</option>
                <option value="7">その他</option>
            </select>

            <!-- -start project current situation other -->
            <input v-if="mas_basic.project_current_situation == 7"
            v-model="mas_basic.project_current_situation_other"
            class="form-control col-3 ml-2" type="text">
        </div>
        <!-- -end project current situation -->

        <!-- -start project setback -->
        <div class="form-group row">
            <label for="" class="col-3 col-form-label">セットバック</label>
            <div class="form-check icheck-cyan form-check-inline">
                <input v-model="mas_basic.project_set_back"
                class="form-check-input" type="radio" id="project_set_back_1" value="1">
                <label class="form-check-label" for="project_set_back_1">有</label>
            </div>
            <div class="form-check icheck-cyan form-check-inline">
                <input v-model="mas_basic.project_set_back"
                class="form-check-input" type="radio" id="project_set_back_2" value="2">
                <label class="form-check-label" for="project_set_back_2">無</label>
            </div>
        </div>
        <!-- -end project setback -->

        <!-- -start project private road -->
        <div class="form-group row">
            <label for="" class="col-3 col-form-label">私道</label>
            <div class="form-check icheck-cyan form-check-inline">
                <input v-model="mas_basic.project_private_road"
                class="form-check-input" type="radio" id="project_private_road_1" value="1">
                <label class="form-check-label" for="project_private_road_1">有</label>
            </div>
            <div class="form-check icheck-cyan form-check-inline">
                <input v-model="mas_basic.project_private_road"
                class="form-check-input" type="radio" id="project_private_road_2" value="2">
                <label class="form-check-label" for="project_private_road_2">無</label>
            </div>
        </div>
        <!-- -end project private road -->

        <!-- -start project urbanization area -->
        <div class="form-group row">
            <label for="" class="col-3 col-form-label">都市計画</label>
            <select v-model="mas_basic.project_urbanization_area"
            class="form-control col-5"
            :disabled="!initial.editable || !initial.is_modified">
                <option value="0" selected="selected"></option>
                <option value="1">市街化区域</option>
                <option value="2">市街化調整区域</option>
                <option value="3">区画整理地内</option>
                <option value="4">非線引区域</option>
                <option value="5">都市計画区域外</option>
            </select>
        </div>
        <!-- -end project urbanization area -->

        <template v-if="mas_basic.project_urbanization_area == 3">
            <div class="form-group row">
                <!-- -start project urbanization area status -->
                <label for="" class="col-3 col-form-label">土地区画整理事業</label>
                <div class="form-check icheck-cyan form-check-inline">
                    <input v-model="mas_basic.project_urbanization_area_status"
                    class="form-check-input" type="radio" id="project_urbanization_area_status_1" value="1"
                    :disabled="!initial.editable || !initial.is_modified">
                    <label class="form-check-label" for="project_urbanization_area_status_1">計画有</label>
                </div>
                <div class="form-check icheck-cyan form-check-inline">
                    <input v-model="mas_basic.project_urbanization_area_status"
                    class="form-check-input" type="radio" id="project_urbanization_area_status_2" value="2"
                    :disabled="!initial.editable || !initial.is_modified">
                    <label class="form-check-label" for="project_urbanization_area_status_2">施工中</label>
                </div>
                <!-- -end project urbanization area status -->

                <!-- -start project urbanization area sub -->
                <select v-model="mas_basic.project_urbanization_area_sub"
                class="form-control col-2"
                :disabled="!initial.editable || !initial.is_modified">
                    <option value="0" selected="selected"></option>
                    <option value="1">保留地</option>
                    <option value="2">仮換地</option>
                </select>
                <!-- -end project urbanization area sub -->
            </div>

            <!-- -start project urbanization area date -->
            <div class="form-group row">
                <label for="" class="col-3 col-form-label">収益開始日</label>
                <date-picker v-model="mas_basic.project_urbanization_area_date"
                type="date" input-class="form-control input-date w-100"
                value-type="format" format="YYYY/MM/DD"
                :disabled="!initial.editable || !initial.is_modified">
                </date-picker>
            </div>
            <!-- -end project urbanization area date -->
        </template>

    </div>
</div>
