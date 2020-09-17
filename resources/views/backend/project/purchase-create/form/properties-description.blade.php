<div class="card mt-2">
   <div style="font-weight: 700;" class="card-header">物件概要</div>
   <div class="card-body">
      <div class="form-group row">
         <label style="font-weight: normal;" for="" class="col-3 col-form-label">対象商品</label>
         <div class="col-9">
            <div class="sub-label">種類</div>
            <div class="row">
               <div v-if="purchase_doc.properties_description_a == 1" class="col-12" data-id="A83-1"
               :style="purchase_doc.properties_description_status == 1 ? {background: '#e9ecef'}: ''"
               ><strong class="text-danger">土地（更地）</strong></div>
               <div v-else-if="purchase_doc.properties_description_a == 2" class="col-12" data-id="A83-1"
               :style="purchase_doc.properties_description_status == 1 ? {background: '#e9ecef'}: ''"
               ><strong class="text-danger">土地（解体建物付）</strong></div>
               <div v-else-if="purchase_doc.properties_description_a == 3" class="col-12" data-id="A83-1"
               :style="purchase_doc.properties_description_status == 1 ? {background: '#e9ecef'}: ''"
               ><strong class="text-danger">土地（商品用建物有）</strong></div>
               <div v-else-if="purchase_doc.properties_description_a == 4" class="col-12" data-id="A83-1"
               :style="purchase_doc.properties_description_status == 1 ? {background: '#e9ecef'}: ''"
               ><strong class="text-danger">土地（解建＋商建付）</strong></div>
               <input v-model="purchase_doc.properties_description_a" type="hidden" name="" value="">
            </div>
         </div>
      </div>
      <div v-if="purchase_doc.properties_description_a == 2 || purchase_doc.properties_description_a == 4" class="form-group row">
         <label style="font-weight: normal;" for="" class="col-3 col-form-label"></label>
         <div class="col-9">
            <div class="sub-label">古屋解体</div>
            <div class="form-check icheck-cyan d-inline">
               <input v-model="purchase_doc.properties_description_b" class="form-check-input" type="radio" name="properties_description_b" id="properties_description_b_1" value="1" :disabled="purchase_doc.properties_description_status == 1"
                  data-id="A83-2">
               <label class="form-check-label" for="properties_description_b_1">売主</label>
            </div>
            <div class="form-check icheck-cyan d-inline">
               <input v-model="purchase_doc.properties_description_b" class="form-check-input" type="radio" name="properties_description_b" id="properties_description_b_2" value="2" :disabled="purchase_doc.properties_description_status == 1">
               <label class="form-check-label" for="properties_description_b_2">買主</label>
            </div>
         </div>
      </div>
      <div v-if="purchase_doc.properties_description_b == 2" class="form-group row">
         <label style="font-weight: normal;" for="" class="col-3 col-form-label"></label>
         <div class="col-9">
            <div class="sub-label">建物所有権移転</div>
            <div class="form-check icheck-cyan d-inline">
               <input v-model="purchase_doc.properties_description_c" class="form-check-input" type="radio" name="properties_description_c" id="properties_description_c_1" value="1" :disabled="purchase_doc.properties_description_status == 1"
                  data-id="A83-3">
               <label class="form-check-label" for="properties_description_c_1">する</label>
            </div>
            <div class="form-check icheck-cyan d-inline">
               <input v-model="purchase_doc.properties_description_c" class="form-check-input" type="radio" name="properties_description_c" id="properties_description_c_2" value="2" :disabled="purchase_doc.properties_description_status == 1">
               <label class="form-check-label" for="properties_description_c_2">しない</label>
            </div>
            <div class="form-check icheck-cyan d-inline">
               <input v-model="purchase_doc.properties_description_d" class="form-check-input" type="checkbox" name="properties_description_d" id="properties_description_d" data-id="A83-4" :disabled="purchase_doc.properties_description_status == 1">
               <label class="form-check-label" for="properties_description_d">残置物も買主にて撤去</label>
            </div>
            <div class="form-check icheck-cyan d-inline">
               <input v-model="purchase_doc.properties_description_f" class="form-check-input" type="checkbox" name="properties_description_f" id="properties_description_f" data-id="A83-6" :disabled="purchase_doc.properties_description_status == 1">
               <label class="form-check-label" for="properties_description_f">エアコンも買主にて撤去</label>
            </div>
         </div>
      </div>
      <div v-if="purchase_doc.properties_description_a == 3 || purchase_doc.properties_description_a == 4" class="form-group row">
         <label style="font-weight: normal;" for="" class="col-3 col-form-label"></label>
         <div class="col-9">
            <div class="sub-label">商品用建物の種類</div>
            <div class="form-check icheck-cyan d-inline">
               <input v-model="purchase_doc.properties_description_e" class="form-check-input" type="radio" name="properties_description_e" id="properties_description_e_1" value="1" :disabled="purchase_doc.properties_description_status == 1"
                  data-id="A83-5">
               <label class="form-check-label" for="properties_description_e_1">中古住宅</label>
            </div>
            <div class="form-check icheck-cyan d-inline">
               <input v-model="purchase_doc.properties_description_e" class="form-check-input" type="radio" name="properties_description_e" id="properties_description_e_2" value="2" :disabled="purchase_doc.properties_description_status == 1">
               <label class="form-check-label" for="properties_description_e_2">収益物件（住居）</label>
            </div>
            <div class="form-check icheck-cyan d-inline">
               <input v-model="purchase_doc.properties_description_e" class="form-check-input" type="radio" name="properties_description_e" id="properties_description_e_3" value="3" :disabled="purchase_doc.properties_description_status == 1">
               <label class="form-check-label" for="properties_description_e_3">収益物件（店舗のみ）</label>
            </div>
         </div>
      </div>
      <div class="incomplete_memo form-row p-2 bg-light">
         <div class="col-auto form-text">
            <div class="form-check icheck-cyan d-inline">
               <input v-model="purchase_doc.properties_description_status" class="form-check-input" type="radio" name="properties_description_status" id="properties_description_status_1" value="1"  data-id="A83-101"  >
               <label class="form-check-label" for="properties_description_status_1">完</label>
            </div>
            <div class="form-check icheck-cyan d-inline">
               <input v-model="purchase_doc.properties_description_status" class="form-check-input" type="radio" name="properties_description_status" id="properties_description_status_2" value="2">
               <label class="form-check-label" for="properties_description_status_2">未</label>
            </div>
         </div>
         <div class="col-auto form-text">
            <span class="">未完メモ：</span>
         </div>
         <div class="col-6">
            <input v-model="purchase_doc.properties_description_memo" class="form-control" name="properties_description_a" type="text" value=""  data-id="A83-102"  placeholder="未完となっている項目や理由を記入してください" data-parsley-trigger="keyup" data-parsley-maxlength="128">
         </div>
      </div>
   </div>
</div>
