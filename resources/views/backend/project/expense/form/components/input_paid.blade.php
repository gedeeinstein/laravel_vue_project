<currency-input v-model.number="{{ $model }}"
    class="form-control form-control-w-lg input-money"
    :currency="null" :precision="{ min: 0, max: 0 }"
    :allow-negative="false"
    :disabled="!initial.editable"
/>
{{ $slot }}