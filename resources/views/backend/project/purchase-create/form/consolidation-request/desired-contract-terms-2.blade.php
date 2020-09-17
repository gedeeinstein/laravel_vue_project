<div v-if="purchase_sale.urbanization_area && purchase_sale.urbanization_area_sub_2 && purchase_doc.contract_c == 1" class="form-check icheck-cyan">
   <input @click="confirmation(purchase_doc.desired_contract_terms_h, $event)" v-model="purchase_doc.desired_contract_terms_h" class="form-check-input" type="checkbox" name="" id="C08" value="1" data-id="A86-C08" :disabled="purchase_doc.gathering_request_status == 1">
   <label class="form-check-label" for="C08">区画整理事業完了時において、清算金が発生した場合は売主様負担とさせて下さい。</label>
</div>
<div v-if="purchase_sale.urbanization_area && purchase_sale.urbanization_area_sub_2 && purchase_doc.contract_c == 2" class="form-check icheck-cyan">
   <input @click="confirmation(purchase_doc.desired_contract_terms_i, $event)" v-model="purchase_doc.desired_contract_terms_i" class="form-check-input" type="checkbox" name="" id="C09" value="1" data-id="A86-C09" :disabled="purchase_doc.gathering_request_status == 1">
   <label class="form-check-label" for="C09">区画整理事業完了時において、清算金が発生した場合は買主負担とします。</label>
</div>
<div v-if="purchase_sale.urbanization_area && purchase_sale.urbanization_area_sub_2 && purchase_doc.contract_d == 1" class="form-check icheck-cyan">
   <input @click="confirmation(purchase_doc.desired_contract_terms_j, $event)" v-model="purchase_doc.desired_contract_terms_j" class="form-check-input" type="checkbox" name="" id="C10" value="1" data-id="A86-C10" :disabled="purchase_doc.gathering_request_status == 1">
   <label class="form-check-label" for="C10">区画整理事業における賦課金が発生した場合は売主様負担とさせて下さい。</label>
</div>
<div v-if="purchase_sale.urbanization_area && purchase_sale.urbanization_area_sub_2 && purchase_doc.contract_d == 2" class="form-check icheck-cyan">
   <input @click="confirmation(purchase_doc.desired_contract_terms_k, $event)" v-model="purchase_doc.desired_contract_terms_k" class="form-check-input" type="checkbox" name="" id="C11" value="1" data-id="A86-C11" :disabled="purchase_doc.gathering_request_status == 1">
   <label class="form-check-label" for="C11">区画整理事業における賦課金が発生した場合は買主負担とします。</label>
</div>
