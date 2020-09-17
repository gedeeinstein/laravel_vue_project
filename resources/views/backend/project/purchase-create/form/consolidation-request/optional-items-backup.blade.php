<div class="sub-label">任意選択の項目</div>
<div v-if="(purchase_doc.front_road_b == 3 && purchase_doc.front_road_c == 1 && purchase_doc.front_road_a == 1)
          || (purchase_doc.front_road_b == 3 && purchase_doc.front_road_c == 2 && purchase_doc.front_road_a == 1 && purchase_doc.front_road_e == 1)
          || (purchase_doc.front_road_b == 3 && purchase_doc.front_road_c == 2 && purchase_doc.front_road_a == 1 && purchase_doc.front_road_e == 2)
          || (purchase_doc.front_road_b == 3 && purchase_doc.front_road_c == 2 && purchase_doc.front_road_a != 1 && purchase_doc.front_road_e == 1)
          || (purchase_doc.front_road_b == 3 && purchase_doc.front_road_c == 2 && purchase_doc.front_road_a != 1 && purchase_doc.front_road_e == 2)
          || (purchase_doc.front_road_b == 4)
          || (purchase_doc.front_road_b == 5 && purchase_doc.front_road_c == 1 && purchase_doc.front_road_a == 1)
          || (purchase_doc.front_road_b == 5 && purchase_doc.front_road_c == 2 && purchase_doc.front_road_a == 1 && purchase_doc.front_road_e == 1)
          || (purchase_doc.front_road_b == 5 && purchase_doc.front_road_c == 2 && purchase_doc.front_road_a == 1 && purchase_doc.front_road_e == 2)
          || (purchase_doc.front_road_b == 7 && purchase_doc.front_road_c == 1)
          || (purchase_doc.front_road_b == 7 && purchase_doc.front_road_c == 2 && purchase_doc.front_road_e == 1)
          || (purchase_doc.front_road_b == 7 && purchase_doc.front_road_c == 2 && purchase_doc.front_road_e == 2)
          || (purchase_doc.front_road_b == 8 && purchase_doc.front_road_e == 1)
          || (purchase_doc.front_road_b == 8 && purchase_doc.front_road_e == 2)
          || (purchase_doc.front_road_b == 9 && purchase_doc.front_road_e == 1)
          || (purchase_doc.front_road_b == 9 && purchase_doc.front_road_e == 2)"
class="form-check icheck-cyan">
   <input @click="confirmation(purchase_doc.optional_items_a, $event)" v-model="purchase_doc.optional_items_a" class="form-check-input" type="checkbox" name="" id="optional_items_a" value="1" :disabled="purchase_doc.gathering_request_status == 1"
      data-id="A86-D01">
   <label class="form-check-label" for="optional_items_a">引渡日までに本物件において建築確認済み証を取得することを停止条件とさせて下さい。尚、当該申請にかかる費用は買主の負担とします。</label>
</div>
<div v-if="(purchase_doc.front_road_b == 2 && purchase_doc.front_road_c == 1 && purchase_doc.front_road_d == 2)
          || (purchase_doc.front_road_b == 2 && purchase_doc.front_road_c == 2 && purchase_doc.front_road_e == 1)
          || (purchase_doc.front_road_b == 3 && purchase_doc.front_road_c == 2 && purchase_doc.front_road_a == 1 && purchase_doc.front_road_e == 1)
          || (purchase_doc.front_road_b == 3 && purchase_doc.front_road_c == 2 && purchase_doc.front_road_a != 1 && purchase_doc.front_road_e == 1)
          || (purchase_doc.front_road_b == 5 && purchase_doc.front_road_c == 2 && purchase_doc.front_road_a == 1 && purchase_doc.front_road_e == 1)
          || (purchase_doc.front_road_b == 5 && purchase_doc.front_road_c == 2 && purchase_doc.front_road_a != 1 && purchase_doc.front_road_e == 1)
          || (purchase_doc.front_road_b == 6 && purchase_doc.front_road_c == 2 && purchase_doc.front_road_a == 1 && purchase_doc.front_road_e == 1)
          || (purchase_doc.front_road_b == 6 && purchase_doc.front_road_c == 2 && purchase_doc.front_road_a != 1 && purchase_doc.front_road_e == 1)
          || (purchase_doc.front_road_b == 7 && purchase_doc.front_road_c == 2 && purchase_doc.front_road_e == 1)
          || (purchase_doc.front_road_b == 8 && purchase_doc.front_road_e == 1)
          || (purchase_doc.front_road_b == 9 && purchase_doc.front_road_e == 1)"
class="form-check icheck-cyan">
   <input @click="confirmation(purchase_doc.optional_items_b, $event)" v-model="purchase_doc.optional_items_b" class="form-check-input" type="checkbox" name="" id="optional_items_b" value="1" :disabled="purchase_doc.gathering_request_status == 1"
      data-id="A86-D02">
   <label class="form-check-label" for="optional_items_b">前面道路の持分を取得することを条件とさせて下さい。尚、前面道路の持分の価格は本物件価格に含まれるものとします。</label>
</div>
<div v-if="(purchase_doc.front_road_b == 2 && purchase_doc.front_road_c == 2 && purchase_doc.front_road_e == 1)
          || (purchase_doc.front_road_b == 3 && purchase_doc.front_road_c == 2 && purchase_doc.front_road_a == 1 && purchase_doc.front_road_e == 1)
          || (purchase_doc.front_road_b == 3 && purchase_doc.front_road_c == 2 && purchase_doc.front_road_a != 1 && purchase_doc.front_road_e == 1)
          || (purchase_doc.front_road_b == 5 && purchase_doc.front_road_c == 2 && purchase_doc.front_road_a == 1 && purchase_doc.front_road_e == 1)
          || (purchase_doc.front_road_b == 5 && purchase_doc.front_road_c == 2 && purchase_doc.front_road_a != 1 && purchase_doc.front_road_e == 1)
          || (purchase_doc.front_road_b == 6 && purchase_doc.front_road_c == 2 && purchase_doc.front_road_a == 1 && purchase_doc.front_road_e == 1)
          || (purchase_doc.front_road_b == 6 && purchase_doc.front_road_c == 2 && purchase_doc.front_road_a != 1 && purchase_doc.front_road_e == 1)
          || (purchase_doc.front_road_b == 7 && purchase_doc.front_road_c == 2 && purchase_doc.front_road_e == 1)
          || (purchase_doc.front_road_b == 8 && purchase_doc.front_road_e == 1)
          || (purchase_doc.front_road_b == 9 && purchase_doc.front_road_e == 1)"
class="form-check icheck-cyan">
   <input @click="confirmation(purchase_doc.optional_items_c, $event)" v-model="purchase_doc.optional_items_c" class="form-check-input" type="checkbox" name="" id="optional_items_c" value="1" :disabled="purchase_doc.gathering_request_status == 1"
      data-id="A86-D03">
   <label class="form-check-label" for="optional_items_c">前面道路の他共有者全員から通行掘削同意書の署名・捺印を頂くことを条件とさせて下さい。</label>
</div>
<div v-if="(purchase_doc.front_road_b == 8 && purchase_doc.front_road_e == 1)
          || (purchase_doc.front_road_b == 8 && purchase_doc.front_road_e == 2)"
class="form-check icheck-cyan">
   <input @click="confirmation(purchase_doc.optional_items_d, $event)" v-model="purchase_doc.optional_items_d" class="form-check-input" type="checkbox" name="" id="optional_items_d" value="1" :disabled="purchase_doc.gathering_request_status == 1"
      data-id="A86-D04">
   <label class="form-check-label" for="optional_items_d">本書面に定めのない前面道路に関する事項は、別途協議させて頂きたいと思います。</label>
</div>
<div v-if="(purchase_doc.front_road_b == 6 && purchase_doc.front_road_c == 1 && purchase_doc.front_road_a != 1)
          || (purchase_doc.front_road_b == 6 && purchase_doc.front_road_c == 2 && purchase_doc.front_road_a != 1 && purchase_doc.front_road_e == 1)
          || (purchase_doc.front_road_b == 6 && purchase_doc.front_road_c == 2 && purchase_doc.front_road_a != 1 && purchase_doc.front_road_e == 2)"
class="form-check icheck-cyan">
   <input @click="confirmation(purchase_doc.optional_items_e, $event)" v-model="purchase_doc.optional_items_e" class="form-check-input" type="checkbox" name="" id="optional_items_e" value="1" :disabled="purchase_doc.gathering_request_status == 1"
      data-id="A86-D05">
   <label class="form-check-label" for="optional_items_e">引渡日までに売主様の責任と負担において、道路部分と宅地部分の分筆登記を完了して頂きますようお願いします。</label>
</div>
<div v-if="(purchase_doc.front_road_b == 3 && purchase_doc.front_road_c == 1 && purchase_doc.front_road_a == 1)
          || (purchase_doc.front_road_b == 3 && purchase_doc.front_road_c == 2 && purchase_doc.front_road_a == 1 && purchase_doc.front_road_e == 1)
          || (purchase_doc.front_road_b == 3 && purchase_doc.front_road_c == 2 && purchase_doc.front_road_a == 1 && purchase_doc.front_road_e == 2)
          || (purchase_doc.front_road_b == 5 && purchase_doc.front_road_c == 1 && purchase_doc.front_road_a == 1)
          || (purchase_doc.front_road_b == 5 && purchase_doc.front_road_c == 2 && purchase_doc.front_road_a == 1 && purchase_doc.front_road_e == 1)
          || (purchase_doc.front_road_b == 5 && purchase_doc.front_road_c == 2 && purchase_doc.front_road_a == 1 && purchase_doc.front_road_e == 2)
          || (purchase_doc.front_road_b == 6 && purchase_doc.front_road_c == 1 && purchase_doc.front_road_a == 1)
          || (purchase_doc.front_road_b == 6 && purchase_doc.front_road_c == 2 && purchase_doc.front_road_a == 1 && purchase_doc.front_road_e == 1)
          || (purchase_doc.front_road_b == 6 && purchase_doc.front_road_c == 2 && purchase_doc.front_road_a == 1 && purchase_doc.front_road_e == 2)"
class="form-check icheck-cyan">
   <input @click="confirmation(purchase_doc.optional_items_f, $event)" v-model="purchase_doc.optional_items_f" class="form-check-input" type="checkbox" name="" id="optional_items_f" value="1" :disabled="purchase_doc.gathering_request_status == 1"
      data-id="A86-D06">
   <label class="form-check-label" for="optional_items_f">引渡日までに売主様の責任と負担において、狭隘協議を行った上で分筆登記を完了して頂きますようお願いします。</label>
</div>
<div v-if="(purchase_doc.front_road_b == 7 && purchase_doc.front_road_c == 1)
          || (purchase_doc.front_road_b == 7 && purchase_doc.front_road_c == 2 && purchase_doc.front_road_e == 1)
          || (purchase_doc.front_road_b == 7 && purchase_doc.front_road_c == 2 && purchase_doc.front_road_e == 2)"
class="form-check icheck-cyan">
   <input @click="confirmation(purchase_doc.optional_items_g, $event)" v-model="purchase_doc.optional_items_g" class="form-check-input" type="checkbox" name="" id="optional_items_g" value="1" :disabled="purchase_doc.gathering_request_status == 1"
      data-id="A86-D07">
   <label class="form-check-label" for="optional_items_g">役所が指導する道路後退距離を確保するよう、売主様の責任と負担において引き渡し日までに分筆登記を完了して頂きますようお願いします。</label>
</div>
<div class="form-check icheck-cyan">
   <input @click="confirmation(purchase_doc.optional_items_h, $event)" v-model="purchase_doc.optional_items_h" class="form-check-input" type="checkbox" name="" id="optional_items_h" value="1" :disabled="purchase_doc.gathering_request_status == 1"
      data-id="A86-D08">
   <label class="form-check-label" for="optional_items_h">使用収益開始日以降の決済とさせて下さい。</label>
</div>
<div class="form-check icheck-cyan">
   <input @click="confirmation(purchase_doc.optional_items_i, $event)" v-model="purchase_doc.optional_items_i" class="form-check-input" type="checkbox" name="" id="optional_items_i" value="1" :disabled="purchase_doc.gathering_request_status == 1"
      data-id="A86-D09">
   <label class="form-check-label" for="optional_items_i">売主様は、買主が再販売を目的として購入する事を承諾し、契約日以降の販売活動を認めるものとします。</label>
</div>
<div v-if="(purchase_doc.front_road_b == 2 && purchase_doc.front_road_c == 2 && purchase_doc.front_road_e == 2)
          || (purchase_doc.front_road_b == 3 && purchase_doc.front_road_c == 2 && purchase_doc.front_road_a == 1 && purchase_doc.front_road_e == 2)
          || (purchase_doc.front_road_b == 3 && purchase_doc.front_road_c == 2 && purchase_doc.front_road_a != 1 && purchase_doc.front_road_e == 2)
          || (purchase_doc.front_road_b == 5 && purchase_doc.front_road_c == 2 && purchase_doc.front_road_a == 1 && purchase_doc.front_road_e == 2)
          || (purchase_doc.front_road_b == 5 && purchase_doc.front_road_c == 2 && purchase_doc.front_road_a != 1 && purchase_doc.front_road_e == 2)
          || (purchase_doc.front_road_b == 6 && purchase_doc.front_road_c == 2 && purchase_doc.front_road_a == 1 && purchase_doc.front_road_e == 2)
          || (purchase_doc.front_road_b == 6 && purchase_doc.front_road_c == 2 && purchase_doc.front_road_a != 1 && purchase_doc.front_road_e == 2)
          || (purchase_doc.front_road_b == 7 && purchase_doc.front_road_c == 2 && purchase_doc.front_road_e == 2)
          || (purchase_doc.front_road_b == 8 && purchase_doc.front_road_e == 2)
          || (purchase_doc.front_road_b == 9 && purchase_doc.front_road_e == 2)"
class="form-check icheck-cyan">
   <input @click="confirmation(purchase_doc.optional_items_j, $event)" v-model="purchase_doc.optional_items_j" class="form-check-input" type="checkbox" name="" id="optional_items_j" value="1" :disabled="purchase_doc.gathering_request_status == 1"
      data-id="A86-D10">
   <label class="form-check-label" for="optional_items_j">引渡日までに前面道路の持分と、他共有者全員から通行掘削同意書の署名・捺印を取得することを停止条件とさせて下さい。</label>
</div>
<div v-if="(purchase_doc.front_road_b == 8 && purchase_doc.front_road_e == 1)
          || (purchase_doc.front_road_b == 8 && purchase_doc.front_road_e == 2)"
class="form-check icheck-cyan">
   <input @click="confirmation(purchase_doc.optional_items_k, $event)" v-model="purchase_doc.optional_items_k" class="form-check-input" type="checkbox" name="" id="optional_items_k" value="1" :disabled="purchase_doc.gathering_request_status == 1"
      data-id="A86-D11">
   <label class="form-check-label" for="optional_items_k">本物件の前面道路について、契約日までに取り決め等についての詳細調査を行い、その結果次第では本書を取り下げさせていただく可能性があることをご了承下さい。</label>
</div>
