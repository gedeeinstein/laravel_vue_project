<div class="card mb-1">
    <div class="card-header">買付情報</div>
    <div class="card-body">
        <table class="table table-bordered table-small w-auto">
            <tr>

                <!-- start - mas_section.section_number -->
                <th class="bg-light-gray text-nowrap">
                    <div data-id="C22-1">区画番号</div>
                </th>
                <td>@{{ mas_section.section_number }}</td>
                <!-- end - mas_section.section_number -->

                <!-- start - section_sale.section_staff -->
                <th class="bg-light-gray text-nowrap">
                    <div data-id="C22-18">販売担当</div>
                </th>
                <td>@{{ users[section_sale.section_staff].full_name }}</td>
                <!-- end - section_sale.section_staff -->

                <!-- start - mas_section.condition_build -->
                <th class="bg-light-gray text-nowrap">
                    <div data-id="C22-2">建築条件</div>
                </th>
                <td v-if="mas_section.condition_build == 1">無</td>
                <td v-if="mas_section.condition_build == 2">社</td>
                <td v-if="mas_section.condition_build == 3">
                    他(@{{ mas_section.condition_build_sub }})
                </td>
                <!-- end - mas_section.condition_build -->

            </tr>
        </table>
        <div class="plans nested-tabs" data-id="C22-17">
            <ul class="nav nav-tabs  nav-tabs-parent mt-2">

                <!-- start - purchases.tab -->
                <template v-for="(purchase, index) in purchases">
                    <li class="nav-item dropdown">
                        <a @click.prevent="show_purchase(index)" :class="{ active: index == active_tab }"
                            :style="tab_style[index]"
                            class="nav-link" href="#">情報@{{ index + 1 }}
                        </a>

                        <i @click.prevent="show_purchase(index)" data-toggle="dropdown"
                            class="dropdown-toggle" id="purchase-menu"
                            aria-haspopup="true" aria-expanded="false">
                        </i>
                        <div>
                            <div class="dropdown-menu" aria-labelledby="purchase-menu">
                                <a @click.prevent="copy_value(null, 'purchases', index)" class="dropdown-item">
                                    <i class="far fa-copy"> コピー</i>
                                </a>
                                <a v-if="purchases.length > 1"
                                    @click.prevent="remove_data(index, 'purchases')" class="dropdown-item">
                                    <i class="far fa-trash"> 削除</i>
                                </a>
                            </div>
                        </div>
                    </li>
                </template>
                <!-- end - purchases.tab -->

                <!-- start - purchases.add_tab -->
                <li class="nav-item nav-add">
                    <a @click.prevent="add_data('purchases')"
                        class="nav-link" href="#">追加 <i class="fas fa-plus-circle"></i>
                    </a>
                </li>
                <!-- end - purchases.add_tab -->

            </ul>
            <div class="tab-content">
                <table class="table table-bordered table-small table-salepurchase">
                    <tr>
                        <th class="bg-light-gray text-nowrap">買付金額</th>
                        <!-- start - purchases.price -->
                        <td class="td1">
                            <template>
                                <currency-input v-model="purchases[active_tab].price"
                                    class="form-control form-control-w-xl input-money" placeholder="金額"
                                    :currency="null" :precision="{min: 0, max: 0}" :allow-negative="false"
                                    data-parsley-decimal-maxlength="[12,0]" data-parsley-trigger="change focusout"
                                    data-parsley-no-focus data-id="C22-3" :disabled="!initial.editable || is_abolished"
                                />
                            </template>
                        </td>
                        <!-- end - purchases.price -->

                        <!-- start - purchases.price_memo -->
                        <td class="td2">
                            <input v-model="purchases[active_tab].price_memo"
                                class="form-control w-100" data-id="C22-4"
                                type="text" placeholder="メモ" :disabled="!initial.editable || is_abolished"
                            >
                        </td>
                        <!-- end - purchases.price_memo -->

                        <th class="bg-light-gray text-nowrap">外構</th>
                        <!-- start - purchases.outdoor_facility -->
                        <td class="td3">
                            <div class="form-check form-check-inline icheck-cyan">
                                <input v-model="purchases[active_tab].outdoor_facility"
                                    id="outdoor_facility_1" class="form-check-input"
                                    type="radio" value="1" data-id="C22-11" :disabled="!initial.editable || is_abolished"
                                >
                                <label class="form-check-label" for="outdoor_facility_1">OK</label>
                            </div>
                            <div class="form-check form-check-inline icheck-cyan">
                                <input v-model="purchases[active_tab].outdoor_facility"
                                    id="outdoor_facility_2" class="form-check-input"
                                    type="radio" value="2" :disabled="!initial.editable || is_abolished"
                                >
                                <label class="form-check-label" for="outdoor_facility_2">NG</label>
                            </div>
                        </td>
                        <!-- end - purchases.outdoor_facility -->

                        <!-- start - purchases.outdoor_facility_memo -->
                        <td class="td4">
                            <input v-model="purchases[active_tab].outdoor_facility_memo"
                                class="form-control w-100" data-id="C22-12"
                                type="text" placeholder="メモ" :disabled="!initial.editable || is_abolished"
                            >
                        </td>
                        <!-- end - purchases.outdoor_facility_memo -->
                    </tr>
                    <tr>
                        <th class="bg-light-gray text-nowrap">手付金額</th>
                        <!-- start - purchases.contract_deposit -->
                        <td>
                            <template>
                                <currency-input v-model="purchases[active_tab].contract_deposit"
                                    class="form-control form-control-w-xl input-money" placeholder="金額"
                                    :currency="null" :precision="{min: 0, max: 0}" :allow-negative="false"
                                    data-parsley-decimal-maxlength="[12,0]" data-parsley-trigger="change focusout"
                                    data-parsley-no-focus data-id="C22-5" :disabled="!initial.editable || is_abolished"
                                />
                            </template>
                        </td>
                        <!-- end - purchases.contract_deposit -->

                        <!-- start - purchases.deposit_memo -->
                        <td>
                            <input v-model="purchases[active_tab].deposit_memo"
                                class="form-control w-100" data-id="C22-6"
                                type="text" placeholder="メモ" :disabled="!initial.editable || is_abolished"
                            >
                        </td>
                        <!-- end - purchases.deposit_memo -->

                        <th class="bg-light-gray text-nowrap">登記</th>
                        <!-- start - purchases.registration -->
                        <td>
                            <div class="form-check form-check-inline icheck-cyan">
                                <input v-model="purchases[active_tab].registration"
                                    id="registration_1" class="form-check-input"
                                    type="radio" value="1" data-id="C22-13" :disabled="!initial.editable || is_abolished"
                                >
                                <label class="form-check-label" for="registration_1">OK</label>
                            </div>
                            <div class="form-check form-check-inline icheck-cyan">
                                <input v-model="purchases[active_tab].registration"
                                    id="registration_2" class="form-check-input"
                                    type="radio" value="2" :disabled="!initial.editable || is_abolished"
                                >
                                <label class="form-check-label" for="registration_2">一部OK</label>
                            </div>
                            <div class="form-check form-check-inline icheck-cyan">
                                <input v-model="purchases[active_tab].registration"
                                    id="registration_3" class="form-check-input"
                                    type="radio" value="3" :disabled="!initial.editable || is_abolished"
                                >
                                <label class="form-check-label" for="registration_3">NG</label>
                            </div>
                        </td>
                        <!-- end - purchases.registration -->

                        <!-- start - purchases.registration_memo -->
                        <td>
                            <input v-model="purchases[active_tab].registration_memo"
                                class="form-control w-100" data-id="C22-14"
                                type="text" placeholder="メモ" :disabled="!initial.editable || is_abolished"
                            >
                        </td>
                        <!-- end - purchases.registration_memo -->
                    </tr>
                    <tr>
                        <th class="bg-light-gray text-nowrap">契約希望日</th>
                        <!-- start - purchases.contract_date_request -->
                        <td>
                            <date-picker v-model="purchases[active_tab].contract_date_request"
                                type="date" class="form-control-w-xl"
                                input-class="form-control form-control-w-xl input-date"
                                format="YYYY/MM/DD" value-type="format" data-id="C22-7" placeholder="日付"
                                :disabled="!initial.editable || is_abolished">
                            </date-picker>
                        </td>
                        <!-- end - purchases.contract_date_request -->

                        <!-- start - purchases.contract_date_request_memo -->
                        <td>
                            <input v-model="purchases[active_tab].contract_date_request_memo"
                                class="form-control w-100" data-id="C22-8"
                                type="text" placeholder="メモ" :disabled="!initial.editable || is_abolished"
                            >
                        </td>
                        <!-- end - purchases.contract_date_request_memo -->

                        <th class="bg-light-gray text-nowrap">その他</th>
                        <!-- start - purchases.memo -->
                        <td colspan="2">
                            <input v-model="purchases[active_tab].memo"
                                class="form-control w-100" data-id="C22-15"
                                type="text" :disabled="!initial.editable || is_abolished"
                            >
                        </td>
                        <!-- end - purchases.memo -->
                    </tr>
                    <tr>
                        <th class="bg-light-gray text-nowrap">決済希望日</th>
                        <!-- start - purchases.payment_date_request -->
                        <td>
                            <date-picker v-model="purchases[active_tab].payment_date_request"
                                type="date" class="form-control-w-xl"
                                input-class="form-control form-control-w-xl input-date"
                                format="YYYY/MM/DD" value-type="format" data-id="C22-9" placeholder="日付"
                                :disabled="!initial.editable || is_abolished">
                            </date-picker>
                        </td>
                        <!-- end - purchases.payment_date_request -->

                        <!-- start - purchases.payment_date_request_memo -->
                        <td>
                            <input v-model="purchases[active_tab].payment_date_request_memo"
                                class="form-control w-100"
                                data-id="C22-10" type="text" placeholder="メモ" :disabled="!initial.editable || is_abolished"
                            >
                        </td>
                        <!-- end - purchases.payment_date_request_memo -->

                        <th v-if="inspection_request_check" class="bg-light-gray text-nowrap">承認結果</th>
                        <td v-if="inspection_request_check">
                            <div data-id="C22-16">
                                <!-- start - inspection.examination -->
                                @if (Auth::user()->isGlobalAdmin())
                                    <div>
                                        <div class="form-check form-check-inline icheck-cyan">
                                            <input v-model="active_inspection.examination"
                                                id="examination_1" class="form-check-input"
                                                type="radio" value="1" :disabled="!initial.editable || is_abolished"
                                            >
                                            <label class="form-check-label" for="examination_1">未承認</label>
                                        </div>
                                        <div class="form-check form-check-inline icheck-cyan">
                                            <input v-model="active_inspection.examination"
                                                id="examination_2" class="form-check-input"
                                                type="radio" value="2" :disabled="!initial.editable || is_abolished"
                                            >
                                            <label class="form-check-label" for="examination_2">承認</label>
                                        </div>
                                        <div class="form-check form-check-inline icheck-cyan">
                                            <input v-model="active_inspection.examination"
                                                id="examination_3" class="form-check-input"
                                                type="radio" value="3" :disabled="!initial.editable || is_abolished"
                                            >
                                            <label class="form-check-label" for="examination_3">非承認</label>
                                        </div>
                                    </div>
                                @endif
                                <!-- end - inspection.examination -->

                                <!-- start - purchases.examination_label -->
                                <div data-id="C22-16-2">
                                    <span v-if="active_inspection.examination == 1"
                                        style="background: #17a2b8;"
                                        class="badge badge-danger px-1 py-2 mr-1">未承認
                                    </span>
                                    <span v-if="active_inspection.examination == 2"
                                        style="background: #00c;"
                                        class="badge badge-danger px-1 py-2 mr-1">承認
                                    </span>
                                    <span v-if="active_inspection.examination == 3"
                                        style="background: #CC0000;"
                                        class="badge badge-danger px-1 py-2 mr-1">非承認
                                    </span>
                                </div>
                                <!-- end - purchases.examination_label -->
                            </div>
                        </td>
                        <td v-if="inspection_request_check">
                            <!-- start - purchases.examination_text -->
                            @if (Auth::user()->isGlobalAdmin())
                                <div data-id="C22-17">
                                    <input v-model="active_inspection.examination_text"
                                        class="form-control w-100" type="text"
                                        :disabled="!initial.editable || is_abolished"
                                    >
                                </div>
                            @endif
                            <!-- end - purchases.examination_text -->

                            <!-- start - purchases.examination_text -->
                            <div class="mt-1">
                                <textarea :value="active_inspection.examination_text"
                                    :rows="active_inspection.examination_text ?
                                           parseInt(active_inspection.examination_text.length / 50) + 1 : 1"
                                    cols="50" data-id="C22-17-2" class="form-control w-100"
                                    :disabled="true"
                                    style="border: none !important; background: none; resize: none;">
                                </textarea>
                            </div>
                            <!-- end - purchases.examination_text -->
                        </td>
                    </tr>
                </table>

                <!-- start - purchases.button -->
                <div class="form-controls mx-n2 mx-sm-0 mb-3">
                    <div class="row mx-n1">
                        @php
                          $column_class = 'px-1 col-sm-6 col-md-auto my-1';
                          $button_class = 'btn btn-wide btn-block btn-info px-4 mr-3 fs-14';
                        @endphp
                        <div class="{{ $column_class }}">
                            <button type="submit" class="{{ $button_class }}" data-id="C22-21"
                                :disabled="!initial.editable || is_abolished">
                                <i v-if="!initial.submited" class="fas fa-save"></i>
                                <i v-else class="fas fa-spinner fa-spin"></i>
                                保存
                            </button>
                        </div>
                        <div class="{{ $column_class }}">
                            <button @click="inspection_request" type="button"
                                :disabled="!initial.editable || is_abolished"
                                class="{{ $button_class }}" data-id="C22-22">
                                承認リクエスト
                            </button>
                        </div>
                        <div class="{{ $column_class }}">
                            <button @click="abolished_request" type="button"
                                :disabled="!initial.editable || is_abolished"
                                class="{{ $button_class }}" data-id="C22-23">
                                廃止
                            </button>
                        </div>
                    </div>
                </div>
                <!-- end - purchases.button -->

            </div>
        </div>
    </div>
</div>
