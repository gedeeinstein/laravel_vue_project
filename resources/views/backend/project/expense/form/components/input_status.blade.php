<template>
    <div class="form-check icheck-cyan icheck-sm d-inline">
        <input v-model="{{ $model }}" :id="{{ $id."+'_0'" }}" class="form-check-input" :name="{{ $id }}" type="radio" value="1" :disabled="!initial.editable">
        <label class="form-check-label" :for="{{ $id."+'_0'" }}">無</label>
    </div>
    <div class="form-check icheck-cyan icheck-sm d-inline">
        <input v-model="{{ $model }}" :id="{{ $id."+'_1'" }}" class="form-check-input" :name="{{ $id }}" type="radio" value="2" :disabled="!initial.editable">
        <label class="form-check-label" :for="{{ $id."+'_1'" }}">保</label>
    </div>
    <div class="form-check icheck-cyan icheck-sm d-inline">
        <input v-model="{{ $model }}" :id="{{ $id."+'_2'" }}" class="form-check-input" :name="{{ $id }}" type="radio" value="3" :disabled="!initial.editable">
        <label class="form-check-label" :for="{{ $id."+'_2'" }}">済</label>
    </div>
</template>
{{ $slot }}