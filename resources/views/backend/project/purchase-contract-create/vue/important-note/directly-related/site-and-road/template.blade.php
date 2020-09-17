<script type="text/x-template" id="important-note-site-and-road">
  <div class="form-group row">
      <label for="" class="col-3 col-form-label" style="font-weight: normal;">敷地等と道路との関係</label>
      <div class="col-9">
          <table class="table table-bordered table-small">
              <thead>
                  <tr>
                      <th class="">接道方向</th>
                      <th class="">公道私道の別</th>
                      <th class="">下記種類</th>
                      <th class="">幅員</th>
                      <th class="">接道の長さ</th>
                  </tr>
              </thead>
              <tbody>
                  <tr>
                      <td>
                          <select v-model="entry.create_site_and_road_direction_0"
                          :disabled="isDisabled || isCompleted"
                          class="form-control form-control-sm w-100" data-id="A1310-67" name="">
                              <option></option>
                              <option value="1">東</option>
                              <option value="2">西</option>
                              <option value="3">南</option>
                              <option value="4">北</option>
                              <option value="5">北東</option>
                              <option value="6">南東</option>
                              <option value="7">北西</option>
                              <option value="8">南西</option>
                          </select>
                      </td>
                      <td>
                          <select v-model="entry.create_site_and_road_0"
                          :disabled="isDisabled || isCompleted"
                          class="form-control form-control-sm w-100" data-id="A1310-68" name="">
                              <option></option>
                              <option value="1">公道</option>
                              <option value="2">私道</option>
                          </select>
                      </td>
                      <td>
                          <select v-model="entry.create_site_and_road_type_0"
                          :disabled="isDisabled || isCompleted"
                          class="form-control form-control-sm w-100" data-id="A1310-69" name="">
                              <option></option>
                              <option value="1">1</option>
                              <option value="2">2</option>
                              <option value="3">3</option>
                              <option value="4">4</option>
                              <option value="5">5</option>
                              <option value="6">6</option>
                              <option value="7">7</option>
                              <option value="8">8</option>
                          </select>
                      </td>
                      <td>
                          <div class="form-group">
                              <div class="input-group input-group-small">
                                  <input v-model="entry.width_0"
                                  :disabled="isDisabled || isCompleted || !entry.create_site_and_road_type_0"
                                  class="form-control input-decimal form-control-w-sm" data-id="A1310-70" type="number" value="">
                                  <div class="input-group-append">
                                      <div class="input-group-text input-group-text-xs">m</div>
                                  </div>
                              </div>
                          </div>
                      </td>
                      <td>
                          <div class="form-group">
                              <div class="input-group input-group-small">
                                  <input v-model="entry.length_of_roadway_0"
                                  :disabled="isDisabled || isCompleted || !entry.create_site_and_road_type_0"
                                  class="form-control input-decimal form-control-w-sm" data-id="A1310-71" type="number" value="">
                                  <div class="input-group-append">
                                      <div class="input-group-text input-group-text-xs">m</div>
                                  </div>
                              </div>
                          </div>
                      </td>
                  </tr>
                      <tr>
                      <td>
                          <select v-model="entry.create_site_and_road_direction_1"
                          :disabled="isDisabled || isCompleted"
                          class="form-control form-control-sm w-100" name="">
                              <option></option>
                              <option value="1">東</option>
                              <option value="2">西</option>
                              <option value="3">南</option>
                              <option value="4">北</option>
                              <option value="5">北東</option>
                              <option value="6">南東</option>
                              <option value="7">北西</option>
                              <option value="8">南西</option>
                          </select>
                      </td>
                      <td>
                          <select v-model="entry.create_site_and_road_1"
                          :disabled="isDisabled || isCompleted"
                          class="form-control form-control-sm w-100" name="">
                              <option></option>
                              <option value="1">公道</option>
                              <option value="2">私道</option>
                          </select>
                      </td>
                      <td>
                          <select v-model="entry.create_site_and_road_type_1"
                          :disabled="isDisabled || isCompleted"
                          class="form-control form-control-sm w-100" name="">
                              <option></option>
                              <option value="1">1</option>
                              <option value="2">2</option>
                              <option value="3">3</option>
                              <option value="4">4</option>
                              <option value="5">5</option>
                              <option value="6">6</option>
                              <option value="7">7</option>
                              <option value="8">8</option>
                          </select>
                      </td>
                      <td>
                          <div class="form-group">
                              <div class="input-group input-group-small">
                                  <input v-model="entry.width_1"
                                  :disabled="isDisabled || isCompleted || !entry.create_site_and_road_type_1"
                                  class="form-control input-decimal form-control-w-sm" type="number" value="">
                                  <div class="input-group-append">
                                      <div class="input-group-text input-group-text-xs">m</div>
                                  </div>
                              </div>
                          </div>
                      </td>
                      <td>
                          <div class="form-group">
                              <div class="input-group input-group-small">
                                  <input v-model="entry.length_of_roadway_1"
                                  :disabled="isDisabled || isCompleted || !entry.create_site_and_road_type_1"
                                  class="form-control input-decimal form-control-w-sm" type="number" value="">
                                  <div class="input-group-append">
                                      <div class="input-group-text input-group-text-xs">m</div>
                                  </div>
                              </div>
                          </div>
                      </td>
                  </tr>
                      <tr>
                      <td>
                          <select v-model="entry.create_site_and_road_direction_2"
                          :disabled="isDisabled || isCompleted"
                          class="form-control form-control-sm w-100" name="">
                              <option></option>
                              <option value="1">東</option>
                              <option value="2">西</option>
                              <option value="3">南</option>
                              <option value="4">北</option>
                              <option value="5">北東</option>
                              <option value="6">南東</option>
                              <option value="7">北西</option>
                              <option value="8">南西</option>
                          </select>
                      </td>
                      <td>
                          <select v-model="entry.create_site_and_road_2"
                          :disabled="isDisabled || isCompleted"
                          class="form-control form-control-sm w-100" name="">
                              <option></option>
                              <option value="1">公道</option>
                              <option value="2">私道</option>
                          </select>
                      </td>
                      <td>
                          <select v-model="entry.create_site_and_road_type_2"
                          :disabled="isDisabled || isCompleted"
                          class="form-control form-control-sm w-100" name="">
                              <option></option>
                              <option value="1">1</option>
                              <option value="2">2</option>
                              <option value="3">3</option>
                              <option value="4">4</option>
                              <option value="5">5</option>
                              <option value="6">6</option>
                              <option value="7">7</option>
                              <option value="8">8</option>
                          </select>
                      </td>
                      <td>
                          <div class="form-group">
                              <div class="input-group input-group-small">
                                  <input v-model="entry.width_2"
                                  :disabled="isDisabled || isCompleted || !entry.create_site_and_road_type_2"
                                  class="form-control input-decimal form-control-w-sm" type="number" value="">
                                  <div class="input-group-append">
                                      <div class="input-group-text input-group-text-xs">m</div>
                                  </div>
                              </div>
                          </div>
                      </td>
                      <td>
                          <div class="form-group">
                              <div class="input-group input-group-small">
                                  <input v-model="entry.length_of_roadway_2"
                                  :disabled="isDisabled || isCompleted || !entry.create_site_and_road_type_2"
                                  class="form-control input-decimal form-control-w-sm" type="number" value="">
                                  <div class="input-group-append">
                                      <div class="input-group-text input-group-text-xs">m</div>
                                  </div>
                              </div>
                          </div>
                      </td>
                  </tr>
                      <tr>
                      <td>
                          <select v-model="entry.create_site_and_road_direction_3"
                          :disabled="isDisabled || isCompleted"
                          class="form-control form-control-sm w-100" name="">
                              <option></option>
                              <option value="1">東</option>
                              <option value="2">西</option>
                              <option value="3">南</option>
                              <option value="4">北</option>
                              <option value="5">北東</option>
                              <option value="6">南東</option>
                              <option value="7">北西</option>
                              <option value="8">南西</option>
                          </select>
                      </td>
                      <td>
                          <select v-model="entry.create_site_and_road_3"
                          :disabled="isDisabled || isCompleted"
                          class="form-control form-control-sm w-100" name="">
                              <option></option>
                              <option value="1">公道</option>
                              <option value="2">私道</option>
                          </select>
                      </td>
                      <td>
                          <select v-model="entry.create_site_and_road_type_3"
                          :disabled="isDisabled || isCompleted"
                          class="form-control form-control-sm w-100" name="">
                              <option></option>
                              <option value="1">1</option>
                              <option value="2">2</option>
                              <option value="3">3</option>
                              <option value="4">4</option>
                              <option value="5">5</option>
                              <option value="6">6</option>
                              <option value="7">7</option>
                              <option value="8">8</option>
                          </select>
                      </td>
                      <td>
                          <div class="form-group">
                              <div class="input-group input-group-small">
                                  <input v-model="entry.width_3"
                                  :disabled="isDisabled || isCompleted || !entry.create_site_and_road_type_3"
                                  class="form-control input-decimal form-control-w-sm" type="number" value="">
                                  <div class="input-group-append">
                                      <div class="input-group-text input-group-text-xs">m</div>
                                  </div>
                              </div>
                          </div>
                      </td>
                      <td>
                          <div class="form-group">
                              <div class="input-group input-group-small">
                                  <input v-model="entry.length_of_roadway_3"
                                  :disabled="isDisabled || isCompleted || !entry.create_site_and_road_type_3"
                                  class="form-control input-decimal form-control-w-sm" type="number" value="">
                                  <div class="input-group-append">
                                      <div class="input-group-text input-group-text-xs">m</div>
                                  </div>
                              </div>
                          </div>
                      </td>
                  </tr>
              </tbody>
          </table>

          <template v-if="entry.create_site_and_road_type_0 == 5 || entry.create_site_and_road_type_1 == 5
                        || entry.create_site_and_road_type_2 == 5 || entry.create_site_and_road_type_3 == 5">
              <div class="sub-label">道路位置指定（道路の番号５番）</div>
              <div class="row mt-1 ml-0">
                  <div class="col-1 col-form-label">
                      <label class="form-check-label" for="">指定日</label>
                  </div>
                  <div class="col-2">
                      <input v-model="entry.designated_date" :disabled="isDisabled || isCompleted"
                      data-parsley-trigger="keyup" data-parsley-maxlength="128"
                      class="form-control" type="text" value="" data-id="A1310-72">
                  </div>
                  <div class="col-auto col-form-label">
                      <label class="form-check-label" for="">第</label>
                  </div>
                  <div class="input-group col-3">
                      <input v-model="entry.number" :disabled="isDisabled || isCompleted"
                      data-parsley-trigger="keyup" data-parsley-maxlength="128"
                      class="form-control" type="text" value="" data-id="A1310-73">
                      <div class="input-group-append">
                          <div class="input-group-text">号</div>
                      </div>
                  </div>
              </div>
          </template>

          <div class="sub-label">道路境界線後退（セットバック）による建築確認対象面積の減少</div>
          <div class="form-check form-check-inline icheck-cyan">
              <input v-model="entry.setback" :disabled="isDisabled || isCompleted"
              class="form-check-input" type="radio" id="setback_1" value="1" data-id="A1310-74">
              <label class="form-check-label" for="setback_1">無</label>
          </div>
          <div class="form-check form-check-inline icheck-cyan">
              <input v-model="entry.setback" :disabled="isDisabled || isCompleted"
              class="form-check-input" type="radio" id="setback_2" value="2">
              <label class="form-check-label" for="setback_2">有</label>
              <div v-if="entry.setback == 2"
              class="input-group ml-3">
                  <div class="col-form-label">→セットバックする部分の面積</div>
                  <input v-model="entry.setback_area" :disabled="isDisabled || isCompleted"
                  class="form-control input-decimal col-2 ml-2" type="number" value="" data-id="A1310-75">
                  <div class="input-group-append">
                      <div class="input-group-text">m<sup>2</sup></div>
                  </div>
              </div>
          </div>

          <div class="sub-label">条例による制限</div>
          <div class="form-check form-check-inline icheck-cyan">
              <input v-model="entry.restricted_ordinance" :disabled="isDisabled || isCompleted"
              class="form-check-input" type="radio" id="restricted_ordinance_1" value="1" data-id="A1310-76">
              <label class="form-check-label" for="restricted_ordinance_1">無</label>
          </div>
          <div class="form-check form-check-inline icheck-cyan">
              <input v-model="entry.restricted_ordinance" :disabled="isDisabled || isCompleted"
              class="form-check-input" type="radio" id="restricted_ordinance_2" value="2">
              <label class="form-check-label" for="restricted_ordinance_2">有</label>
              <div v-if="entry.restricted_ordinance == 2"
              class="input-group ml-3">
                  <div class="col-form-label">→</div>
                  <input v-model="entry.restricted_ordinance_text" :disabled="isDisabled || isCompleted"
                  data-parsley-trigger="keyup" data-parsley-maxlength="128"
                  class="form-control ml-2" type="text" value="" data-id="A1310-77">
              </div>
          </div>
          <div v-if="entry.restricted_ordinance == 2"
          class="row mt-1 ml-0">
              <div class="col-auto col-form-label">
                  <label class="form-check-label" for="">路地状部分の長さ</label>
              </div>
              <div class="input-group col-2">
                  <input v-model="entry.alley_part_length" :disabled="isDisabled || isCompleted"
                  class="form-control input-decimal" type="number" value="" data-id="A1310-78">
                  <div class="input-group-append">
                      <div class="input-group-text">m</div>
                  </div>
              </div>
              <div class="col-auto col-form-label">
                  <label class="form-check-label" for="">路地状部分の幅員</label>
              </div>
              <div class="input-group col-2">
                  <input v-model="entry.alley_part_width" :disabled="isDisabled || isCompleted"
                  class="form-control input-decimal" type="number" value="" data-id="A1310-79">
                  <div class="input-group-append">
                      <div class="input-group-text">号</div>
                  </div>
              </div>
          </div>
          <div class="sub-label" data-id="A1310-80">道路の種類</div>
          <ol class="road-type-contents list-bullet">
              <li class="bullet" :class="{checked : entry.create_site_and_road_type_0 == 1 || entry.create_site_and_road_type_1 == 1
                     || entry.create_site_and_road_type_2 == 1 || entry.create_site_and_road_type_3 == 1}">
                        道路法による道路（法第42条第1項第1号道路）
              </li>
              <li class="bullet" :class="{checked : entry.create_site_and_road_type_0 == 2 || entry.create_site_and_road_type_1 == 2
                     || entry.create_site_and_road_type_2 == 2 || entry.create_site_and_road_type_3 == 2}">
                  都市計画法、土地区画整理法、旧住宅地造成事業法、都市再開発法、新都市基盤整備法、大都市法、密集市街地整備法による道路（法第42条第1項第3号道路）
              </li>
              <li class="bullet" :class="{checked : entry.create_site_and_road_type_0 == 3 || entry.create_site_and_road_type_1 == 3
                     || entry.create_site_and_road_type_2 == 3 || entry.create_site_and_road_type_3 == 3}">
                  既存道（建築基準法第3章適用の際に、現に存するもの）（法第42条第1項第3号道路）
              </li>
              <li class="bullet" :class="{checked : entry.create_site_and_road_type_0 == 4 || entry.create_site_and_road_type_1 == 4
                     || entry.create_site_and_road_type_2 == 4 || entry.create_site_and_road_type_3 == 4}">
                  道路法、都市計画法、土地区画整理法、都市再開発法、新都市基盤整備法、大都市法、密集市街地整備法の事業による計画道路（2年以内に事業が執行予定、特定行政庁の指定あり）（法第42条第1項第4号道路）
              </li>
              <li class="bullet" :class="{checked : entry.create_site_and_road_type_0 == 5 || entry.create_site_and_road_type_1 == 5
                     || entry.create_site_and_road_type_2 == 5 || entry.create_site_and_road_type_3 == 5}">
                  土地を建築物の敷地として利用するため、上記1〜4の法によらないで道を築造しようとする者が特定行政庁から指定を受けたもの（位置指定道路）（法第42条第1項第5号道路）
              </li>
              <li class="bullet" :class="{checked : entry.create_site_and_road_type_0 == 6 || entry.create_site_and_road_type_1 == 6
                     || entry.create_site_and_road_type_2 == 6 || entry.create_site_and_road_type_3 == 6}">
                  上記3の既存道のうち、幅員が4m（または6m）未満のもので特定行政庁が指定したもの
              </li>
              <li class="bullet" :class="{checked : entry.create_site_and_road_type_0 == 7 || entry.create_site_and_road_type_1 == 7
                     || entry.create_site_and_road_type_2 == 7 || entry.create_site_and_road_type_3 == 7}">
                  <input v-model="entry.road_type_text" :disabled="isDisabled || isCompleted || (entry.create_site_and_road_type_0 != 7 && entry.create_site_and_road_type_1 != 7
                         && entry.create_site_and_road_type_2 != 7 && entry.create_site_and_road_type_3 != 7)"
                         data-parsley-trigger="keyup" data-parsley-maxlength="128"
                  class="form-control" type="text" value="" data-id="A1310-81" placeholder="その他">
              </li>
              <li class="bullet" :class="{checked : entry.create_site_and_road_type_0 == 8 || entry.create_site_and_road_type_1 == 8
                     || entry.create_site_and_road_type_2 == 8 || entry.create_site_and_road_type_3 == 8}">
                  建築基準法上の道路に該当しない通路（建築確認不可）
              </li>
          </ol>
          <div class="sub-label">備考</div>
          <textarea v-model="entry.site_and_road_text" :disabled="isDisabled || isCompleted"
          data-parsley-trigger="keyup" data-parsley-maxlength="4096"
          class="form-control" id="" data-id="A1310-82"></textarea>
      </div>
  </div>
  <hr>
</script>
