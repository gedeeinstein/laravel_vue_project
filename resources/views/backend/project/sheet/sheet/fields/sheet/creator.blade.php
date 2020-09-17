<div class="form-group mb-md-0" v-init="name = 'sheet-' +( sheetIndex +1 )+ '-name'">
    <label :for="name">シート作者名</label>
    <input type="text" class="form-control" :name="name" :id="name" v-model="sheet.creator_name" 
        :disabled="status.loading" data-parsley-maxlength="128"
        data-parsley-trigger="change focusout" data-parsley-no-focus />
</div>