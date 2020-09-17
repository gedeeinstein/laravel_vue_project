<div class="{{ $column->left }} d-flex align-items-center">
    <div class="question">
        <span>D6.</span>
        <span>@lang('project.sheet.checklist.label.road.embankment')</span>
    </div>
</div>

<div class="{{ $column->right }}">    
    <div class="form-group mb-0">
        <div class="row">
            <div class="col-lg-6 mt-2 mt-lg-0">
                
                <!-- Road embankment fill - Start -->
                <div class="input-group input-decimal" v-init="name = 'sheet-' +( sheetIndex +1 )+ '-checklist-road-embankment'">
                    <template>
                        <currency-input class="form-control" :name="name" :id="name" v-model="checklist.fill" 
                            :currency="null" :precision="config.currency.precision" :allow-negative="config.currency.negative"
                            :disabled="status.loading || 1 === checklist.no_fill" data-parsley-no-focus data-parsley-currency-maxlength="8" 
                            data-parsley-trigger="change focusout" />
                    </template>
                    <div class="input-group-append">
                        <label class="input-group-text fs-14 px-2" :for="name">m<sup>3</sup></label>
                    </div>
                </div>
                <div class="form-result"></div>
                <!-- Road embankment fill - End -->
    
            </div>
            <div class="col-lg-6 mt-2 mt-lg-0">
                
                <!-- Road embankment none - Start -->
                <div class="icheck-cyan" v-init="name = 'sheet-' +( sheetIndex +1 )+ '-checklist-road-embankment-none'">
                    <input type="checkbox" :id="name" :name="name" data-parsley-checkmin="1"
                        :disabled="status.loading" :true-value="1" :false-value="0" v-model.number="checklist.no_fill" />
                    <label :for="name" class="fs-12 noselect w-100">
                        <span>@lang('project.sheet.checklist.option.road.embankment.none')</span>
                    </label>
                </div>
                <!-- Road embankment none - End -->
    
            </div>
        </div>
    </div>

</div>