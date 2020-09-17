<div class="{{ $column->left }} d-flex align-items-center">
    <div class="question">
        <span>D1.</span>
        <span>@lang('project.sheet.checklist.label.water_access')</span>
    </div>
</div>

<div class="{{ $column->right }}">    
    <div class="form-group mb-0" v-init="name = 'sheet-' +( sheetIndex +1 )+ '-checklist-water-access'">
        <div class="row">
            <div class="col-lg-6 mt-2 mt-lg-0">
    
                <div class="input-group input-integer">
                    <template>
                        <currency-input class="form-control" :name="name" :id="name" v-model="checklist.water_draw_count" 
                            :currency="null" :precision="0" :allow-negative="config.currency.negative"
                            :disabled="status.loading" data-parsley-no-focus data-parsley-currency-max="65535" 
                            data-parsley-trigger="change focusout" />
                    </template>
                    <div class="input-group-append">
                        <label class="input-group-text fs-14 px-2" :for="name">
                            @lang('project.sheet.checklist.label.location')
                        </label>
                    </div>
                </div>
                <div class="form-result"></div>
    
            </div>
        </div>
    </div>

</div>