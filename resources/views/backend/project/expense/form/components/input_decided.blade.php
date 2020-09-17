<currency-input v-model.number="{{ $model }}"
    class="form-control form-control-w-lg input-money"
    :currency="null" :precision="{ min: 0, max: 0 }"
    :allow-negative="false" :disabled="!initial.editable">
</currency-input>
<span v-if="initial.editable">
    <i @click="copyExpense(row, 'decided')" class="copy_xxxx_button far fa-copy cur-pointer text-secondary text-primary ml-1"
        data-toggle="tooltip" title="" data-original-title="支払額へコピー"></i>
</span>
{{ $slot }}