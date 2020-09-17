<div class="sub-label" style="background: #eee; padding: 0.2em 0.4em; margin: 0.5em 0; font-weight: bold;">
    許諾依頼事項</div>
<div class="form-check icheck-cyan">
   <input @click="confirmation(purchase_doc.request_permission_a, $event)" v-model="purchase_doc.request_permission_a" class="form-check-input" type="checkbox" name="" id="request_permission_a" value="1" data-id="A86-B01" :disabled="purchase_doc.gathering_request_status == 1">
   <label class="form-check-label" for="request_permission_a">本書面に記載の金額、条件等が取りまとめ仲介業者様及び売主様以外に漏洩している事が発覚した場合は、本書の申し込みを取り下げる場合がある事をご了承下さい。</label>
</div>
<div class="form-check icheck-cyan">
   <input @click="confirmation(purchase_doc.request_permission_b, $event)" v-model="purchase_doc.request_permission_b" class="form-check-input" type="checkbox" name="" id="request_permission_b" value="1" data-id="A86-B02" :disabled="purchase_doc.gathering_request_status == 1">
   <label class="form-check-label" for="request_permission_b">本書について受託いただける場合、末尾記載の有効期限満了日までに売主様直筆の署名による売渡承諾書を発行頂き、仲介業者様もしくは売主様による手渡し、FAXもしくはEメールの手段で当社が確認できるようにお願いします。FAXの場合は、電話で双方の確認とさせて下さい。</label>
</div>
<div class="form-check icheck-cyan">
   <input @click="confirmation(purchase_doc.request_permission_c, $event)" v-model="purchase_doc.request_permission_c" class="form-check-input" type="checkbox" name="" id="request_permission_c" value="1" data-id="A86-B03" :disabled="purchase_doc.gathering_request_status == 1">
   <label class="form-check-label" for="request_permission_c">末尾記載の有効期限日時点で売渡承諾書が確認できない場合、本書は取り下げさせて下さい。</label>
</div>
<div class="form-check icheck-cyan">
   <input @click="confirmation(purchase_doc.request_permission_d, $event)" v-model="purchase_doc.request_permission_d" class="form-check-input" type="checkbox" name="" id="request_permission_d" value="1" data-id="A86-B04" :disabled="purchase_doc.gathering_request_status == 1">
   <label class="form-check-label" for="request_permission_d">売主様からご申告をいただいた本書面は、本物件が契約合意に至った場合、契約書に添付することを同意お願いします。</label>
</div>
<div class="form-check icheck-cyan">
   <input @click="confirmation(purchase_doc.request_permission_e, $event)" v-model="purchase_doc.request_permission_e" class="form-check-input" type="checkbox" name="" id="request_permission_e" value="1"  data-id="A86-B05" :disabled="purchase_doc.gathering_request_status == 1">
   <label class="form-check-label" for="request_permission_e">購入法人名義について当社もしくは当社グループ会社になる可能性がある事をご了承ください。</label>
</div>
