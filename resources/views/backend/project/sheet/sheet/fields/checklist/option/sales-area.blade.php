<div class="form-group mb-0" v-init="name = 'sheet-' +( sheetIndex +1 )+ '-checklist-sales-area'">
    <div class="row">
        @php $column = 'col-md-auto' @endphp
        <div class="{{ $column }}">

            <div class="icheck-cyan" v-init="id = 'sheet-' +( sheetIndex +1 )+ '-checklist-sales-area-single'">
                <input type="radio" :id="id" :name="name" data-parsley-checkmin="1" required
                    :disabled="status.loading" :value="1" v-model="checklist.sales_area" />
                <label :for="id" class="fs-12 noselect w-100">
                    <span>1</span><span>@lang('project.sheet.checklist.option.sales_area.single')</span>
                </label>
            </div>

        </div>
        <div class="{{ $column }}">

            <div class="icheck-cyan" v-init="id = 'sheet-' +( sheetIndex +1 )+ '-checklist-sales-area-multi'">
                <input type="radio" :id="id" :name="name" data-parsley-checkmin="1" required
                    :disabled="status.loading" :value="2" v-model="checklist.sales_area" />
                <label :for="id" class="fs-12 noselect w-100">
                    <span>2</span><span>@lang('project.sheet.checklist.option.sales_area.multi')</span>
                </label>
            </div>

        </div>
    </div>
    <div class="form-result"></div>
</div>