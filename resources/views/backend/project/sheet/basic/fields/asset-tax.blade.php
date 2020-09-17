<div class="form-group">
    @php 
        $name = 'basic-asset-tax';
        $label = __('project.sheet.label.asset_tax');
    @endphp
    <label for="{{ $name }}">{!! $label !!}</label>
    <div class="input-group input-money">
        <template>
            <currency-input class="form-control" name="{{ $name }}" id="{{ $name }}" v-model="project.fixed_asset_tax_route_value" 
                :currency="null" :precision="0" :allow-negative="config.currency.negative"
                :disabled="status.loading" placeholder="{{ $label }}" />
        </template>
        <div class="input-group-append">
            <label class="input-group-text fs-14 px-2" for="{{ $name }}">円</label>
        </div>
    </div>
    <div class="py-1 px-2">
        <span v-if="!project.fixed_asset_tax_route_value">???円</span>
        <span v-else>@{{ project.overall_area * project.fixed_asset_tax_route_value | numeralFormat }}円</span>
    </div>
</div>