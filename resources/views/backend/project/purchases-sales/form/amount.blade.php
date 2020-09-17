<div class="col-md-6">
    <div class="card card-project">
        <div class="card-header">
            <strong>@lang('project_purchases_sales.amount')</strong>
        </div>
        <div class="card-body">
            <div class="row form-group">
                <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3 col-header">
                    <strong>@lang('project_purchases_sales.expected_purchase_price')</strong>
                </div>
                <div class="col-8 pl-0">

                    <!-- start - purchase_sale.purchase_price -->
                    <template>
                        <currency-input v-model="purchase_sales.purchase_price"
                            class="form-control"
                            :currency="null" readonly="readonly"
                            :precision="{ min: 0, max: 4 }"
                            :allow-negative="false"
                        />
                    </template>
                    <!-- end - purchase_sale.purchase_price -->

                    <!-- start - url to project sheet -->
                    <div class="help-block col-12">※予定価格の修正は <a target="_blank" href="{{ $project_sheet_url }}">PJシート</a> で行ってください。</div>
                    <!-- end - url to project sheet -->
                    
                </div>
            </div>
        </div>
    </div>
</div>
