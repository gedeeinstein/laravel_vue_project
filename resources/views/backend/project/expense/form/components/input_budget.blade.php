<span>@{{ index+1 }}.</span>
<input :value="{{ $model }} | numeralFormat" class="form-control form-control-w-lg" type="text" readonly="readonly">
<span v-if="initial.editable">
    <i @click="copyExpense(row, 'budget')" class="far fa-copy cur-pointer text-secondary text-primary ml-1"
        data-toggle="tooltip" title="" data-original-title="決定総額へコピー"></i>
</span>
{{ $slot }}