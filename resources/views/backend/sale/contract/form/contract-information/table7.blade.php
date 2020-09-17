<template v-if="buildings.length > 0">
    <div class="bg-success text-center">商品建物</div>
    <div class="table-responsive">
        <table class="table table-bordered table-small table-parcel-list mt-0">
            <thead>
                <tr>
                    <th class="parcel_address_join">所在/地番/家屋番号</th>
                    <th class="parcel_buil_type">種類</th>
                    <th class="parcel_buil_structure">構造</th>
                    <th class="parcel_size">建物m<sup>2</sup>数</th>
                    <th class="parcel_buil_new_or_old">中古/新築</th>
                    <th class="parcel_buil_receipt">受領書</th>
                    <th class="">受領日</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(building, index) in buildings">
                    <!-- start - mas_lot_building address -->
                    <td>
                        <div class="form-group">
                            <input v-model="building_addresses[index]"
                                class="form-control w-100" type="text"
                                data-id="C28-1" :readonly="true"
                            >
                        </div>
                    </td>
                    <!-- end - mas_lot_building address -->

                    <!-- start - mas_lot_building.building_usetype -->
                    <td>
                        <div class="form-group">
                            <select v-model="building.building_usetype"
                                class="form-control form-control-w-xl" data-id="C28-2"
                                :disabled="true">
                                <option value="0"></option>
                                <option v-for="type in master_use_types" :value="type.id">
                                    @{{ type.value }}
                                </option>
                            </select>
                        </div>
                    </td>
                    <!-- end - mas_lot_building.building_usetype -->

                    <!-- start - mas_lot_building.building_structure -->
                    <td>
                        <div class="form-group">
                            <select v-model="building.building_structure"
                                class="form-control form-control-w-xl" :disabled="true">
                                <option value="0"></option>
                                <option v-for="structure in master_building_structures" :value="structure.id">
                                    @{{ structure.value }}
                                </option>
                            </select>
                            <input v-model="building.building_floor_count"
                                class="form-control form-control-w-xs" type="text"
                                data-id="C28-4" :readonly="true"
                            > 階建て
                        </div>
                    </td>
                    <!-- end - mas_lot_building.building_structure -->

                    <!-- start - mas_lot_building floor size -->
                    <td>
                        <input v-model="building_floor_sizes[index]"
                            class="form-control form-control-w-md" data-id="C28-5"
                            type="text" :readonly="true"
                        >
                    </td>
                    <!-- end - mas_lot_building floor size -->

                    <!-- start - sale_contract_residence.type -->
                    <td>
                        <div class="form-check form-check-inline icheck-cyan">
                            <input v-model="residences[index].type" :id="'type_1' + index" :disabled="!initial.editable"
                                class="form-check-input" type="radio" value="1" data-id="C28-6">
                            <label class="form-check-label" :for="'type_1' + index">中古</label>
                        </div>
                        <div class="form-check form-check-inline icheck-cyan">
                            <input v-model="residences[index].type" :id="'type_2' + index" :disabled="!initial.editable"
                                class="form-check-input" type="radio" value="2">
                            <label class="form-check-label" :for="'type_2' + index">新築</label>
                        </div>
                    </td>
                    <!-- end - sale_contract_residence.type -->

                    <!-- start - sale_contract_residence.receipt -->
                    <td>
                        <div class="form-group">
                            <select v-model="residences[index].receipt" :disabled="!initial.editable"
                                class="form-control form-control-w-sm" data-id="C28-7">
                                <option value="1"></option>
                                <option value="2">未</option>
                                <option value="3">済</option>
                            </select>
                        </div>
                    </td>
                    <!-- end - sale_contract_residence.receipt -->

                    <!-- start - sale_contract_residence.receipt_date -->
                    <td>
                        <date-picker v-model="residences[index].receipt_date"
                            type="date" class="w-100"
                            input-class="form-control w-100 input-date"
                            format="YYYY/MM/DD" value-type="format" data-id="C28-9"
                            :disabled="!initial.editable">
                        </date-picker>
                    </td>
                    <!-- end - sale_contract_residence.receipt_date -->
                </tr>
            </tbody>
        </table>
    </div>
    <hr>
</template>
