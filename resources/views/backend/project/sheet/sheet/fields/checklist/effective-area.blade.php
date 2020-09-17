<div class="form-group" v-init="name = 'sheet-' +( sheetIndex +1 )+ '-checklist-effective-area'">
    <label :for="name">@lang('project.sheet.checklist.label.effective_area')</label>
    <div class="row mx-n3">
        <div class="px-3 col-md-6">

            <div class="input-group input-decimal">
                <div class="input-group-prepend">
                    <button type="button" class="btn btn-input-group fs-14" @click="calculateEffectiveArea( checklist )"
                        :disabled="!( project.overall_area && checklist.breakthrough_rate )">
                        @if( !empty( $locale ) && 'jp' == $locale )
                            <span>自</span>
                        @else
                            <i class="far fa-calculator"></i>
                        @endif
                    </button>
                </div>
                <template>
                    <currency-input class="form-control" :name="name" :id="name" v-model="checklist.effective_area" 
                        :currency="null" :precision="{min: 0, max: 2}" :allow-negative="config.currency.negative"
                        :disabled="status.loading" placeholder="0.00" data-parsley-no-focus
                        data-parsley-currency-maxlength="8" data-parsley-trigger="change focusout" />
                </template>
                <div class="input-group-append">
                    <label class="input-group-text fs-14 px-2" :for="name">m<sup>2</sup></label>
                </div>
            </div>
            <div class="form-result"></div>

        </div>
        <div class="px-3 col-md-6 d-flex align-items-center">

            <span v-if="!checklist.effective_area">?坪</span>
            <span v-else>@{{ checklist.effective_area | tsubo | numeralFormat(2) }}坪</span>

        </div>
    </div>
</div>