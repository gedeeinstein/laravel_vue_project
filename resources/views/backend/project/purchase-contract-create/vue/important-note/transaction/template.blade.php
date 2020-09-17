<script type="text/x-template" id="important-note-transaction">
    <div class="card mt-2">
        <div class="card-header" style="font-weight: bold;">II 取引条件に関する事項</div>
        <div class="card-body">
            <div class="form-group row">
                <label for="" class="col-3 col-form-label" style="font-weight: normal;">手付解除</label>
                <div class="col-9">
                    <div class="form-check form-check-inline icheck-cyan">
                        <input v-model="entry.manual_release" :disabled="isDisabled || isCompleted"
                        class="form-check-input" type="radio" id="manual_release_1" value="1" data-id="A1312-1">
                        <label class="form-check-label" for="manual_release_1">有</label>
                    </div>
                    <div class="form-check form-check-inline icheck-cyan">
                        <input v-model="entry.manual_release" :disabled="isDisabled || isCompleted"
                        class="form-check-input" type="radio" id="manual_release_2" value="2">
                        <label class="form-check-label" for="manual_release_2">無</label>
                    </div>
                </div>
            </div>            
            <template v-if="contract.seller == 2">
                <div class="form-group row">
                    <label for="" class="col-3 col-form-label" style="font-weight: normal;">手付金等の保全措置の概要</label>
                    <div class="col-9">
                        <div class="row">
                            <div class="col-2">保全措置</div>
                            <div class="col-10">
                                <div class="form-check form-check-inline icheck-cyan">
                                    <input v-model="entry.deposit_conservation_measures" :disabled="isDisabled || isCompleted"
                                    class="form-check-input" type="radio" id="deposit_conservation_measures_1" value="1" data-id="A1312-3">
                                    <label class="form-check-label" for="deposit_conservation_measures_1">講じない</label>
                                </div>
                                <div class="form-check form-check-inline icheck-cyan">
                                    <input v-model="entry.deposit_conservation_measures" :disabled="isDisabled || isCompleted"
                                    class="form-check-input" type="radio" id="deposit_conservation_measures_2" value="2">
                                    <label class="form-check-label" for="deposit_conservation_measures_2">講じる(未完成物件)</label>
                                </div>
                                <div class="form-check form-check-inline icheck-cyan">
                                    <input v-model="entry.deposit_conservation_measures" :disabled="isDisabled || isCompleted"
                                    class="form-check-input" type="radio" id="deposit_conservation_measures_3" value="3">
                                    <label class="form-check-label" for="deposit_conservation_measures_3">講じる(完成物件)</label>
                                </div>
                            </div>
                        </div>
                        <template v-if="entry.deposit_conservation_measures != 1">
                            <div class="row">
                                <div class="col-2">保全方式</div>
                                <div class="col-10">
                                    <div class="form-check form-check-inline icheck-cyan">
                                        <input v-model="entry.deposit_conservation_method" :disabled="isDisabled || isCompleted"
                                        class="form-check-input" type="radio" id="deposit_conservation_method_1" value="1" data-id="A1312-8">
                                        <label class="form-check-label" for="deposit_conservation_method_1">保証委託契約</label>
                                    </div>
                                    <div class="form-check form-check-inline icheck-cyan">
                                        <input v-model="entry.deposit_conservation_method" :disabled="isDisabled || isCompleted"
                                        class="form-check-input" type="radio" id="deposit_conservation_method_2" value="2">
                                        <label class="form-check-label" for="deposit_conservation_method_2">補償保険契約</label>
                                    </div>
                                    <div class="form-check form-check-inline icheck-cyan">
                                        <input v-model="entry.deposit_conservation_method" :disabled="isDisabled || isCompleted"
                                        class="form-check-input" type="radio" id="deposit_conservation_method_3" value="3">
                                        <label class="form-check-label" for="deposit_conservation_method_3">手付金等寄託契約および質権設定契約</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-2">保全機関</div>
                                <div class="col-7">
                                    <input v-model="entry.deposit_conservation_period" :disabled="isDisabled || isCompleted"
                                    data-parsley-trigger="keyup" data-parsley-maxlength="128"
                                    class="form-control ml-1" type="text" value="" data-id="A1312-9">
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            </template>
            <template v-else>
                <div class="form-group row">
                    <label for="" class="col-3 col-form-label text-danger" style="font-weight: normal;">売主が業者ではないため、該当しません。</label>
                </div>
            </template>
            <div class="form-group row">
                <label for="" class="col-3 col-form-label" style="font-weight: normal;">支払金・預り金の保全</label>
                <div class="col-9">
                    <div class="row">
                        <div class="col-2">保全措置</div>
                        <div class="col-10">
                            <div class="form-check form-check-inline icheck-cyan">
                                <input v-model="entry.payment_deposit_measures" :disabled="isDisabled || isCompleted"
                                class="form-check-input" type="radio" id="payment_deposit_measures_1" value="1" data-id="A1312-4">
                                <label class="form-check-label" for="payment_deposit_measures_1">講じない</label>
                            </div>
                            <div class="form-check form-check-inline icheck-cyan">
                                <input v-model="entry.payment_deposit_measures" :disabled="isDisabled || isCompleted"
                                class="form-check-input" type="radio" id="payment_deposit_measures_2" value="2">
                                <label class="form-check-label" for="payment_deposit_measures_2">講じる</label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-2">保全機関</div>
                        <div class="col-7">
                            <input v-model="entry.payment_deposit_period" :disabled="isDisabled || isCompleted"
                            data-parsley-trigger="keyup" data-parsley-maxlength="128"
                            class="form-control ml-1" type="text" value="" data-id="A1312-5">
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <label for="" class="col-3 col-form-label" style="font-weight: normal;">契約不適合の履行に関する措置の概要</label>
                <div class="col-9">
                    <div class="strong">契約不適合の履行に関する措置</div>
                    <div class="form-check form-check-inline icheck-cyan">
                        <input v-model="entry.liability_for_collateral_measures" :disabled="isDisabled || isCompleted"
                        class="form-check-input" type="radio" id="liability_for_collateral_measures_1" value="1" data-id="A1312-6">
                        <label class="form-check-label" for="liability_for_collateral_measures_1">講じない</label>
                    </div>
                    <div class="form-check form-check-inline icheck-cyan">
                        <input v-model="entry.liability_for_collateral_measures" :disabled="isDisabled || isCompleted"
                        class="form-check-input" type="radio" id="liability_for_collateral_measures_2" value="2">
                        <label class="form-check-label" for="liability_for_collateral_measures_2">講じる</label>
                    </div>
                    <div class="mt-2"><strong>契約不適合の履行措置の内容</strong></div>
                    <textarea v-model="entry.liability_for_collateral_measures_text"
                    :disabled="isDisabled || isCompleted || entry.liability_for_collateral_measures != 2"
                    data-parsley-trigger="keyup" data-parsley-maxlength="4096"
                    class="form-control" id="" data-id="A1312-7"></textarea>
                </div>
            </div>

            <div class="incomplete_memo form-row p-2 bg-light">
                <div class="col-auto form-text">
                    <div class="form-check form-check-inline icheck-cyan">
                        <input v-model="entry.transaction_terms_status"
                        class="form-check-input" type="radio" name="" id="transaction_terms_status_1" value="1">
                        <label class="form-check-label" for="transaction_terms_status_1">完</label>
                    </div>
                    <div class="form-check form-check-inline icheck-cyan">
                        <input v-model="entry.transaction_terms_status"
                        class="form-check-input" type="radio" name="" id="transaction_terms_status_2" value="2">
                        <label class="form-check-label" for="transaction_terms_status_2">未</label>
                    </div>
                </div>
                <div class="col-auto form-text">
                    <span class="">未完メモ：</span>
                </div>
                <div class="col-6">
                    <input v-model="entry.transaction_terms_memo" :disabled="isDisabled || isCompleted"
                    data-parsley-trigger="keyup" data-parsley-maxlength="128"
                    class="form-control" name="" type="text" value="" placeholder="未完となっている項目や理由を記入してください">
                </div>
            </div>
        </div>
    </div>
</script>
