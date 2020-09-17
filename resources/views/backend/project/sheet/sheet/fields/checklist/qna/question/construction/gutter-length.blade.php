<div class="{{ $column->left }} d-flex align-items-center">
    <div class="question">
        <span>D5.</span>
        <span>@lang('project.sheet.checklist.label.road.gutter_length')</span>
    </div>
</div>

<div class="{{ $column->right }}">    
    <div class="form-group mb-0">
        <div class="row">
            <div class="col-lg-6 mt-2 mt-lg-0">
                
                <!-- Road gutter length - Start -->
                <div class="input-group input-decimal" v-init="name = 'sheet-' +( sheetIndex +1 )+ '-checklist-road-gutter-length'">
                    <template>
                        <currency-input class="form-control" :name="name" :id="name" v-model="checklist.side_groove_length" 
                            :currency="null" :precision="config.currency.precision" :allow-negative="config.currency.negative"
                            :disabled="status.loading" placeholder="@lang('project.sheet.checklist.option.road.length')" 
                            data-parsley-no-focus data-parsley-currency-maxlength="8" data-parsley-trigger="change focusout" />
                    </template>
                    <div class="input-group-append">
                        <label class="input-group-text fs-14 px-2" :for="name">m</label>
                    </div>
                </div>
                <div class="form-result"></div>
                <!-- Road gutter length - End -->
    
            </div>
        </div>
    </div>

</div>