<div class="card-subheader01 row align-items-center mx-0">
    <!-- start - exists_building_residential -->
    <div class="col-12 my-2">
        <div class="form-check icheck-cyan d-inline">
            <template v-if="initial.editable">
                <input @click="switchSection(buildings, 'exists_building_residential', $event)"
                    v-model="buildings.active" id="exists_building_residential" class="form-check-input" type="checkbox">
                <label class="form-check-label" for="exists_building_residential">建物 該当</label>
            </template>
            <template v-else>
                <input id="exists_building_residential" class="form-check-input" type="checkbox"
                       disabled="disabled"
                       :checked="buildings.data[0].id != null">
                <label class="form-check-label" for="exists_building_residential">建物 該当</label>
            </template>
        </div>
    </div>
    <!-- end - exists_building_residential -->
</div>

<!-- start - building table input -->
<div v-for="(field, row) in buildings.data" class="table-whole-hover my-0">
    <table class="table  table-bordered table-small table-parcel-list mb-0">
        <thead>
            <tr>
                <th class="parcel_address">所在</th>
                <th class="parcel_number">地番</th>
                <th class="parcel_buil_number">家屋番号</th>
                <th class="parcel_buil_type">種類</th>
                <th class="parcel_buil_attach">付属建物</th>
                <th class="parcel_buil_date">建築時期</th>
                <th class="parcel_project_owner">所有者/持分</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <!-- start - parcel_address -->
                <td>
                    <!-- parcel_city -->
                    <div class="form-group">
                        <select v-model="field.parcel_city" class="form-control form-control-sm w-100"
                            :disabled="!buildings.active"
                            :data-parsley-required="buildings.active"
                            data-parsley-error-message="建物の所在を入力してください。">
                            <option value="0"></option>
                            <option value="-1">その他</option>
                            <option v-for="city in master_parcel_cities" :value="city.id">
                                @{{ city.name }}
                            </option>
                        </select>
                    </div>
                    <!-- parcel_city_extra -->
                    <div v-if="field.parcel_city == -1" class="form-group">
                        <input v-model="field.parcel_city_extra" class="form-control form-control-w-xl form-control-sm"
                            type="text" placeholder="その他市区町村" :disabled="!buildings.active"
                            :data-parsley-required="buildings.active"
                            data-parsley-error-message="建物の所在を入力してください。">
                    </div>
                    <!-- parcel_town -->
                    <div class="form-group">
                        <input v-model="field.parcel_town" class="form-control form-control-w-xl form-control-sm"
                            type="text" placeholder="町域" :disabled="!buildings.active">
                        <template v-if="buildings.active">
                            <span>
                                <i @click="copyArea(row, 'building')" class="far fa-copy cur-pointer text-secondary"
                                    data-toggle="tooltip" data-original-title="所在コピー"></i>
                            </span>
                        </template>
                    </div>
                </td>
                <!-- end - parcel_address -->

                <!-- start - parcel_number -->
                <td>
                    <div class="form-group">
                        <!-- parcel_number_first -->
                        <template>
                            <currency-input v-model="field.parcel_number_first"
                                class="form-control form-control-w-xs form-control-sm input-integer"
                                :disabled="!buildings.active" :currency="null"
                                :precision="{ min: 0, max: 0 }"
                                :allow-negative="false" />
                        </template>
                        <span>番</span>
                        <!-- parcel_number_second -->
                        <template>
                            <currency-input v-model="field.parcel_number_second"
                                class="form-control form-control-w-xs form-control-sm input-integer"
                                :disabled="!buildings.active" :currency="null"
                                :precision="{ min: 0, max: 0 }"
                                :allow-negative="false" />
                        </template>
                    </div>
                </td>
                <!-- end - parcel_number -->

                <!-- start - parcel_build_number -->
                <td>
                    <div class="form-group">
                        <span>
                            <!-- building_number_first -->
                            <template>
                                <currency-input v-model="field.building_number_first"
                                    class="form-control form-control-w-xs form-control-sm input-integer"
                                    data-parsley-trigger="change focusout"
                                    :data-parsley-required="[ field.parcel_number_first ? 'true' : 'false' ]"
                                    :disabled="!buildings.active" :currency="null"
                                    :precision="{ min: 0, max: 0 }"
                                    :allow-negative="false" />
                            </template>
                        </span>
                        <span>番</span>
                        <span>
                            <!-- building_number_second -->
                            <template>
                                <currency-input v-model="field.building_number_second"
                                    class="form-control form-control-w-xs form-control-sm input-integer"
                                    data-parsley-trigger="change focusout"
                                    :data-parsley-required="[ field.parcel_number_second ? 'true' : 'false' ]"
                                    :disabled="!buildings.active" :currency="null"
                                    :precision="{ min: 0, max: 0 }"
                                    :allow-negative="false" />
                            </template>
                        </span>
                        <span>の</span>
                        <span>
                            <!-- building_number_third -->
                            <template>
                                <currency-input v-model="field.building_number_third"
                                    class="form-control form-control-w-xs form-control-sm input-integer"
                                    :disabled="!buildings.active" :currency="null"
                                    :precision="{ min: 0, max: 0 }"
                                    :allow-negative="false" />
                            </template>
                        </span>
                    </div>
                    <div class="form-result"></div>
                </td>
                <!-- end - parcel_build_number -->

                <!-- start - parcel_build_type -->
                <td>
                    <div class="form-group">
                        <!-- building_usetype -->
                        <select v-model="field.building_usetype" class="form-control form-control-w-md form-control-sm"
                            :disabled="!buildings.active">
                            <option value="0"></option>
                            <option v-for="type in master_use_types" :value="type.id">
                                @{{ type.value }}
                            </option>
                        </select>
                    </div>
                </td>
                <!-- end - parcel_build_type -->


                <!-- start - parcel_build_attach -->
                <td>
                    <div class="form-group icheck-cyan mx-2">
                        <!-- building_attached -->
                        <input v-model="field.building_attached" type="checkbox"
                               :id="'building_attached' + row" :for="'building_attached' + row" :value="1" :disabled="!buildings.active">
                        <label :for="'building_attached' + row">有</label>
                    </div>
                    <div v-if="field.building_attached == 1" class="form-group">
                        <!-- building_attached_select -->
                        <select v-model="field.building_attached_select" class="form-control form-control-sm" :disabled="!buildings.active">
                            <option :value="0" selected="selected"></option>
                            <option :value="1">車庫</option>
                            <option :value="2">倉庫</option>
                            <option :value="3">納屋</option>
                            <option :value="4">物置</option>
                        </select>
                    </div>
                </td>
                <!-- end - parcel_build_attach -->

                <!-- start - parcel_build_date -->
                <td>
                    <div class="form-group d-flex align-items-center mx-n1">
                        <!-- building_date_nengou -->
                        <select v-model.number="field.building_date_nengou" :id="'date-nengou-'+row"
                        :data-parsley-date-nengou="['#date-nengou-'+row, '#value-nengou-'+row, '#month-nengou-'+row, '#day-nengou-'+row]"
                        data-parsley-trigger="change"
                        :data-parsley-required="dateCheck(field)"
                        :disabled="!buildings.active"
                        class="form-control form-control-sm mx-1">
                            <option :value="0" selected="selected"></option>
                            <option :value="1">昭和</option>
                            <option :value="2">平成</option>
                            <option :value="3">令和</option>
                        </select>
                        <!-- building_date_year -->
                        <span>
                            <input v-model.number="field.building_date_year" :id="'value-nengou-'+row"
                            :data-parsley-date-nengou="['#date-nengou-'+row, '#value-nengou-'+row, '#month-nengou-'+row, '#day-nengou-'+row]"
                            :data-parsley-required="dateCheck(field)"
                            data-parsley-required-message="建築時期 は全て入力してください。"
                            data-parsley-trigger="change focusout"
                            v-mask="'####'"
                            class="form-control form-control-w-xs form-control-sm mx-1 input-integer" type="tel"
                            :disabled="!buildings.active">
                        </span>
                        <span>年</span>
                        <!-- building_date_month -->
                        <span>
                            <input v-model.number="field.building_date_month" :id="'month-nengou-'+row"
                            data-parsley-trigger="change focusout"
                            :data-parsley-date-nengou="['#date-nengou-'+row, '#value-nengou-'+row, '#month-nengou-'+row, '#day-nengou-'+row]"
                            :data-parsley-required="dateCheck(field)"
                            data-parsley-required-message="建築時期 は全て入力してください。"
                            data-parsley-max="12"
                            v-mask="'##'" class="form-control form-control-w-xs form-control-sm mx-1 input-integer" type="tel"
                            :disabled="!buildings.active">
                        </span>
                        <span>月</span>
                        <!-- building_date_day -->
                        <span>
                            <input v-model.number="field.building_date_day" :id="'day-nengou-'+row"
                            data-parsley-trigger="change focusout"
                            :data-parsley-date-nengou="['#date-nengou-'+row, '#value-nengou-'+row, '#month-nengou-'+row, '#day-nengou-'+row]"
                            :data-parsley-required="dateCheck(field)"
                            data-parsley-required-message="建築時期 は全て入力してください。"
                            data-parsley-max="31"
                            v-mask="'##'" class="form-control form-control-w-xs form-control-sm mx-1 input-integer" type="tel"
                            :disabled="!buildings.active">
                        </span>
                        <span>日</span>
                    </div>
                    <div class="form-result nengou"></div>
                </td>
                <!-- end - parcel_build_date -->


                <!-- start - parcel_project_owners -->
                <td>
                    <template v-for="(owner, index) in field.building_owners" v-if="!owner.deleted">
                        <div class="form-group d-flex align-items-center mx-n1">
                            <!-- pj_property_owners_id -->
                            <select v-model="owner.pj_property_owners_id"
                                class="form-control form-control-w-lg form-control-sm mx-1 building-owners"
                                :class="'building-owners-'+row"
                                :disabled="!buildings.active"
                                :data-parsley-notequalto="'.building-owners-'+row"
                                data-parsley-trigger="change focusout"
                                :data-parsley-required="buildings.active"
                                data-parsley-required-message="建物の所有者を入力してください。">
                                <option value=""></option>
                                <option v-for="property_owner in property_owners" :value="property_owner.id">
                                    @{{ property_owner.name }}
                                </option>
                            </select>
                            <!-- share_denom -->
                            <template>
                                <currency-input v-model.number="owner.share_denom" @input="calculateTotalShare(field.building_owners)"
                                    class="form-control form-control-w-xs form-control-sm mx-1 input-integer"
                                    :disabled="!buildings.active" :currency="null"
                                    :precision="{ min: 0, max: 0 }"
                                    :allow-negative="false" />
                            </template>
                            <span>分の</span>
                            <!-- share_number -->
                            <template>
                                <currency-input v-model.number="owner.share_number" @input="calculateTotalShare(field.building_owners)"
                                    class="form-control form-control-w-xs form-control-sm mx-1 input-integer"
                                    :disabled="!buildings.active" :currency="null"
                                    :precision="{ min: 0, max: 0 }"
                                    :allow-negative="false" />
                            </template>
                            <template v-if="buildings.active">
                                <span v-if="index == 0">
                                    <i @click="addBuildingOwners(row)" class="fa fa-plus-circle cur-pointer text-primary"
                                        data-toggle="tooltip" data-original-title="所有者追加"></i>
                                </span>
                                <span v-else>
                                    <i @click="removeBuildingOwners(row, index)"
                                        class="fa fa-minus-circle cur-pointer text-danger" data-toggle="tooltip"
                                        data-original-title="所有者削除"></i>
                                </span>
                            </template>
                        </div>
                        <div class="form-result"></div>
                    </template>
                    <div v-if="field.building_owners[0].other_denom != field.building_owners[0].other_number" class="form-group d-flex align-items-center mx-n1 mt-2">
                        <!-- other -->
                        <input value="その他" class="form-control form-control-w-lg form-control-sm mx-1" type="text" readonly="readonly">
                        <!-- other_denom -->
                        <input :value="field.building_owners[0].other_denom" class="form-control form-control-w-xs form-control-sm mx-1 border-integer"
                            type="text" readonly="readonly">
                        <span>分の</span>
                        <!-- other_number -->
                        <input :value="field.building_owners[0].other_number" class="form-control form-control-w-xs form-control-sm mx-1 border-integer"
                            type="text" readonly="readonly">
                    </div>

                    <!-- start - add building row -->
                    <div v-if="buildings.active" class="row-control-buttons mt-5">
                        <span v-if="row == 0">
                            <i @click="addBuildingRow" class="fa fa-plus-circle cur-pointer text-primary"
                                data-toggle="tooltip" data-original-title="行を追加"></i>
                        </span>
                        <span v-else>
                            <i @click="removeBuildingRow(row)" class="fa fa-minus-circle cur-pointer text-danger"
                                data-toggle="tooltip" title="" data-original-title="行を削除"></i>
                        </span>
                    </div>
                    <!-- end - add building row -->

                </td>
                <!-- end - parcel_project_owners -->

            </tr>
        </tbody>
    </table>
    <table class="table table-bordered table-small table-parcel-list mt-0">
        <thead>
            <tr>
                <th class="parcel_buil_structure">構造</th>
                <th class="parcel_buil_roof">屋根</th>
                <th class="parcel_buil_space">床面積</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>
                    <div class="form-group row mx-n1">
                        <div class="col-6 px-1">
                            <!-- building_structure -->
                            <select v-model="field.building_structure" class="form-control form-control-sm mx-1"
                            :disabled="!buildings.active">
                                <option value="0"></option>
                                <option v-for="structure in master_building_structures" :value="structure.id">
                                    @{{ structure.value }}
                                </option>
                            </select>
                        </div>
                        <div class="col-3 px-1">
                            <!-- building_floor_count -->
                            <input v-model="field.building_floor_count" class="form-control form-control-sm mx-1 input-integer" type="tel"
                            @input="calculateFloorCount(field)"
                            v-mask="'##'"
                            :disabled="!buildings.active">
                        </div>
                        <div class="col-3 px-1 d-flex align-items-center">
                            <span>階建て</span>
                        </div>
                    </div>
                    <div class="form-result px-2"></div>
                </td>
                <td>
                    <div class="form-group">
                        <!-- building_roof -->
                        <select v-model="field.building_roof" class="form-control form-control-sm"
                            :disabled="!buildings.active">
                            <option value="0"></option>
                            <option v-for="roof in master_building_roofs" :value="roof.id">
                                @{{ roof.value }}
                            </option>
                        </select>
                    </div>
                </td>
                <td>
                    <div class="form-group row mx-n1">
                        <div v-for="(floor, index) in field.building_floors" class="px-1 col-2 my-1" v-show="!floor.deleted">
                            <div class="row mx-0">
                                <div class="px-0 col-5 d-flex align-items-center justify-content-end mr-1">
                                    <span>@{{ index+1 }}階</span>
                                </div>
                                <div class="px-0 col-6">
                                    <!-- floor_size -->
                                    <template>
                                        <currency-input v-model="floor.floor_size"
                                            class="form-control form-control-sm w-100 input-decimal"
                                            :disabled="!buildings.active" :currency="null"
                                            :precision="{ min: 0, max: 4 }"
                                            :allow-negative="false" />
                                    </template>
                                </div>
                            </div>
                        </div>
                    </div>
                </td>
            </tr>
        </tbody>
    </table>
</div>
<!-- end - building table input -->
