<div class="{{ $column->left }}">
    <div class="question">
        <span class="d-none d-md-inline-block">&nbsp;&nbsp;</span>
        <span>@lang('project.sheet.checklist.label.retaining_wall.height')</span>
    </div>
</div>

<div class="{{ $column->right }}">    
    <div class="form-group mb-0" v-init="name = 'sheet-' +( sheetIndex +1 )+ '-checklist-retaining-wall-height'">
        <div class="row">
            @php $column = 'col-6 col-sm-4 col-lg-3' @endphp
            <div class="{{ $column }}">
    
                <div class="icheck-cyan" v-init="id = 'sheet-' +( sheetIndex +1 )+ '-checklist-retaining-wall-height-1'">
                    <input type="radio" :id="id" :name="name" data-parsley-checkmin="1"
                        :disabled="status.loading" :value="1" v-model="checklist.retaining_wall_height" />
                    <label :for="id" class="fs-12 noselect w-100">
                        <span>0.5</span>
                    </label>
                </div>
    
            </div>
            <div class="{{ $column }}">
    
                <div class="icheck-cyan" v-init="id = 'sheet-' +( sheetIndex +1 )+ '-checklist-retaining-wall-height-2'">
                    <input type="radio" :id="id" :name="name" data-parsley-checkmin="1"
                        :disabled="status.loading" :value="2" v-model="checklist.retaining_wall_height" />
                    <label :for="id" class="fs-12 noselect w-100">
                        <span>0.75</span>
                    </label>
                </div>
    
            </div>
            <div class="{{ $column }}">
    
                <div class="icheck-cyan" v-init="id = 'sheet-' +( sheetIndex +1 )+ '-checklist-retaining-wall-height-3'">
                    <input type="radio" :id="id" :name="name" data-parsley-checkmin="1"
                        :disabled="status.loading" :value="3" v-model="checklist.retaining_wall_height" />
                    <label :for="id" class="fs-12 noselect w-100">
                        <span>1</span>
                    </label>
                </div>
    
            </div>
            <div class="{{ $column }}">
    
                <div class="icheck-cyan" v-init="id = 'sheet-' +( sheetIndex +1 )+ '-checklist-retaining-wall-height-4'">
                    <input type="radio" :id="id" :name="name" data-parsley-checkmin="1"
                        :disabled="status.loading" :value="4" v-model="checklist.retaining_wall_height" />
                    <label :for="id" class="fs-12 noselect w-100">
                        <span>1.5</span>
                    </label>
                </div>
    
            </div>
            <div class="{{ $column }}">
    
                <div class="icheck-cyan" v-init="id = 'sheet-' +( sheetIndex +1 )+ '-checklist-retaining-wall-height-5'">
                    <input type="radio" :id="id" :name="name" data-parsley-checkmin="1"
                        :disabled="status.loading" :value="5" v-model="checklist.retaining_wall_height" />
                    <label :for="id" class="fs-12 noselect w-100">
                        <span>1.75</span>
                    </label>
                </div>
    
            </div>
            <div class="{{ $column }}">
    
                <div class="icheck-cyan" v-init="id = 'sheet-' +( sheetIndex +1 )+ '-checklist-retaining-wall-height-6'">
                    <input type="radio" :id="id" :name="name" data-parsley-checkmin="1"
                        :disabled="status.loading" :value="6" v-model="checklist.retaining_wall_height" />
                    <label :for="id" class="fs-12 noselect w-100">
                        <span>1.95</span>
                    </label>
                </div>
    
            </div>
            <div class="{{ $column }}">
    
                <div class="icheck-cyan" v-init="id = 'sheet-' +( sheetIndex +1 )+ '-checklist-retaining-wall-height-7'">
                    <input type="radio" :id="id" :name="name" data-parsley-checkmin="1"
                        :disabled="status.loading" :value="7" v-model="checklist.retaining_wall_height" />
                    <label :for="id" class="fs-12 noselect w-100">
                        <span>@lang('project.sheet.checklist.option.retaining_wall.more')</span>
                    </label>
                </div>
    
            </div>
        </div>
    </div>

</div>