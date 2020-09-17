<div class="form-group" v-init="name = 'sheet-' +( sheetIndex +1 )+ '-checklist-resale-fee'">
    @php $option = 'project.sheet.checklist.option.fee' @endphp
    <label :for="name">@lang('project.sheet.checklist.label.resale_fee')</label>
    <select class="form-control" :name="name" :id="name" v-model.number="checklist.resale_brokerage_fee" :disabled="status.loading">
        <option value="0"></option>
        <option value="1">@lang("{$option}.expense")</option>
        <option value="2">@lang("{$option}.revenue")</option>
        <option value="3">@lang("{$option}.none")</option>
    </select>
    <div class="form-result"></div>
</div>