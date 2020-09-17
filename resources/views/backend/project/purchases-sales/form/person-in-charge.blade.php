<div class="col-md-6">
    <div class="card card-project">
        <div class="card-header">
            <strong>@lang('project_purchases_sales.responsibility_situation_etc')</strong>
        </div>
        <div class="card-body">
            <div class="form-group row">
                <label class="col-3 col-form-label">社内仕入れ担当</label>
                <div class="col-9">
                    <div class="row">
                        <template v-for="(item, index) in buyerStaff">

                            <!-- start - item.user_id -->
                            <select class="form-control col-9" name="buyerStaff"
                                :class="[(index !== 0) ? 'mt-2' : '']" v-model="item.user_id"
                                :disabled="!initial.editable">
                                <option value="0"></option>
                                <template v-for="value in buyer_staff_with_user_id">
                                    <template v-for="user in value.users">
                                        <option :value="user.id">@{{ value.kind_in_house_abbreviation }} @{{user.last_name}}@{{user.first_name}}</option>
                                    </template>
                                </template>
                            </select>
                            <!-- end - item.user_id -->

                            <!-- start - add icon -->
                            <div v-if="index === 0" class="col-1 pt-2">
                                <span v-if="initial.editable">
                                    <i @click="addInput" class="add_button fa fa-plus-circle cur-pointer text-primary"
                                        data-toggle="tooltip" title="" data-original-title="担当者追加">
                                    </i>
                                </span>
                            </div>
                            <!-- end - add icon -->

                            <!-- start - remove icon -->
                            <div v-else class="col-1 mt-2 pt-2">
                                <span v-if="initial.editable">
                                    <i @click="removeInput(index)" class="remove_button fa fa-minus-circle cur-pointer text-danger"
                                        data-toggle="tooltip" title="" data-original-title="所有者削除">
                                    </i>
                                </span>
                            </div>
                            <!-- end - remove icon -->

                        </template>
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-3 col-form-label">PJメモ</label>
                <div class="col-9">
                    <div class="row" v-for="(memo, index) in PJMemos" :class="[ (index !== 0) ? 'pt-1' : '' ]">

                        <!-- start - memo.content -->
                        <input class="form-control col-9" name="PJMemos" type="text"
                            v-model="memo.content" :style="memo.deleted_at ? {color: '#DCDCDC'} : ''"
                            :disabled="!initial.editable" data-parsley-trigger="keyup"
                            data-parsley-maxlength="128"
                        >
                        <!-- end - memo.content -->

                        <!-- start - add icon -->
                        <div v-if="index === 0" class="col-1 pt-2">
                            <span v-if="initial.editable">
                                <i @click="addMemo" class="add_button fa fa-plus-circle cur-pointer text-primary"
                                    data-toggle="tooltip" title="" data-original-title="メモを追加">
                                </i>
                            </span>
                        </div>
                        <!-- end - add icon -->

                        <!-- start - remove icon -->
                        <div v-else class="col-1 pt-2">
                            <span v-if="!memo.deleted_at && initial.editable">
                                <i @click="removeMemo(index)" class="remove_button fa fa-minus-circle cur-pointer text-danger"
                                    data-toggle="tooltip" title="" data-original-title="所有者削除">
                                </i>
                            </span>
                        </div>
                        <!-- end - remove icon -->

                    </div>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-3 col-form-label">仕入状況</label>

                <!-- start - project_status -->
                <div class="col-9 p-0">
                    <div class="icheck-cyan d-inline pr-2">
                        <input class="form-check-input" type="radio" name="project_status"
                            id="projecy_status1" value="1" v-model="purchase_sales.project_status"
                            :disabled="!initial.editable || !initial.radio_editable"
                        >
                        <label class="form-check-label rank-registration" for="projecy_status1">登</label>
                    </div>
                    <div class="icheck-cyan d-inline pr-2">
                        <input class="form-check-input" type="radio" name="project_status"
                            id="projecy_status2" value="2" v-model="purchase_sales.project_status"
                            :disabled="!initial.editable"
                        >
                        <label class="form-check-label rank-paid" for="projecy_status2">決</label>
                    </div>
                    <div class="icheck-cyan d-inline pr-2">
                        <input class="form-check-input" type="radio" name="project_status"
                            id="projecy_status3" value="3" v-model="purchase_sales.project_status"
                            :disabled="!initial.editable"
                        >
                        <label class="form-check-label rank-contract" for="projecy_status3">済</label>
                    </div>
                    <div class="icheck-cyan d-inline pr-2">
                        <input class="form-check-input" type="radio" name="project_status"
                            id="projecy_status4" value="4" v-model="purchase_sales.project_status"
                            :disabled="!initial.editable"
                        >
                        <label class="form-check-label rank-agree" for="projecy_status4">確</label>
                    </div>
                    <div class="icheck-cyan d-inline pr-2">
                        <input class="form-check-input" type="radio" name="project_status"
                            id="projecy_status5" value="5" v-model="purchase_sales.project_status"
                            :disabled="!initial.editable"
                        >
                        <label class="form-check-label rank-purchase" for="projecy_status5">買</label>
                    </div>
                    <div class="icheck-cyan d-inline pr-2">
                        <input class="form-check-input" type="radio" name="project_status"
                            id="projecy_status6" value="6" v-model="purchase_sales.project_status"
                            :disabled="!initial.editable"
                        >
                        <label class="form-check-label rank-information" for="projecy_status6">情</label>
                    </div>
                    <div class="icheck-cyan d-inline pr-2">
                        <input class="form-check-input" type="radio" name="project_status"
                            id="projecy_status7" value="7" v-model="purchase_sales.project_status"
                            :disabled="!initial.editable"
                        >
                        <label class="form-check-label rank-pending" for="projecy_status7">保</label>
                    </div>
                    <div class="icheck-cyan d-inline pr-2">
                        <input class="form-check-input" type="radio" name="project_status"
                            id="projecy_status8" value="8" v-model="purchase_sales.project_status"
                            :disabled="!initial.editable"
                        >
                        <label class="form-check-label rank-exclude mt-1" for="projecy_status8">OUT</label>
                    </div>
                </div>
                <!-- end - memo.content -->

            </div>
        </div>
    </div>
</div>
