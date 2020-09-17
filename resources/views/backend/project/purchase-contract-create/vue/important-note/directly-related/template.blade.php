<script type="text/x-template" id="important-note-directly-related">
  <div class="card mt-2">
      <div class="card-header" style="font-weight: bold;">I 対象となる宅地に直接関係する事項</div>
      <div class="card-body">

          <!-- land -->
          <important-note-land v-model="entry"></important-note-land>
          <!-- land -->
          <!-- building -->
          <important-note-building v-model="entry"></important-note-building>
          <!-- building -->
          <!-- city planning restriction -->
          <important-note-city-planning-restriction v-model="entry" :purchase_sale="purchase_sale">
          </important-note-city-planning-restriction>
          <!-- city planning restriction -->
          <!-- building standard restriction -->
          <important-note-building-standard-restriction v-model="entry" :master_value="master_value"
          :residentials="residentials" :roads="roads">
          </important-note-building-standard-restriction>
          <!-- building standard restriction -->
          <!-- site and road -->
          <important-note-site-and-road v-model="entry"></important-note-site-and-road>
          <!-- site and road -->
          <!-- law restriction -->
          <important-note-law-restriction v-model="entry" :purchase_sale="purchase_sale"></important-note-law-restriction>
          <!-- law restriction -->

          <div class="incomplete_memo form-row p-2 bg-light">
              <div class="col-auto form-text">
                  <div class="form-check form-check-inline icheck-cyan">
                      <input v-model="entry.restricted_law_status"
                      class="form-check-input" type="radio" name="" id="restricted_law_status_1" value="1">
                      <label class="form-check-label" for="restricted_law_status_1">完</label>
                  </div>
                  <div class="form-check form-check-inline icheck-cyan">
                      <input v-model="entry.restricted_law_status"
                      class="form-check-input" type="radio" name="" id="restricted_law_status_2" value="2">
                      <label class="form-check-label" for="restricted_law_status_2">未</label>
                  </div>
              </div>
              <div class="col-auto form-text">
                  <span class="">未完メモ：</span>
              </div>
              <div class="col-6">
                  <input v-model="entry.restricted_law_memo" :disabled="isDisabled || isCompleted"
                  class="form-control" name="" type="text" value="" placeholder="未完となっている項目や理由を記入してください">
              </div>
          </div>
      </div>
  </div>
</script>
