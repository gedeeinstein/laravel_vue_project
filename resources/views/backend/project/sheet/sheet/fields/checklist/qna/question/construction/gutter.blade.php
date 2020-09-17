<div class="{{ $column->left }} d-flex align-items-center">
    <div class="question">
        <span>D4.</span>
        <span>@lang('project.sheet.checklist.label.road.gutter')</span>
    </div>
</div>

<div class="{{ $column->right }}">    
    <div class="form-group mb-0" v-init="name = 'sheet-' +( sheetIndex +1 )+ '-checklist-road-gutter'">
        <div class="row">
            @php $column = 'col-sm-4 col-md-auto' @endphp
            <div class="{{ $column }}">
    
                <div class="icheck-cyan" v-init="id = 'sheet-' +( sheetIndex +1 )+ '-checklist-road-gutter-one-side'">
                    <input type="radio" :id="id" :name="name" data-parsley-checkmin="1"
                        :disabled="status.loading" :value="1" v-model="checklist.side_groove" />
                    <label :for="id" class="fs-12 noselect w-100">
                        <span>@lang('project.sheet.checklist.option.road.gutter.one_side')</span>
                    </label>
                </div>
    
            </div>
            <div class="{{ $column }}">
    
                <div class="icheck-cyan" v-init="id = 'sheet-' +( sheetIndex +1 )+ '-checklist-road-gutter-both-sides'">
                    <input type="radio" :id="id" :name="name" data-parsley-checkmin="1"
                        :disabled="status.loading" :value="2" v-model="checklist.side_groove" />
                    <label :for="id" class="fs-12 noselect w-100">
                        <span>@lang('project.sheet.checklist.option.road.gutter.both_sides')</span>
                    </label>
                </div>
    
            </div>
            <div class="{{ $column }}">
    
                <div class="icheck-cyan" v-init="id = 'sheet-' +( sheetIndex +1 )+ '-checklist-road-gutter-none'">
                    <input type="radio" :id="id" :name="name" data-parsley-checkmin="1"
                        :disabled="status.loading" :value="3" v-model="checklist.side_groove" />
                    <label :for="id" class="fs-12 noselect w-100">
                        <span>@lang('project.sheet.checklist.option.road.gutter.none')</span>
                    </label>
                </div>
    
            </div>
        </div>
    </div>

</div>