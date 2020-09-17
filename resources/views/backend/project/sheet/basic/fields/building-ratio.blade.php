<div class="form-group">
    @php 
        $name = 'basic-building-ratio';
        $label = __('project.sheet.label.building_ratio');
    @endphp
    <label for="{{ $name }}">{{ $label }}</label>
    <div class="input-group input-decimal">
        <template>
            <currency-input class="form-control" name="{{ $name }}" id="{{ $name }}" v-model="project.building_coverage_ratio" 
                :currency="null" :precision="config.currency.precision" :allow-negative="config.currency.negative"
                data-parsley-no-focus data-parsley-trigger="change focusout" data-parsley-percentage-min="0" data-parsley-percentage-max="80" 
                :disabled="status.loading" placeholder="{{ $label }}" />
        </template>
        <div class="input-group-append">
            <label class="input-group-text fs-14 px-2" for="{{ $name }}">%</label>
        </div>
    </div>
    <div class="form-result"></div>
</div>