<div class="form-group" v-init="name = 'sheet-' +( sheetIndex +1 )+ '-checklist-loan'">
    <label :for="name">@lang('project.sheet.checklist.label.loan')</label>
    <div class="input-group input-money">
        <template>
            <currency-input class="form-control" :name="name" :id="name" v-model="checklist.loan_borrowing_amount" 
                :currency="null" :precision="0" :allow-negative="config.currency.negative"
                :disabled="status.loading" placeholder="0" data-parsley-no-focus
                data-parsley-currency-maxlength="12" data-parsley-trigger="change focusout" />
        </template>
        <div class="input-group-append">
            <label class="input-group-text fs-14 px-2" :for="name">円</label>
        </div>
    </div>
    <div class="form-result"></div>
</div>