<div class="form-group">
    @php 
        $name = 'basic-floor-ratio';
        $label = __('project.sheet.label.floor_ratio');
    @endphp
    <label for="{{ $name }}">{{ $label }}</label>
    <div class="input-group input-decimal">
        <template>
            <currency-input class="form-control" name="{{ $name }}" id="{{ $name }}" v-model="project.floor_area_ratio" 
                :currency="null" :precision="config.currency.precision" :allow-negative="config.currency.negative"
                data-parsley-no-focus data-parsley-trigger="change focusout" data-parsley-percentage-min="50" data-parsley-percentage-max="1300" 
                :disabled="status.loading" placeholder="{{ $label }}" />
        </template>
        <div class="input-group-append">
            <label class="input-group-text fs-14 px-2" for="{{ $name }}">%</label>
        </div>
    </div>
    <div class="form-result"></div>
</div>