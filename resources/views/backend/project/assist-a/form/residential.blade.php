<div class="card-subheader01 row align-items-center mx-0">
    <!-- start - exists_land_residential -->
    <div class="col-4">
        <div class="form-check icheck-cyan">
            <template v-if="initial.editable">
                <input @click="switchSection(residentials, 'exists_land_residential', $event)" id="exists_land_residential" class="form-check-input" type="checkbox"
                       v-model="residentials.active"
                       :value="residentials.active">
                <label class="form-check-label" for="exists_land_residential">宅地 該当</label>
            </template>
            <template v-else>
                <input id="exists_land_residential" class="form-check-input" type="checkbox"
                       disabled="disabled"
                       :checked="residentials.data[0].id != null">
                <label class="form-check-label" for="exists_land_residential">宅地 該当</label>
            </template>
        </div>
    </div>
    <!-- end - exists_land_residential -->

    <!-- start - residential total -->
    <div class="col-8">
        <div class="row justify-content-end mt-2">
            <!-- residential_calculated_total -->
            <div class="col-3">
                <div class="row justify-content-center">
                    <label class="col-4">合計</label>
                    <div class="col">
                        @{{ residential_calculated_total | numeralFormat }}
                        <span class="unit">筆</span>
                    </div>
                </div>
            </div>
            <!-- residential_calculated_total -->
            <div class="col-3">
                <div class="row justify-content-center">
                    <label class="col-4">登記</label>
                    <div class="col">
                        @{{ residential_calculated_registration | numeralFormat(2) }}
                        <span class="unit">m<sup>2</sup></span>
                    </div>
                </div>
            </div>
            <!-- residential_calculated_total -->
            <div class="col-3">
                <div class="row justify-content-center">
                    <label class="col-4">実測</label>
                    <div class="col">
                        @{{ residential_calculated_actual | numeralFormat(2) }}
                        <span class="unit">m<sup>2</sup></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- end - residential total -->
</div>

<!-- start - residential table input -->
<table id="residential-table" class="table table-hover table-bordered table-small table-parcel-list">
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
        <tr v-for="(field, row) in residentials.data">
            <!-- start - parcel_address -->
            <td>
                <!-- parcel_city -->
                <div class="form-group">
                    <select v-model="field.parcel_city" class="form-control form-control-sm w-100"
                        :disabled="!residentials.active"
                        :data-parsley-required="residentials.active"
                        data-parsley-error-message="宅地の所在を入力してください。">
                        <option value=""></option>
                        <option value="-1">その他</option>
                        <option v-for="city in master_parcel_cities" :value="city.id">
                            @{{ city.name }}
                        </option>
                    </select>
                </div>
                <!-- parcel_city_extra -->
                <div v-if="field.parcel_city == -1" class="form-group">
                    <input v-model="field.parcel_city_extra" class="form-control form-control-w-xl form-control-sm"
                        type="text" placeholder="その他市区町村" :disabled="!residentials.active"
                        :data-parsley-required="residentials.active"
                        data-parsley-error-message="宅地の所在を入力してください。">
                </div>
                <!-- parcel_town -->
                <div class="form-group">
                    <input v-model="field.parcel_town" class="form-control form-control-w-xl form-control-sm"
                        type="text" placeholder="町域" :disabled="!residentials.active">
                    <template v-if="residentials.active">
                        <span v-if="row != 0">
                            <i @click="copyArea(row, 'residential')" class="far fa-copy cur-pointer text-secondary"
                                data-toggle="tooltip" data-original-title="所在コピー"></i>
                        </span>
                    </template>
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
                            class="form-control form-control-w-xs form-control-sm input-integer"
                            :disabled="!residentials.active" :currency="null"
                            :precision="{ min: 0, max: 0 }"
                            :allow-negative="false" />
                    </template>
                    <span>番</span>
                    <!-- parcel_number_second -->
                    <template>
                        <currency-input v-model="field.parcel_number_second"
                            class="form-control form-control-w-xs form-control-sm input-integer"
                            :disabled="!residentials.active" :currency="null"
                            :precision="{ min: 0, max: 0 }"
                            :allow-negative="false" />
                    </template>
                </div>
            </td>
            <!-- end - parcel_number -->

            <!-- start - parcel_land_category -->
            <td>
                <div class="form-group">
                    <!-- parcel_land_category -->
                    <select v-model="field.parcel_land_category" class="form-control form-control-1btn form-control-sm"
                        :disabled="!residentials.active">
                        <option value="0"></option>
                        <option v-for="land in master_land_categories" :value="land.id">
                            @{{ land.value }}
                        </option>
                    </select>
                    <template v-if="residentials.active">
                        <span v-if="row != 0">
                            <i @click="copyText('parcel_land_category', index, row, 'residential')"
                                class="far fa-copy cur-pointer text-secondary" data-toggle="tooltip"
                                data-original-title="地目コピー"></i>
                        </span>
                    </template>
                </div>
            </td>
            <!-- end - parcel_land_category -->

            <!-- start - parcel_use_district -->
            <td>
                <div v-for="(use_districts, index) in field.use_districts" v-show="!use_districts.deleted" class="form-group">
                    <!-- use_district -->
                    <select v-model="use_districts.value" class="form-control form-control-w-md form-control-sm"
                        :disabled="!residentials.active">
                        <option value="0"></option>
                        <option v-for="district in master_use_districts" :value="district.id">
                            @{{ district.value }}
                        </option>
                    </select>
                    <template v-if="residentials.active">
                        <span v-if="index == 0">
                            <i @click="addTableInput(field.use_districts)"
                                class="fa fa-plus-circle cur-pointer text-primary" data-toggle="tooltip"
                                data-original-title="用途地域追加"></i>
                        </span>
                        <span v-else>
                            <i @click="removeTableInput(field.use_districts, index)"
                                class="fa fa-minus-circle cur-pointer text-danger" data-toggle="tooltip"
                                data-original-title="用途地域削除"></i>
                        </span>
                        <span v-if="row != 0 || index != 0">
                            <i @click="copyText('use_districts', index, row, 'residential')"
                                class="far fa-copy cur-pointer text-secondary" data-toggle="tooltip"
                                data-original-title="用途地域コピー"></i>
                        </span>
                    </template>
                </div>
            </td>
            <!-- end - parcel_use_district -->

            <!-- start - parcel_build_ratio -->
            <td>
                <div v-for="(build_ratios, index) in field.build_ratios" v-show="!build_ratios.deleted" class="form-group">
                    <!-- build_ratios -->
                    <template>
                        <currency-input v-model="build_ratios.value"
                            class="form-control form-control-w-sm form-control-sm input-decimal"
                            :disabled="!residentials.active" :currency="null"
                            :precision="{ min: 0, max: 4 }"
                            :allow-negative="false" />
                    </template>
                    <template v-if="residentials.active">
                        <span v-if="index == 0">
                            <i @click="addTableInput(field.build_ratios)"
                                class="fa fa-plus-circle cur-pointer text-primary" data-toggle="tooltip"
                                data-original-title="建ぺい率追加"></i>
                        </span>
                        <span v-else>
                            <i @click="removeTableInput(field.build_ratios, index)"
                                class="fa fa-minus-circle cur-pointer text-danger" data-toggle="tooltip"
                                data-original-title="建ぺい率削除"></i>
                        </span>
                        <span v-if="row != 0 || index != 0">
                            <i @click="copyText('build_ratios', index, row, 'residential')"
                                class="far fa-copy cur-pointer text-secondary" data-toggle="tooltip"
                                data-original-title="建ぺい率コピー"></i>
                        </span>
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
                            class="form-control form-control-w-sm form-control-sm input-decimal"
                            :disabled="!residentials.active" :currency="null"
                            :precision="{ min: 0, max: 4 }"
                            :allow-negative="false" />
                    </template>
                    <template v-if="residentials.active">
                        <span v-if="index == 0">
                            <i @click="addTableInput(field.floor_ratios)"
                                class="fa fa-plus-circle cur-pointer text-primary" data-toggle="tooltip"
                                data-original-title="容積率追加"></i>
                        </span>
                        <span v-else>
                            <i @click="removeTableInput(field.floor_ratios, index)"
                                class="fa fa-minus-circle cur-pointer text-danger" data-toggle="tooltip"
                                data-original-title="容積率削除"></i>
                        </span>
                        <span v-if="row != 0 || index != 0">
                            <i @click="copyText('floor_ratios', index, row, 'residential')"
                                class="far fa-copy cur-pointer text-secondary" data-toggle="tooltip"
                                data-original-title="容積率コピー"></i>
                        </span>
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
                            class="form-control form-control-w-sm form-control-sm input-decimal"
                            :disabled="!residentials.active" :currency="null"
                            :precision="{ min: 0, max: 2 }"
                            :allow-negative="false" />
                    </template>
                    <template v-if="residentials.active">
                        <span v-if="row != 0">
                            <i @click="copyText('parcel_size', index, row, 'residential')"
                                class="far fa-copy cur-pointer text-secondary" data-toggle="tooltip"
                                data-original-title="地積(登記)をコピー"></i>
                        </span>
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
                            class="form-control form-control-w-sm form-control-sm input-decimal"
                            :disabled="!residentials.active" :currency="null"
                            :precision="{ min: 0, max: 2 }"
                            :allow-negative="false" />
                    </template>
                    <template v-if="residentials.active">
                        <span v-if="row != 0">
                            <i @click="copyText('parcel_size_survey', index, row, 'residential')"
                                class="far fa-copy cur-pointer text-secondary" data-toggle="tooltip"
                                data-original-title="地積(実測)をコピー"></i>
                        </span>
                    </template>
                </div>
            </td>
            <!-- end - parcel_size_survey -->

            <!-- start - parcel_project_owners -->
            <td>
                <template v-for="(owner, index) in field.residential_owners" v-if="!owner.deleted">
                    <div class="form-group d-flex align-items-center mx-n1">
                        <!-- pj_property_owners_id -->
                        <select v-model="owner.pj_property_owners_id"
                            class="form-control form-control-w-lg form-control-sm mx-1"
                            :class="'residential-owners-'+row"
                            :disabled="!residentials.active"
                            :data-parsley-notequalto="'.residential-owners-'+row"
                            data-parsley-trigger="change focusout"
                            :data-parsley-required="residentials.active"
                            data-parsley-required-message="宅地の所有者を入力してください。">
                            <option value=""></option>
                            <option v-for="property_owner in property_owners" :value="property_owner.id">
                                @{{ property_owner.name }}
                            </option>
                        </select>
                        <!-- share_denom -->
                        <template>
                            <currency-input v-model.number="owner.share_denom" @input="calculateTotalShare(field.residential_owners)"
                                class="form-control form-control-w-xs form-control-sm mx-1 input-integer"
                                :disabled="!residentials.active" :currency="null"
                                :precision="{ min: 0, max: 0 }"
                                :allow-negative="false" />
                        </template>
                        <span>分の</span>
                        <!-- share_number -->
                        <template>
                            <currency-input v-model.number="owner.share_number" @input="calculateTotalShare(field.residential_owners)"
                                class="form-control form-control-w-xs form-control-sm mx-1 input-integer"
                                :disabled="!residentials.active" :currency="null"
                                :precision="{ min: 0, max: 0 }"
                                :allow-negative="false" />
                        </template>
                        <template v-if="residentials.active">
                            <span v-if="index == 0">
                                <i @click="addResidentialOwners(row)" class="fa fa-plus-circle cur-pointer text-primary"
                                    data-toggle="tooltip" data-original-title="所有者追加"></i>
                            </span>
                            <span v-else>
                                <i @click="removeResidentialOwners(row, index)"
                                    class="fa fa-minus-circle cur-pointer text-danger" data-toggle="tooltip"
                                    data-original-title="所有者削除"></i>
                            </span>
                        </template>
                    </div>
                    <div class="form-result"></div>
                </template>
                <div v-if="field.residential_owners[0].other_denom != field.residential_owners[0].other_number" class="form-group d-flex align-items-center mx-n1 mt-2">
                    <!-- other -->
                    <input value="その他" class="form-control form-control-w-lg form-control-sm mx-1" type="text" readonly="readonly">
                    <!-- other_denom -->
                    <input :value="field.residential_owners[0].other_denom" class="form-control form-control-w-xs form-control-sm mx-1 border-integer"
                        type="text" readonly="readonly">
                    <span>分の</span>
                    <!-- other_number -->
                    <input :value="field.residential_owners[0].other_number" class="form-control form-control-w-xs form-control-sm mx-1 border-integer"
                        type="text" readonly="readonly">
                </div>

                <!-- start - add residential row -->
                <div v-if="residentials.active" class="row-control-buttons">
                    <span v-if="row == 0">
                        <i @click="addResidentialRow" class="fa fa-plus-circle cur-pointer text-primary"
                            data-toggle="tooltip" data-original-title="行を追加"></i>
                    </span>
                    <span v-else>
                        <i @click="removeResidentialRow(row)" class="fa fa-minus-circle cur-pointer text-danger"
                            data-toggle="tooltip" title="" data-original-title="行を削除"></i>
                    </span>
                </div>
                <!-- end - add residential row -->

            </td>
            <!-- end - parcel_project_owners -->
        </tr>
    </tbody>
</table>
<!-- end - residential table input -->