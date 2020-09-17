<script type="text/x-template" id="important-note-land">
  <div class="form-group row">
      <label for="" class="col-3 col-form-label" style="font-weight: normal;">登記記録に記録された事項(土地)</label>
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
                      <input v-model="entry.owner_a_land" :disabled="isDisabled || isCompleted"
                      class="form-check-input" type="radio" id="owner_a_1_land" value="1" data-id="A1310-1">
                      <label class="form-check-label" for="owner_a_1_land">別添謄本の通り</label>
                  </div>
                  <div class="form-check form-check-inline icheck-cyan">
                      <input v-model="entry.owner_a_land" :disabled="isDisabled || isCompleted"
                      class="form-check-input" type="radio" id="owner_a_2_land" value="2">
                      <label class="form-check-label" for="owner_a_2_land">自由入力</label>
                  </div>
              </div>
          </div>
          <div class="row mb-1">
              <div class="col-4 col-form-label">所有者住所</div>
              <div class="col-8">
                  <input v-model="entry.owner_address_a_land"
                  :disabled="isDisabled || isCompleted || entry.owner_a_land != 2"
                  data-parsley-trigger="keyup" data-parsley-maxlength="128"
                  class="form-control mb-1" type="text" value="" data-id="A1310-2">
              </div>
          </div>
          <div class="row mb-1">
              <div class="col-4 col-form-label">所有者氏名</div>
              <div class="col-8">
                  <input v-model="entry.owner_name_a_land"
                  :disabled="isDisabled || isCompleted || entry.owner_a_land != 2"
                  data-parsley-trigger="keyup" data-parsley-maxlength="128"
                  class="form-control mb-1" type="text" value="" data-id="A1310-3">
              </div>
          </div>
          <div class="row mb-1">
              <div class="col-4 col-form-label">所有権にかかる権利に関する事項</div>
              <div class="col-8">
                  <div class="form-check form-check-inline icheck-cyan">
                      <input v-model="entry.ownership_a_land" :disabled="isDisabled || isCompleted"
                      class="form-check-input" type="radio" id="ownership_a_1_land" value="1" data-id="A1310-4">
                      <label class="form-check-label" for="ownership_a_1_land">有</label>
                  </div>
                  <div class="form-check form-check-inline icheck-cyan">
                      <input v-model="entry.ownership_a_land" :disabled="isDisabled || isCompleted"
                      class="form-check-input" type="radio" id="ownership_a_2_land" value="2">
                      <label class="form-check-label" for="ownership_a_2_land">無</label>
                  </div>
                  <textarea v-model="entry.ownership_memo_a_land"
                  :disabled="isDisabled || isCompleted || entry.ownership_a_land != 1"
                  data-parsley-trigger="keyup" data-parsley-maxlength="4096"
                  class="form-control" id="" data-id="A1310-5"></textarea>
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
                      <input v-model="entry.ownership_b_land" :disabled="isDisabled || isCompleted"
                      class="form-check-input" type="radio" id="ownership_b_1_land" value="1" data-id="A1310-6">
                      <label class="form-check-label" for="ownership_b_1_land">有</label>
                  </div>
                  <div class="form-check form-check-inline icheck-cyan">
                      <input v-model="entry.ownership_b_land" :disabled="isDisabled || isCompleted"
                      class="form-check-input" type="radio" id="ownership_b_2_land" value="2">
                      <label class="form-check-label" for="ownership_b_2_land">無</label>
                  </div>
                  <textarea v-model="entry.ownership_memo_b_land"
                  :disabled="isDisabled || isCompleted || entry.ownership_b_land != 1"
                  data-parsley-trigger="keyup" data-parsley-maxlength="4096"
                  class="form-control" id="" data-id="A1310-7"></textarea>
              </div>
          </div>
      </div>
  </div>
  <hr>
</script>
