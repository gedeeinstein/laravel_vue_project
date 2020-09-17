<div class="{{ $column->left }} d-flex align-items-center">
    <div class="question">
        <span>F2.</span>
        <span>@lang('project.sheet.checklist.label.driveway.road_consent')</span>
    </div>
</div>

<div class="{{ $column->right }}">    
    <div class="form-group mb-0" v-init="name = 'sheet-' +( sheetIndex +1 )+ '-checklist-road-consent'">
        <div class="row">
            @php $column = 'col-sm-4 col-md-auto' @endphp
            <div class="{{ $column }}">
    
                <div class="icheck-cyan" v-init="id = 'sheet-' +( sheetIndex +1 )+ '-checklist-road-consent-yes'">
                    <input type="radio" :id="id" :name="name" data-parsley-checkmin="1"
                        :disabled="status.loading" :value="1" v-model="checklist.traffic_excavation_consent" />
                    <label :for="id" class="fs-12 noselect w-100">
                        <span>@lang('project.sheet.checklist.option.road.consent.yes')</span>
                    </label>
                </div>
    
            </div>
            <div class="{{ $column }}">
    
                <div class="icheck-cyan" v-init="id = 'sheet-' +( sheetIndex +1 )+ '-checklist-road-consent-no'">
                    <input type="radio" :id="id" :name="name" data-parsley-checkmin="1"
                        :disabled="status.loading" :value="2" v-model="checklist.traffic_excavation_consent" />
                    <label :for="id" class="fs-12 noselect w-100">
                        <span>@lang('project.sheet.checklist.option.road.consent.no')</span>
                    </label>
                </div>
    
            </div>
        </div>
    </div>

</div>