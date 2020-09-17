<template v-if="roads.data.length > 0">
    <div class="card-subheader01 row align-items-center mx-0 my-2">
        <div class="col-4">
            <div class="form-check icheck-cyan">
                <input id="exists_road_residential" class="form-check-input" type="checkbox" checked="checked"
                    disabled="disabled">
                <label class="form-check-label" for="exists_road_residential">道路 該当</label>
            </div>
        </div>
    </div>
    <div class="table-whole-hover">
        <!-- start - road table input -->
        <table id="road-table" class="table table-hover table-bordered table-small table-parcel-list">
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
                <template v-for="(field, row) in roads.data">
                    <tr>
                        <!-- start - parcel_address -->
                        <td>
                            <!-- parcel_city -->
                            <div class="form-group">
                                <select v-model="field.parcel_city" class="form-control form-control-sm"
                                    disabled>
                                    <option value="0"></option>
                                    <option value="-1">その他</option>
                                    <option v-for="(city, index) in master_parcel_cities" :value="index">
                                        @{{ city }}
                                    </option>
                                </select>
                            </div>
                            <!-- parcel_city_extra -->
                            <div v-if="field.parcel_city == -1" class="form-group">
                                <input v-model="field.parcel_city_extra" class="form-control form-control-w-xl form-control-sm"
                                    type="text" placeholder="その他市区町村" readonly>
                            </div>
                            <!-- parcel_town -->
                            <div class="form-group">
                                <input v-model="field.parcel_town" class="form-control form-control-w-xl form-control-sm"
                                    type="text" placeholder="町域" readonly>
                            </div>
                            <div class="form-result"></div>
                        </td>
                        <!-- end - parcel_address -->
                        <!-- start - parcel_number -->
                        <td>
                            <div class="form-group">
                                <!-- parcel_number_first -->
                                <template>
                                    <currency-input v-model="field.parcel_number_first"
                                        class="form-control form-control-w-xs form-control-sm border-integer"
                                        :currency="null" readonly="readonly"
                                        :precision="{ min: 0, max: 4 }"
                                        :allow-negative="false" />
                                </template>
                                <span>番</span>
                                <!-- parcel_number_second -->
                                <template>
                                    <currency-input v-model="field.parcel_number_second"
                                        class="form-control form-control-w-xs form-control-sm border-integer"
                                        :currency="null" readonly="readonly"
                                        :precision="{ min: 0, max: 4 }"
                                        :allow-negative="false" />
                                </template>
                            </div>
                        </td>
                        <!-- end - parcel_number -->
                        <!-- start - parcel_land_category -->
                        <td>
                            <div class="form-group">
                                <!-- parcel_land_category -->
                                <input :value="master_values[field.parcel_land_category]" class="form-control form-control-w-xl form-control-sm"
                                    type="text" readonly>
                            </div>
                        </td>
                        <!-- end - parcel_land_category -->
                        <!-- start - parcel_use_district -->
                        <td>
                            <div v-for="(use_districts, index) in field.use_districts" v-show="!use_districts.deleted" class="form-group">
                                <!-- use_district -->
                                <input :value="master_values[use_districts.value]" class="form-control form-control-w-xl form-control-sm"
                                    type="text" readonly>
                            </div>
                        </td>
                        <!-- end - parcel_use_district -->
                        <!-- start - parcel_build_ratio -->
                        <td>
                            <div v-for="(build_ratios, index) in field.build_ratios" v-show="!build_ratios.deleted" class="form-group">
                                <!-- build_ratios -->
                                <template>
                                    <currency-input v-model="build_ratios.value"
                                        class="form-control form-control-w-sm form-control-sm border-decimal"
                                        :currency="null" readonly="readonly"
                                        :precision="{ min: 0, max: 4 }"
                                        :allow-negative="false" />
                                </template>
                            </div>
                        </td>
                        <!-- end - parcel_build_ratio -->
                        <!-- start - parcel_floor_ratio -->
                        <td>
                            <div v-for="(floor_ratios, index) in field.floor_ratios" v-show="!floor_ratios.deleted" class="form-group">
                                <!-- floor_ratios -->
                                <template>
                                    <currency-input v-model="floor_ratios.value"
                                        class="form-control form-control-w-sm form-control-sm border-decimal"
                                        :currency="null" readonly="readonly"
                                        :precision="{ min: 0, max: 4 }"
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
                                    <currency-input v-model="field.parcel_size"
                                        class="form-control form-control-w-sm form-control-sm border-decimal"
                                        :currency="null" readonly="readonly"
                                        :precision="{ min: 0, max: 4 }"
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
                                    <currency-input v-model="field.parcel_size_survey"
                                        class="form-control form-control-w-sm form-control-sm border-decimal"
                                        :currency="null" readonly="readonly"
                                        :precision="{ min: 0, max: 4 }"
                                        :allow-negative="false" />
                                </template>
                            </div>
                        </td>
                        <!-- end - parcel_size_survey -->
                        <!-- start - parcel_project_owners -->
                        <td>
                            <template v-for="(owner, index) in field.road_owners" v-if="!owner.deleted">
                                <div class="form-group d-flex align-items-center mx-n1">
                                    <!-- pj_property_owners_id -->
                                    <input :value="property_owners[owner.pj_property_owners_id]" class="form-control form-control-w-lg form-control-sm mx-1"
                                        type="text" readonly>
                                    <!-- share_denom -->
                                    <template>
                                        <currency-input v-model.number="owner.share_denom"
                                            class="form-control form-control-w-xs form-control-sm mx-1 border-integer"
                                            :currency="null" readonly="readonly"
                                            :precision="{ min: 0, max: 4 }"
                                            :allow-negative="false" />
                                    </template>
                                    <span>分の</span>
                                    <!-- share_number -->
                                    <template>
                                        <currency-input v-model.number="owner.share_number"
                                            class="form-control form-control-w-xs form-control-sm mx-1 border-integer"
                                            :currency="null" readonly="readonly"
                                            :precision="{ min: 0, max: 4 }"
                                            :allow-negative="false" />
                                    </template>
                                </div>
                                <div class="form-result"></div>
                            </template>
                            <div v-if="field.road_owners[0].other_denom != field.road_owners[0].other_number" class="form-group d-flex align-items-center mx-n1 mt-2">
                                <!-- other -->
                                <input value="その他" class="form-control form-control-w-lg form-control-sm mx-1" type="text" readonly="readonly">
                                <!-- other_denom -->
                                <input :value="field.road_owners[0].other_denom" class="form-control form-control-w-xs form-control-sm mx-1 border-integer"
                                    type="text" readonly="readonly">
                                <span>分の</span>
                                <!-- other_number -->
                                <input :value="field.road_owners[0].other_number" class="form-control form-control-w-xs form-control-sm mx-1 border-integer"
                                    type="text" readonly="readonly">
                            </div>
                        </td>
                        <!-- end - parcel_project_owners -->
                    </tr>
                    <tr>
                        <td colspan="9">
                            <div class="form-group row">
                                <label for="" class="col-form-label pl-4 pr-4">都市計画</label>									　
                                <select v-model="field.road_purchase.urbanization_area" class="form-control form-control-sm" data-id="A62-1"
                                    :disabled="field.road_purchase.urbanization_area_same == 1 || !initial.editable"
                                    data-parsley-no-focus required data-parsley-trigger="change focusout">
                                    <option value="1">市街化区域</option>
                                    <option value="2">市街化調整区域</option>
                                    <option value="3">区画整理地内</option>
                                    <option value="4">非線引区域</option>
                                    <option value="5">都市計画区域外</option>
                                </select>
                                <template v-if="field.road_purchase.urbanization_area == 3">
                                    <label for="" class="col-form-label pl-4 pr-4">土地区画整理事業</label>
                                    <select v-model="field.road_purchase.urbanization_area_sub" class="form-control form-control-w-md form-control-sm" data-id="A62-2"
                                        :disabled="field.road_purchase.urbanization_area_same == 1 || !initial.editable">
                                        <option value="1">保留地</option>
                                        <option value="2">仮換地</option>
                                    </select>
                                    <input v-model="field.road_purchase.urbanization_area_number" class="form-control form-control-w-md ml-1 form-control-sm"
                                        type="text" value="" placeholder="街区番号" data-id="A62-3" :disabled="!initial.editable"
                                        data-parsley-trigger="keyup" data-parsley-maxlength="128">
                                    <label for="" class="col-form-label pl-4 pr-4">収益開始日</label>
                                    <date-picker v-model="field.road_purchase.urbanization_area_date" type="date" name="" input-class="form-control form-control-sm input-date w-100" value-type="format" format="YYYY/MM/DD"
                                        :disabled="field.road_purchase.urbanization_area_same == 1 || !initial.editable" data-id="A62-4">
                                    </date-picker>
                                </template>
                                <div class="form-check icheck-cyan d-inline mt-1 ml-4">
                                    <input :id="'urbanization_area_same_road'+row" v-model="field.road_purchase.urbanization_area_same" class="form-check-input" type="checkbox" name="" value="1" :disabled="!initial.editable" data-id="A62-5">
                                    <label class="form-check-label" :for="'urbanization_area_same_road'+row">基本と同じ</label>
                                </div>
                            </div>
                        </td>
                    </tr>
                </template>
            </tbody>
        </table>
        <!-- end - road table input -->
    </div>
</template>
