<div class="{{ $column->left }} d-flex align-items-center">
    <div class="question">
        <span>B1.</span>
        <span>@lang('project.sheet.checklist.label.building_type')</span>
    </div>
</div>

<div class="{{ $column->right }}">    
    <div class="form-group mb-0" v-init="name = 'sheet-' +( sheetIndex +1 )+ '-checklist-building-type'">
        <div class="row">
            @php $column = 'col-sm-4 col-md-auto' @endphp
            <div class="{{ $column }}">
    
                <div class="icheck-cyan" v-init="id = 'sheet-' +( sheetIndex +1 )+ '-checklist-building-type-wood'">
                    <input type="radio" :id="id" :name="name" data-parsley-checkmin="1"
                        :disabled="status.loading" :value="1" v-model="checklist.type_of_building" />
                    <label :for="id" class="fs-12 noselect w-100">
                        <span>@lang('project.sheet.checklist.option.building_type.wood')</span>
                    </label>
                </div>
    
            </div>
            <div class="{{ $column }}">
    
                <div class="icheck-cyan" v-init="id = 'sheet-' +( sheetIndex +1 )+ '-checklist-building-type-steel'">
                    <input type="radio" :id="id" :name="name" data-parsley-checkmin="1"
                        :disabled="status.loading" :value="2" v-model="checklist.type_of_building" />
                    <label :for="id" class="fs-12 noselect w-100">
                        <span>@lang('project.sheet.checklist.option.building_type.steel')</span>
                    </label>
                </div>
    
            </div>
            <div class="{{ $column }}">
    
                <div class="icheck-cyan" v-init="id = 'sheet-' +( sheetIndex +1 )+ '-checklist-building-type-rc'">
                    <input type="radio" :id="id" :name="name" data-parsley-checkmin="1"
                        :disabled="status.loading" :value="3" v-model="checklist.type_of_building" />
                    <label :for="id" class="fs-12 noselect w-100">
                        <span>RC</span>
                    </label>
                </div>
    
            </div>
        </div>
    </div>

</div>