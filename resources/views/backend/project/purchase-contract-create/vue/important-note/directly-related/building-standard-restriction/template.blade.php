<script type="text/x-template" id="important-note-building-standard-restriction">
  <div class="form-group row">
      <label for="" class="col-3 col-form-label" style="font-weight: normal;">建築基準法に基づく制限
          <div>
              <button onclick="window.open('{{ route('project.assist.a', $project->id) }}')"
              class="btn btn-wide btn-info px-4 mr-1" data-id="A1310-41">
                建ぺい率・容積率変更
              </button>
          </div>
      </label>
      <div class="col-9">
          <div data-id="A1310-42">
              <strong v-if="!parcel_use_districts[1]">用途地域：
                  <span class="text-danger">@{{ parcel_use_districts[0] ? parcel_use_districts[0].value : '' }}</span>
              </strong>
              <strong v-else>用途地域：
                  <span class="text-danger">@{{ parcel_use_districts[0] ? parcel_use_districts[0].value : '' }}</span>,
                    @{{ parcel_use_districts[1] ? parcel_use_districts[1].value : '' }}
              </strong>
          </div>
          <div class="row mt-1 ml-0">
              <div class="col-3 col-form-label icheck-cyan">
                  <input v-model="entry.use_district" :disabled="isDisabled || isCompleted"
                  id="use_district" class="form-check-input" type="checkbox" value="1" data-id="A1310-43">
                  <label class="form-check-label" for="use_district">特別用途地区</label>
              </div>
              <div class="col-9">
                  <input v-model="entry.use_district_text"
                  :disabled="isDisabled || isCompleted || entry.use_district == false"
                  data-parsley-trigger="keyup" data-parsley-maxlength="128"
                  class="form-control" type="text" value="" data-id="A1310-44">
              </div>
          </div>
          <div class="row mt-1 ml-0">
              <div class="col-3 col-form-label icheck-cyan">
                  <input v-model="entry.restricted_use_district" :disabled="isDisabled || isCompleted"
                  id="restricted_use_district" class="form-check-input" type="checkbox" value="1" data-id="A1310-45">
                  <label class="form-check-label" for="restricted_use_district">特別用途制限区域</label>
              </div>
              <div class="col-9">
                  <input v-model="entry.restricted_use_district_text"
                  :disabled="isDisabled || isCompleted || entry.restricted_use_district == false"
                  data-parsley-trigger="keyup" data-parsley-maxlength="128"
                  class="form-control" type="text" value="" data-id="A1310-46">
              </div>
          </div>

          <div class="my-3">
              <div class="form-inline mt-1">
                  <div class="input-group">
                      <label class="form-check-label" for=""><strong>建ぺい率</strong></label>
                      <input v-model="entry.building_coverage_ratio" :disabled="isDisabled || isCompleted"
                      class="form-control col-4 ml-2 input-decimal" type="number" value="" data-id="A1310-47">
                      <div class="input-group-append">
                          <div class="input-group-text">%</div>
                      </div>
                      <span v-if="parcel_build_ratios.length > 1" class="text-danger ml-2 mt-2" data-id="A1310-48">※複数存在します</span>
                  </div>
              </div>
              <div class="form-check icheck-cyan">
                  <input v-model="entry.fire_prevention_area" :disabled="isDisabled || isCompleted"
                  class="form-check-input" type="radio" id="fire_prevention_area_1" value="1" data-id="A1310-49">
                  <label class="form-check-label" for="fire_prevention_area_1">角地緩和有</label>
              </div>
              <div class="form-check icheck-cyan">
                  <input v-model="entry.fire_prevention_area" :disabled="isDisabled || isCompleted"
                  class="form-check-input" type="radio" id="fire_prevention_area_2" value="2">
                  <label class="form-check-label" for="fire_prevention_area_2">防火地域内耐火建築物</label>
              </div>
              <div class="form-check icheck-cyan">
                  <input v-model="entry.fire_prevention_area" :disabled="isDisabled || isCompleted"
                  class="form-check-input" type="radio" id="fire_prevention_area_3" value="3">
                  <label class="form-check-label" for="fire_prevention_area_3">角地＋防火耐火</label>
              </div>
              <div class="form-check icheck-cyan">
                  <input v-model="entry.fire_prevention_area" :disabled="isDisabled || isCompleted"
                  class="form-check-input" type="radio" id="fire_prevention_area_4" value="4">
                  <label class="form-check-label" for="fire_prevention_area_4">建ぺい制限なし</label>
              </div>
              <div>
                  <div class="form-inline icheck-cyan">
                      <input v-model="entry.fire_prevention_area" :disabled="isDisabled || isCompleted"
                      class="form-check-input" type="radio" id="fire_prevention_area_5" value="5">
                      <label class="form-check-label" for="fire_prevention_area_5">自由入力</label>
                      <input v-model="entry.fire_prevention_area_text"
                      :disabled="isDisabled || isCompleted || entry.fire_prevention_area != 5"
                      data-parsley-trigger="keyup" data-parsley-maxlength="128"
                      class="form-control ml-2" type="text" value="" data-id="A1310-50">
                  </div>
              </div>
              <div class="form-check icheck-cyan">
                  <input v-model="entry.fire_prevention_area" :disabled="isDisabled || isCompleted"
                  class="form-check-input" type="radio" id="fire_prevention_area_6" value="6">
                  <label class="form-check-label" for="fire_prevention_area_6">上記いずれにも該当しない</label>
              </div>
          </div>
          <div class="row mb-2">
              <div class="col-2 col-form-label">
                  <label class="form-check-label" for="">容積率</label>
              </div>
              <div class="col-2">
                  <div class="input-group">
                      <input v-model="entry.floor_area_ratio_text" :disabled="isDisabled || isCompleted"
                      class="form-control input-decimal" type="number" value="" data-id="A1310-51">
                      <div class="input-group-append">
                          <div class="input-group-text">%</div>
                      </div>
                  </div>
              </div>
              <div v-if="parcel_floor_ratios.length > 1"
              class="col-3">
                  <span class="text-danger form-text" data-id="A1310-52">※複数存在します</span>
              </div>
          </div>
          <div class="row">
              <div class="col-2 col-form-label">
                  <label class="form-check-label" for="">道路幅員</label>
              </div>
              <div class="col-2">
                  <div class="input-group">
                      <input v-model="entry.road_width" :disabled="isDisabled || isCompleted"
                      class="form-control input-decimal" type="number" value="" data-id="A1310-53">
                      <div class="input-group-append">
                          <div class="input-group-text">m</div>
                      </div>
                  </div>
              </div>
              <div class="col-3">
                  <span class="form-text">× @{{ multiplication_rate }} / 10 x 100 =
                      <template v-if="entry.road_width">@{{ road_width_calculation }}</template>%
                  </span>
              </div>
          </div>

          <div class="my-3">
              <div class="row mb-1">
                  <div class="col-4">壁面線の制限</div>
                  <div class="col-8">
                      <div class="form-check form-check-inline icheck-cyan">
                          <input v-model="entry.wall_restrictions" :disabled="isDisabled || isCompleted"
                          class="form-check-input" type="radio" id="wall_restrictions_1" value="1" data-id="A1310-110">
                          <label class="form-check-label" for="wall_restrictions_1">有</label>
                      </div>
                      <div class="form-check form-check-inline icheck-cyan">
                          <input v-model="entry.wall_restrictions" :disabled="isDisabled || isCompleted"
                          class="form-check-input" type="radio" id="wall_restrictions_2" value="2">
                          <label class="form-check-label" for="wall_restrictions_2">無</label>
                      </div>
                  </div>
              </div>
              <div class="row mb-1">
                  <div class="col-4">外壁後退</div>
                  <div class="col-8">
                      <div class="form-check form-check-inline icheck-cyan">
                          <input v-model="entry.exterior_wall_receding" :disabled="isDisabled || isCompleted"
                          class="form-check-input" type="radio" id="exterior_wall_receding_1" value="1" data-id="A1310-54">
                          <label class="form-check-label" for="exterior_wall_receding_1">無</label>
                      </div>
                      <div class="form-check form-check-inline icheck-cyan">
                          <input v-model="entry.exterior_wall_receding" :disabled="isDisabled || isCompleted"
                          class="form-check-input" type="radio" id="exterior_wall_receding_2" value="2">
                          <label class="form-check-label" for="exterior_wall_receding_2">1m以上</label>
                      </div>
                      <div class="form-check form-check-inline icheck-cyan">
                          <input v-model="entry.exterior_wall_receding" :disabled="isDisabled || isCompleted"
                          class="form-check-input" type="radio" id="exterior_wall_receding_3" value="3">
                          <label class="form-check-label" for="exterior_wall_receding_3">1.5m以上</label>
                      </div>
                  </div>
              </div>
              <div class="row mb-1">
                  <div class="col-4">敷地面積の最低限度</div>
                  <div class="col-8">
                      <div class="form-check form-check-inline icheck-cyan">
                          <input v-model="entry.minimum_floor_area" :disabled="isDisabled || isCompleted"
                          class="form-check-input" type="radio" id="minimum_floor_area_1" value="1" data-id="A1310-55">
                          <label class="form-check-label" for="minimum_floor_area_1">有</label>
                      </div>
                      <div class="form-check form-check-inline icheck-cyan">
                          <input v-model="entry.minimum_floor_area" :disabled="isDisabled || isCompleted"
                          class="form-check-input" type="radio" id="minimum_floor_area_2" value="2">
                          <label class="form-check-label" for="minimum_floor_area_2">無</label>
                          <div class="input-group">
                              <input v-model="entry.minimum_floor_area_text"
                              :disabled="isDisabled || isCompleted || entry.minimum_floor_area != 1"
                              class="form-control input-decimal col-3 ml-2" type="number" value="" data-id="A1310-56">
                              <div class="input-group-append">
                                  <div class="input-group-text">m</div>
                              </div>
                          </div>
                      </div>
                  </div>
              </div>
              <div class="row mb-1">
                  <div class="col-4">建築協定</div>
                  <div class="col-8">
                      <div class="form-check form-check-inline icheck-cyan">
                          <input v-model="entry.building_agreement" :disabled="isDisabled || isCompleted"
                          class="form-check-input" type="radio" id="building_agreement_1" value="1" data-id="A1310-57">
                          <label class="form-check-label" for="building_agreement_1">有</label>
                      </div>
                      <div class="form-check form-check-inline icheck-cyan">
                          <input v-model="entry.building_agreement" :disabled="isDisabled || isCompleted"
                          class="form-check-input" type="radio" id="building_agreement_2" value="2">
                          <label class="form-check-label" for="building_agreement_2">無</label>
                      </div>
                  </div>
              </div>
              <div class="row mb-1">
                  <div class="col-4">絶対高さ制限</div>
                  <div class="col-8">
                      <template v-if="parcel_use_districts_key[0] == 1 || parcel_use_districts_key[0] == 11
                      || parcel_use_districts_key[1] == 11">
                      <div class="form-check form-check-inline icheck-cyan">
                          <input v-model="entry.absolute_height_limit" :disabled="isDisabled || isCompleted"
                          class="form-check-input" type="radio" id="absolute_height_limit_1" value="1" data-id="A1310-58">
                          <label class="form-check-label" for="absolute_height_limit_1">10m</label>
                      </div>
                      <div class="form-check form-check-inline icheck-cyan">
                          <input v-model="entry.absolute_height_limit" :disabled="isDisabled || isCompleted"
                          class="form-check-input" type="radio" id="absolute_height_limit_2" value="2">
                          <label class="form-check-label" for="absolute_height_limit_2">12m</label>
                      </div>
                      <div class="form-check form-check-inline icheck-cyan">
                          <input v-model="entry.absolute_height_limit" :disabled="isDisabled || isCompleted"
                          class="form-check-input" type="radio" id="absolute_height_limit_3" value="3">
                          <label class="form-check-label" for="absolute_height_limit_3">その他</label>
                          <div class="form-inline ml-2">
                              <div class=" input-group">
                                  <input v-model="entry.absolute_height_limit_text"
                                  :disabled="isDisabled || isCompleted || entry.absolute_height_limit != 3"
                                  class="form-control input-decimal col-3" type="number" value="" data-id="A1310-59">
                                  <div class="input-group-append">
                                      <div class="input-group-text">m</div>
                                  </div>
                              </div>
                          </div>
                      </div>
                      </template>
                  </div>
              </div>
              <div class="row mb-1">
                  <div class="col-4">道路斜線制限</div>
                  <div v-if="parcel_use_districts_key[0]"
                  class="col-8 text-primary" data-id="A1310-60">有</div>
              </div>
              <div class="row mb-1">
                  <div class="col-4">隣地斜線制限</div>
                  <div v-if="parcel_use_districts_key[0] == 1 || parcel_use_districts_key[0] == 11
                  || parcel_use_districts_key[1] == 11"
                  class="col-8 text-danger" data-id="A1310-61">無</div>
                  <div v-else class="col-8 text-primary">有</div>
              </div>
              <div class="row mb-1">
                  <div class="col-4">北側斜線制限</div>
                  <div v-if="parcel_use_districts_key[0] == 1 || parcel_use_districts_key[0] == 11
                  || parcel_use_districts_key[1] == 11 || parcel_use_districts_key[0] == 2 || parcel_use_districts_key[0] == 4
                  || parcel_use_districts_key[1] == 4"
                  class="col-8 text-primary" data-id="A1310-62">有</div>
                  <div v-else class="col-8 text-danger">無</div>
              </div>
              <div class="row mb-1">
                  <div class="col-4">日影規制</div>
                  <div v-if="parcel_use_districts_key[0]"
                  class="col-8 text-primary">有</div>
              </div>
              <div class="row mb-1">
                  <div class="col-4">私道の変更または廃止の制限</div>
                  <div class="col-8">
                      <div class="form-check form-check-inline icheck-cyan">
                          <input v-model="entry.private_road_change_or_abolition_restrictions" :disabled="isDisabled || isCompleted"
                          id="private_road_change_or_abolition_restrictions_1" class="form-check-input" type="radio" value="1" data-id="A1310-64">
                          <label class="form-check-label" for="private_road_change_or_abolition_restrictions_1">有</label>
                      </div>
                      <div class="form-check form-check-inline icheck-cyan">
                          <input v-model="entry.private_road_change_or_abolition_restrictions" :disabled="isDisabled || isCompleted"
                          id="private_road_change_or_abolition_restrictions_2" class="form-check-input" type="radio" value="2">
                          <label class="form-check-label" for="private_road_change_or_abolition_restrictions_2">無</label>
                      </div>
                  </div>
              </div>
              <div class="my-3">
                  <label class="col-form-label" for=""><strong>備考</strong></label>
                  <textarea v-model="entry.building_standard_act_remarks" :disabled="isDisabled || isCompleted"
                  data-parsley-trigger="keyup" data-parsley-maxlength="4096"
                  class="form-control" id="" data-id="A1310-65"></textarea>
              </div>
          </div>
          <div data-id="A1310-66">
              <ul>
                  <li v-if="entry.private_road_change_or_abolition_restrictions == 1 && parcel_use_districts[0] && parcel_use_districts[1]"
                  class="text-danger">
                      用途地域：敷地が２以上の用途地域にわたるときは、用途地域の建築物の用途制限については建築基準法91条により敷地の過半が属する用途地域の制限を受けます。
                  </li>
                  <li v-if="parcel_build_ratios.length > 1 || parcel_floor_ratios.length > 1"
                      class="text-danger">
                      建ぺい率及び容積率：建築確認申請上の敷地は、制限の異なる２つの地域にまたがっているため、建ぺい率及び容積率の制限は、各地域の建ぺい率及び容積率にそれぞれ各地域にある敷地面積の全体の敷地面積に対する割合を乗じて得た数値の合計となります。
                  </li>
              </ul>
          </div>
      </div>
  </div>
  <hr>
</script>
