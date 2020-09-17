<div class="col-md-6">
    <div class="card card-project">
        <div class="card-header">
            @lang('project_purchases_sales.area')
        </div>
        <div class="card-body">
            <div class="row form-group">
                <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3 col-header">
                    <strong>@lang('project_purchases_sales.total_registered_area')</strong>
                </div>

                <!-- start - purchase_sale.registry_size -->
                <div class="input-group col-4 pl-0 pr-1">
                    <input class="form-control" name="registry_size" type="text"
                        :value="purchase_sales.registry_size | numeralFormat(2)"
                        readonly="readonly"
                    >
                    <div class="input-group-append">
                        <div class="input-group-text">m<sup>2</sup></div>
                    </div>
                </div>
                <!-- end - purchase_sale.registry_size -->

                <!-- start - purchase_sale.registry_size tsubo -->
                <div class="input-group col-4 pl-0 pr-1">
                    <input class="form-control" type="text"
                        :value="purchase_sales.registry_size | tsubo | numeralFormat(2)"
                        readonly="readonly"
                    >
                    <div class="input-group-append">
                        <div class="input-group-text">坪</div>
                    </div>
                </div>
                <!-- end - purchase_sale.registry_size tsubo -->

                <!-- start - purchase_sale.registry_size_status -->
                <div class="col-md-7 offset-md-3 mt-3 pl-0">
                    <div class="icheck-cyan d-inline pl-2">
                        <input id="registry_size_status_1" v-model="purchase_sales.registry_size_status"
                            name="registry_size_status" class="form-check-input" type="radio"
                            value="1" :disabled="!initial.editable"
                        >
                        <label class="form-check-label" for="registry_size_status_1">確</label>
                    </div>
                    <div class="icheck-cyan d-inline pl-2">
                        <input id="registry_size_status_2" v-model="purchase_sales.registry_size_status"
                            name="registry_size_status" class="form-check-input" type="radio"
                            value="2" :disabled="!initial.editable"
                        >
                        <label class="form-check-label" for="registry_size_status_2">予</label>
                    </div>
                </div>
                <!-- end - purchase_sale.registry_size_status -->

            </div>
            <div class="row form-group">
                <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3 col-header">
                    <strong>@lang('project_purchases_sales.measured_total_area')</strong>
                </div>

                <!-- start - purchase_sale.survey_size -->
                <div class="input-group col-4 pl-0 pr-1">
                    <input class="form-control" name="survey_size" type="text"
                        :value="purchase_sales.survey_size | numeralFormat(2)"
                        readonly="readonly"
                    >
                    <div class="input-group-append">
                        <div class="input-group-text">m<sup>2</sup></div>
                    </div>
                </div>
                <!-- end - purchase_sale.survey_size -->

                <!-- start - purchase_sale.survey_size tsubo -->
                <div class="input-group col-4 pl-0 pr-1">
                    <input class="form-control" type="text"
                        :value="purchase_sales.survey_size | tsubo | numeralFormat(2)"
                        readonly="readonly"
                    >
                    <div class="input-group-append">
                        <div class="input-group-text">坪</div>
                    </div>
                </div>
                <!-- end - purchase_sale.survey_size tsubo -->

                <!-- start - purchase_sale.survey_size_status -->
                <div class="col-md-7 offset-md-3 mt-3 pl-0">
                    <div class="icheck-cyan d-inline pl-2">
                        <input class="form-check-input" type="radio" name="survey_size_status"
                            id="survey_size_status1" value="1" v-model="purchase_sales.survey_size_status"
                            :disabled="!initial.editable"
                        >
                        <label class="form-check-label" for="survey_size_status1">確</label>
                    </div>
                    <div class="icheck-cyan d-inline pl-2">
                        <input class="form-check-input" type="radio" name="survey_size_status"
                            id="survey_size_status2" value="2" v-model="purchase_sales.survey_size_status"
                            :disabled="!initial.editable"
                        >
                        <label class="form-check-label" for="survey_size_status2">予</label>
                    </div>
                    <div class="icheck-cyan d-inline pl-2">
                        <input class="form-check-input" type="radio" name="survey_size_status"
                            id="survey_size_status3" value="3" v-model="purchase_sales.survey_size_status"
                            :disabled="!initial.editable"
                        >
                        <label class="form-check-label" for="survey_size_status3">無</label>
                    </div>
                </div>
                <!-- end - purchase_sale.survey_size_status -->

            </div>
            <div class="row form-group">
                <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3 col-header">
                    <span class="text-info">※</span>
                    <strong>@lang('project_purchases_sales.the_area_of_​​this_pj')</strong>
                </div>
                <div class="input-group col-4 pl-0 pr-1">

                    <!-- start - purchase_sale.project_size -->
                    <template>
                        <currency-input v-model="purchase_sales.project_size"
                            class="form-control input-decimal" :currency="null"
                            :precision="{ min: 0, max: 2 }" :allow-negative="false"
                            data-parsley-no-focus required data-parsley-trigger="change focusout"
                            :disabled="status.loading || !initial.editable"
                        />
                    </template>
                    <div class="input-group-append">
                        <div class="input-group-text">m<sup>2</sup></div>
                    </div>
                    <!-- end - purchase_sale.project_size -->

                </div>
                <div class="input-group col-4 pl-0 pr-1">
                    <input class="form-control" type="text"
                        :value="purchase_sales.project_size | tsubo | numeralFormat(2)"
                        readonly="readonly"
                    >
                    <div class="input-group-append">
                        <div class="input-group-text">坪</div>
                    </div>
                </div>
                <div class="form-result col-12 offset-md-3 pl-0"></div>

                <!-- start - purchase_sale.project_size_status -->
                <div class="col-md-7 offset-md-3 mt-3 pl-0">
                    <div class="icheck-cyan d-inline pr-2">
                        <input class="form-check-input" type="radio" name="project_size_status"
                            id="project_size_status1" value="1" v-model="purchase_sales.project_size_status"
                            :disabled="!initial.editable"
                        >
                        <label class="form-check-label" for="project_size_status1">確定</label>
                    </div>
                    <div class="icheck-cyan d-inline pr-2">
                        <input class="form-check-input" type="radio" name="project_size_status"
                            id="project_size_status2" value="2" v-model="purchase_sales.project_size_status"
                            :disabled="!initial.editable"
                        >
                        <label class="form-check-label" for="project_size_status2">登予</label>
                    </div>
                    <div class="icheck-cyan d-inline pr-2">
                        <input class="form-check-input" type="radio" name="project_size_status"
                            id="project_size_status3" value="3" v-model="purchase_sales.project_size_status"
                            :disabled="!initial.editable"
                        >
                        <label class="form-check-label" for="project_size_status3">無</label>
                    </div>
                    <div class="icheck-cyan d-inline">
                        <input class="form-check-input" type="radio" name="project_size_status"
                            id="project_size_status4" value="4" v-model="purchase_sales.project_size_status"
                            :disabled="!initial.editable"
                        >
                        <label class="form-check-label" for="project_size_status4">実予</label>
                    </div>
                </div>
                <!-- end - purchase_sale.project_size_status -->

            </div>
        </div>
    </div>
</div>
