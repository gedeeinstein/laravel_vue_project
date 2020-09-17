<select v-model="{{ $model }}" class="form-control form-control-w-xl" :disabled="!initial.editable">
    <option value="0"></option>
    <option v-for="bank in master.bank" :value="bank.id">
        @{{ bank.name }}
    </option>
</select>
{{ $slot }}