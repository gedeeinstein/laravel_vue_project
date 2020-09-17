<div class="{{ $column->left }}">
    <div class="question">
        <span class="d-none d-md-inline-block">&nbsp;&nbsp;</span>
        <span>@lang('project.sheet.checklist.label.retaining_wall.length')</span>
    </div>
</div>

<div class="{{ $column->right }}">    
    <div class="form-group mb-0" v-init="name = 'sheet-' +( sheetIndex +1 )+ '-checklist-retaining-wall-length'">
        <div class="row">
            <div class="col-lg-6 mt-2 mt-lg-0">
    
                <div class="input-group input-decimal">
                    <template>
                        <currency-input class="form-control" :name="name" :id="name" v-model="checklist.retaining_wall_length" 
                            :currency="null" :precision="config.currency.precision" :allow-negative="config.currency.negative"
                            :disabled="status.loading" data-parsley-currency-maxlength="8" data-parsley-no-focus
                            data-parsley-trigger="change focusout" />
                    </template>
                    <div class="input-group-append">
                        <label class="input-group-text fs-14 px-2" :for="name">m</label>
                    </div>
                </div>
                <div class="form-result"></div>
    
            </div>
        </div>
    </div>

</div>