<div class="col-md-6">
    <div class="card card-project">
        <div class="card-header">
            <strong>@lang('project_purchases_sales.basic_city_planning')</strong>
        </div>
        <div class="card-body">
            <div class="form-group row">
                <div class="col-lg-4">
                    <label><span class="text-danger">※</span> 基本都市計画</label>
                </div>
                <div class="col-lg-8">

                    <!-- start - purchase_sale.project_urbanization_area -->
                    <select class="form-control" name="project_urbanization_area"
                        v-model="purchase_sales.project_urbanization_area" data-parsley-no-focus required
                        data-parsley-trigger="change focusout" :disabled="status.loading || !initial.editable">
                        <option value="0"></option>
                        <option value="1">市街化区域</option>
                        <option value="2">市街化調整区域</option>
                        <option value="3">区画整理地内</option>
                        <option value="4">非線引区域</option>
                        <option value="5">都市計画区域外</option>
                    </select>
                    <!-- end - purchase_sale.project_urbanization_area -->

                </div>
            </div>
            <template v-if="parseInt( purchase_sales.project_urbanization_area ) === 3">

                <div class="form-group row">
                    <div class="col-lg-4">
                        <label>土地区画整理事業</label>
                    </div>
                    <div class="col-lg-8">

                        <!-- start - purchase_sale.project_urbanization_area_status -->
                        <div class="row mb-2">
                            <div class="col-auto">
                                <div class="icheck-cyan d-inline mr-2">
                                    <input class="form-check-input" type="radio"
                                        name="project_urbanization_area_status"
                                        id="project_urbanization_area_status1" value="1"
                                        v-model="purchase_sales.project_urbanization_area_status"
                                        :disabled="!initial.editable"
                                    >
                                    <label class="form-check-label" for="project_urbanization_area_status1">計画有</label>
                                </div>
                            </div>
                            <div class="col-auto">
                                <div class="icheck-cyan d-inline mr-2">
                                    <input class="form-check-input" type="radio"
                                        name="project_urbanization_area_status"
                                        id="project_urbanization_area_status2" value="2"
                                        v-model="purchase_sales.project_urbanization_area_status"
                                        :disabled="!initial.editable"
                                    >
                                    <label class="form-check-label" for="project_urbanization_area_status2">施工中</label>
                                </div>
                            </div>
                        </div>
                        <!-- end - purchase_sale.project_urbanization_area_status -->

                        <!-- start - purchase_sale.project_urbanization_area_sub -->
                        <div class="row">
                            <div class="col-12">
                                <select class="form-control" name="project_urbanization_area_sub"
                                    v-model="purchase_sales.project_urbanization_area_sub"
                                    :disabled="!initial.editable">
                                    <option value="0"></option>
                                    <option value="1">保留地</option>
                                    <option value="2">仮換地</option>
                                </select>
                            </div>
                        </div>
                        <!-- end - purchase_sale.project_urbanization_area_sub -->

                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-lg-4">
                        <label>収益開始日</label>
                    </div>

                    <!-- start - purchase_sale.project_urbanization_area_date -->
                    <div class="col-lg-8">
                        <date-picker type="date" class="w-100" input-class="form-control input-date"
                            value-type="format" format="YYYY/MM/DD" name="project_urbanization_area_date"
                            v-model="purchase_sales.project_urbanization_area_date" :disabled="!initial.editable">
                        </date-picker>
                    </div>
                    <!-- end - purchase_sale.project_urbanization_area_date -->

                </div>
            </template>
        </div>
    </div>
</div>
