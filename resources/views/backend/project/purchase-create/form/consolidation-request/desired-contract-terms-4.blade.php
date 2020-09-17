<div v-if="purchase_doc.properties_description_a == 2 && purchase_doc.properties_description_b == 1" class="form-check icheck-cyan">
   <input @click="confirmation(purchase_doc.desired_contract_terms_n, $event)" v-model="purchase_doc.desired_contract_terms_n" class="form-check-input" type="checkbox" name="" id="C60" value="1" data-id="A86-C60" :disabled="purchase_doc.gathering_request_status == 1">
   <label class="form-check-label" for="C60">売主様の責任と負担において既存建物及び工作物の解体撤去工事を行い、滅失登記を完了させた上で引渡をお願いします。</label>
</div>
<div v-if="(purchase_doc.properties_description_a == 3 && purchase_doc.properties_description_e == 1)
          || (purchase_doc.properties_description_a == 4 && purchase_doc.properties_description_e == 1)
          || (purchase_doc.properties_description_a == 4 && purchase_doc.properties_description_b == 2 && purchase_doc.properties_description_c == 1 && purchase_doc.properties_description_e == 1)
          || (purchase_doc.properties_description_a == 4 && purchase_doc.properties_description_b == 2 && purchase_doc.properties_description_c == 2 && purchase_doc.properties_description_e == 1)" class="form-check icheck-cyan">
   <input @click="confirmation(purchase_doc.desired_contract_terms_o, $event)" v-model="purchase_doc.desired_contract_terms_o" class="form-check-input" type="checkbox" name="" id="C61" value="1" data-id="A86-C61" :disabled="purchase_doc.gathering_request_status == 1">
   <label class="form-check-label" for="C61">売主様の責任と負担において、ホームスペクション（建物状況調査）を実施完了後、お引渡しいただきますようにお願い致します。</label>
</div>
<div v-if="(purchase_doc.properties_description_a == 3 && purchase_doc.properties_description_e == 1)
          || (purchase_doc.properties_description_a == 4 && purchase_doc.properties_description_e == 1)
          || (purchase_doc.properties_description_a == 4 && purchase_doc.properties_description_b == 2 && purchase_doc.properties_description_c == 1 && purchase_doc.properties_description_e == 1)
          || (purchase_doc.properties_description_a == 4 && purchase_doc.properties_description_b == 2 && purchase_doc.properties_description_c == 2 && purchase_doc.properties_description_e == 1)" class="form-check icheck-cyan">
   <input @click="confirmation(purchase_doc.desired_contract_terms_p, $event)" v-model="purchase_doc.desired_contract_terms_p" class="form-check-input" type="checkbox" name="" id="C62" value="1" data-id="A86-C62" :disabled="purchase_doc.gathering_request_status == 1">
   <label class="form-check-label" for="C62">お引渡しまでの間に発覚した不具合については引渡し前に補修していただきますようにお願いします。</label>
</div>
<div v-if="(purchase_doc.properties_description_a == 3 && purchase_doc.properties_description_e == 1)
          || (purchase_doc.properties_description_a == 3 && purchase_doc.properties_description_e == 2)
          || (purchase_doc.properties_description_a == 3 && purchase_doc.properties_description_e == 3)
          || (purchase_doc.properties_description_a == 4 && purchase_doc.properties_description_e == 1)
          || (purchase_doc.properties_description_a == 4 && purchase_doc.properties_description_e == 1)
          || (purchase_doc.properties_description_a == 4 && purchase_doc.properties_description_e == 2)
          || (purchase_doc.properties_description_a == 4 && purchase_doc.properties_description_e == 3)
          || (purchase_doc.properties_description_a == 4 && purchase_doc.properties_description_b == 2 && purchase_doc.properties_description_c == 1 && purchase_doc.properties_description_e == 1)
          || (purchase_doc.properties_description_a == 4 && purchase_doc.properties_description_b == 2 && purchase_doc.properties_description_c == 1 && purchase_doc.properties_description_e == 2)
          || (purchase_doc.properties_description_a == 4 && purchase_doc.properties_description_b == 2 && purchase_doc.properties_description_c == 1 && purchase_doc.properties_description_e == 3)
          || (purchase_doc.properties_description_a == 4 && purchase_doc.properties_description_b == 2 && purchase_doc.properties_description_c == 2 && purchase_doc.properties_description_e == 1)
          || (purchase_doc.properties_description_a == 4 && purchase_doc.properties_description_b == 2 && purchase_doc.properties_description_c == 2 && purchase_doc.properties_description_e == 2)
          || (purchase_doc.properties_description_a == 4 && purchase_doc.properties_description_b == 2 && purchase_doc.properties_description_c == 2 && purchase_doc.properties_description_e == 3)" class="form-check icheck-cyan">
   <input @click="confirmation(purchase_doc.desired_contract_terms_q, $event)" v-model="purchase_doc.desired_contract_terms_q" class="form-check-input" type="checkbox" name="" id="C63" value="1" data-id="A86-C63" :disabled="purchase_doc.gathering_request_status == 1">
   <label class="form-check-label" for="C63">本物件の敷地内（建物内外問わず）に、放置物等がある場合、売主様の責任と負担において、決済日までに撤去した上でお引き渡しいただきますようお願い致します。</label>
</div>
<div v-if="(purchase_doc.properties_description_a == 3 && purchase_doc.properties_description_e == 2)
          || (purchase_doc.properties_description_a == 3 && purchase_doc.properties_description_e == 3)
          || (purchase_doc.properties_description_a == 4 && purchase_doc.properties_description_e == 2)
          || (purchase_doc.properties_description_a == 4 && purchase_doc.properties_description_e == 3)
          || (purchase_doc.properties_description_a == 4 && purchase_doc.properties_description_b == 2 && purchase_doc.properties_description_c == 1 && purchase_doc.properties_description_e == 2)
          || (purchase_doc.properties_description_a == 4 && purchase_doc.properties_description_b == 2 && purchase_doc.properties_description_c == 1 && purchase_doc.properties_description_e == 3)
          || (purchase_doc.properties_description_a == 4 && purchase_doc.properties_description_b == 2 && purchase_doc.properties_description_c == 2 && purchase_doc.properties_description_e == 2)
          || (purchase_doc.properties_description_a == 4 && purchase_doc.properties_description_b == 2 && purchase_doc.properties_description_c == 2 && purchase_doc.properties_description_e == 3)" class="form-check icheck-cyan">
   <input @click="confirmation(purchase_doc.desired_contract_terms_r, $event)" v-model="purchase_doc.desired_contract_terms_r" class="form-check-input" type="checkbox" name="" id="C64" value="1" data-id="A86-C64" :disabled="purchase_doc.gathering_request_status == 1">
   <label class="form-check-label" for="C64">契約日より、７日前までに、以下の書類・データ等について確認させていただきますようお願いいたします。<br />イ）本物件の賃借人との間における賃貸借契約書及び重要事項説明書<br />ロ）本物件の賃借人の過去６月間の賃料入金履歴<br />以上の内容次第で、本書の申込み条件の変更、もしくは取り下げの可能性があることをご了承下さい。</label>
</div>
<div v-if="(purchase_doc.properties_description_a == 3 && purchase_doc.properties_description_e == 2)
          || (purchase_doc.properties_description_a == 3 && purchase_doc.properties_description_e == 3)
          || (purchase_doc.properties_description_a == 4 && purchase_doc.properties_description_e == 2)
          || (purchase_doc.properties_description_a == 4 && purchase_doc.properties_description_e == 2)
          || (purchase_doc.properties_description_a == 4 && purchase_doc.properties_description_e == 3)
          || (purchase_doc.properties_description_a == 4 && purchase_doc.properties_description_b == 2 && purchase_doc.properties_description_c == 1 && purchase_doc.properties_description_e == 2)
          || (purchase_doc.properties_description_a == 4 && purchase_doc.properties_description_b == 2 && purchase_doc.properties_description_c == 1 && purchase_doc.properties_description_e == 3)
          || (purchase_doc.properties_description_a == 4 && purchase_doc.properties_description_b == 2 && purchase_doc.properties_description_c == 2 && purchase_doc.properties_description_e == 2)
          || (purchase_doc.properties_description_a == 4 && purchase_doc.properties_description_b == 2 && purchase_doc.properties_description_c == 2 && purchase_doc.properties_description_e == 3)" class="form-check icheck-cyan">
   <input @click="confirmation(purchase_doc.desired_contract_terms_s, $event)" v-model="purchase_doc.desired_contract_terms_s" class="form-check-input" type="checkbox" name="" id="C65" value="1" data-id="A86-C65" :disabled="purchase_doc.gathering_request_status == 1">
   <label class="form-check-label" for="C65">売主様の責任と負担において、居住部分がある場合、ホームスペクション（建物状況調査）を実施完了後、お引渡しいただきますようにお願い致します。</label>
</div>
<div v-if="(purchase_doc.properties_description_a == 4 && purchase_doc.properties_description_e == 1)
          || (purchase_doc.properties_description_a == 4 && purchase_doc.properties_description_e == 2)
          || (purchase_doc.properties_description_a == 4 && purchase_doc.properties_description_e == 3)" class="form-check icheck-cyan">
   <input @click="confirmation(purchase_doc.desired_contract_terms_t, $event)" v-model="purchase_doc.desired_contract_terms_t" class="form-check-input" type="checkbox" name="" id="C66" value="1" data-id="A86-C66" :disabled="purchase_doc.gathering_request_status == 1">
   <label class="form-check-label" for="C66">売主の責任と負担において、買主が指定した建物の解体撤去工事及び滅失登記を引渡し時までにしていただきますようお願い致します。</label>
</div>
