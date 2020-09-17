<div class="form-check icheck-cyan icheck-sm d-inline">
    <input v-model="{{ $model }}" :id="{{ $id }}" class="form-check-input" name="taxfree" type="checkbox" value="1" :disabled="!initial.editable || row.const_tax">
    <label class="form-check-label" :for="{{ $id }}">非</label>
</div>
{{ $slot }}