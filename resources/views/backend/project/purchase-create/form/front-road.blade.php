<div class="card mt-2">
    <div style="font-weight: 700;" class="card-header">前面道路</div>
    <div class="card-body">

        <div class="form-group row">
            <label style="font-weight: normal;" for="" class="col-3 col-form-label">道路幅員(セットバック後)</label>
            <div class="col-9">
                <div class="form-check icheck-cyan form-check-inline">
                    <input v-model="purchase_doc.road_size_contract_a" class="form-check-input" type="checkbox" id="road_size_contract_a" value="1" data-id="A84-1" :disabled="purchase_doc.front_road_status == 1">
                    <label class="form-check-label" for="road_size_contract_a">4m未満</label>
                </div>
                <div class="form-check icheck-cyan form-check-inline">
                    <input v-model="purchase_doc.road_size_contract_b" class="form-check-input" type="checkbox" id="road_size_contract_b" value="1" data-id="A84-2" :disabled="purchase_doc.front_road_status == 1">
                    <label class="form-check-label" for="road_size_contract_b">4.0 ～ 4.9m</label>
                </div>
                <div class="form-check icheck-cyan form-check-inline">
                    <input v-model="purchase_doc.road_size_contract_c" class="form-check-input" type="checkbox" id="road_size_contract_c" value="1" data-id="A84-3" :disabled="purchase_doc.front_road_status == 1">
                    <label class="form-check-label" for="road_size_contract_c">5.0 ～ 5.9m</label>
                </div>
                <div class="form-check icheck-cyan form-check-inline">
                    <input v-model="purchase_doc.road_size_contract_d" class="form-check-input" type="checkbox" id="road_size_contract_d" value="1" data-id="A84-4" :disabled="purchase_doc.front_road_status == 1">
                    <label class="form-check-label" for="road_size_contract_d">6m以上</label>
                </div>
            </div>
        </div>

        <div class="form-group row">
            <label style="font-weight: normal;" for="" class="col-3 col-form-label">道路種別</label>
            <div class="col-9">
                <div v-if="!purchase_doc.road_type_contract_a && !purchase_doc.road_type_contract_b &&
                    !purchase_doc.road_type_contract_c && !purchase_doc.road_type_contract_d &&
                    !purchase_doc.road_type_contract_e && !purchase_doc.road_type_contract_f &&
                    !purchase_doc.road_type_contract_g && !purchase_doc.road_type_contract_h &&
                    !purchase_doc.road_type_contract_i" class="text-danger"
                    :style="purchase_doc.front_road_status == 1 ? {background: '#e9ecef'} : ''"
                    >道路種別を選択してください</div>
                <div class="row">
                    <div class="col-4">
                        <div class="form-check icheck-cyan form-check-inline">
                            <input v-model="purchase_doc.road_type_contract_a" @change="sub2_contract(purchase_doc)"
                            class="form-check-input" type="checkbox" id="road_type_contract_a" value="1" :disabled="purchase_doc.front_road_status == 1"
                                data-id="A84-5">
                            <label class="form-check-label" for="road_type_contract_a">１項１号　国県市道</label>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="form-check icheck-cyan form-check-inline">
                            <input v-model="purchase_doc.road_type_contract_b" @change="sub2_contract(purchase_doc)"
                            class="form-check-input" type="checkbox" id="road_type_contract_b" value="1" :disabled="purchase_doc.front_road_status == 1"
                                data-id="A84-6">
                            <label class="form-check-label" for="road_type_contract_b">１項２号　都市計画当事業道路</label>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="form-check icheck-cyan form-check-inline">
                            <input v-model="purchase_doc.road_type_contract_c" @change="sub2_contract(purchase_doc)"
                            class="form-check-input" type="checkbox" id="road_type_contract_c" value="1" :disabled="purchase_doc.front_road_status == 1"
                                data-id="A84-7">
                            <label class="form-check-label" for="road_type_contract_c">１項３号　法施工前道路</label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-4">
                        <div class="form-check icheck-cyan form-check-inline">
                            <input v-model="purchase_doc.road_type_contract_d" @change="sub2_contract(purchase_doc)"
                            class="form-check-input" type="checkbox" id="road_type_contract_d" value="1" :disabled="purchase_doc.front_road_status == 1"
                                data-id="A84-8">
                            <label class="form-check-label" for="road_type_contract_d">１項４号　事業予定道路</label>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="form-check icheck-cyan form-check-inline">
                            <input v-model="purchase_doc.road_type_contract_e" @change="sub2_contract(purchase_doc)"
                            class="form-check-input" type="checkbox" id="road_type_contract_e" value="1" :disabled="purchase_doc.front_road_status == 1"
                                data-id="A84-9">
                            <label class="form-check-label" for="road_type_contract_e">１項５号　位置指定道路</label>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="form-check icheck-cyan form-check-inline">
                            <input v-model="purchase_doc.road_type_contract_f" @change="sub2_contract(purchase_doc)"
                            class="form-check-input" type="checkbox" id="road_type_contract_f" value="1" :disabled="purchase_doc.front_road_status == 1"
                                data-id="A84-10">
                            <label class="form-check-label" for="road_type_contract_f">２項道路　狭あい道路</label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-4">
                        <div class="form-check icheck-cyan form-check-inline">
                            <input v-model="purchase_doc.road_type_contract_g" @change="sub2_contract(purchase_doc)"
                            class="form-check-input" type="checkbox" id="road_type_contract_g" value="1" :disabled="purchase_doc.front_road_status == 1"
                                data-id="A84-11">
                            <label class="form-check-label" for="road_type_contract_g">３項道路　水平道路</label>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="form-check icheck-cyan form-check-inline">
                            <input v-model="purchase_doc.road_type_contract_h" @change="sub2_contract(purchase_doc)"
                            class="form-check-input" type="checkbox" id="road_type_contract_h" value="1" :disabled="purchase_doc.front_road_status == 1"
                                data-id="A84-12">
                            <label class="form-check-label" for="road_type_contract_h">特定道路　協定道路</label>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="form-check icheck-cyan form-check-inline">
                            <input v-model="purchase_doc.road_type_contract_i" @change="sub2_contract(purchase_doc)"
                            class="form-check-input" type="checkbox" id="road_type_contract_i" value="1" :disabled="purchase_doc.front_road_status == 1"
                                data-id="A84-13">
                            <label class="form-check-label" for="road_type_contract_i">表記ナシ　私道</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <template v-if="purchase_doc.road_type_contract_a || purchase_doc.road_type_contract_b ||
            purchase_doc.road_type_contract_c || purchase_doc.road_type_contract_d ||
            purchase_doc.road_type_contract_e || purchase_doc.road_type_contract_f ||
            purchase_doc.road_type_contract_g || purchase_doc.road_type_contract_h ||
            purchase_doc.road_type_contract_i">
            <div class="form-group row">
                <label style="font-weight: normal;" for="" class="col-3 col-form-label">公道・私道</label>
                <div class="col-9">
                    <div v-if="(purchase_doc.road_type_contract_a || purchase_doc.road_type_contract_d)
                              && (!purchase_doc.road_type_contract_b && !purchase_doc.road_type_contract_c
                              && !purchase_doc.road_type_contract_e && !purchase_doc.road_type_contract_f
                              && !purchase_doc.road_type_contract_g
                              && !purchase_doc.road_type_contract_h && !purchase_doc.road_type_contract_i)" class="text-danger" data-id="A84-21"
                              :style="purchase_doc.front_road_status == 1 ? {background: '#e9ecef'} : ''"
                              >公道
                    </div>
                    <div v-else-if="(purchase_doc.road_type_contract_h || purchase_doc.road_type_contract_i)
                                   && (!purchase_doc.road_type_contract_a && !purchase_doc.road_type_contract_b
                                   && !purchase_doc.road_type_contract_c && !purchase_doc.road_type_contract_d
                                   && !purchase_doc.road_type_contract_e && !purchase_doc.road_type_contract_f
                                   && !purchase_doc.road_type_contract_g)" class="text-danger" data-id="A84-21"
                                   :style="purchase_doc.front_road_status == 1 ? {background: '#e9ecef'} : ''"
                                   >私道
                    </div>                    
                    <div v-else>
                        <div class="text-danger" data-id="A84-21" :style="purchase_doc.front_road_status == 1 ? {background: '#e9ecef'} : ''">種別を選択して下さい。</div>
                        <div class="form-check icheck-cyan form-check-inline">
                            <input v-model="purchase_doc.road_type_sub2_contract_a" class="form-check-input" type="checkbox" id="road_type_sub2_contract_a" value="1" data-id="A84-22" :disabled="purchase_doc.front_road_status == 1">
                            <label class="form-check-label" for="road_type_sub2_contract_a">公道</label>
                        </div>
                        <div class="form-check icheck-cyan form-check-inline">
                            <input v-model="purchase_doc.road_type_sub2_contract_b" class="form-check-input" type="checkbox" id="road_type_sub2_contract_b" value="1" data-id="A84-23" :disabled="purchase_doc.front_road_status == 1">
                            <label class="form-check-label" for="road_type_sub2_contract_b">私道</label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <label style="font-weight: normal;" for="" class="col-3 col-form-label">役所寄付</label>
                <div class="col-9">
                    <template v-if="purchase_doc.road_type_contract_b">
                        <div class="text-danger" data-id="A84-31"
                            :style="purchase_doc.front_road_status == 1 ? {background: '#e9ecef'} : ''"
                            >１項２号　都市計画当事業道路の役所寄付を選択して下さい</div>
                        <div class="form-check icheck-cyan form-check-inline">
                            <input v-model="purchase_doc.road_type_sub1_contract" class="form-check-input" type="radio" id="road_type_sub1_contract_1" value="1" data-id="A84-32" :disabled="purchase_doc.front_road_status == 1">
                            <label class="form-check-label" for="road_type_sub1_contract_1">役所寄付済</label>
                        </div>
                        <div class="form-check icheck-cyan form-check-inline">
                            <input v-model="purchase_doc.road_type_sub1_contract" class="form-check-input" type="radio" id="road_type_sub1_contract_2" value="2" :disabled="purchase_doc.front_road_status == 1">
                            <label class="form-check-label" for="road_type_sub1_contract_2">役所未寄付</label>
                        </div>
                    </template>
                    <template v-else>
                        <div class="text-danger" data-id="A84-31"
                            :style="purchase_doc.front_road_status == 1 ? {background: '#e9ecef'} : ''"
                            >この道路種別では入力の必要はありません。</div>
                    </template>
                </div>
            </div>
            <div class="form-group row">
                <label style="font-weight: normal;" for="" class="col-3 col-form-label">前面道路持ち分</label>
                <div class="col-9">
                    <div v-if="(!purchase_doc.road_type_sub2_contract_a && !purchase_doc.road_type_sub2_contract_b)
                                  || (purchase_doc.road_type_sub2_contract_a && !purchase_doc.road_type_sub2_contract_b)"
                                  class="text-danger" data-id="A84-41"
                                  :style="purchase_doc.front_road_status == 1 ? {background: '#e9ecef'} : ''"
                                  >この道路種別では入力の必要はありません。
                    </div>
                    <div v-if="purchase_doc.road_type_sub2_contract_b">
                        <div class="text-danger"
                            :style="purchase_doc.front_road_status == 1 ? {background: '#e9ecef'} : ''"
                            >売主は前面道路持ち分を</div>
                        <div class="form-check icheck-cyan form-check-inline">
                            <input v-model="purchase_doc.road_type_sub3_contract" class="form-check-input" type="radio" id="road_type_sub3_contract_1" value="1" data-id="A84-42" :disabled="purchase_doc.front_road_status == 1">
                            <label class="form-check-label" for="road_type_sub3_contract_1">持っている</label>
                        </div>
                        <div class="form-check icheck-cyan form-check-inline">
                            <input v-model="purchase_doc.road_type_sub3_contract" class="form-check-input" type="radio" id="road_type_sub3_contract_2" value="2" :disabled="purchase_doc.front_road_status == 1">
                            <label class="form-check-label" for="road_type_sub3_contract_2">持っていない</label>
                        </div>
                    </div>
                </div>
            </div>
        </template>

        <div class="form-group row">
            <label style="font-weight: normal;" for="" class="col-3 col-form-label">備考</label>
            <div class="col-9">
                <textarea v-model="purchase_doc.front_road_f" class="form-control" id="" data-id="A84-51" :disabled="purchase_doc.front_road_status == 1" data-parsley-trigger="keyup" data-parsley-maxlength="1024"></textarea>
            </div>
        </div>

        <div class="incomplete_memo form-row p-2 bg-light">
            <div class="col-auto form-text">
                <div class="form-check icheck-cyan form-check-inline">
                    <input v-model="purchase_doc.front_road_status" class="form-check-input" type="radio" name="front_road_status" id="front_road_status_1" value="1"  data-id="A84-101">
                    <label class="form-check-label" for="front_road_status_1">完</label>
                </div>
                <div class="form-check icheck-cyan form-check-inline">
                    <input v-model="purchase_doc.front_road_status" class="form-check-input" type="radio" name="front_road_status" id="front_road_status_2" value="2">
                    <label class="form-check-label" for="front_road_status_2">未</label>
                </div>
            </div>
            <div class="col-auto form-text">
                <span class="">未完メモ：</span>
            </div>
            <div class="col-6">
                <input v-model="purchase_doc.front_road_memo" class="form-control" name="" type="text" value=""  data-id="A84-102"  placeholder="未完となっている項目や理由を記入してください" data-parsley-trigger="keyup" data-parsley-maxlength="128">
            </div>
        </div>
    </div>
</div>
