<div class="form-group mb-2" v-init="name = 'sheet-' +( sheetIndex +1 )+ '-name'">
    <label :for="name">シート名</label>
    <input type="text" class="form-control" :name="name" :id="name" v-model="sheet.name" 
        :disabled="status.loading" data-parsley-maxlength="128" required
        data-parsley-trigger="change focusout" data-parsley-no-focus />
</div>