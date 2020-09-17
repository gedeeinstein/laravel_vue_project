<div class="card-subheader01 row align-items-center mx-0">
    <!-- start - exists_building_residential -->
    <div class="col-12 my-2">
        <div class="form-check icheck-cyan d-inline">
            <input id="exists_building_residential" class="form-check-input" type="checkbox" :checked="buildings.active"
                :disabled="buildings.disable_input">
            <label class="form-check-label" for="exists_building_residential">建物 該当</label>
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
                        <select v-model="field.parcel_city" class="form-control form-control-sm"
                            :disabled="buildings.disable_input">
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
                            type="text" placeholder="その他市区町村" :disabled="buildings.disable_input">
                    </div>
                    <!-- parcel_town -->
                    <div class="form-group">
                        <input v-model="field.parcel_town" class="form-control form-control-w-xl form-control-sm"
                            type="text" placeholder="町域" :disabled="buildings.disable_input">
                    </div>
                </td>
                <!-- end - parcel_address -->

                <!-- start - parcel_number -->
                <td>
                    <div class="form-group">
                        <!-- parcel_number_first -->
                        <template>
                            <currency-input v-model="field.parcel_number_first"
                                class="form-control form-control-w-xs form-control-sm"
                                :disabled="buildings.disable_input" :currency="null"
                                :precision="{ min: 0, max: 4 }"
                                :allow-negative="false" />
                        </template>
                        <span>番</span>
                        <!-- parcel_number_second -->
                        <template>
                            <currency-input v-model="field.parcel_number_second"
                                class="form-control form-control-w-xs form-control-sm"
                                :disabled="buildings.disable_input" :currency="null"
                                :precision="{ min: 0, max: 4 }"
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
                                    class="form-control form-control-w-xs form-control-sm"
                                    :disabled="buildings.disable_input" :currency="null"
                                    :precision="{ min: 0, max: 4 }"
                                    :allow-negative="false" />
                            </template>
                        </span>
                        <span>番</span>
                        <span>
                            <!-- building_number_second -->
                            <template>
                                <currency-input v-model="field.building_number_second"
                                    class="form-control form-control-w-xs form-control-sm"
                                    :disabled="buildings.disable_input" :currency="null"
                                    :precision="{ min: 0, max: 4 }"
                                    :allow-negative="false" />
                            </template>
                        </span>
                        <span>の</span>
                        <span>
                            <!-- building_number_third -->
                            <template>
                                <currency-input v-model="field.building_number_third"
                                    class="form-control form-control-w-xs form-control-sm"
                                    :disabled="buildings.disable_input" :currency="null"
                                    :precision="{ min: 0, max: 4 }"
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
                            :disabled="buildings.disable_input">
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
                               id="building_attached" :value="1" :disabled="buildings.disable_input">
                        <label for="building_attached">有</label>
                    </div>
                    <div v-if="field.building_attached == 1" class="form-group">
                        <!-- building_attached_select -->
                        <select v-model="field.building_attached_select" class="form-control form-control-sm" :disabled="buildings.disable_input">
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
                        <select v-model="field.building_date_nengou" id="date-nengou"
                                :disabled="buildings.disable_input"
                                class="form-control form-control-w-ms form-control-sm mx-1">
                            <option :value="0" selected="selected"></option>
                            <option :value="1">昭和</option>
                            <option :value="2">平成</option>
                            <option :value="3">令和</option>
                        </select>
                        <!-- building_date_year -->
                        <span>
                            <input v-model="field.building_date_year"
                                   class="form-control form-control-w-sm form-control-sm mx-1" type="tel"
                                   :disabled="buildings.disable_input">
                        </span>
                        <span>年</span>
                        <!-- building_date_month -->
                        <span>
                            <input v-model="field.building_date_month"
                                   class="form-control form-control-w-xs form-control-sm mx-1" type="tel"
                                   :disabled="buildings.disable_input">
                        </span>
                        <span>月</span>
                        <!-- building_date_day -->
                        <span>
                            <input v-model="field.building_date_day"
                                   class="form-control form-control-w-xs form-control-sm mx-1" type="tel"
                                   :disabled="buildings.disable_input">
                        </span>
                        <span>日</span>
                    </div>
                    <div class="form-result"></div>
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
                                :disabled="buildings.disable_input">
                                <option value=""></option>
                                <option v-for="owner in master_property_owners" :value="owner.id">
                                    @{{ owner.name }}
                                </option>
                            </select>
                            <!-- share_denom -->
                            <template>
                                <currency-input v-model.number="owner.share_denom"
                                    class="form-control form-control-w-xs form-control-sm mx-1"
                                    :disabled="buildings.disable_input" :currency="null"
                                    :precision="{ min: 0, max: 4 }"
                                    :allow-negative="false" />
                            </template>
                            <span>分の</span>
                            <!-- share_number -->
                            <template>
                                <currency-input v-model.number="owner.share_number"
                                    class="form-control form-control-w-xs form-control-sm mx-1"
                                    :disabled="buildings.disable_input" :currency="null"
                                    :precision="{ min: 0, max: 4 }"
                                    :allow-negative="false" />
                            </template>
                        </div>
                        <div class="form-result"></div>
                    </template>
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
                            :disabled="buildings.disable_input">
                                <option value="0"></option>
                                <option v-for="structure in master_building_structures" :value="structure.id">
                                    @{{ structure.value }}
                                </option>
                            </select>
                        </div>
                        <div class="col-3 px-1">
                            <!-- building_floor_count -->
                            <input v-model="field.building_floor_count" class="form-control form-control-sm mx-1" type="tel"
                            :disabled="buildings.disable_input">
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
                            :disabled="buildings.disable_input">
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
                                            class="form-control form-control-sm w-100"
                                            :disabled="buildings.disable_input" :currency="null"
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