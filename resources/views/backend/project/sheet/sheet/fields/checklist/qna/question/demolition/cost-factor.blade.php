<div class="{{ $column->left }} d-flex align-items-center">
    <div class="question">
        <span>B3.</span>
        <span>@lang('project.sheet.checklist.label.cost_factor')</span>
    </div>
</div>

<div class="{{ $column->right }}">    
    <div class="form-group mb-0">
        <div class="row">
            @php $column = 'col-12 col-md-auto' @endphp
            <div class="{{ $column }}">

                <div class="icheck-cyan" v-init="name = 'sheet-' +( sheetIndex +1 )+ '-checklist-cost-factor-obstacles'">
                    <input type="checkbox" :id="name" :name="name" data-parsley-checkmin="1"
                        :disabled="status.loading" :true-value="1" :false-value="0" v-model.number="checklist.many_trees_and_stones" />
                    <label :for="name" class="fs-12 noselect w-100">
                        <span>@lang('project.sheet.checklist.option.cost_factor.obstacles')</span>
                    </label>
                </div>
    
            </div>
            <div class="{{ $column }}">

                <div class="icheck-cyan" v-init="name = 'sheet-' +( sheetIndex +1 )+ '-checklist-cost-factor-storeroom'">
                    <input type="checkbox" :id="name" :name="name" data-parsley-checkmin="1"
                        :disabled="status.loading" :true-value="1" :false-value="0" v-model.number="checklist.big_storeroom" />
                    <label :for="name" class="fs-12 noselect w-100">
                        <span>@lang('project.sheet.checklist.option.cost_factor.storeroom')</span>
                    </label>
                </div>
    
            </div>
            <div class="{{ $column }}">

                <div class="icheck-cyan" v-init="name = 'sheet-' +( sheetIndex +1 )+ '-checklist-cost-factor-accessibility'">
                    <input type="checkbox" :id="name" :name="name" data-parsley-checkmin="1"
                        :disabled="status.loading" :true-value="1" :false-value="0" v-model.number="checklist.hard_to_enter" />
                    <label :for="name" class="fs-12 noselect w-100">
                        <span>@lang('project.sheet.checklist.option.cost_factor.accessibility')</span>
                    </label>
                </div>

            </div>
        </div>
    </div>

</div>