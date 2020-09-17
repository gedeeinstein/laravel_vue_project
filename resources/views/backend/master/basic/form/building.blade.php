<div class="card-subheader01 row align-items-center mx-0 my-2">
    <!-- start - exists_building_residential -->
    <div class="col-12 py-1 pl-2">
        <label class="form-check-label" for="exists_building_residential">建物 該当</label>
        {{-- <div class="form-check icheck-cyan d-inline">
            <template>
                <input @click="switchSection(buildings, 'exists_building_residential', $event)"
                    v-model="buildings.active"
                    :disabled="!initial.editable || !initial.is_modified"
                    id="exists_building_residential" class="form-check-input" type="checkbox">
                <label class="form-check-label" for="exists_building_residential">建物 該当</label>
            </template>
        </div> --}}
    </div>
    <!-- end - exists_building_residential -->
</div>

<!-- start - building table input -->
<div v-for="(data, table) in buildings.data" class="table-whole-hover mt-2">
    <table class="table  table-bordered table-small table-parcel-list mb-0 mt-0">
        <thead>
            <tr>
                <th class="parcel_address">所在</th>
                <th class="parcel_number">地番</th>
                <th class="parcel_buil_number">家屋番号</th>
                <th class="parcel_buil_type">種類</th>
                <th class="parcel_buil_attach">付属建物</th>
                <th class="parcel_buil_date">建築時期</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <!-- start - parcel_address -->
                <td>
                    <!-- parcel_city -->
                    <div class="form-group">
                        <select v-model="data.mas_building.parcel_city" class="form-control w-100 form-control-sm"
                            :data-parsley-required="buildings.active"
                            data-parsley-error-message="建物の所在を入力してください。"
                            :disabled="!initial.editable || !initial.is_modified">
                            <option value="0"></option>
                            <option value="-1">その他</option>
                            <option v-for="city in master_parcel_cities" :value="city.id">
                                @{{ city.name }}
                            </option>
                        </select>
                    </div>
                    <!-- parcel_city_extra -->
                    <div v-if="data.mas_building.parcel_city == -1" class="form-group">
                        <input v-model="data.mas_building.parcel_city_extra" class="form-control w-100 form-control-sm"
                            :data-parsley-required="buildings.active"
                            data-parsley-error-message="建物の所在を入力してください。"
                            type="text" placeholder="その他市区町村" :disabled="!initial.editable || !initial.is_modified">
                    </div>
                    <!-- parcel_town -->
                    <div class="form-group">
                        <input v-model="data.mas_building.parcel_town" class="form-control w-100 form-control-sm"
                            type="text" placeholder="町域" :disabled="!initial.editable || !initial.is_modified">
                    </div>
                </td>
                <!-- end - parcel_address -->

                <!-- start - parcel_number -->
                <td>
                    <div class="form-group">
                        <!-- parcel_number_first -->
                        <template>
                            <currency-input v-model="data.mas_building.parcel_number_first"
                                class="form-control form-control-w-xs form-control-sm input-integer"
                                :disabled="!initial.editable || !initial.is_modified" :currency="null"
                                :precision="{ min: 0, max: 4 }"
                                :allow-negative="false" />
                        </template>
                        <span>番</span>
                        <!-- parcel_number_second -->
                        <template>
                            <currency-input v-model="data.mas_building.parcel_number_second"
                                class="form-control form-control-w-xs form-control-sm input-integer"
                                :disabled="!initial.editable || !initial.is_modified" :currency="null"
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
                                <currency-input v-model="data.mas_building.building_number_first"
                                    class="form-control form-control-w-xs form-control-sm input-integer"
                                    data-parsley-trigger="change focusout"
                                    :data-parsley-required="[ data.mas_building.parcel_number_first ? 'true' : 'false' ]"
                                    :disabled="!initial.editable || !initial.is_modified" :currency="null"
                                    :precision="{ min: 0, max: 4 }"
                                    :allow-negative="false" />
                            </template>
                        </span>
                        <span>番</span>
                        <span>
                            <!-- building_number_second -->
                            <template>
                                <currency-input v-model="data.mas_building.building_number_second"
                                    class="form-control form-control-w-xs form-control-sm input-integer"
                                    data-parsley-trigger="change focusout"
                                    :data-parsley-required="[ data.mas_building.parcel_number_second ? 'true' : 'false' ]"
                                    :disabled="!initial.editable || !initial.is_modified" :currency="null"
                                    :precision="{ min: 0, max: 4 }"
                                    :allow-negative="false" />
                            </template>
                        </span>
                        <span>の</span>
                        <span>
                            <!-- building_number_third -->
                            <template>
                                <currency-input v-model="data.mas_building.building_number_third"
                                    class="form-control form-control-w-xs form-control-sm input-integer"
                                    :disabled="!initial.editable || !initial.is_modified" :currency="null"
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
                        <select v-model="data.mas_building.building_usetype" class="form-control form-control-w-md form-control-sm"
                            :disabled="!initial.editable || !initial.is_modified">
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
                        <input v-model="data.mas_building.building_attached" type="checkbox"
                               :id="'building_attached'+table" :value="1" :disabled="!initial.editable || !initial.is_modified">
                        <label :for="'building_attached'+table">有</label>
                    </div>
                    <div v-if="data.mas_building.building_attached == 1" class="form-group">
                        <!-- building_attached_select -->
                        <select v-model="data.mas_building.building_attached_select" class="form-control form-control-sm" :disabled="!initial.editable || !initial.is_modified">
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
                        <select v-model="data.mas_building.building_date_nengou" id="date-nengou"
                                data-parsley-trigger="change focusout"
                                :data-parsley-required="dateCheck(data.mas_building)"
                                :disabled="!initial.editable || !initial.is_modified"
                                class="form-control form-control-w-ms form-control-sm mx-1">
                            <option :value="0" selected="selected"></option>
                            <option :value="1">昭和</option>
                            <option :value="2">平成</option>
                            <option :value="3">令和</option>
                        </select>
                        <!-- building_date_year -->
                        <span>
                            <input v-model="data.mas_building.building_date_year"
                                   class="form-control form-control-w-sm form-control-sm mx-1 input-integer" type="tel"
                                   data-parsley-date-nengou="#date-nengou"
                                   :data-parsley-required="dateCheck(data.mas_building)"
                                   data-parsley-required-message="建築時期 は全て入力してください。"
                                   data-parsley-trigger="focusout"
                                   v-mask="'####'"
                                   :disabled="!initial.editable || !initial.is_modified">
                        </span>
                        <span>年</span>
                        <!-- building_date_month -->
                        <span>
                            <input v-model="data.mas_building.building_date_month"
                                   class="form-control form-control-w-xs form-control-sm mx-1 input-integer" type="tel"
                                   data-parsley-trigger="change focusout"
                                   :data-parsley-required="dateCheck(data.mas_building)"
                                   data-parsley-required-message="建築時期 は全て入力してください。"
                                   data-parsley-max="12"
                                   v-mask="'##'"
                                   :disabled="!initial.editable || !initial.is_modified">
                        </span>
                        <span>月</span>
                        <!-- building_date_day -->
                        <span>
                            <input v-model="data.mas_building.building_date_day"
                                   class="form-control form-control-w-xs form-control-sm mx-1 input-integer" type="tel"
                                   data-parsley-trigger="change focusout"
                                   :data-parsley-required="dateCheck(data.mas_building)"
                                   data-parsley-required-message="建築時期 は全て入力してください。"
                                   data-parsley-max="31"
                                   v-mask="'##'"
                                   :disabled="!initial.editable || !initial.is_modified">
                        </span>
                        <span>日</span>
                    </div>
                    <div class="form-result"></div>
                </td>
                <!-- end - parcel_build_date -->

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
                            <select v-model="data.mas_building.building_structure" class="form-control form-control-sm mx-1"
                            :disabled="!initial.editable || !initial.is_modified">
                                <option value="0"></option>
                                <option v-for="structure in master_building_structures" :value="structure.id">
                                    @{{ structure.value }}
                                </option>
                            </select>
                        </div>
                        <div class="col-3 px-1">
                            <!-- building_floor_count -->
                            <input v-model="data.mas_building.building_floor_count" class="form-control form-control-sm mx-1" type="tel"
                            :disabled="!initial.editable || !initial.is_modified"
                            @input="calculateFloorCount(data.mas_building)"
                            v-mask="'##'">
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
                        <select v-model="data.mas_building.building_roof" class="form-control form-control-sm"
                            :disabled="!initial.editable || !initial.is_modified">
                            <option value="0"></option>
                            <option v-for="roof in master_building_roofs" :value="roof.id">
                                @{{ roof.value }}
                            </option>
                        </select>
                    </div>
                </td>
                <td>
                    <div class="form-group row mx-n1">
                        <div v-for="(floor, index) in data.mas_building.building_floors" class="px-1 col-2 my-1" v-show="!floor.deleted">
                            <div class="row mx-0">
                                <div class="px-0 col-5 d-flex align-items-center justify-content-end mr-1">
                                    <span>@{{ index+1 }}階</span>
                                </div>
                                <div class="px-0 col-6">
                                    <!-- floor_size -->
                                    <template>
                                        <currency-input v-model="floor.floor_size"
                                            class="form-control form-control-sm w-100 input-decimal"
                                            :disabled="!initial.editable || !initial.is_modified" :currency="null"
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
