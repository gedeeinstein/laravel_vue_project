<div class="form-group d-flex flex-column mb-0 h-100" v-init="name = 'sheet-' +( sheetIndex +1 )+ '-memo'">
    <label :for="name">メモ</label>
    <textarea class="form-control flex-grow-1" :name="name" :id="name" v-model="sheet.memo" 
        :disabled="status.loading" data-parsley-maxlength="128" rows="3" 
        data-parsley-trigger="change focusout" data-parsley-no-focus>
    </textarea>
</div>