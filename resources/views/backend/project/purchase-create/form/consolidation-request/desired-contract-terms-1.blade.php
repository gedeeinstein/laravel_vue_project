<div class="sub-label" style="background: #eee; padding: 0.2em 0.4em; margin: 0.5em 0; font-weight: bold;">
    契約希望条件</div>
<div v-if="purchase_doc.heads_up_a == 1" class="form-check icheck-cyan">
   <input @click="confirmation(purchase_doc.desired_contract_terms_a, $event)" v-model="purchase_doc.desired_contract_terms_a" class="form-check-input" type="checkbox" name="" id="C01" value="1" data-id="A86-C01" :disabled="purchase_doc.gathering_request_status == 1">
   <label class="form-check-label" for="C01">本物件資料から上下水道が直ちに利用可能であると認識しております。利用可能であることを契約条件とさせて下さい。</label>
</div>
<div v-if="purchase_doc.contract_a == 1" class="form-check icheck-cyan">
   <input @click="confirmation(purchase_doc.desired_contract_terms_b, $event)" v-model="purchase_doc.desired_contract_terms_b" class="form-check-input" type="checkbox" name="" id="C02" value="1" data-id="A86-C02" :disabled="purchase_doc.gathering_request_status == 1">
   <label class="form-check-label" for="C02">売買対象面積は公簿面積で確定するものとし、公簿面積と実測面積に変動ある場合でも、売買代金清算はしないものとさせて下さい。</label>
</div>
<div v-if="purchase_doc.contract_a == 2" class="form-check icheck-cyan">
   <input @click="confirmation(purchase_doc.desired_contract_terms_c, $event)" v-model="purchase_doc.desired_contract_terms_c" class="form-check-input" type="checkbox" name="" id="C03" value="1" data-id="A86-C03" :disabled="purchase_doc.gathering_request_status == 1">
   <label class="form-check-label" for="C03">売買対象面積について、公簿面積と実測面積とに変動ある場合、実測面積を基準として売買代金清算とさせて下さい。尚、実測清算の対象は宅地部分に限るものとし、道路部分は含みません。</label>
</div>
<div v-if="purchase_doc.contract_b == 1" class="form-check icheck-cyan">
   <input @click="confirmation(purchase_doc.desired_contract_terms_d, $event)" v-model="purchase_doc.desired_contract_terms_d" class="form-check-input" type="checkbox" name="" id="C04" value="1" data-id="A86-C04" :disabled="purchase_doc.gathering_request_status == 1">
   <label class="form-check-label" for="C04">売主様の責任と負担において確定測量および地積更生登記をお願いします。不調時は白紙解除とさせて下さい。</label>
</div>
<div v-if="purchase_doc.contract_b == 2" class="form-check icheck-cyan">
   <input @click="confirmation(purchase_doc.desired_contract_terms_e, $event)" v-model="purchase_doc.desired_contract_terms_e" class="form-check-input" type="checkbox" name="" id="C05" value="1" data-id="A86-C05" :disabled="purchase_doc.gathering_request_status == 1">
   <label class="form-check-label" for="C05">買主負担で確定測量および地積更生登記を実施しますが、不調時は白紙解除とさせて下さい。</label>
</div>
<div v-if="purchase_doc.contract_b == 3" class="form-check icheck-cyan">
   <input @click="confirmation(purchase_doc.desired_contract_terms_f, $event)" v-model="purchase_doc.desired_contract_terms_f" class="form-check-input" type="checkbox" name="" id="C06" value="1" data-id="A86-C06" :disabled="purchase_doc.gathering_request_status == 1">
   <label class="form-check-label" for="C06">引渡までに境界の明示をお願いします。境界不明時は境界を復元の上、引渡をお願いします。</label>
</div>
<div v-if="purchase_doc.contract_b == 4" class="form-check icheck-cyan">
   <input @click="confirmation(purchase_doc.desired_contract_terms_g, $event)" v-model="purchase_doc.desired_contract_terms_g" class="form-check-input" type="checkbox" name="" id="C07" value="1" data-id="A86-C07" :disabled="purchase_doc.gathering_request_status == 1">
   <label class="form-check-label" for="C07">売主様は確定測量および地積更生登記を行わないことに、買主は同意しております。</label>
</div>
