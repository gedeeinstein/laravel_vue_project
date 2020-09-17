<input v-model="{{ $model }}" class="form-control form-control-w-xl" type="text" :disabled="!initial.editable">
{{ $slot }}