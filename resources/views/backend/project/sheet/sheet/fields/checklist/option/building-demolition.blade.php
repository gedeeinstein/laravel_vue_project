<div class="form-group mb-0">
    
    <div class="icheck-cyan" v-init="name = 'sheet-' +( sheetIndex +1 )+ '-checklist-building-demolition'">
        <input type="checkbox" :id="name" :name="name" data-parsley-checkmin="1"
            :disabled="status.loading" :true-value="1" :false-value="0" v-model.number="checklist.building_demolition_work" />
        <label :for="name" class="fs-12 noselect w-100">
            <span>@lang('project.sheet.checklist.option.demolition.building')</span>
        </label>
    </div>

</div>