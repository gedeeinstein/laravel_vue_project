<div class="card mt-2">
   <div style="font-weight: 700;" class="card-header">契約書</div>
   <div class="card-body">
      <div class="form-group row">
         <label style="font-weight: normal;" for="contract_a" class="col-3 col-form-label">4条 面積</label>
         <div class="col-9">
            <div class="form-check icheck-cyan d-inline">
               <input v-model="purchase_doc.contract_a" class="form-check-input" type="radio" name="purchase_doc.contract_a" id="purchase_doc.contract_a_1" value="1" :disabled="purchase_doc.contract_status == 1"
                  data-id="A85-1">
               <label class="form-check-label" for="purchase_doc.contract_a_1">公簿取引</label>
            </div>
            <div class="form-check icheck-cyan d-inline">
               <input v-model="purchase_doc.contract_a" class="form-check-input" type="radio" name="purchase_doc.contract_a" id="purchase_doc.contract_a_2" value="2" :disabled="purchase_doc.contract_status == 1">
               <label class="form-check-label" for="purchase_doc.contract_a_2">実測清算取引</label>
            </div>
         </div>
      </div>
      <div class="form-group row">
         <label style="font-weight: normal;" for="contract_b" class="col-3 col-form-label">5条 境界</label>
         <div class="col-9">
            <div class="sub-label">境界の確定測量と地積登記</div>
            <div>
               <div class="form-check icheck-cyan d-inline">
                  <input v-model="purchase_doc.contract_b" class="form-check-input" type="radio" name="purchase_doc.contract_b" id="purchase_doc.contract_b_1" value="1" :disabled="purchase_doc.contract_status == 1"
                     data-id="A85-2">
                  <label class="form-check-label" for="purchase_doc.contract_b_1">売主負担で依頼</label>
               </div>
               <div class="form-check icheck-cyan d-inline">
                  <input v-model="purchase_doc.contract_b" class="form-check-input" type="radio" name="purchase_doc.contract_b" id="purchase_doc.contract_b_2" value="2" :disabled="purchase_doc.contract_status == 1">
                  <label class="form-check-label" for="purchase_doc.contract_b_2">買主負担で依頼</label>
               </div>
               <div class="form-check icheck-cyan d-inline">
                  <input v-model="purchase_doc.contract_b" class="form-check-input" type="radio" name="purchase_doc.contract_b" id="purchase_doc.contract_b_3" value="3" :disabled="purchase_doc.contract_status == 1">
                  <label class="form-check-label" for="purchase_doc.contract_b_3">確定測量なし、境界明示有り</label>
               </div>
               <div class="form-check icheck-cyan d-inline">
                  <input v-model="purchase_doc.contract_b" class="form-check-input" type="radio" name="purchase_doc.contract_b" id="purchase_doc.contract_b_4" value="4" :disabled="purchase_doc.contract_status == 1">
                  <label class="form-check-label" for="purchase_doc.contract_b_4">確定測量なし、境界明示無し</label>
               </div>
            </div>
         </div>
      </div>
      <div v-if="purchase_sale.urbanization_area_sub_2" class="form-group row">
         <label style="font-weight: normal;" for="contract_c" class="col-3 col-form-label">6条 所有権の移転、登記</label>
         <div class="col-9">
            <div class="sub-label">区画整理事業完了時清算金</div>
            <div>
               <div class="form-check icheck-cyan d-inline">
                  <input v-model="purchase_doc.contract_c" class="form-check-input" type="radio" name="purchase_doc.contract_c" id="purchase_doc.contract_c_1" value="1" :disabled="purchase_doc.contract_status == 1"
                     data-id="A85-3">
                  <label class="form-check-label" for="purchase_doc.contract_c_1">売主負担頼</label>
               </div>
               <div class="form-check icheck-cyan d-inline">
                  <input v-model="purchase_doc.contract_c" class="form-check-input" type="radio" name="purchase_doc.contract_c" id="purchase_doc.contract_c_2_d" value="2" :disabled="purchase_doc.contract_status == 1">
                  <label class="form-check-label" for="purchase_doc.contract_c_2_d">買主負担頼</label>
               </div>
            </div>
            <div class="sub-label">区画整理事業における賦課金</div>
            <div>
               <div class="form-check icheck-cyan d-inline">
                  <input v-model="purchase_doc.contract_d" class="form-check-input" type="radio" name="purchase_doc.contract_d" id="purchase_doc.contract_d_1" value="1" :disabled="purchase_doc.contract_status == 1"
                     data-id="A85-4">
                  <label class="form-check-label" for="purchase_doc.contract_d_1">売主負担頼</label>
               </div>
               <div class="form-check icheck-cyan d-inline">
                  <input v-model="purchase_doc.contract_d" class="form-check-input" type="radio" name="purchase_doc.contract_d" id="purchase_doc.contract_d_2" value="2" :disabled="purchase_doc.contract_status == 1">
                  <label class="form-check-label" for="purchase_doc.contract_d_2">買主負担頼</label>
               </div>
            </div>
         </div>
      </div>
      <div class="incomplete_memo form-row p-2 bg-light">
         <div class="col-auto form-text">
            <div class="form-check icheck-cyan d-inline">
               <input v-model="purchase_doc.contract_status" class="form-check-input" type="radio" name="purchase_doc.contract_status" id="purchase_doc.contract_status_1" value="1"  data-id="A85-101"  >
               <label class="form-check-label" for="purchase_doc.contract_status_1">完</label>
            </div>
            <div class="form-check icheck-cyan d-inline">
               <input v-model="purchase_doc.contract_status" class="form-check-input" type="radio" name="purchase_doc.contract_status" id="purchase_doc.contract_status_2" value="2">
               <label class="form-check-label" for="purchase_doc.contract_status_2">未</label>
            </div>
         </div>
         <div class="col-auto form-text">
            <span class="">未完メモ：</span>
         </div>
         <div class="col-6">
            <input v-model="purchase_doc.contract_memo" class="form-control" name="" type="text" value=""  data-id="A85-102"  placeholder="未完となっている項目や理由を記入してください" data-parsley-trigger="keyup" data-parsley-maxlength="128">
         </div>
      </div>
   </div>
</div>
