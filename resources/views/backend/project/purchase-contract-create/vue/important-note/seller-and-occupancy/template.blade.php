<script type="text/x-template" id="important-note-seller-and-occupancy">
    <div class="card mt-2">
        <div class="card-header" style="font-weight: bold;">B 売主の表示と占有に関する事項</div>
        <div class="card-body">

            <div class="form-group row">
                <label for="" class="col-3 col-form-label" style="font-weight: normal;">1. 売主</label>
                <div class="col-9">
                    <div class="row mb-1">
                        <div class="col-4">売主</div>
                        <div v-if="different_name > 0" class="col-8" data-id="A139-1">
                            登記簿記載の所有者と異なる
                        </div>
                        <div v-else class="col-8" data-id="A139-1">
                            登記簿記載の所有者と同じ
                        </div>
                    </div>
                    <div class="row mb-1">
                        <div class="col-4 col-form-label">住所</div>
                        <div class="col-8">
                            <input v-model="entry.seller_and_occupancy_address"
                            :disabled="isDisabled || isCompleted"
                            data-parsley-trigger="keyup" data-parsley-maxlength="128"
                            class="form-control" type="text" value="" data-id="A139-2">
                        </div>
                    </div>
                    <div class="row mb-1">
                        <div class="col-4 col-form-label">氏名</div>
                        <div class="col-8">
                            <input v-model="entry.seller_and_occupancy_name"
                            :disabled="isDisabled || isCompleted"
                            data-parsley-trigger="keyup" data-parsley-maxlength="128"
                            class="form-control mb-1" type="text" value="" data-id="A139-3">
                            <button v-for="contractor_name in contractors_name"
                            :disabled="isCompleted || isDisabled" @click="add_contractor(contractor_name)"
                            class="btn btn-wide btn-info px-4 mr-1" type="button" data-id="A139-4">
                                 @{{ contractor_name }}
                            </button>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-4 col-form-label">備考</div>
                        <div class="col-8">
                            <textarea v-model="entry.seller_and_occupancy_remarks" :disabled="isDisabled || isCompleted"
                            data-parsley-trigger="keyup" data-parsley-maxlength="4096"
                            class="form-control" id="" data-id="A139-5"></textarea>
                        </div>
                    </div>
                </div>
            </div>
            <hr>

            <div class="form-group row">
                <label for="" class="col-3 col-form-label" style="font-weight: normal;">2. 売買契約締結時の占有に関する事項</label>
                <div class="col-9">
                    <div class="row mb-1">
                        <div class="col-4">第三者占有</div>
                        <div v-if="building_third_person_occupied == 1"
                        class="col-8" data-id="A139-6">
                            無
                        </div>
                        <div v-if="building_third_person_occupied == 2"
                        class="col-8" data-id="A139-6">
                            有　（住所、氏名を入力）
                        </div>
                    </div>
                    <template v-if="building_third_person_occupied == 2">
                        <div class="row mb-1">
                            <div class="col-4 col-form-label">住所</div>
                            <div class="col-8">
                                <input v-model="entry.seller_and_occupancy_occupation_address" :disabled="isDisabled || isCompleted"
                                data-parsley-trigger="keyup" data-parsley-maxlength="128"
                                class="form-control" type="text" value="" data-id="A139-7">
                            </div>
                        </div>
                        <div class="row mb-1">
                            <div class="col-4 col-form-label">氏名</div>
                            <div class="col-8">
                                <input v-model="entry.seller_and_occupancy_occupation_name" :disabled="isDisabled || isCompleted"
                                data-parsley-trigger="keyup" data-parsley-maxlength="128"
                                class="form-control" type="text" value="" data-id="A139-8">
                            </div>
                        </div>
                    </template>
                    <div class="row">
                        <div class="col-4 col-form-label">占有に関する事項</div>
                        <div class="col-8">
                            <textarea v-model="entry.seller_and_occupancy_occupation_matter" :disabled="isDisabled || isCompleted"
                            data-parsley-trigger="keyup" data-parsley-maxlength="4096"
                            class="form-control" id="" data-id="A139-9"></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="incomplete_memo form-row p-2 bg-light">
                <div class="col-auto form-text">
                    <div class="form-check form-check-inline icheck-cyan">
                        <input v-model="entry.seller_and_occupancy_status"
                        class="form-check-input" type="radio" name="" id="seller_and_occupancy_status_1" value="1">
                        <label class="form-check-label" for="seller_and_occupancy_status_1">完</label>
                    </div>
                    <div class="form-check form-check-inline icheck-cyan">
                        <input v-model="entry.seller_and_occupancy_status"
                        class="form-check-input" type="radio" name="" id="seller_and_occupancy_status_2" value="0">
                        <label class="form-check-label" for="seller_and_occupancy_status_2">未</label>
                    </div>
                </div>
                <div class="col-auto form-text">
                    <span class="">未完メモ：</span>
                </div>
                <div class="col-6">
                    <input v-model="entry.seller_and_occupancy_memo" :disabled="isDisabled || isCompleted"
                    data-parsley-trigger="keyup" data-parsley-maxlength="128"
                    class="form-control" name="" type="text" value="" placeholder="未完となっている項目や理由を記入してください">
                </div>
            </div>
        </div>
    </div>
</script>
