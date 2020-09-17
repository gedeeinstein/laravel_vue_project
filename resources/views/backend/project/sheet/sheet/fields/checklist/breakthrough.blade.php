<div class="form-group" v-init="name = 'sheet-' +( sheetIndex +1 )+ '-checklist-breakthrough-rate'">
    <label :for="name">@lang('project.sheet.checklist.label.breakthrough')</label>
    <div class="input-group input-decimal">
        <template>
            <currency-input class="form-control" :name="name" :id="name" v-model="checklist.breakthrough_rate" 
                :currency="null" :precision="config.currency.precision" :allow-negative="config.currency.negative"
                :disabled="status.loading" placeholder="0.00" data-parsley-no-focus
                data-parsley-currency-maxlength="8" data-parsley-trigger="change focusout" />
        </template>
        <div class="input-group-append">
            <label class="input-group-text fs-14 px-2" :for="name">%</label>
        </div>
    </div>
    <div class="form-result"></div>
</div>