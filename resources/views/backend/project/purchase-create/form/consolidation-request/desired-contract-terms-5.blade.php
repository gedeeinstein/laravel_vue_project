<div v-if="purchase_doc.properties_description_a == 2 && purchase_doc.properties_description_b == 2 && purchase_doc.properties_description_c == 1 && purchase_doc.properties_description_d == 0"
class="form-check icheck-cyan">
   <input @click="confirmation(purchase_doc.desired_contract_terms_u, $event)" v-model="purchase_doc.desired_contract_terms_u" class="form-check-input" type="checkbox" name="" id="C50" value="1" data-id="A86-C50" :disabled="purchase_doc.gathering_request_status == 1">
   <label class="form-check-label" for="C50">現況有姿にてお引き渡しを受けるものとします。　ただし、残置物については売主様にて撤去いただくようお願い致します。</label>
</div>
<div v-if="(purchase_doc.properties_description_a == 2 && purchase_doc.properties_description_b == 2 && purchase_doc.properties_description_c == 1 && purchase_doc.properties_description_d == 1)
           || (purchase_doc.properties_description_a == 4 && purchase_doc.properties_description_b == 2 && purchase_doc.properties_description_c == 1 && purchase_doc.properties_description_d == 1)"
class="form-check icheck-cyan">
   <input @click="confirmation(purchase_doc.desired_contract_terms_v, $event)" v-model="purchase_doc.desired_contract_terms_v" class="form-check-input" type="checkbox" name="" id="C51" value="1" data-id="A86-C51" :disabled="purchase_doc.gathering_request_status == 1">
   <label class="form-check-label" for="C51">現況有姿にてお引き渡しを受けるものとします。</label>
</div>
<div v-if="purchase_doc.properties_description_a == 2 && purchase_doc.properties_description_b == 2 && purchase_doc.properties_description_c == 2 && purchase_doc.properties_description_d == 0"
class="form-check icheck-cyan">
   <input @click="confirmation(purchase_doc.desired_contract_terms_w, $event)" v-model="purchase_doc.desired_contract_terms_w" class="form-check-input" type="checkbox" name="" id="C52" value="1" data-id="A86-C52" :disabled="purchase_doc.gathering_request_status == 1">
   <label class="form-check-label" for="C52">現況有姿にてお引き渡しを受けるものしますが、解体予定のため建物の所有権移転は行わず、それに代えて買主の責任と負担で引渡し後速やかに滅失登記を行うこととします。また、残置物については売主様にて撤去いただくようお願い致します。</label>
</div>
<div v-if="purchase_doc.properties_description_a == 2 && purchase_doc.properties_description_b == 2 && purchase_doc.properties_description_c == 2 && purchase_doc.properties_description_d == 1"
class="form-check icheck-cyan">
   <input @click="confirmation(purchase_doc.desired_contract_terms_x, $event)" v-model="purchase_doc.desired_contract_terms_x" class="form-check-input" type="checkbox" name="" id="C53" value="1" data-id="A86-C53" :disabled="purchase_doc.gathering_request_status == 1">
   <label class="form-check-label" for="C53">現況有姿にてお引き渡しを受けるものとしますが、解体予定のため建物の所有権移転は行わず、それに代えて買主の責任と負担で引渡し後速やかに滅失登記を行うこととします。</label>
</div>
<div v-if="purchase_doc.properties_description_a == 4 && purchase_doc.properties_description_b == 2 && purchase_doc.properties_description_c == 1 && purchase_doc.properties_description_d == 0"
class="form-check icheck-cyan">
   <input @click="confirmation(purchase_doc.desired_contract_terms_y, $event)" v-model="purchase_doc.desired_contract_terms_y" class="form-check-input" type="checkbox" name="" id="C54" value="1" data-id="A86-C54" :disabled="purchase_doc.gathering_request_status == 1">
   <label class="form-check-label" for="C54">現況有姿にてお引き渡しを受けるものとします。　ただし、買主が指定した建物の残置物については売主様にて撤去いただくようお願い致します。</label>
</div>
<div v-if="purchase_doc.properties_description_a == 4 && purchase_doc.properties_description_b == 2 && purchase_doc.properties_description_c == 2 && purchase_doc.properties_description_d == 0"
class="form-check icheck-cyan">
   <input @click="confirmation(purchase_doc.desired_contract_terms_z, $event)" v-model="purchase_doc.desired_contract_terms_z" class="form-check-input" type="checkbox" name="" id="C55" value="1" data-id="A86-C55" :disabled="purchase_doc.gathering_request_status == 1">
   <label class="form-check-label" for="C55">現況有姿にてお引き渡しを受けるものしますが、買主が指定した建物については解体予定のため建物の所有権移転は行わず、それに代えて買主の責任と負担で引渡し後速やかに滅失登記を行うこととします。また、残置物については売主様にて撤去いただくようお願い致します。</label>
</div>
<div v-if="purchase_doc.properties_description_a == 4 && purchase_doc.properties_description_b == 2 && purchase_doc.properties_description_c == 2 && purchase_doc.properties_description_d == 1"
class="form-check icheck-cyan">
   <input @click="confirmation(purchase_doc.desired_contract_terms_aa, $event)" v-model="purchase_doc.desired_contract_terms_aa" class="form-check-input" type="checkbox" name="" id="C56" value="1" data-id="A86-C56" :disabled="purchase_doc.gathering_request_status == 1">
   <label class="form-check-label" for="C56">現況有姿にてお引き渡しを受けるものとしますが、買主が指定した建物については解体予定のため建物の所有権移転は行わず、それに代えて買主の責任と負担で引渡し後速やかに滅失登記を行うこととします。</label>
</div>
<div v-if="(purchase_doc.properties_description_a == 2 && purchase_doc.properties_description_b == 2 && purchase_doc.properties_description_c == 1 && purchase_doc.properties_description_d == 1 && purchase_doc.properties_description_f == 1)
           || (purchase_doc.properties_description_a == 2 && purchase_doc.properties_description_b == 2 && purchase_doc.properties_description_c == 2 && purchase_doc.properties_description_d == 1 && purchase_doc.properties_description_f == 1)
           || (purchase_doc.properties_description_a == 4 && purchase_doc.properties_description_b == 2 && purchase_doc.properties_description_c == 1 && purchase_doc.properties_description_d == 1 && purchase_doc.properties_description_f == 1)
           || (purchase_doc.properties_description_a == 4 && purchase_doc.properties_description_b == 2 && purchase_doc.properties_description_c == 2 && purchase_doc.properties_description_d == 1 && purchase_doc.properties_description_f == 1)"
class="form-check icheck-cyan">
   <input @click="confirmation(purchase_doc.desired_contract_terms_ab, $event)" v-model="purchase_doc.desired_contract_terms_ab" class="form-check-input" type="checkbox" name="" id="C57" value="1" data-id="A86-C57" :disabled="purchase_doc.gathering_request_status == 1">
   <label class="form-check-label" for="C57">売主様の責任と負担にて、建物内に設置されているエアコン（室外機含む）を撤去していただいた後のお引き渡しとしていただきますようお願い致します。</label>
</div>
