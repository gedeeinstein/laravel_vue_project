<input v-model="{{ $model }}" v-mask="'##/##'" class="form-control form-control-w-sm input-date" type="text" placeholder="年/月" :disabled="!initial.editable">
{{ $slot }}