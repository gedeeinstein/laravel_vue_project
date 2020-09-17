<script type="text/x-template" id="important-note-real-estate">
  <div class="card mt-2">
      <div class="card-header" style="font-weight: bold;">A 不動産の表示に関する事項</div>
      <div class="card-body">
          <div class="form-group row">
              <label for="" class="col-3 col-form-label" style="font-weight: normal;">不動産の表示及び土地の測量に関する備考</label>
              <div class="col-9">
                  <textarea v-model="entry.display_and_remarks_of_land" :disabled="isDisabled || isCompleted"
                  data-parsley-trigger="keyup" data-parsley-maxlength="4096"
                  class="form-control" id="" data-id="A138-1"></textarea>
              </div>
          </div>
          <div v-if="building_kind"
          class="form-group row">
              <label for="" class="col-3 col-form-label" style="font-weight: normal;">不動産の表示建物に関する備考</label>
              <div class="col-9">
                  <textarea v-model="entry.build_remarks" :disabled="isDisabled || isCompleted"
                  data-parsley-trigger="keyup" data-parsley-maxlength="4096"
                  class="form-control" id="" data-id="A138-2"></textarea>
              </div>
          </div>
          <div class="incomplete_memo form-row p-2 bg-light">
              <div class="col-auto form-text">
                  <div class="form-check form-check-inline icheck-cyan">
                      <input v-model="entry.real_estate_related_status"
                      class="form-check-input" type="radio" name="" id="real_estate_related_status_1" value="1" data-id="A138-101">
                      <label class="form-check-label" for="real_estate_related_status_1">完</label>
                  </div>
                  <div class="form-check form-check-inline icheck-cyan">
                      <input v-model="entry.real_estate_related_status"
                      class="form-check-input" type="radio" name="" id="real_estate_related_status_2" value="2">
                      <label class="form-check-label" for="real_estate_related_status_2">未</label>
                  </div>
              </div>
              <div class="col-auto form-text">
                  <span class="">未完メモ：</span>
              </div>
              <div class="col-6">
                  <input v-model="entry.real_estate_related_memo" :disabled="isDisabled || isCompleted"
                  data-parsley-trigger="keyup" data-parsley-maxlength="128"
                  class="form-control" name="" type="text" value="" data-id="A138-102" placeholder="未完となっている項目や理由を記入してください">
              </div>
          </div>
      </div>
  </div>
</script>
