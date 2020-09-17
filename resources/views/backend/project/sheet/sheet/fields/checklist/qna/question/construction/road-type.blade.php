<div class="{{ $column->left }} d-flex align-items-center">
    <div class="question">
        <span>D2.</span>
        <span>@lang('project.sheet.checklist.label.road.type')</span>
    </div>
</div>

<div class="{{ $column->right }}">    
    <div class="form-group mb-0" v-init="name = 'sheet-' +( sheetIndex +1 )+ '-checklist-road-type'">
        <div class="row">
            @php $column = 'col-sm-4 col-md-auto' @endphp
            <div class="{{ $column }}">
    
                <div class="icheck-cyan" v-init="id = 'sheet-' +( sheetIndex +1 )+ '-checklist-road-designated'">
                    <input type="radio" :id="id" :name="name" data-parsley-checkmin="1"
                        :disabled="status.loading" :value="1" v-model="checklist.new_road_type" />
                    <label :for="id" class="fs-12 noselect w-100">
                        <span>@lang('project.sheet.checklist.option.road.designated')</span>
                    </label>
                </div>
    
            </div>
            <div class="{{ $column }}">
    
                <div class="icheck-cyan" v-init="id = 'sheet-' +( sheetIndex +1 )+ '-checklist-road-private'">
                    <input type="radio" :id="id" :name="name" data-parsley-checkmin="1"
                        :disabled="status.loading" :value="2" v-model="checklist.new_road_type" />
                    <label :for="id" class="fs-12 noselect w-100">
                        <span>@lang('project.sheet.checklist.option.road.private')</span>
                    </label>
                </div>
    
            </div>
            <div class="{{ $column }}">
    
                <div class="icheck-cyan" v-init="id = 'sheet-' +( sheetIndex +1 )+ '-checklist-road-none'">
                    <input type="radio" :id="id" :name="name" data-parsley-checkmin="1"
                        :disabled="status.loading" :value="3" v-model="checklist.new_road_type" />
                    <label :for="id" class="fs-12 noselect w-100">
                        <span>@lang('project.sheet.checklist.option.road.none')</span>
                    </label>
                </div>
    
            </div>
        </div>
    </div>

</div>