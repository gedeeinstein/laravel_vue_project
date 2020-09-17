<div class="card">
   <div style="font-weight: 700;" class="card-header">注意喚起</div>
   <div class="card-body">
      <div class="form-group row">
         <label style="font-weight: normal;" for="" class="col-3 col-form-label">上下水道は現況引込がある</label>
         <div class="col-9">
            <div class="form-check icheck-cyan d-inline">
               <input v-model="purchase_doc.heads_up_a" class="form-check-input" type="radio" name="heads_up_a" id="heads_up_a_1" value="1" data-id="A82-1" :disabled="purchase_doc.heads_up_status == 1">
               <label class="form-check-label" for="heads_up_a_1">YES</label>
            </div>
            <div class="form-check icheck-cyan d-inline">
               <input v-model="purchase_doc.heads_up_a" class="form-check-input" type="radio" name="heads_up_a" id="heads_up_a_2" value="2" :disabled="purchase_doc.heads_up_status == 1">
               <label class="form-check-label" for="heads_up_a_2">NO</label>
            </div>
            <div class="form-check icheck-cyan d-inline">
               <input v-model="purchase_doc.heads_up_a" class="form-check-input" type="radio" name="heads_up_a" id="heads_up_a_3" value="3" :disabled="purchase_doc.heads_up_status == 1">
               <label class="form-check-label" for="heads_up_a_3">未確認</label>
            </div>
         </div>
      </div>
      <div class="form-group row">
         <label style="font-weight: normal;" for="" class="col-3 col-form-label">造成時水道加入金前払がある</label>
         <div class="col-9">
            <div class="form-check icheck-cyan d-inline">
               <input v-model="purchase_doc.heads_up_b" class="form-check-input" type="radio" name="heads_up_b" id="heads_up_b_1" value="1" data-id="A82-2" :disabled="purchase_doc.heads_up_status == 1">
               <label class="form-check-label" for="heads_up_b_1">YES</label>
            </div>
            <div class="form-check icheck-cyan d-inline">
               <input v-model="purchase_doc.heads_up_b" class="form-check-input" type="radio" name="heads_up_b" id="heads_up_b_2" value="2" :disabled="purchase_doc.heads_up_status == 1">
               <label class="form-check-label" for="heads_up_b_2">NO</label>
            </div>
            <div class="form-check icheck-cyan d-inline">
               <input v-model="purchase_doc.heads_up_b" class="form-check-input" type="radio" name="heads_up_b" id="heads_up_b_3" value="3" :disabled="purchase_doc.heads_up_status == 1">
               <label class="form-check-label" for="heads_up_b_3">未確認</label>
            </div>
         </div>
      </div>
      <div class="form-group row">
         <label style="font-weight: normal;" for="" class="col-3 col-form-label">都市計画道路予定が無い</label>
         <div class="col-9">
            <div class="form-check icheck-cyan d-inline">
               <input v-model="purchase_doc.heads_up_c" class="form-check-input" type="radio" name="heads_up_c" id="heads_up_c_1" value="1" data-id="A82-3" :disabled="purchase_doc.heads_up_status == 1">
               <label class="form-check-label" for="heads_up_c_1">YES</label>
            </div>
            <div class="form-check icheck-cyan d-inline">
               <input v-model="purchase_doc.heads_up_c" class="form-check-input" type="radio" name="heads_up_c" id="heads_up_c_2" value="2" :disabled="purchase_doc.heads_up_status == 1">
               <label class="form-check-label" for="heads_up_c_2">NO</label>
            </div>
            <div class="form-check icheck-cyan d-inline">
               <input v-model="purchase_doc.heads_up_c" class="form-check-input" type="radio" name="heads_up_c" id="heads_up_c_3" value="3" :disabled="purchase_doc.heads_up_status == 1">
               <label class="form-check-label" for="heads_up_c_3">未確認</label>
            </div>
         </div>
      </div>
      <div class="form-group row">
         <label style="font-weight: normal;" for="" class="col-3 col-form-label">道路・隣地と高低差1.5Ｍ以上</label>
         <div class="col-9">
            <div class="form-check icheck-cyan d-inline">
               <input v-model="purchase_doc.heads_up_d" class="form-check-input" type="radio" name="heads_up_d" id="heads_up_d_1" value="1" data-id="A82-4" :disabled="purchase_doc.heads_up_status == 1">
               <label class="form-check-label" for="heads_up_d_1">YES</label>
            </div>
            <div class="form-check icheck-cyan d-inline">
               <input v-model="purchase_doc.heads_up_d" class="form-check-input" type="radio" name="heads_up_d" id="heads_up_d_2" value="2" :disabled="purchase_doc.heads_up_status == 1">
               <label class="form-check-label" for="heads_up_d_2">NO</label>
            </div>
            <div class="form-check icheck-cyan d-inline">
               <input v-model="purchase_doc.heads_up_d" class="form-check-input" type="radio" name="heads_up_d" id="heads_up_d_3" value="3" :disabled="purchase_doc.heads_up_status == 1">
               <label class="form-check-label" for="heads_up_d_3">未確認</label>
            </div>
         </div>
      </div>
      <div class="form-group row">
         <label style="font-weight: normal;" for="" class="col-3 col-form-label">土壌汚染の可能性がある</label>
         <div class="col-9">
            <div class="form-check icheck-cyan d-inline">
               <input v-model="purchase_doc.heads_up_e" class="form-check-input" type="radio" name="heads_up_e" id="heads_up_e_1" value="1" data-id="A82-5" :disabled="purchase_doc.heads_up_status == 1">
               <label class="form-check-label" for="heads_up_e_1">YES</label>
            </div>
            <div class="form-check icheck-cyan d-inline">
               <input v-model="purchase_doc.heads_up_e" class="form-check-input" type="radio" name="heads_up_e" id="heads_up_e_2" value="2" :disabled="purchase_doc.heads_up_status == 1">
               <label class="form-check-label" for="heads_up_e_2">NO</label>
            </div>
            <div class="form-check icheck-cyan d-inline">
               <input v-model="purchase_doc.heads_up_e" class="form-check-input" type="radio" name="heads_up_e" id="heads_up_e_3" value="3" :disabled="purchase_doc.heads_up_status == 1">
               <label class="form-check-label" for="heads_up_e_3">未確認</label>
            </div>
         </div>
      </div>
      <div class="form-group row">
         <label style="font-weight: normal;" for="" class="col-3 col-form-label">対象物件規模</label>
         <div class="col-9">
            <div class="form-check icheck-cyan d-inline">
               <input v-model="purchase_doc.heads_up_f" class="form-check-input" type="radio" name="heads_up_f" id="heads_up_f_1" value="1"
                  data-id="A82-6" :disabled="purchase_doc.heads_up_status == 1">
               <label class="form-check-label" for="heads_up_f_1">一般</label>
            </div>
            <div class="form-check icheck-cyan d-inline">
               <input v-model="purchase_doc.heads_up_f" class="form-check-input" type="radio" name="heads_up_f" id="heads_up_f_2" value="2" :disabled="purchase_doc.heads_up_status == 1">
               <label class="form-check-label" for="heads_up_f_2">1000m<sup>2</sup>以内</label>
            </div>
            <div class="form-check icheck-cyan d-inline">
               <input v-model="purchase_doc.heads_up_f" class="form-check-input" type="radio" name="heads_up_f" id="heads_up_f_3" value="3" :disabled="purchase_doc.heads_up_status == 1">
               <label class="form-check-label" for="heads_up_f_3">1000m<sup>2</sup>～</label>
            </div>
            <div class="form-check icheck-cyan d-inline">
               <input v-model="purchase_doc.heads_up_f" class="form-check-input" type="radio" name="heads_up_f" id="heads_up_f_4" value="4" :disabled="purchase_doc.heads_up_status == 1">
               <label class="form-check-label" for="heads_up_f_4">3000m<sup>2</sup>～</label>
            </div>
            <div class="form-check icheck-cyan d-inline">
               <input v-model="purchase_doc.heads_up_f" class="form-check-input" type="radio" name="heads_up_f" id="heads_up_f_5" value="5" :disabled="purchase_doc.heads_up_status == 1">
               <label class="form-check-label" for="heads_up_f_5">その他</label>
            </div>
            <div v-if="purchase_doc.heads_up_f != 1">
              <div class="sub-label mt-2 mb-2">コンサルティング</div>
              <div class="form-check icheck-cyan d-inline">
                 <input v-model="purchase_doc.heads_up_g" class="form-check-input" type="radio" name="heads_up_g" id="heads_up_g_1" value="1"
                    data-id="A82-7" :disabled="purchase_doc.heads_up_status == 1">
                 <label class="form-check-label" for="heads_up_g_1">有</label>
              </div>
              <div class="form-check icheck-cyan d-inline">
                 <input v-model="purchase_doc.heads_up_g" class="form-check-input" type="radio" name="heads_up_g" id="heads_up_g_2" value="2" :disabled="purchase_doc.heads_up_status == 1">
                 <label class="form-check-label" for="heads_up_g_2">無</label>
              </div>
            </div>
         </div>
      </div>
      <div class="incomplete_memo form-row p-2 bg-light">
         <div class="col-auto form-text">
            <div class="form-check icheck-cyan d-inline">
               <input v-model="purchase_doc.heads_up_status" class="form-check-input" type="radio" name="heads_up_status" id="heads_up_status_1" value="1"  data-id="A82-101">
               <label class="form-check-label" for="heads_up_status_1">完</label>
            </div>
            <div class="form-check icheck-cyan d-inline">
               <input v-model="purchase_doc.heads_up_status" class="form-check-input" type="radio" name="heads_up_status" id="heads_up_status_2" value="2">
               <label class="form-check-label" for="heads_up_status_2">未</label>
            </div>
         </div>
         <div class="col-auto form-text">
            <span class="">未完メモ：</span>
         </div>
         <div class="col-6">
            <input v-model="purchase_doc.heads_up_memo" class="form-control" name="" type="text" value=""  data-id="A82-102"  placeholder="未完となっている項目や理由を記入してください" data-parsley-trigger="keyup" data-parsley-maxlength="128">
         </div>
      </div>
   </div>
</div>
