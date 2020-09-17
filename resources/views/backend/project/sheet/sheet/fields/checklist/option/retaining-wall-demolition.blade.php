<div class="form-group mb-0">
    
    <div class="icheck-cyan" v-init="name = 'sheet-' +( sheetIndex +1 )+ '-checklist-retaining-wall-demolition'">
        <input type="checkbox" :id="name" :name="name" data-parsley-checkmin="1"
            :disabled="status.loading" :true-value="1" :false-value="0" v-model.number="checklist.demolition_work_of_retaining_wall" />
        <label :for="name" class="fs-12 noselect w-100">
            <span>@lang('project.sheet.checklist.option.demolition.retaining_wall')</span>
        </label>
    </div>

</div>