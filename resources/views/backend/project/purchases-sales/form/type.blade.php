<div class="col-md-6">
    <div class="card card-project">
        <div class="card-header">
            <strong>@lang('project_purchases_sales.type')</strong>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-3">
                    <span class="text-danger">※</span>
                    @lang('project_purchases_sales.property_line')
                </div>
                <div class="col-md-6">

                    <!-- start - purchase_sale.project_type -->
                    <div class="form-group">
                        <select class="form-control" name="project_type"
                            v-model="purchase_sales.project_type" :disabled="!initial.editable"
                            data-parsley-no-focus required data-parsley-trigger="change focusout">
                            <option></option>
                            <option value="1">土地</option>
                            <option value="2">建物</option>
                            <option value="3">マンション</option>
                        </select>
                        <div class="form-result"></div>
                    </div>
                    <!-- end - purchase_sale.project_type -->

                </div>
            </div>
        </div>
    </div>
</div>
