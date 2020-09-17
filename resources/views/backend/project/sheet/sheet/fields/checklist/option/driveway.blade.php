<div class="form-group mb-0">
    
    <div class="icheck-cyan" v-init="name = 'sheet-' +( sheetIndex +1 )+ '-checklist-driveway'">
        <input type="checkbox" :id="name" :name="name" data-parsley-checkmin="1"
            :disabled="status.loading" :true-value="1" :false-value="0" v-model.number="checklist.driveway" />
        <label :for="name" class="fs-12 noselect w-100">
            <span>@lang('project.sheet.checklist.option.driveway')</span>
        </label>
    </div>

</div>