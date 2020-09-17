
<!-- Company Name - Start -->
<div class="row form-group py-2 border-0">
    @php $label = __('label.$company.form.label.name') @endphp
    <div class="col-md-3 col-header">
        <span class="bg-danger label-required">@lang('label.required')</span>
        <strong class="field-title">{{ $label }}</strong>
    </div>
    <div class="col-md-9 col-content">
        <input type="text" id="company-name" name="company-name" class="form-control" v-model.trim="item.name" data-parsley-no-focus
            required placeholder="{{ $label }}" data-parsley-trigger="change focusout" :disabled="status.loading" data-parsley-maxlength="128" />
    </div>
</div>
<!-- Company Name - End -->

<!-- Company Kana Name - Start -->
<div class="row form-group py-2 border-0">
    @php $label = __('label.$company.form.label.name_kana') @endphp
    <div class="col-md-3 col-header">
        <span class="bg-danger label-required">@lang('label.required')</span>
        <strong class="field-title">{{ $label }}</strong>
    </div>
    <div class="col-md-9 col-content">
        <input type="text" id="company-name-kana" name="company-name-kana" class="form-control" v-model.trim="item.name_kana" data-parsley-no-focus
            required placeholder="{{ $label }}" data-parsley-trigger="change focusout" :disabled="status.loading" data-parsley-maxlength="128" />
    </div>
</div>
<!-- Company Kana Name - End -->

<!-- Company Types - Start -->
<div class="row form-group py-2 border-0" v-if="types.length">
    @php $label = __('label.$company.form.label.type') @endphp
    <div class="col-md-3 col-header">
        <strong class="field-title">{{ $label }}</strong>
    </div>
    <div class="col-md-9 col-content">
        <div class="row mx-n2">
            <div v-for="entry in types" class="px-2 col-12 col-sm-6 col-lg-4 paste-in">
                <div v-if="entry.tooltip.length" class="icheck-cyan" data-tooltip :title="entry.tooltip" data-tooltip-extend="tooltip-offset-left">
                    <input type="checkbox" :id="'company-' + entry.name" :name="'company-' + entry.name" data-parsley-checkmin="1"
                        v-model="item[ entry.type ]" :disabled="status.loading" :true-value="1" :false-value="0" />
                    <label :for="'company-' + entry.name" class="text-uppercase fs-12 noselect w-100">@{{ entry.label }}</label>
                </div>
                <div v-else class="icheck-cyan">
                    <input type="checkbox" :id="'company-' + entry.name" :name="'company-' + entry.name" data-parsley-checkmin="1"
                        v-model="item[ entry.type ]" :disabled="status.loading" :true-value="1" :false-value="0" />
                    <label :for="'company-' + entry.name" class="text-uppercase fs-12 noselect w-100">@{{ entry.label }}</label>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Company Types - End -->
