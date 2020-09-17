<div class="form-group">
    @php 
        $name = 'basic-closing-date';
        $label = __('project.sheet.label.closing_date');
    @endphp
    <label for="{{ $name }}">{{ $label }}</label>

    <date-picker v-model="project.estimated_closing_date" type="date" class="w-100" input-class="form-control form-control-reset input-date" :disabled="status.loading"
        :editable="false" :format="config.date.format" value-type="format" :input-attr="{ placeholder: '@lang( $label )' }">
    </date-picker>
</div>