<div class="form-check icheck-cyan">
   <input @click="confirmation(purchase_doc.desired_contract_terms_l, $event)" v-model="purchase_doc.desired_contract_terms_l" class="form-check-input" type="checkbox" name="" id="C12" value="1" data-id="A86-C12" :disabled="purchase_doc.gathering_request_status == 1">
   <label class="form-check-label" for="C12">売主様の責任と負担において解体工事を行い、更地での引渡をお願いします。</label>
</div>
<div v-if="purchase_third_person_occupied == 1" class="form-check icheck-cyan">
   <input @click="confirmation(purchase_doc.desired_contract_terms_m, $event)" v-model="purchase_doc.desired_contract_terms_m" class="form-check-input" type="checkbox" name="" id="C18" value="1" data-id="A86-C18" :disabled="purchase_doc.gathering_request_status == 1">
   <label class="form-check-label" for="C18">本物件について、被占有物件に該当しないことを前提として本書を提出させていただいております。実態として、本物件が被占有物件であった場合は、申告頂くようお願いします。</label>
</div>
