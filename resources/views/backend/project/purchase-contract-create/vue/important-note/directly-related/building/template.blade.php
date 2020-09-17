<script type="text/x-template" id="important-note-building">
  <div class="form-group row">
      <label for="" class="col-3 col-form-label" style="font-weight: normal;">登記記録に記録された事項(建物)</label>
      <div class="col-9">
          <div class="row mb-1">
              <div class="col-4">権利部（甲区）</div>
              <div class="col-8">

              </div>
          </div>
          <div class="row mb-1">
              <div class="col-4 col-form-label">所有者</div>
              <div class="col-8">
                  <div class="form-check form-check-inline icheck-cyan">
                      <input v-model="entry.owner_a_building" :disabled="isDisabled || isCompleted"
                      class="form-check-input" type="radio" id="owner_a_1_building" value="1" data-id="A1310-8">
                      <label class="form-check-label" for="owner_a_1_building">別添謄本の通り</label>
                  </div>
                  <div class="form-check form-check-inline icheck-cyan">
                      <input v-model="entry.owner_a_building" :disabled="isDisabled || isCompleted"
                      class="form-check-input" type="radio" id="owner_a_2_building" value="2">
                      <label class="form-check-label" for="owner_a_2_building">自由入力</label>
                  </div>
              </div>
          </div>
          <div class="row mb-1">
              <div class="col-4 col-form-label">所有者住所</div>
              <div class="col-8">
                  <input v-model="entry.owner_address_a_building"
                  :disabled="isDisabled || isCompleted || entry.owner_a_building != 2"
                  data-parsley-trigger="keyup" data-parsley-maxlength="128"
                  class="form-control mb-1" type="text" value="" data-id="A1310-9">
              </div>
          </div>
          <div class="row mb-1">
              <div class="col-4 col-form-label">所有者氏名</div>
              <div class="col-8">
                  <input v-model="entry.owner_name_a_building"
                  :disabled="isDisabled || isCompleted || entry.owner_a_building != 2"
                  data-parsley-trigger="keyup" data-parsley-maxlength="128"
                  class="form-control mb-1" type="text" value="" data-id="A1310-10">
              </div>
          </div>
          <div class="row mb-1">
              <div class="col-4 col-form-label">所有権にかかる権利に関する事項</div>
              <div class="col-8">
                  <div class="form-check form-check-inline icheck-cyan">
                      <input v-model="entry.ownership_a_building" :disabled="isDisabled || isCompleted"
                      class="form-check-input" type="radio" id="ownership_a_1_building" value="1" data-id="A1310-11">
                      <label class="form-check-label" for="ownership_a_1_building">有</label>
                  </div>
                  <div class="form-check form-check-inline icheck-cyan">
                      <input v-model="entry.ownership_a_building" :disabled="isDisabled || isCompleted"
                      class="form-check-input" type="radio" id="ownership_a_2_building" value="2">
                      <label class="form-check-label" for="ownership_a_2_building">無</label>
                  </div>
                  <textarea v-model="entry.ownership_memo_a_building"
                  :disabled="isDisabled || isCompleted || entry.ownership_a_building != 1"
                  data-parsley-trigger="keyup" data-parsley-maxlength="4096"
                  class="form-control" id="" data-id="A1310-12"></textarea>
              </div>
          </div>

          <div class="row mb-1">
              <div class="col-4">権利部（乙区）</div>
              <div class="col-8">

              </div>
          </div>
          <div class="row mb-1">
              <div class="col-4 col-form-label">所有権にかかる権利に関する事項</div>
              <div class="col-8">
                  <div class="form-check form-check-inline icheck-cyan">
                      <input v-model="entry.ownership_b_building" :disabled="isDisabled || isCompleted"
                      class="form-check-input" type="radio" id="ownership_b_1_building" value="1" data-id="A1310-13">
                      <label class="form-check-label" for="ownership_b_1_building">有</label>
                  </div>
                  <div class="form-check form-check-inline icheck-cyan">
                      <input v-model="entry.ownership_b_building" :disabled="isDisabled || isCompleted"
                      class="form-check-input" type="radio" id="ownership_b_2_building" value="2">
                      <label class="form-check-label" for="ownership_b_2_building">無</label>
                  </div>
                  <textarea v-model="entry.ownership_memo_b_building"
                  :disabled="isDisabled || isCompleted || entry.ownership_b_building != 1"
                  data-parsley-trigger="keyup" data-parsley-maxlength="4096"
                  class="form-control" id="" data-id="A1310-14"></textarea>
              </div>
          </div>
      </div>
  </div>
  <hr>
</script>
