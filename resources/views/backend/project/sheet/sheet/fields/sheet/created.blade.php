<div class="form-group mb-md-0" v-init="name = 'sheet-' +( sheetIndex +1 )+ '-created'">
    <label :for="name">作成日</label>
    <date-picker type="date" class="w-100" input-class="form-control form-control-reset" v-model="sheet.created_at" 
        :disabled="status.loading" :editable="false" :format="config.date.format" value-type="format" :input-attr="{ placeholder: '作成日' }">
    </date-picker>
</div>