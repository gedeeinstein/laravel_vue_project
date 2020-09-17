<div class="form-group">
    @php 
        $name = 'basic-overall-area';
        $label = __('project.sheet.label.overall_area');
    @endphp
    <label for="{{ $name }}">{{ $label }}</label>
    <div class="input-group input-decimal">
        <template>
            <currency-input class="form-control" name="{{ $name }}" id="{{ $name }}" v-model="project.overall_area" 
                :currency="null" :precision="config.currency.precision" :allow-negative="config.currency.negative"
                :disabled="status.loading" placeholder="{{ $label }}" />
        </template>
        <div class="input-group-append">
            <label class="input-group-text fs-14 px-2" for="{{ $name }}">m<sup>2</sup></label>
        </div>
    </div>
    <div class="py-1 px-2">
        <span v-if="!project.overall_area">?坪</span>
        <span v-else>@{{ project.overall_area | tsubo | numeralFormat(2) }}坪</span>
    </div>
</div>