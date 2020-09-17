<div class="col-md-6">
    <div class="card card-project">
        <div class="card-header">
            <strong>@lang('project_purchases_sales.inquiry')</strong>
        </div>
        <div class="card-body">
            <div class="form-group">
                <div class="row">
                    <div class="col-md-3">
                        <span class="text-danger">※</span>
                        @lang('project_purchases_sales.inquiry_route')
                    </div>

                    <!-- start - purchase_sale.offer_route -->
                    <div class="col-md-9">
                        <div class="form-group">
                            <multiselect v-model="purchase_sales.offer_route"
                                :options="kind_real_estate" placeholder=""
                                :close-on-select="true" select-label="" deselect-label
                                selected-label="選択中" track-by="name" label="name"
                                data-parsley-no-focus data-parsley-trigger="change focusout">
                            </multiselect>
                            <div class="form-result"></div>
                        </div>
                    </div>
                    <!-- end - purchase_sale.offer_route -->

                </div>
            </div>
            <div class="row">
                <div class="col-md-3">
                    <span class="text-danger">※</span>
                    @lang('project_purchases_sales.inquiry_date')
                </div>

                <!-- start - purchase_sale.offer_date-->
                <div class="col-md-9">
                    <div class="form-group">
                        <date-picker type="date" name="offer_date" value-type="format"
                            input-class="form-control input-date" format="YYYY/MM/DD"
                            v-model="purchase_sales.offer_date" class="w-100"
                            :disabled="!initial.editable" :input-attr="{ required: true }"
                            data-parsley-trigger="change focusout">
                        </date-picker>
                        <div class="form-result"></div>
                    </div>
                </div>
                <!-- end - purchase_sale.offer_date-->

            </div>
        </div>
    </div>
</div>
