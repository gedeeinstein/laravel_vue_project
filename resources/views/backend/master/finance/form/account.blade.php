<div class="row mt-1">
    <div class="col-12">
        <div class="card">
            <div class="card-header text-bold">基本出金口座</div>
            <div class="card-body">
                <div class="row form-group">
                    <div class="col-2">主口座</div>
                    <div class="col-10">
                        <!-- Start - Purchase Accounts Main -->
                        <template v-for="(account, index) in accounts">
                            <div v-if="!account.deleted" class="row">
                                <select v-model="account.account_main" class="form-control col-4 mt-2">
                                    <option value=""></option>
                                    <option v-for="bank in master.banks" :value="bank.id">
                                        @{{ bank.name }}
                                    </option>
                                </select>
                                <span class="pt-3">
                                    <i v-if="index == 0" @click="addAccount()" class="fa fa-plus-circle cur-pointer text-primary ml-2"
                                        data-toggle="tooltip" title=""
                                        data-original-title="行を追加">
                                    </i>
                                    <i v-else @click="removeAccount(index)" class="fa fa-minus-circle cur-pointer text-danger ml-2"
                                        data-toggle="tooltip" title=""
                                        data-original-title="行を削除">
                                    </i>
                                </span>
                            </div>
                        </template>
                        <!-- End - Purchase Accounts Main -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>