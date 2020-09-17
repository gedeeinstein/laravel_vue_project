<date-picker
    v-model="{{ $model }}" type="date"
    v-mask="'####/##/##'"
    class="w-100" input-class="form-control form-control-w-lg input-date"
    format="YYYY/MM/DD" value-type="format"
    :disabled="!initial.editable"
/>
{{ $slot }}