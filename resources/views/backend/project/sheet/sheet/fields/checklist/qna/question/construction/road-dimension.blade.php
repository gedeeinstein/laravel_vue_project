<div class="{{ $column->left }} d-flex align-items-center">
    <div class="question">
        <span>D3.</span>
        <span>@lang('project.sheet.checklist.label.road.dimension')</span>
    </div>
</div>

<div class="{{ $column->right }}">    
    <div class="form-group mb-0">
        <div class="row">
            <div class="col-lg mt-2 mt-lg-0">
                
                <!-- Road width - Start -->
                <div class="input-group input-decimal" v-init="name = 'sheet-' +( sheetIndex +1 )+ '-checklist-road-width'">
                    <template>
                        <currency-input class="form-control" :name="name" :id="name" v-model="checklist.new_road_width" 
                            :currency="null" :precision="config.currency.precision" :allow-negative="config.currency.negative"
                            :disabled="status.loading" placeholder="@lang('project.sheet.checklist.option.road.width')" 
                            data-parsley-no-focus data-parsley-currency-maxlength="8" data-parsley-trigger="change focusout" />
                    </template>
                    <div class="input-group-append">
                        <label class="input-group-text fs-14 px-2" :for="name">m</label>
                    </div>
                </div>
                <div class="form-result"></div>
                <!-- Road width - End -->
    
            </div>

            <div class="col-lg-auto d-none d-lg-block py-2">
                <span>x</span>
            </div>

            <div class="col-lg mt-2 mt-lg-0">

                <!-- Road length - Start -->
                <div class="input-group input-decimal" v-init="name = 'sheet-' +( sheetIndex +1 )+ '-checklist-road-length'">
                    <template>
                        <currency-input class="form-control" :name="name" :id="name" v-model="checklist.new_road_length" 
                            :currency="null" :precision="config.currency.precision" :allow-negative="config.currency.negative"
                            :disabled="status.loading" placeholder="@lang('project.sheet.checklist.option.road.length')" 
                            data-parsley-no-focus data-parsley-currency-maxlength="8" data-parsley-trigger="change focusout" />
                    </template>
                    <div class="input-group-append">
                        <label class="input-group-text fs-14 px-2" :for="name">m</label>
                    </div>
                </div>
                <div class="form-result"></div>
                <!-- Road length - End -->
    
            </div>
        </div>
    </div>

</div>