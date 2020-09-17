<div class="card-subheader01 row align-items-center mx-0 my-2">
    <!-- start - exists_road_residential -->
    <div class="col-4 py-1 pl-2">
        <label class="form-check-label" for="exists_road_residential">道路 該当</label>
        {{-- <div class="form-check icheck-cyan">
            <template>
                <input @click="switchSection(roads, 'exists_road_residential', $event)"
                       id="exists_road_residential" class="form-check-input" type="checkbox"
                       v-model="roads.active"
                       :disabled="!initial.editable || !initial.is_modified">
                <label class="form-check-label" for="exists_road_residential">道路 該当</label>
            </template>
        </div> --}}
    </div>
    <!-- end - exists_road_residential -->
</div>

<!-- start - road_a table -->
<div id="road-table" v-for="(data, table) in roads.data" class="table-whole-hover mt-2">
    <table class="table table-bordered table-small table-parcel-list border-bottom-0 mb-0 mt-0">
        <thead>
            <tr>
                <th class="parcel_address">所在</th>
                <th class="parcel_number">地番</th>
                <th class="parcel_land_category">地目</th>
                <th class="parcel_use_district">用途地域</th>
                <th class="parcel_build_ratio">建ぺい率</th>
                <th class="parcel_floor_ratio">容積率</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <!-- start - parcel_address -->
                <td>
                    <!-- parcel_city -->
                    <div class="form-group">
                        <select v-model="data.mas_road.parcel_city" class="form-control w-100 form-control-sm"
                            :data-parsley-required="roads.active"
                            data-parsley-error-message="道路の所在を入力してください。"
                            :disabled="!initial.editable || !initial.is_modified">
                            <option value="0"></option>
                            <option value="-1">その他</option>
                            <option v-for="city in master_parcel_cities" :value="city.id">
                                @{{ city.name }}
                            </option>
                        </select>
                    </div>
                    <!-- parcel_city_extra -->
                    <div v-if="data.mas_road.parcel_city == -1" class="form-group">
                        <input v-model="data.mas_road.parcel_city_extra" class="form-control w-100 form-control-sm"
                            :data-parsley-required="roads.active"
                            data-parsley-error-message="道路の所在を入力してください。"
                            type="text" placeholder="その他市区町村" :disabled="!initial.editable || !initial.is_modified">
                    </div>
                    <!-- parcel_town -->
                    <div class="form-group">
                        <input v-model="data.mas_road.parcel_town" class="form-control w-100 form-control-sm"
                            type="text" placeholder="町域" :disabled="!initial.editable || !initial.is_modified">
                    </div>
                    <div class="form-result"></div>
                </td>
                <!-- end - parcel_address -->

                <!-- start - parcel_number -->
                <td>
                    <div class="form-group">
                        <!-- parcel_number_first -->
                        <template>
                            <currency-input v-model="data.mas_road.parcel_number_first"
                                class="form-control form-control-w-xs form-control-sm input-integer"
                                :disabled="!initial.editable || !initial.is_modified" :currency="null" :precision="{ min: 0, max: 4 }"
                                :allow-negative="false" />
                        </template>
                        <span>番</span>
                        <!-- parcel_number_second -->
                        <template>
                            <currency-input v-model="data.mas_road.parcel_number_second"
                                class="form-control form-control-w-xs form-control-sm input-integer"
                                :disabled="!initial.editable || !initial.is_modified" :currency="null" :precision="{ min: 0, max: 4 }"
                                :allow-negative="false" />
                        </template>
                    </div>
                </td>
                <!-- end - parcel_number -->

                <!-- start - parcel_land_category -->
                <td>
                    <div class="form-group">
                        <!-- parcel_land_category -->
                        <select v-model="data.mas_road.parcel_land_category" class="form-control form-control-sm"
                            :disabled="!initial.editable || !initial.is_modified">
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
                    <div v-for="(use_districts, index) in data.mas_road.use_districts" v-show="!use_districts.deleted" class="form-group">
                        <!-- use_district -->
                        <select v-model="use_districts.value" class="form-control form-control-w-xl form-control-sm"
                            style="width: 80%"
                            :disabled="!initial.editable || !initial.is_modified">
                            <option value="0"></option>
                            <option v-for="district in master_use_districts" :value="district.id">
                                @{{ district.value }}
                            </option>
                        </select>
                        <template v-if="initial.editable && initial.is_modified">
                            <span v-if="index == 0">
                                <i @click="addTableInput(data.mas_road.use_districts)"
                                    class="fa fa-plus-circle cur-pointer text-primary" data-toggle="tooltip"
                                    data-original-title="用途地域追加"></i>
                            </span>
                            <span v-else>
                                <i @click="removeTableInput(data.mas_road.use_districts, index)"
                                    class="fa fa-minus-circle cur-pointer text-danger" data-toggle="tooltip"
                                    data-original-title="用途地域削除"></i>
                            </span>
                            <span>
                                <i @click="copyText('use_districts', index, table, 'road')"
                                    class="far fa-copy cur-pointer text-secondary" data-toggle="tooltip"
                                    data-original-title="所在コピー"></i>
                            </span>
                        </template>
                    </div>
                </td>
                <!-- end - parcel_use_district -->

                <!-- start - parcel_build_ratio -->
                <td>
                    <div v-for="(build_ratios, index) in data.mas_road.build_ratios" v-show="!build_ratios.deleted" class="form-group">
                        <!-- build_ratios -->
                        <template>
                            <currency-input v-model="build_ratios.value"
                                class="form-control form-control-w-sm form-control-sm input-decimal"
                                :disabled="!initial.editable || !initial.is_modified" :currency="null"
                                :precision="{ min: 0, max: 4 }"
                                :allow-negative="false" />
                        </template>
                        <template v-if="initial.editable && initial.is_modified">
                            <span v-if="index == 0">
                                <i @click="addTableInput(data.mas_road.build_ratios)"
                                    class="fa fa-plus-circle cur-pointer text-primary" data-toggle="tooltip"
                                    data-original-title="建ぺい率追加"></i>
                            </span>
                            <span v-else>
                                <i @click="removeTableInput(data.mas_road.build_ratios, index)"
                                    class="fa fa-minus-circle cur-pointer text-danger" data-toggle="tooltip"
                                    data-original-title="建ぺい率削除"></i>
                            </span>
                            <span>
                                <i @click="copyText('build_ratios', index, table, 'road')"
                                    class="far fa-copy cur-pointer text-secondary" data-toggle="tooltip"
                                    data-original-title="所在コピー"></i>
                            </span>
                        </template>
                    </div>
                </td>
                <!-- end - parcel_build_ratio -->

                <!-- start - parcel_floor_ratio -->
                <td>
                    <div v-for="(floor_ratios, index) in data.mas_road.floor_ratios" v-show="!floor_ratios.deleted" class="form-group">
                        <!-- floor_ratios -->
                        <template>
                            <currency-input v-model="floor_ratios.value"
                                class="form-control form-control-w-sm form-control-sm input-decimal"
                                :disabled="!initial.editable || !initial.is_modified" :currency="null"
                                :precision="{ min: 0, max: 4 }"
                                :allow-negative="false" />
                        </template>
                        <template v-if="initial.editable && initial.is_modified">
                            <span v-if="index == 0">
                                <i @click="addTableInput(data.mas_road.floor_ratios)"
                                    class="fa fa-plus-circle cur-pointer text-primary" data-toggle="tooltip"
                                    data-original-title="容積率追加"></i>
                            </span>
                            <span v-else>
                                <i @click="removeTableInput(data.mas_road.floor_ratios, index)"
                                    class="fa fa-minus-circle cur-pointer text-danger" data-toggle="tooltip"
                                    data-original-title="容積率削除"></i>
                            </span>
                            <span>
                                <i @click="copyText('floor_ratios', index, table, 'road')"
                                    class="far fa-copy cur-pointer text-secondary" data-toggle="tooltip"
                                    data-original-title="所在コピー"></i>
                            </span>
                        </template>
                    </div>
                </td>
                <!-- end - parcel_floor_ratio -->
            </tr>
        </tbody>
    </table>
    <!-- end - road_a table -->

    <!-- start - road_b table -->
    <table class="table table-bordered table-small table-parcel-list mt-0 mb-0 border-top-0">
        <tbody>
            <tr class="d-flex align-items-stretch">
                <td class="parcel_risk">
                    <div class="form-group row">
                        <div class="col-4">防火指定</div>
                        <!-- start - fire_protection -->
                        <div class="col-8">
                            <select v-model="data.mas_road.fire_protection"
                                    class="form-control form-control-sm"
                                    :disabled="!initial.editable || !initial.is_modified || data.mas_road.fire_protection_same == 1">
                                <option value="0" selected="selected"></option>
                                <option value="1">防火地域</option>
                                <option value="2">準防火地域</option>
                                <option value="3">法22条区域</option>
                                <option value="4">指定なし</option>
                            </select>
                            <div class="form-check icheck-cyan">
                                <input v-model="data.mas_road.fire_protection_same"
                                       @change="sameAsBasicRoad(data.mas_road.fire_protection_same)"
                                       :id="'road_fire_protection_same_'+table" class="form-check-input"
                                       type="checkbox" true-value="1" false-value="0"
                                       :disabled="!initial.editable || !initial.is_modified">
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
                            <select v-model="data.mas_road.cultural_property_reserves"
                                    class="form-control form-control-sm"
                                    :disabled="!initial.editable || !initial.is_modified || data.mas_road.cultural_property_reserves_same == 1">
                                <option value="3">未確認</option>
                                <option value="2">NO</option>
                                <option value="1">YES</option>
                            </select>
                            <div class="form-check icheck-cyan">
                                <input v-model="data.mas_road.cultural_property_reserves_same"
                                       :id="'road_cultural_property_reserves_same_'+table" class="form-check-input"
                                       @change="sameAsBasicRoad(data.mas_road.cultural_property_reserves_same)"
                                       type="checkbox" true-value="1" false-value="0"
                                       :disabled="!initial.editable || !initial.is_modified">
                                <label class="form-check-label text-nowrap"
                                       :for="'road_cultural_property_reserves_same_'+table">基本と同じ</label>
                            </div>
                            <input v-model="data.mas_road.cultural_property_reserves_name"
                                   v-if="data.mas_road.cultural_property_reserves == 1"
                                   class="form-control form-control-sm" type="text" placeholder="遺跡名"
                                   :disabled="!initial.editable || !initial.is_modified || data.mas_road.cultural_property_reserves_same == 1">
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
                            <select v-model="data.mas_road.district_planning"
                                    class="form-control form-control-sm"
                                    :disabled="!initial.editable || !initial.is_modified || data.mas_road.district_planning_same == 1">
                                <option value="3">未確認</option>
                                <option value="2">NO</option>
                                <option value="1">YES</option>
                            </select>
                            <div class="form-check icheck-cyan">
                                <input v-model="data.mas_road.district_planning_same"
                                       @change="sameAsBasicRoad(data.mas_road.district_planning_same)"
                                       :id="'road_district_planning_same_'+table" class="form-check-input"
                                       type="checkbox" true-value="1" false-value="0"
                                       :disabled="!initial.editable || !initial.is_modified">
                                <label class="form-check-label text-nowrap"
                                       :for="'road_district_planning_same_'+table">基本と同じ</label>
                            </div>
                            <input v-model="data.mas_road.district_planning_name"
                                   v-if="data.mas_road.district_planning == 1"
                                   class="form-control form-control-sm" type="text" placeholder="地区名"
                                   :disabled="!initial.editable || !initial.is_modified || data.mas_road.district_planning_same == 1">
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
                            <select v-model="data.mas_road.scenic_area"
                                    class="form-control form-control-sm"
                                    :disabled="!initial.editable || !initial.is_modified || data.mas_road.scenic_area_same == 1">
                                <option value="3">未確認</option>
                                <option value="2">NO</option>
                                <option value="1">YES</option>
                            </select>
                            <div class="form-check icheck-cyan">
                                <input v-model="data.mas_road.scenic_area_same"
                                       @change="sameAsBasicRoad(data.mas_road.scenic_area_same)"
                                       :id="'road_scenic_area_same_'+table" class="form-check-input"
                                       type="checkbox" true-value="1" false-value="0"
                                       :disabled="!initial.editable || !initial.is_modified">
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
                            <select v-model="data.mas_road.landslide"
                                    class="form-control form-control-sm"
                                    :disabled="!initial.editable || !initial.is_modified || data.mas_road.landslide_same == 1">
                                <option value="3">未確認</option>
                                <option value="2">NO</option>
                                <option value="1">YES</option>
                            </select>
                            <div class="form-check icheck-cyan">
                                <input v-model="data.mas_road.landslide_same"
                                       @change="sameAsBasicRoad(data.mas_road.landslide_same)"
                                       :id="'road_landslide_same_'+table" class="form-check-input"
                                       type="checkbox" true-value="1" false-value="0"
                                       :disabled="!initial.editable || !initial.is_modified">
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
                            <select v-model="data.mas_road.residential_land_development"
                                    class="form-control form-control-sm"
                                    :disabled="!initial.editable || !initial.is_modified || data.mas_road.residential_land_development_same == 1">
                                <option value="3">未確認</option>
                                <option value="2">NO</option>
                                <option value="1">YES</option>
                            </select>
                            <div class="form-check icheck-cyan">
                                <input v-model="data.mas_road.residential_land_development_same"
                                       @change="sameAsBasicRoad(data.mas_road.residential_land_development_same)"
                                       :id="'road_residential_land_development_same_'+table" class="form-check-input"
                                       type="checkbox" true-value="1" false-value="0"
                                       :disabled="!initial.editable || !initial.is_modified">
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
    <!-- start - mas basic -->
    <table class="table table-bordered table-small table-parcel-list mt-0 mb-0 border-top-0">
        <tbody>
            <tr>
                <td class="parcel_risk" colspan="2">
                    <div class="form-group row">
                        <div class="col-1" style="white-space: nowrap;">現況</div>
                        <div class="col-11">
                            <!-- start - project current situation -->
                            <select v-model="data.mas_road.project_current_situation"
                            :disabled="data.mas_road.project_current_situation_same_to_basic == 1"
                            class="form-control form-control-w-md form-control-sm">
                                <option value="" selected="selected"></option>
                                <option value="1">更地</option>
                                <option value="2">畑</option>
                                <option value="3">田</option>
                                <option value="4">古屋あり</option>
                                <option value="5">造成前</option>
                                <option value="6">造成中</option>
                                <option value="7">その他</option>
                            </select>
                            <!-- end - project current situation -->
                            <!-- start - project current situation other -->
                            <input v-if="data.mas_road.project_current_situation == 7"
                            v-model="data.mas_road.project_current_situation_other"
                            :disabled="data.mas_road.project_current_situation_same_to_basic == 1"
                            class="form-control form-control-w-lg mt-1" type="text" value="">
                            <!-- end - project current situation other -->
                            <!-- start - project current situation same to basic-->
                            <div class="form-check icheck-cyan form-check-inline mt-1 ml-3">
                                <input v-model="data.mas_road.project_current_situation_same_to_basic"
                                class="form-check-input" :id="'project_current_situation_same_to_basic_road_'+table" type="checkbox" value="1" checked="checked">
                                <label class="form-check-label" :for="'project_current_situation_same_to_basic_road_'+table">基本と同じ</label>
                            </div>
                            <!-- end - project current situation same to basic-->
                        </div>
                    </div>
                </td>
                <td class="parcel_risk" colspan="2">
                    <div class="form-group row">
                        <div class="col-3" style="white-space: nowrap;">セットバック</div>
                        <div class="col-9">
                            <!-- start - project set back -->
                            <div class="form-check icheck-cyan form-check-inline">
                                <input v-model="data.mas_road.project_set_back"
                                :disabled="data.mas_road.project_set_back_same_to_basic == 1"
                                class="form-check-input" type="radio" :id="'project_set_back_road_1_'+table" value="1">
                                <label class="form-check-label" :for="'project_set_back_road_1_'+table">有</label>
                            </div>
                            <div class="form-check icheck-cyan form-check-inline">
                                <input v-model="data.mas_road.project_set_back"
                                :disabled="data.mas_road.project_set_back_same_to_basic == 1"
                                class="form-check-input" type="radio" :id="'project_set_back_road_2_'+table" value="0">
                                <label class="form-check-label" :for="'project_set_back_road_2_'+table">無</label>
                            </div>
                            <!-- end - project set back -->
                            <!-- start - project project set back same to basic -->
                            <div class="form-check icheck-cyan form-check-inline mt-1 ml-3">
                                <input v-model="data.mas_road.project_set_back_same_to_basic"
                                class="form-check-input" type="checkbox" :id="'project_set_back_same_to_basic_road_'+table" value="1" checked="checked">
                                <label class="form-check-label" :for="'project_set_back_same_to_basic_road_'+table">基本と同じ</label>
                            </div>
                            <!-- end - project project set back same to basic -->
                        </div>
                    </div>
                </td>
                <td class="parcel_risk" colspan="2">
                    <div class="form-group row">
                        <div class="col-2">私道</div>
                        <div class="col-10">
                            <!-- start - project private road -->
                            <div class="form-check icheck-cyan form-check-inline">
                                <input v-model="data.mas_road.project_private_road"
                                :disabled="data.mas_road.project_private_road_same_to_basic == 1"
                                class="form-check-input" type="radio" :id="'project_private_road_road_1_'+table" value="1">
                                <label class="form-check-label" :for="'project_private_road_road_1_'+table">有</label>
                            </div>
                            <div class="form-check icheck-cyan form-check-inline">
                                <input v-model="data.mas_road.project_private_road"
                                :disabled="data.mas_road.project_private_road_same_to_basic == 1"
                                class="form-check-input" type="radio" :id="'project_private_road_road_2_'+table" value="0">
                                <label class="form-check-label" :for="'project_private_road_road_2_'+table">無</label>
                            </div>
                            <!-- end - project private road -->
                            <!-- start - project private road same to basic -->
                            <div class="form-check icheck-cyan form-check-inline mt-1 ml-3">
                                <input v-model="data.mas_road.project_private_road_same_to_basic"
                                class="form-check-input" type="checkbox" value="1" :id="'project_private_road_same_to_basic_road_'+table" checked="checked">
                                <label class="form-check-label" :for="'project_private_road_same_to_basic_road_'+table">基本と同じ</label>
                            </div>
                            <!-- end - project private road same to basic -->
                        </div>
                    </div>
                </td>
            </tr>
        </tbody>
    </table>
    <!-- end - mas basic -->
    <!-- start - purchase sale -->
    <table class="table table-bordered table-small table-parcel-list mt-0 mb-0 border-top-0">
        <tbody>
            <tr>
                <td class="parcel-risk" colspan="6">
                    <div class="form-group row">
                        <!-- start - urbanization area -->
                        <label for="" class="col-form-label pl-4 pr-4">都市計画</label>									　
                        <select v-model="data.mas_road.urbanization_area" class="form-control form-control-sm"
                            :disabled="data.mas_road.urbanization_area_same == 1 || !initial.editable || !initial.is_modified">
                            <option value="1">市街化区域</option>
                            <option value="2">市街化調整区域</option>
                            <option value="3">区画整理地内</option>
                            <option value="4">非線引区域</option>
                            <option value="5">都市計画区域外</option>
                        </select>
                        <!-- end - urbanization area -->
                        <template v-if="data.mas_road.urbanization_area == 3">
                            <!-- start - urbanization area sub -->
                            <label for="" class="col-form-label pl-4 pr-4">土地区画整理事業</label>
                            <select v-model="data.mas_road.urbanization_area_sub" class="form-control form-control-w-md form-control-sm"
                                :disabled="data.mas_road.urbanization_area_same == 1 || !initial.editable || !initial.is_modified">
                                <option value="1">保留地</option>
                                <option value="2">仮換地</option>
                            </select>
                            <!-- end - urbanization area sub -->
                            <!-- start - urbanization area number -->
                            <input v-model="data.mas_road.urbanization_area_number" class="form-control form-control-w-md ml-1 form-control-sm"
                                :disabled="!initial.editable || !initial.is_modified"
                                data-parsley-trigger="keyup" data-parsley-maxlength="128"
                                type="text" value="" placeholder="街区番号">
                            <!-- end - urbanization area number -->
                            <!-- start - urbanization area date -->
                            <label for="" class="col-form-label pl-4 pr-4">収益開始日</label>
                            <date-picker v-model="data.mas_road.urbanization_area_date" type="date" input-class="form-control form-control-sm input-date w-100" value-type="format" format="YYYY/MM/DD"
                                :disabled="data.mas_road.urbanization_area_same == 1 || !initial.editable || !initial.is_modified">
                            </date-picker>
                            <!-- end - urbanization area date -->
                        </template>
                        <!-- start - urbanization area same -->
                        <div class="form-check icheck-cyan d-inline mt-1 ml-4">
                            <input :id="'urbanization_area_same_road'+table" @change="sameAsBasicRoad(data.mas_road.urbanization_area_same)"
                            v-model="data.mas_road.urbanization_area_same" class="form-check-input" type="checkbox" value="1"
                            :disabled="!initial.editable || !initial.is_modified">
                            <label class="form-check-label" :for="'urbanization_area_same_road'+table">基本と同じ</label>
                        </div>
                        <!-- end - urbanization area same -->
                    </div>
                </td>
            </tr>
        </tbody>
    </table>
    <!-- end - purchase sale -->
</div>
