<div class="sub-label" style="background: #eee; padding: 0.2em 0.4em; margin: 0.5em 0; font-weight: bold;">
    告知事項</div>
<div class="form-check icheck-cyan">
   <input @click="confirmation(purchase_doc.notices_a, $event)" v-model="purchase_doc.notices_a" class="form-check-input" type="checkbox" name="" id="notices_a" value="1" :disabled="purchase_doc.gathering_request_status == 1"
     data-id="A86-A01">
   <label class="form-check-label" for="notices_a">自殺や事故、近隣トラブルなど。</label>
</div>
<div v-if="purchase_doc.properties_description_a == 3 || purchase_doc.properties_description_a == 4" class="form-check icheck-cyan">
   <input @click="confirmation(purchase_doc.notices_b, $event)" v-model="purchase_doc.notices_b" class="form-check-input" type="checkbox" name="" id="notices_b" value="1" :disabled="purchase_doc.gathering_request_status == 1"
     data-id="A86-A02">
   <label class="form-check-label" for="notices_b">建物の補修履歴及び実施内容。また現時点での不具合や補修検討事項。</label>
</div>
<div v-if="purchase_doc.properties_description_e == 2 || purchase_doc.properties_description_e == 3" class="form-check icheck-cyan">
   <input @click="confirmation(purchase_doc.notices_c, $event)" v-model="purchase_doc.notices_c" class="form-check-input" type="checkbox" name="" id="notices_c" value="1" :disabled="purchase_doc.gathering_request_status == 1"
     data-id="A86-A03">
   <label class="form-check-label" for="notices_c">賃借人についての告知事項（賃料滞納者の有無、クレーマー等）。</label>
</div>
<div class="form-check icheck-cyan">
   <input @click="confirmation(purchase_doc.notices_d, $event)" v-model="purchase_doc.notices_d" class="form-check-input" type="checkbox" name="" id="notices_d" value="1" :disabled="purchase_doc.gathering_request_status == 1"
     data-id="A86-A04">
   <label class="form-check-label" for="notices_d">本物件及び隣接地について、過去または現在においてガソリンスタンドやクリーニング工場等、土壌汚染に関わる施設の存在。</label>
</div>
<div class="form-check icheck-cyan">
   <input @click="confirmation(purchase_doc.notices_e, $event)" v-model="purchase_doc.notices_e" class="form-check-input" type="checkbox" name="" id="notices_e" value="1" :disabled="purchase_doc.gathering_request_status == 1"
     data-id="A86-A05">
   <label class="form-check-label" for="notices_e">その他、特記事項など。</label>
</div>
