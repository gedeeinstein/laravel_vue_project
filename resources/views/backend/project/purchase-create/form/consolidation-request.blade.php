<div class="card mt-2">
   <div style="font-weight: 700;" class="card-header">取り纏め依頼書</div>
   <div class="card-body">
      <div class="form-group row">
         <label style="font-weight: normal;" for="" class="col-3 col-form-label">タイトル</label>
         <div class="col-9">
            <div class="form-check icheck-cyan d-inline">
               <input v-model="purchase_doc.gathering_request_title" class="form-check-input" type="radio" name="gathering_request_title" id="gathering_request_title_1" value="1" :disabled="purchase_doc.gathering_request_status == 1"
                  data-id="A86-1">
               <label class="form-check-label" for="gathering_request_title_1">取り纏め依頼書</label>
            </div>
            <div class="form-check icheck-cyan d-inline">
               <input v-model="purchase_doc.gathering_request_title" class="form-check-input" type="radio" name="gathering_request_title" id="gathering_request_title_2" value="2" :disabled="purchase_doc.gathering_request_status == 1">
               <label class="form-check-label" for="gathering_request_title_2">買付証明書</label>
            </div>
         </div>
      </div>
      <div class="form-group row">
         <label style="font-weight: normal;" for="" class="col-3 col-form-label">宛名</label>
         <div class="col-auto form-text">
            <div class="form-check icheck-cyan d-inline">
               <input v-model="purchase_doc.gathering_request_to_check" class="form-check-input" type="checkbox" name="" id="gathering_request_to_check" value="1" data-id="A86-3" :disabled="purchase_doc.gathering_request_status == 1">
               <label class="form-check-label" for="gathering_request_to_check">契約者と同じ</label>
            </div>
         </div>
         <div class="col-3">
            <div class="input-group">
               <input v-model="purchase_doc.gathering_request_to" class="form-control" name="" type="text" value="" data-id="A86-2" :readonly="purchase_doc.gathering_request_to_check == 1" :disabled="purchase_doc.gathering_request_status == 1">
               <div class="input-group-append">
                  <div class="input-group-text">様</div>
               </div>
            </div>
         </div>
      </div>
      <div v-if="purchase_third_person_occupied == 2" class="form-group row">
         <label style="font-weight: normal;" for="" class="col-3 col-form-label">第三者の占有</label>
         <div class="col-9" data-id="A86-4">
            <div class="form-check icheck-cyan">
               <input v-model="purchase_doc.gathering_request_third_party" class="form-check-input" type="radio" name="gathering_request_third_party" id="gathering_request_third_party_1" value="1" :disabled="purchase_doc.gathering_request_status == 1">
               <label class="form-check-label" for="gathering_request_third_party_1">売主様の責任と負担において、本物件の占有者の立ち退きを完了させた上で、お引き渡し頂きますようお願いします。</label>
            </div>
            <div class="form-check icheck-cyan">
               <input v-model="purchase_doc.gathering_request_third_party" class="form-check-input" type="radio" name="gathering_request_third_party" id="gathering_request_third_party_2" value="2" :disabled="purchase_doc.gathering_request_status == 1">
               <label class="form-check-label" for="gathering_request_third_party_2">売主様と買主は双方協力し、本物件の占有者の立ち退きを完了させた上で、お引き渡し頂きますようお願いします。尚、その費用はすべて買主が負担するものとします。</label>
            </div>
            <div class="form-check icheck-cyan">
               <input v-model="purchase_doc.gathering_request_third_party" class="form-check-input" type="radio" name="gathering_request_third_party" id="gathering_request_third_party_3" value="3" :disabled="purchase_doc.gathering_request_status == 1">
               <label class="form-check-label" for="gathering_request_third_party_3">本物件の占有者について、現状のまま引き渡しを受けるものとします。</label>
            </div>
         </div>
      </div>
      <div class="form-group row" data-id="A86-5">
         <label style="font-weight: normal;" for="" class="col-3 col-form-label">契約希望条件 <a target="_blank" href="{{ $agreement_list }}">[定型文ID確認]</a></label>
         <div class="col-9 checkboxes">
            <div class="text-danger mb-2">この画面の各選択肢を変更すると以下の項目も変わりますので、保存前にかならず確認してください。</div>


            {{-- notices --}}
            @include('backend.project.purchase-create.form.consolidation-request.notices')
            {{-- request permission --}}
            @include('backend.project.purchase-create.form.consolidation-request.request-permission')
            {{-- desired contract terms 1--}}
            @include('backend.project.purchase-create.form.consolidation-request.desired-contract-terms-1')
            {{-- desired contract terms 2 --}}
            @include('backend.project.purchase-create.form.consolidation-request.desired-contract-terms-2')
            {{-- desired contract terms 3 --}}
            @include('backend.project.purchase-create.form.consolidation-request.desired-contract-terms-3')
            {{-- desired contract terms 4 --}}
            @include('backend.project.purchase-create.form.consolidation-request.desired-contract-terms-4')
            {{-- desired contract terms 5 --}}
            @include('backend.project.purchase-create.form.consolidation-request.desired-contract-terms-5')
            {{-- optional items --}}
            @include('backend.project.purchase-create.form.consolidation-request.optional-items')

            <div class="sub-label" style="background: #eee; padding: 0.2em 0.4em; margin: 0.5em 0; font-weight: bold;">
                独自項目</div>
            <div v-for="(purchase_doc_optional_memo, index) in purchase_doc_optional_memos" class="freetext">
               <input v-model= "purchase_doc_optional_memo.content" class="form-control mt-1" data-id="A86-6" name="" type="text" value="" placeholder="記載事項を追加する場合は入力"
               :disabled="purchase_doc.gathering_request_status == 1" :required="(index == 0 && purchase_doc_optional_memos[index+1]) || index > 0"
               data-parsley-trigger="keyup" data-parsley-maxlength="128"
               style="width: 95%; display: inline-block;">
               <i v-if="index == 0 && purchase_doc.gathering_request_status != 1" @click="addOptionalMemo" class="button fa fa-plus-circle text-primary" data-toggle="tooltip" title="行を追加" :disabled="purchase_doc.gathering_request_status == 1"></i>
               <i v-else-if="index > 0 && purchase_doc.gathering_request_status != 1" @click="removeOptionalMemo(index)" class="button fa fa-minus-circle text-danger" data-toggle="tooltip" title="行を削除" :disabled="purchase_doc.gathering_request_status == 1"></i>
               <div class="form-result"></div>
            </div>
         </div>
      </div>
      <hr>
      <div class="form-group row">
         <label for="" class="col-3 col-form-label">契約希望日</label>
         <div class="col-9">
            <input v-model="purchase_doc.desired_contract_date" class="form-control col-5" data-id="A86-7" name="" type="text" value="" placeholder="日付または任意の文章を入力" :disabled="purchase_doc.gathering_request_status == 1">
         </div>
      </div>
      <div class="form-group row">
         <label for="" class="col-3 col-form-label">決済希望日</label>
         <div class="col-9">
            <input v-model="purchase_doc.settlement_date" class="form-control col-5" data-id="A86-8" name="" type="text" value="" placeholder="日付または任意の文章を入力" :disabled="purchase_doc.gathering_request_status == 1">
         </div>
      </div>
      <div class="form-group row">
         <label for="" class="col-3 col-form-label">有効期限</label>
         <div class="col-9">
            <date-picker v-model="purchase_doc.expiration_date" type="date" input-class="form-control input-date" format="YYYY/MM/DD" value-type="format" data-id="A86-9"
            placeholder="2020/01/01" :disabled="purchase_doc.gathering_request_status == 1"></date-picker>
         </div>
      </div>
      <div class="incomplete_memo form-row p-2 bg-light">
         <div class="col-auto form-text">
            <div class="form-check icheck-cyan d-inline">
               <input v-model="purchase_doc.gathering_request_status" class="form-check-input" type="radio" name="gathering_request_status" id="gathering_request_status_1" value="1"  data-id="A86-101">
               <label class="form-check-label" for="gathering_request_status_1">完</label>
            </div>
            <div class="form-check icheck-cyan d-inline">
               <input v-model="purchase_doc.gathering_request_status" class="form-check-input" type="radio" name="gathering_request_status" id="gathering_request_status_2" value="2">
               <label class="form-check-label" for="gathering_request_status_2">未</label>
            </div>
         </div>
         <div class="col-auto form-text">
            <span class="">未完メモ：</span>
         </div>
         <div class="col-6">
            <input v-model="purchase_doc.gathering_request_memo" class="form-control" name="" type="text" value=""  data-id="A86-102"  placeholder="未完となっている項目や理由を記入してください" data-parsley-trigger="keyup" data-parsley-maxlength="128">
         </div>
      </div>
   </div>
</div>
