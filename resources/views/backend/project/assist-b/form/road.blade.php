<div class="card-subheader01 row align-items-center mx-0 my-2">
    <!-- start - exists_road_residential -->
    <div class="col-4">
        <div class="form-check icheck-cyan">
            <input id="exists_road_residential" class="form-check-input" type="checkbox" :checked="roads.active"
                :disabled="roads.disable_input">
            <label class="form-check-label" for="exists_road_residential">道路 該当</label>
        </div>
    </div>
    <!-- end - exists_road_residential -->
</div>

<!-- start - road_a table -->
<div id="residential-table" v-for="(data, table) in roads.data" class="table-whole-hover mt-2">
    <table class="table table-bordered table-small table-parcel-list border-bottom-0 mb-0 mt-0">
        <thead>
            <tr>
                <th class="parcel_address">所在</th>
                <th class="parcel_number">地番</th>
                <th class="parcel_land_category">地目</th>
                <th class="parcel_use_district">用途地域</th>
                <th class="parcel_build_ratio">建ぺい率</th>
                <th class="parcel_floor_ratio">容積率</th>
                <th class="parcel_size">地積(登記)</th>
                <th class="parcel_survey_size">地積(実測)</th>
                <th class="parcel_project_owner">所有者/持分</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <!-- start - parcel_address -->
                <td>
                    <!-- parcel_city -->
                    <div class="form-group">
                        <select v-model="data.parcel_city" class="form-control form-control-sm"
                            :disabled="roads.disable_input">
                            <option value="0"></option>
                            <option value="-1">その他</option>
                            <option v-for="city in master_parcel_cities" :value="city.id">
                                @{{ city.name }}
                            </option>
                        </select>
                    </div>
                    <!-- parcel_city_extra -->
                    <div v-if="data.parcel_city == -1" class="form-group">
                        <input v-model="data.parcel_city_extra" class="form-control form-control-w-xl form-control-sm"
                            type="text" placeholder="その他市区町村" :disabled="roads.disable_input">
                    </div>
                    <!-- parcel_town -->
                    <div class="form-group">
                        <input v-model="data.parcel_town" class="form-control form-control-w-xl form-control-sm"
                            type="text" placeholder="町域" :disabled="roads.disable_input">
                    </div>
                    <div class="form-result"></div>
                </td>
                <!-- end - parcel_address -->

                <!-- start - parcel_number -->
                <td>
                    <div class="form-group">
                        <!-- parcel_number_first -->
                        <template>
                            <currency-input v-model="data.parcel_number_first"
                                class="form-control form-control-w-xs form-control-sm"
                                :disabled="roads.disable_input" :currency="null" :precision="{ min: 0, max: 4 }"
                                :allow-negative="false" />
                        </template>
                        <span>番</span>
                        <!-- parcel_number_second -->
                        <template>
                            <currency-input v-model="data.parcel_number_second"
                                class="form-control form-control-w-xs form-control-sm"
                                :disabled="roads.disable_input" :currency="null" :precision="{ min: 0, max: 4 }"
                                :allow-negative="false" />
                        </template>
                    </div>
                </td>
                <!-- end - parcel_number -->

                <!-- start - parcel_land_category -->
                <td>
                    <div class="form-group">
                        <!-- parcel_land_category -->
                        <select v-model="data.parcel_land_category" class="form-control form-control-sm"
                            :disabled="roads.disable_input">
                            <option value="0"></option>
                            <option v-for="land in master_land_categories" :value="land.id">
                                @{{ land.value }}
                            </option>
                        </select>
                    </div>
                </td>
                <!-- end - parcel_land_category -->

                <!-- start - parcel_use_district -->
                <td>
                    <div v-for="(use_districts, index) in data.use_districts" v-show="!use_districts.deleted"
                        class="form-group">
                        <!-- use_district -->
                        <select v-model="use_districts.value" class="form-control form-control-sm"
                            :disabled="roads.disable_input">
                            <option value="0"></option>
                            <option v-for="district in master_use_districts" :value="district.id">
                                @{{ district.value }}
                            </option>
                        </select>
                    </div>
                </td>
                <!-- end - parcel_use_district -->

                <!-- start - parcel_build_ratio -->
                <td>
                    <div v-for="(build_ratios, index) in data.build_ratios" v-show="!build_ratios.deleted"
                        class="form-group">
                        <!-- build_ratios -->
                        <template>
                            <currency-input v-model="build_ratios.value" class="form-control form-control-sm"
                                :disabled="roads.disable_input" :currency="null" :precision="{ min: 0, max: 4 }"
                                :allow-negative="false" />
                        </template>
                    </div>
                </td>
                <!-- end - parcel_build_ratio -->

                <!-- start - parcel_floor_ratio -->
                <td>
                    <div v-for="(floor_ratios, index) in data.floor_ratios" v-show="!floor_ratios.deleted"
                        class="form-group">
                        <!-- floor_ratios -->
                        <template>
                            <currency-input v-model="floor_ratios.value" class="form-control form-control-sm"
                                :disabled="roads.disable_input" :currency="null" :precision="{ min: 0, max: 4 }"
                                :allow-negative="false" />
                        </template>
                    </div>
                </td>
                <!-- end - parcel_floor_ratio -->

                <!-- start - parcel_size -->
                <td>
                    <div class="form-group">
                        <!-- parcel_size -->
                        <template>
                            <currency-input v-model="data.parcel_size" class="form-control form-control-sm"
                                :disabled="roads.disable_input" :currency="null" :precision="{ min: 0, max: 4 }"
                                :allow-negative="false" />
                        </template>
                    </div>
                </td>
                <!-- end - parcel_parcel_size -->

                <!-- start - parcel_size_survey -->
                <td>
                    <!-- parcel_size_survey -->
                    <div class="form-group">
                        <template>
                            <currency-input v-model="data.parcel_size_survey" class="form-control form-control-sm"
                                :disabled="roads.disable_input" :currency="null" :precision="{ min: 0, max: 4 }"
                                :allow-negative="false" />
                        </template>
                    </div>
                </td>
                <!-- end - parcel_size_survey -->

                <!-- start - parcel_project_owners -->
                <td>
                    <template v-for="(owner, index) in data.road_owners">
                        <div class="form-group d-flex align-items-center">
                            <!-- pj_property_owners_id -->
                            <select v-model="owner.pj_property_owners_id"
                                class="form-control form-control-w-lg form-control-sm mx-1"
                                :class="'road-owners-'+table" :disabled="roads.disable_input">
                                <option value=""></option>
                                <option v-for="owner in master_property_owners" :value="owner.id">
                                    @{{ owner.name }}
                                </option>
                            </select>
                            <!-- share_denom -->
                            <template>
                                <currency-input v-model.number="owner.share_denom"
                                    class="form-control form-control-w-xs form-control-sm mx-1"
                                    :disabled="roads.disable_input" :currency="null"
                                    :precision="{ min: 0, max: 4 }" :allow-negative="false" />
                            </template>
                            <span>分の</span>
                            <!-- share_number -->
                            <template>
                                <currency-input v-model.number="owner.share_number"
                                    class="form-control form-control-w-xs form-control-sm mx-1"
                                    :disabled="roads.disable_input" :currency="null"
                                    :precision="{ min: 0, max: 4 }" :allow-negative="false" />
                            </template>
                        </div>
                        <div class="form-result"></div>
                    </template>
                </td>
                <!-- end - parcel_project_owners -->
            </tr>
        </tbody>
    </table>
    <!-- end - road_a table -->

    <!-- start - road_b table -->
    <table class="table table-bordered table-small table-parcel-list mt-0 border-top-0">
        <tbody>
            <tr class="d-flex align-items-stretch">
                <td class="parcel_risk">
                    <div class="form-group row">
                        <div class="col-4">防火指定</div>
                        <!-- start - fire_protection -->
                        <div class="col-8">
                            <select v-model="data.road_b.fire_protection"
                                    class="form-control form-control-sm"
                                    :disabled="data.road_b.fire_protection_same == 1"
                                    :disabled="!initial.editable">
                                <option value="0" selected="selected"></option>
                                <option value="1">防火地域</option>
                                <option value="2">準防火地域</option>
                                <option value="3">法22条区域</option>
                                <option value="4">指定なし</option>
                            </select>
                            <div class="form-check icheck-cyan">
                                <input v-model="data.road_b.fire_protection_same"
                                       @change="sameAsBasicRoad(data.road_b.fire_protection_same)"
                                       :id="'road_fire_protection_same_'+table" class="form-check-input"
                                       type="checkbox" true-value="1" false-value="0"
                                       :disabled="!initial.editable">
                                <label class="form-check-label text-nowrap"
                                       :for="'road_fire_protection_same_'+table">基本と同じ</label>
                            </div>
                        </div>
                       <!-- end - fire_protection -->
                    </div>
                </td>
                <td class="parcel_risk">
                    <div class="form-group row">
                        <div class="col-4">
                            <span class="sticon-haniwa s9" title="文化財埋蔵"></span>
                        </div>
                        <!-- start - cultural_property_reserves -->
                        <div class="col-8">
                            <select v-model="data.road_b.cultural_property_reserves"
                                    class="form-control form-control-sm"
                                    :disabled="data.road_b.cultural_property_reserves_same == 1"
                                    :disabled="!initial.editable">
                                <option value="3">未確認</option>
                                <option value="2">NO</option>
                                <option value="1">YES</option>
                            </select>
                            <div class="form-check icheck-cyan">
                                <input v-model="data.road_b.cultural_property_reserves_same"
                                       :id="'road_cultural_property_reserves_same_'+table" class="form-check-input"
                                       @change="sameAsBasicRoad(data.road_b.cultural_property_reserves_same)"
                                       type="checkbox" true-value="1" false-value="0"
                                       :disabled="!initial.editable">
                                <label class="form-check-label text-nowrap"
                                       :for="'road_cultural_property_reserves_same_'+table">基本と同じ</label>
                            </div>
                            <input v-model="data.road_b.cultural_property_reserves_name"
                                   v-if="data.road_b.cultural_property_reserves == 1"
                                   class="form-control form-control-sm" type="text" placeholder="遺跡名"
                                   :disabled="!initial.editable || data.road_b.cultural_property_reserves_same == 1">
                        </div>
                        <!-- end - cultural_property_reserves -->
                    </div>
                </td>
                <td class="parcel_risk">
                    <div class="form-group row">
                        <div class="col-4">
                            <span class="sticon-district_planning s9" title="地区計画"></span>
                        </div>
                        <!-- start - district_planning -->
                        <div class="col-8">
                            <select v-model="data.road_b.district_planning"
                                    class="form-control form-control-sm"
                                    :disabled="data.road_b.district_planning_same == 1"
                                    :disabled="!initial.editable">
                                <option value="3">未確認</option>
                                <option value="2">NO</option>
                                <option value="1">YES</option>
                            </select>
                            <div class="form-check icheck-cyan">
                                <input v-model="data.road_b.district_planning_same"
                                       @change="sameAsBasicRoad(data.road_b.district_planning_same)"
                                       :id="'road_district_planning_same_'+table" class="form-check-input"
                                       type="checkbox" true-value="1" false-value="0"
                                       :disabled="!initial.editable">
                                <label class="form-check-label text-nowrap"
                                       :for="'road_district_planning_same_'+table">基本と同じ</label>
                            </div>
                            <input v-model="data.road_b.district_planning_name"
                                   v-if="data.road_b.district_planning == 1"
                                   class="form-control form-control-sm" type="text" placeholder="地区名"
                                   :disabled="!initial.editable || data.road_b.district_planning_same == 1">
                        </div>
                        <!-- end - district_planning -->
                    </div>
                </td>
                <td class="parcel_risk">
                    <div class="form-group row">
                        <div class="col-4">
                            <span class="sticon-wind s9" title="風致地区"></span>
                        </div>
                        <!-- start - scenic_area -->
                        <div class="col-8">
                            <select v-model="data.road_b.scenic_area"
                                    class="form-control form-control-sm"
                                    :disabled="data.road_b.scenic_area_same == 1"
                                    :disabled="!initial.editable">
                                <option value="3">未確認</option>
                                <option value="2">NO</option>
                                <option value="1">YES</option>
                            </select>
                            <div class="form-check icheck-cyan">
                                <input v-model="data.road_b.scenic_area_same"
                                       @change="sameAsBasicRoad(data.road_b.scenic_area_same)"
                                       :id="'road_scenic_area_same_'+table" class="form-check-input"
                                       type="checkbox" true-value="1" false-value="0"
                                       :disabled="!initial.editable">
                                <label class="form-check-label text-nowrap"
                                       :for="'road_scenic_area_same_'+table">基本と同じ</label>
                            </div>
                        </div>
                        <!-- end - scenic_area -->
                    </div>
                </td>
                <td class="parcel_risk">
                    <div class="form-group row">
                        <div class="col-4">
                            <span class="sticon-landslide s9" title="地滑り"></span>
                        </div>
                        <!-- start - landslide -->
                        <div class="col-8">
                            <select v-model="data.road_b.landslide"
                                    class="form-control form-control-sm"
                                    :disabled="data.road_b.landslide_same == 1"
                                    :disabled="!initial.editable">
                                <option value="3">未確認</option>
                                <option value="2">NO</option>
                                <option value="1">YES</option>
                            </select>
                            <div class="form-check icheck-cyan">
                                <input v-model="data.road_b.landslide_same"
                                       @change="sameAsBasicRoad(data.road_b.landslide_same)"
                                       :id="'road_landslide_same_'+table" class="form-check-input"
                                       type="checkbox" true-value="1" false-value="0"
                                       :disabled="!initial.editable">
                                <label class="form-check-label text-nowrap"
                                       :for="'road_landslide_same_'+table">基本と同じ</label>
                            </div>
                        </div>
                        <!-- end - landslide -->
                    </div>
                </td>
                <td class="parcel_risk">
                    <div class="form-group row">
                        <div class="col-4">
                            <span class="sticon-residential_land_development s9" title="宅地造成区域法"></span>
                        </div>
                        <!-- start - residential_land_development -->
                        <div class="col-8">
                            <select v-model="data.road_b.residential_land_development"
                                    class="form-control form-control-sm"
                                    :disabled="data.road_b.residential_land_development_same == 1"
                                    :disabled="!initial.editable">
                                <option value="3">未確認</option>
                                <option value="2">NO</option>
                                <option value="1">YES</option>
                            </select>
                            <div class="form-check icheck-cyan">
                                <input v-model="data.road_b.residential_land_development_same"
                                       @change="sameAsBasicRoad(data.road_b.residential_land_development_same)"
                                       :id="'road_residential_land_development_same_'+table" class="form-check-input"
                                       type="checkbox" true-value="1" false-value="0"
                                       :disabled="!initial.editable">
                                <label class="form-check-label text-nowrap"
                                       :for="'road_residential_land_development_same_'+table">基本と同じ</label>
                            </div>
                        </div>
                        <!-- start - residential_land_development -->
                    </div>
                </td>
            </tr>
        </tbody>
    </table>
    <!-- end - road_b table -->

</div>
