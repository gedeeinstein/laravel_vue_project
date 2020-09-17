<div class="form-group mb-0" v-init="name = 'sheet-' +( sheetIndex +1 )+ '-checklist-construction-work'">
    <div class="row">
        @php $column = 'col-md-auto' @endphp
        <div class="{{ $column }}">

            <div class="icheck-cyan" v-init="id = 'sheet-' +( sheetIndex +1 )+ '-checklist-construction-work-none'">
                <input type="radio" :id="id" :name="name" data-parsley-checkmin="1"
                    :disabled="status.loading" :value="1" v-model="checklist.construction_work" />
                <label :for="id" class="fs-12 noselect w-100">
                    <span>@lang('project.sheet.checklist.option.construction.none')</span>
                </label>
            </div>

        </div>
        <div class="{{ $column }}">

            <div class="icheck-cyan" v-init="id = 'sheet-' +( sheetIndex +1 )+ '-checklist-construction-work-non-development'">
                <input type="radio" :id="id" :name="name" data-parsley-checkmin="1"
                    :disabled="status.loading" :value="2" v-model="checklist.construction_work" />
                <label :for="id" class="fs-12 noselect w-100">
                    <span>@lang('project.sheet.checklist.option.construction.non_development')</span>
                </label>
            </div>

        </div>

        <div class="{{ $column }}">

            <div class="icheck-cyan" v-init="id = 'sheet-' +( sheetIndex +1 )+ '-checklist-construction-work-development'">
                <input type="radio" :id="id" :name="name" data-parsley-checkmin="1"
                    :disabled="status.loading" :value="3" v-model="checklist.construction_work" />
                <label :for="id" class="fs-12 noselect w-100">
                    <span>@lang('project.sheet.checklist.option.construction.development')</span>
                </label>
            </div>

        </div>
    </div>
</div>