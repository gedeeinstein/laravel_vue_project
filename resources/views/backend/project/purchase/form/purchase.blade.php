@if($target_building_no_count > 0)
@foreach($target_building_no as $pkey => $buildings_no)
    @foreach($buildings_no as $pkey2 => $building_no)
        <input type="hidden" name="building_no_data[{{$pkey}}][{{$pkey2}}]" value="{{$building_no}}">
    @endforeach
@endforeach
@endif
<div class="cards-purchase">
<form id="save_contractor" action="{{ $update_url['save_purchase'] }}" method="post">
    @php $target_data = 0; @endphp
    @foreach($purchase_targets as $purchase_target)
        @php $target_data++; @endphp
            <input type="hidden" v-model="contractors_list">
            <input type="hidden" name="purchase_id" value="{{$purchase->id}}">
            <input type="hidden" name="project_id" value="{{$project_id}}">
            <input type="hidden" name="purchase_target[{{$purchase_target->id}}]" value="{{$purchase_target->id}}">
            <input type="hidden" name="target_data" value="edit">
            @csrf
            <div class="card card-purchase purchase_no" style="border-color:#191970; min-width:1100px;">
                <div class="card-header" style="color:#fff; background:#191970;">買付No.{{$target_data}}</div>
                <div class="card-body">
                    <div v-if="parseInt(purchase.count) == 1" class="nav-buttons bottom form-inline m-1">
                        <button class="btn btn-light" @click="displayAllContraction($event)">契約者をまとめて設定</button>
                    </div>
                    <table class="table table-bordered table-small table-parcel-list mt-0">
                        <thead>
                            <tr>
                                <th width="130px" class="parcel_contractor">契約者</th>
                                <th width="110px"class="parcel_owner">所有者</th>
                                <th width="80px" class="parcel_type">分類</th>
                                <th width="270px" class="parcel_address_join">所在/地番(家屋番号)</th>
                                <th width="120px" class="parcel_share">持分</th>
                                <th width="120px" class="building_usetype">種類</th>
                                <th width="180px" class="parcel_size">地積(登記)/床面積</th>
                                <th width="50px" class="parcel_ctl"></th>
                            </tr>
                        </thead>
                        <tbody id="purchase-addon-container_{{$target_data}}">
                            <tr id="purchase_contractor_form_{{$target_data}}" class="purchase_contractor_form_{{$target_data}}" style="display:none;">
                                <td style="min-width:130px" id="data_property_copy_{{$target_data}}" rowspan="99"
                                class="purchase_contractor_form" el-id="select_contractor">
                                    <div class="form-group">
                                        <select v-model="new_contractor" 
                                                style="min-width:100%;"
                                                id="purchase_contractor_copy_{{$target_data}}" 
                                                class="form-control form-control-sm contractor_select contractor_select_copy" 
                                                name="target_contractor_copy"
                                                @change="insertPropertyData({{$target_data}}, $event)">
                                            <option value="0" selected="selected"></option>
                                            <option v-for="(contractor_list, key) in contractors_list" :value="key">@{{ contractor_list }}</option>
                                        </select>
                                    </div>
                                </td>
                            
                                <td class="remove" style="min-width:110px">
                                    <div class="form-group">
                                        <input style="min-width:100%;" class="form-control form-control-w-lg form-control-sm" name="" type="text" value="" readonly="readonly" data-id="A73-3">
                                    </div>
                                </td>
                                <td class="remove" style="min-width:81px">
                                    <div class="form-group">
                                        <input style="min-width:100%;" class="form-control form-control-w-sm form-control-sm" name="" type="text" value="" readonly="readonly" data-id="A73-4">
                                    </div>
                                </td>
                                <td class="remove" style="min-width:271px">
                                    <div class="form-group">
                                        <input style="min-width:100%;" class="form-control form-control-w-xxxl form-control-sm" name="" type="text" value="" readonly="readonly" data-id="A73-5">
                                    </div>
                                </td>
                                <td class="remove" style="min-width:121px">
                                    <div class="form-group">
                                        <input class="form-control form-control-w-xs form-control-sm" name="" type="text" value="" readonly="readonly" data-id="A73-6">
                                        分の
                                        <input class="form-control form-control-w-xs form-control-sm" name="" type="text" value="" readonly="readonly" data-id="A73-7">
                                    </div>
                                </td>
                                <td class="remove" style="min-width:121px">
                                    <div class="form-group">
                                        <input style="min-width:100%;" class="form-control form-control-w-lg form-control-sm" name="" type="text" value="" readonly="readonly" data-id="A73-8">
                                    </div>
                                </td>
                                <td class="remove" style="min-width:181px">
                                    <div class="form-group">
                                        <input class="form-control form-control-w-sm form-control-sm" name="" type="text" value="" readonly="readonly" data-id="A73-9"> m<sup>2</sup>
                                    </div>
                                </td>
                                <td class="button" style="min-width:51px">
                                    <i id="olo" class="addon-form add_row_button fa fa-minus-circle cur-pointer text-danger ml-2 align-bottom" data-toggle="tooltip" title="" data-original-title="行を削除"></i>
                                </td>
                            </tr>
                            @php $contractor_count = 0; @endphp
                            @foreach($target_contractor[$purchase_target->id] as $contractor_data)
                            @php $contractor_count++; $inc = 0; @endphp
                            @if($contractor_count == 1)
                            <tr>
                                <td id="data_property_{{$target_data}}" rowspan="2">
                                    <div class="form-group">
                                        <select
                                                style="min-width:100%;" id="purchase_contractor_{{$target_data}}"
                                                code-id="{{$target_data}}"
                                                name="target_contractor[{{$target_data}}][{{$contractor_count-1}}]"
                                                class="form-control form-control-sm contractor_select asc" 
                                                @change="insertPropertyData({{$target_data}}, $event)" >
                                            <option value="0"></option>
                                            <option v-for="(contractor_list, key) in contractors_list" :value="key" :selected="key == {{$contractor_data->pj_lot_contractor_id}}">@{{ contractor_list }}</option>
                                        </select>
                                    </div>
                                </td>
                                <tr row-id="data_property_copy_{{$target_data}}">        
                                    <td class="remove">
                                        <div class="form-group">
                                            <input style="min-width:100%;" class="form-control form-control-w-lg form-control-sm" name="" type="text" value="" readonly="readonly" data-id="A73-3">
                                        </div>
                                    </td>
                                    <td class="remove">
                                        <div class="form-group">
                                            <input style="min-width:100%;" class="form-control form-control-w-sm form-control-sm" name="" type="text" value="" readonly="readonly" :data-id="'kind_'+(parseInt(n)+parseInt(purchase.data_exist))">
                                        </div>
                                    </td>
                                    <td class="remove">
                                        <div class="form-group">
                                            <input style="min-width:100%;" class="form-control form-control-w-xxxl form-control-sm" name="" type="text" value="" readonly="readonly" data-id="A73-5">
                                        </div>
                                    </td>
                                    <td class="remove">
                                        <div class="form-group">
                                            <input class="form-control form-control-w-xs form-control-sm" name="" type="text" value="" readonly="readonly" data-id="A73-6">
                                            分の
                                            <input class="form-control form-control-w-xs form-control-sm" name="" type="text" value="" readonly="readonly" data-id="A73-7">
                                        </div>
                                    </td>
                                    <td class="remove">
                                        <div class="form-group">
                                            <input style="min-width:100%;" class="form-control form-control-w-lg form-control-sm" name="" type="text" value="" readonly="readonly" data-id="A73-8">
                                        </div>
                                    </td>
                                    <td class="remove">
                                        <div class="form-group">
                                            <input class="form-control form-control-w-sm form-control-sm" name="" type="text" value="" readonly="readonly" data-id="A73-9"> m<sup>2</sup>
                                        </div>
                                    </td>
                                    <td class="button">
                                        <i @click="addPurchaseContractor({{$target_data}})" class="add_row_button fa fa-plus-circle cur-pointer text-primary ml-2 align-bottom" data-toggle="tooltip" title="" data-original-title="行を追加"></i>
                                    </td>
                                </tr>    
                            <tr>
                            @else
                            <tr id="purchase_contractor_form_{{$target_data}}" class="purchase_contractor_form_{{$target_data}}">
                                <td id="data_property_copy_{{$target_data}}" class="purchase_contractor_form_{{$target_data}}_{{$contractor_count-1}}" 
                                    rowspan="2" el-id="select_contractor">
                                    <div class="form-group">
                                        <select code-id="{{$target_data}}"
                                                style="min-width:100%;" id="purchase_contractor_copy_{{$target_data}}"
                                                name="target_contractor[{{$target_data}}][{{$contractor_count-1}}]"
                                                class="form-control form-control-sm contractor_select desc contractor_select_copy_row_{{$target_data}}_inc_{{$contractor_count-1}}" 
                                                @change="insertPropertyData2({{$target_data}}, {{$contractor_count-1}}, $event)" >
                                            <option value="0"></option>
                                            <option v-for="(contractor_list, key) in contractors_list" :value="key" :selected="key == {{$contractor_data->pj_lot_contractor_id}}">@{{ contractor_list }}</option>
                                        </select>
                                    </div>
                                </td>
                                    
                                    <td class="remove_{{$target_data}}_{{$contractor_count-1}}">
                                        <div class="form-group">
                                            <input style="min-width:100%;" class="form-control form-control-w-lg form-control-sm" name="" type="text" value="" readonly="readonly" data-id="A73-3">
                                        </div>
                                    </td>
                                    <td class="remove_{{$target_data}}_{{$contractor_count-1}}">
                                        <div class="form-group">
                                            <input style="min-width:100%;" class="form-control form-control-w-sm form-control-sm" name="" type="text" value="" readonly="readonly" :data-id="'kind_'+(parseInt(n)+parseInt(purchase.data_exist))">
                                        </div>
                                    </td>
                                    <td class="remove_{{$target_data}}_{{$contractor_count-1}}">
                                        <div class="form-group">
                                            <input style="min-width:100%;" class="form-control form-control-w-xxxl form-control-sm" name="" type="text" value="" readonly="readonly" data-id="A73-5">
                                        </div>
                                    </td>
                                    <td class="remove_{{$target_data}}_{{$contractor_count-1}}">
                                        <div class="form-group">
                                            <input class="form-control form-control-w-xs form-control-sm" name="" type="text" value="" readonly="readonly" data-id="A73-6">
                                            分の
                                            <input class="form-control form-control-w-xs form-control-sm" name="" type="text" value="" readonly="readonly" data-id="A73-7">
                                        </div>
                                    </td>
                                    <td class="remove_{{$target_data}}_{{$contractor_count-1}}">
                                        <div class="form-group">
                                            <input style="min-width:100%;" class="form-control form-control-w-lg form-control-sm" name="" type="text" value="" readonly="readonly" data-id="A73-8">
                                        </div>
                                    </td>
                                    <td class="remove_{{$target_data}}_{{$contractor_count-1}}">
                                        <div class="form-group">
                                            <input class="form-control form-control-w-sm form-control-sm" name="" type="text" value="" readonly="readonly" data-id="A73-9"> m<sup>2</sup>
                                        </div>
                                    </td>
                                    <td class="button_{{$target_data}}_{{$contractor_count-1}}">
                                        <i id="olo_{{$target_data}}_{{$contractor_count-1}}" class="addon-form_{{$target_data}} add_row_button fa fa-minus-circle cur-pointer text-danger ml-2 align-bottom" title="" data-original-title="行を削除"></i>
                                    </td>
                                </tr>    
                            <tr>
                            @endif 
                                    
                            @endforeach
                        </tbody>
                    </table>

                    <table style="width:auto;" class="table table-bordered table-small buypurchase_table">
                        <tbody>
                            <tr>
                                <th width="150px"><span class="text-danger">※</span>買付価格</th>
                                <td width="150px">
                                    <template>
                                    <currency-input style="min-width:80%;" class="purchase_price form-control form-control-w-lg form-control-sm input-money" 
                                            name="purchase_price[{{$target_data}}]" type="text" value="{{$purchase_target->purchase_price}}"
                                            @if($target_data == 1) v-model="first_price" @endif 
                                            @change="calcPrice()"
                                            :data-parsley-required="count == null" 
                                            :currency="null"
                                            :precision="{ min: 0, max: 0 }"
                                            :allow-negative="false" >
                                    </template>
                                    <span v-if="parseInt(purchase.count) == 1"><i @click="copyPriceTsubo()" id="copy_price_tsubo" class="copy_xxxx_button far fa-copy cur-pointer text-secondary ml-2" data-toggle="tooltip" title="" data-original-title="仕入予定価格をコピー"></i></span>
                                </td>
                                <th width="150px">内手付金</th>
                                <td width="150px">
                                    <template>
                                    <currency-input style="min-width:100%;" class="form-control form-control-w-lg form-control-sm input-money" 
                                            name="purchase_deposit[{{$target_data}}]" type="text" value="{{$purchase_target->purchase_deposit}}"
                                            :currency="null"
                                            :precision="{ min: 0, max: 0 }"
                                            :allow-negative="false" >
                                    </template>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="headding01"
                        style=" background: #005AA0;
                                color: #fff;
                                text-align: center;
                                padding: 0.3em 0.6em;
                                margin: 0.5em 0;"> 建物情報</div>
                    <table class="table table-bordered table-small table-parcel-list mt-0">
                        <tbody id="purchase_card_second_{{$target_data}}">
                            <tr id="building_number_row_{{$target_data}}" class="build_{{$target_data}}" style="display:none;">
                                <td width="170px" class="parcel_th">
                                    <div class="form-group">
                                        <input style="min-width:100%;" class="form-control form-control-w-lg form-control-sm" name="building_no_valx" type="text" value="" readonly="readonly" data-id="A72-8">
                                    </div>
                                </td>
                                <td width="100%">
                                    <div class="form-group">
                                        <div class="form-check icheck-cyan d-inline">
                                            <input class="building-number-input-a form-check-input building-number" type="radio" name="building_no_copy" id="b_product" value="1" checked="true">
                                            <label class="form-check-label" for="b_product">商品用建物</label>
                                        </div>
                                        <div class="form-check icheck-cyan d-inline">
                                            <input class="building-number-input-b form-check-input building-number" type="radio" name="building_no_copy" id="b_demolish" value="2">
                                            <label class="form-check-label" for="b_demolish">解体予定建物</label>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td width="170px" class="parcel_th">
                                    <div class="form-group">
                                        <div class="form-check form-check-inline mr-0 icheck-cyan">
                                            @if($target_building[$purchase_target->id]->exist_unregistered == 1)
                                            <input @change="kindDisplay($event)" v-model="exist_unregistered[{{$target_data}}]" eu-id="{{$target_data}}" class="form-check-input exist_unregistered" type="checkbox" name="exist_unregistered[{{$target_data}}]" id="exist_unregistered_{{$target_data}}" value="1" checked="true">
                                            @else
                                            <input @change="kindDisplay($event)" v-model="exist_unregistered[{{$target_data}}]" eu-id="{{$target_data}}" class="form-check-input exist_unregistered" type="checkbox" name="exist_unregistered[{{$target_data}}]" id="exist_unregistered_{{$target_data}}" value="1">
                                            @endif
                                            <label class="form-check-label exist_unregistered_label" for="exist_unregistered_{{$target_data}}">未登記の建物有り</label>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-group" id="target_building_{{$target_data}}" style="display:none;">
                                        @if($target_building[$purchase_target->id]->kind == 1)
                                            <div class="form-check icheck-cyan d-inline">
                                                <input class="form-check-input" type="radio" name="building_kind[{{$target_data}}]" id="product_{{$target_data}}" value="1" checked="true">
                                                <label class="form-check-label" for="product_{{$target_data}}">商品用建物</label>
                                            </div>
                                            <div class="form-check icheck-cyan d-inline">
                                                <input class="form-check-input" type="radio" name="building_kind[{{$target_data}}]" id="demolish_{{$target_data}}" value="2">
                                                <label class="form-check-label" for="demolish_{{$target_data}}">解体予定建物</label>
                                            </div>
                                        @elseif($target_building[$purchase_target->id]->kind == 2)
                                            <div class="form-check icheck-cyan d-inline">
                                                <input class="form-check-input" type="radio" name="building_kind[{{$target_data}}]" id="product_{{$target_data}}" value="1">
                                                <label class="form-check-label" for="product_{{$target_data}}">商品用建物</label>
                                            </div>
                                            <div class="form-check icheck-cyan d-inline">
                                                <input class="form-check-input" type="radio" name="building_kind[{{$target_data}}]" id="demolish_{{$target_data}}" value="2" checked="true">
                                                <label class="form-check-label" for="demolish_{{$target_data}}">解体予定建物</label>
                                            </div>
                                        @else
                                            <div class="form-check icheck-cyan d-inline">
                                                <input class="form-check-input" type="radio" name="building_kind[{{$target_data}}]" id="product_{{$target_data}}" value="1">
                                                <label class="form-check-label" for="product_{{$target_data}}">商品用建物</label>
                                            </div>
                                            <div class="form-check icheck-cyan d-inline">
                                                <input class="form-check-input" type="radio" name="building_kind[{{$target_data}}]" id="demolish_{{$target_data}}" value="2">
                                                <label class="form-check-label" for="demolish_{{$target_data}}">解体予定建物</label>
                                            </div>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <th>第三者の占有</th>
                                <td>
                                @if($target_building[$purchase_target->id]->purchase_third_person_occupied == 1)
                                    <div class="form-check icheck-cyan d-inline">
                                        <input class="form-check-input" type="radio" name="third_person[{{$target_data}}]" id="third_person_none_{{$target_data}}" value="1" checked="true">
                                        <label class="form-check-label" for="third_person_none_{{$target_data}}">無</label>
                                    </div>
                                    <div class="form-check icheck-cyan d-inline">
                                        <input class="form-check-input" type="radio" name="third_person[{{$target_data}}]" id="third_person_exist_{{$target_data}}" value="2">
                                        <label class="form-check-label" for="third_person_exist_{{$target_data}}">有</label>
                                    </div>
                                @elseif($target_building[$purchase_target->id]->purchase_third_person_occupied == 2)
                                    <div class="form-check icheck-cyan d-inline">
                                        <input class="form-check-input" type="radio" name="third_person[{{$target_data}}]" id="third_person_none_{{$target_data}}" value="1">
                                        <label class="form-check-label" for="third_person_none_{{$target_data}}">無</label>
                                    </div>
                                    <div class="form-check icheck-cyan d-inline">
                                        <input class="form-check-input" type="radio" name="third_person[{{$target_data}}]" id="third_person_exist_{{$target_data}}" value="2" checked="true">
                                        <label class="form-check-label" for="third_person_exist_{{$target_data}}">有</label>
                                    </div>
                                @else
                                    <div class="form-check icheck-cyan d-inline">
                                        <input class="form-check-input" type="radio" name="third_person[{{$target_data}}]" id="third_person_none_{{$target_data}}" value="1">
                                        <label class="form-check-label" for="third_person_none_{{$target_data}}">無</label>
                                    </div>
                                    <div class="form-check icheck-cyan d-inline">
                                        <input class="form-check-input" type="radio" name="third_person[{{$target_data}}]" id="third_person_exist_{{$target_data}}" value="2">
                                        <label class="form-check-label" for="third_person_exist_{{$target_data}}">有</label>
                                    </div>
                                @endif
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    @php
                        if($purchase_target->purchase_not_create_documents == 1) {
                            $avoid_create = true;
                        } else {
                            $avoid_create = false;
                        }
                    @endphp
                    <div style="margin-top: 2em;" class="nav-buttons bottom form-inline justify-content-center mb-1">
                        <button id="{{$target_data}}" name="submit" class="btn btn-primary mr-3" @click="purchaseContractorSubmit($event, {{$target_data}})" type="submit" value="{{$target_data}}">買付作成</button>
                        <div class="form-check form-check-inline mr-0 icheck-cyan">
                            <input class="form-check-input" type="checkbox" 
                                @change="createPurchaseDoc($event)"
                                name="purchase_not_create_documents[{{$target_data}}]" 
                                id="purchase_not_create_documents_{{$target_data}}" 
                                value="1" @if($avoid_create) checked="true" @endif>
                            <label class="form-check-label" for="purchase_not_create_documents_{{$target_data}}">買付証明書を作成しない</label>
                        </div>
                    </div>

                </div>
                <!--col-12-->
            
            </div>
    @endforeach

    <div v-for="n in parseInt(purchase.count)-parseInt(purchase.data_exist)">
        <input type="hidden" name="purchase_id" value="{{$purchase->id}}">
        <input type="hidden" name="project_id" value="{{$project_id}}">
        <input type="hidden" name="target_data" value="create">
        @csrf
        <div class="card card-purchase" style="border-color:#191970; min-width:1100px;">
            <div class="card-header" style="color:#fff; background:#191970;">買付No.@{{ parseInt(n)+parseInt(purchase.data_exist) }}</div>
            <div class="card-body">
                @php $target_data++; @endphp                                 
                <div v-if="parseInt(purchase.count) == 1" class="nav-buttons bottom form-inline m-1">
                    <button class="btn btn-light" @click="displayAllContraction($event)">契約者をまとめて設定</button>
                </div>
                <table class="table table-bordered table-small table-parcel-list mt-0">
                    <thead>
                        <tr>
                            <th width="130px" class="parcel_contractor">契約者</th>
                            <th width="110px" class="parcel_owner">所有者</th>
                            <th width="80px" class="parcel_type">分類</th>
                            <th width="270px" class="parcel_address_join">所在/地番(家屋番号)</th>
                            <th width="120px" class="parcel_share">持分</th>
                            <th width="120px" class="building_usetype">種類</th>
                            <th width="180px" class="parcel_size">地積(登記)/床面積</th>
                            <th width="50px" class="parcel_ctl"></th>
                        </tr>
                    </thead>
                    <tbody :id="'purchase-addon-container_'+(parseInt(n)+parseInt(purchase.data_exist))">
                        <tr :id="'purchase_contractor_form_'+(parseInt(n)+parseInt(purchase.data_exist))" :class="'purchase_contractor_form_'+(parseInt(n)+parseInt(purchase.data_exist))" style="display:none;">
                            <td style="min-width:130px" :id="'data_property_copy_'+(parseInt(n)+parseInt(purchase.data_exist))" rowspan="99"
                            class="purchase_contractor_form" el-id="select_contractor">
                                <div class="form-group">
                                    <select v-model="new_contractor" 
                                            style="min-width:100%;"
                                            :id="'purchase_contractor_copy_'+parseInt(parseInt(n)+parseInt(purchase.data_exist))" 
                                            class="form-control form-control-sm contractor_select contractor_select_copy" 
                                            name="target_contractor_copy"
                                            @change="insertPropertyData(parseInt(n)+parseInt(purchase.data_exist), $event)">
                                        <option value="0" selected="selected"></option>
                                        <option v-for="(contractor_list, key) in contractors_list" :value="key">@{{ contractor_list }}</option>
                                    </select>
                                </div>
                            </td>
                        
                            <td class="remove" style="min-width:110px">
                                <div class="form-group">
                                    <input style="min-width:100%;" class="form-control form-control-w-lg form-control-sm" name="" type="text" value="" readonly="readonly" data-id="A73-3">
                                </div>
                            </td>
                            <td class="remove" style="min-width:81px">
                                <div class="form-group">
                                    <input style="min-width:100%;" class="form-control form-control-w-sm form-control-sm" name="" type="text" value="" readonly="readonly" data-id="A73-4">
                                </div>
                            </td>
                            <td class="remove" style="min-width:271px">
                                <div class="form-group">
                                    <input style="min-width:100%;" class="form-control form-control-w-xxxl form-control-sm" name="" type="text" value="" readonly="readonly" data-id="A73-5">
                                </div>
                            </td>
                            <td class="remove" style="min-width:121px">
                                <div class="form-group">
                                    <input class="form-control form-control-w-xs form-control-sm" name="" type="text" value="" readonly="readonly" data-id="A73-6">
                                    分の
                                    <input class="form-control form-control-w-xs form-control-sm" name="" type="text" value="" readonly="readonly" data-id="A73-7">
                                </div>
                            </td>
                            <td class="remove" style="min-width:121px">
                                <div class="form-group">
                                    <input style="min-width:100%;" class="form-control form-control-w-lg form-control-sm" name="" type="text" value="" readonly="readonly" data-id="A73-8">
                                </div>
                            </td>
                            <td class="remove" style="min-width:181px">
                                <div class="form-group">
                                    <input class="form-control form-control-w-sm form-control-sm" name="" type="text" value="" readonly="readonly" data-id="A73-9"> m<sup>2</sup>
                                </div>
                            </td>
                            <td class="button" style="min-width:51px">
                                <i id="olo" class="addon-form add_row_button fa fa-minus-circle cur-pointer text-danger ml-2 align-bottom" data-toggle="tooltip" title="" data-original-title="行を削除"></i>
                            </td>
                        </tr>

                        <tr>
                            <td :id="'data_property_'+(parseInt(n)+parseInt(purchase.data_exist))" rowspan="2">
                                <div class="form-group">
                                    <select v-model="new_contractor[parseInt(n)+parseInt(purchase.data_exist)]" 
                                            style="min-width:100%;" :id="'purchase_contractor_'+parseInt(n)+parseInt(purchase.data_exist)"
                                            :name="'target_contractor['+(parseInt(n)+parseInt(purchase.data_exist))+'][0]'"
                                            class="form-control form-control-sm contractor_select" 
                                            @change="insertPropertyData(parseInt(n)+parseInt(purchase.data_exist), $event)" >
                                        <option value="0" selected="selected"></option>
                                        <option v-for="(contractor_list, key) in contractors_list" :value="key">@{{ contractor_list }}</option>
                                    </select>
                                </div>
                            </td>
                        <tr :row-id="'data_property_copy_'+(parseInt(n)+parseInt(purchase.data_exist))">
                            <td class="remove">
                                <div class="form-group">
                                    <input style="min-width:100%;" class="form-control form-control-w-lg form-control-sm" name="" type="text" value="" readonly="readonly" data-id="A73-3">
                                </div>
                            </td>
                            <td class="remove">
                                <div class="form-group">
                                    <input style="min-width:100%;" class="form-control form-control-w-sm form-control-sm" name="" type="text" value="" readonly="readonly" :data-id="'kind_'+(parseInt(n)+parseInt(purchase.data_exist))">
                                </div>
                            </td>
                            <td class="remove">
                                <div class="form-group">
                                    <input style="min-width:100%;" class="form-control form-control-w-xxxl form-control-sm" name="" type="text" value="" readonly="readonly" data-id="A73-5">
                                </div>
                            </td>
                            <td class="remove">
                                <div class="form-group">
                                    <input class="form-control form-control-w-xs form-control-sm" name="" type="text" value="" readonly="readonly" data-id="A73-6">
                                    分の
                                    <input class="form-control form-control-w-xs form-control-sm" name="" type="text" value="" readonly="readonly" data-id="A73-7">
                                </div>
                            </td>
                            <td class="remove">
                                <div class="form-group">
                                    <input style="min-width:100%;" class="form-control form-control-w-lg form-control-sm" name="" type="text" value="" readonly="readonly" data-id="A73-8">
                                </div>
                            </td>
                            <td class="remove">
                                <div class="form-group">
                                    <input class="form-control form-control-w-sm form-control-sm" name="" type="text" value="" readonly="readonly" data-id="A73-9"> m<sup>2</sup>
                                </div>
                            </td>
                            <td class="button">
                                <i @click="addPurchaseContractor(parseInt(n)+parseInt(purchase.data_exist))" class="add_row_button fa fa-plus-circle cur-pointer text-primary ml-2 align-bottom" data-toggle="tooltip" title="" data-original-title="行を追加"></i>
                            </td>
                        </tr>
                        
                    </tbody>
                </table>

                <table style="width:auto;" class="table table-bordered table-small buypurchase_table">
                    <tbody>
                        <tr>
                            <th width="150px"><span class="text-danger">※</span>買付価格</th>
                            <td width="150px">
                                <template>
                                <currency-input style="min-width:80%;" class="purchase_price form-control form-control-w-lg form-control-sm input-money" 
                                        :name="'purchase_price['+(parseInt(n)+parseInt(purchase.data_exist))+']'" type="text" :id="'purchase_price_'+(parseInt(n)+parseInt(purchase.data_exist))"
                                        @change="calcPrice()"
                                        :data-parsley-required="'purchase_price_'+(parseInt(n)+parseInt(purchase.data_exist))+' == null'" 
                                        data-parsley-trigger="change focusout"
                                        :data-parsley-errors-container="'#purchase_price_error_'+(parseInt(n)+parseInt(purchase.data_exist))"
                                        data-parsley-required-message="買付価格をすべて0にすることはできません。"
                                        :currency="null"
                                        :precision="{ min: 0, max: 0 }"
                                        :allow-negative="false" >
                                </template>
                                <span v-if="parseInt(purchase.count) == 1"><i @click="copyPriceTsubo()" id="copy_price_tsubo" class="copy_xxxx_button far fa-copy cur-pointer text-secondary ml-2" data-toggle="tooltip" title="" data-original-title="仕入予定価格をコピー"></i></span>
                                <div :id="'purchase_price_error_'+(parseInt(n)+parseInt(purchase.data_exist))"></div>
                            </td>
                            <th width="150px">内手付金</th>
                            <td width="150px">
                                <template>
                                <currency-input style="min-width:100%;" class="form-control form-control-w-lg form-control-sm input-money" 
                                        :name="'purchase_deposit['+(parseInt(n)+parseInt(purchase.data_exist))+']'" type="text" value=""
                                        :currency="null"
                                        :precision="{ min: 0, max: 0 }"
                                        :allow-negative="false" >
                                </template>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <div class="headding01"
                    style=" background: #005AA0;
                            color: #fff;
                            text-align: center;
                            padding: 0.3em 0.6em;
                            margin: 0.5em 0;"> 建物情報</div>
                <table class="table table-bordered table-small table-parcel-list mt-0">
                    <tbody :id="'purchase_card_second_'+(parseInt(n)+parseInt(purchase.data_exist))">
                        <tr :id="'building_number_row_'+(parseInt(n)+parseInt(purchase.data_exist))" :class="'build_'+(parseInt(n)+parseInt(purchase.data_exist))" style="display:none;">
                            <td width="170px" class="parcel_th">
                                <div class="form-group">
                                    <input style="min-width:100%;" class="form-control form-control-w-lg form-control-sm" name="building_no_valx" type="text" value="" readonly="readonly" data-id="A72-8">
                                </div>
                            </td>
                            <td width="100%">
                                <div class="form-group">
                                    <div class="form-check icheck-cyan d-inline">
                                        <input class="building-number-input-a form-check-input building-number" type="radio" name="building_no_copy" id="b_product" value="1" checked="true">
                                        <label class="form-check-label" for="b_product">商品用建物</label>
                                    </div>
                                    <div class="form-check icheck-cyan d-inline">
                                        <input class="building-number-input-b form-check-input building-number" type="radio" name="building_no_copy" id="b_demolish" value="2">
                                        <label class="form-check-label" for="b_demolish">解体予定建物</label>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td width="170px" class="parcel_th">
                                <div class="form-group">
                                    <div class="form-check form-check-inline mr-0 icheck-cyan">
                                        <input v-model="exist_unregistered[(parseInt(n)+parseInt(purchase.data_exist))]" class="form-check-input" type="checkbox" :name="'exist_unregistered['+(parseInt(n)+parseInt(purchase.data_exist))+']'" :id="'exist_unregistered_'+(parseInt(n)+parseInt(purchase.data_exist))" :value="1">
                                        <label class="form-check-label" :for="'exist_unregistered_'+(parseInt(n)+parseInt(purchase.data_exist))">未登記の建物有り</label>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="form-group" v-if="exist_unregistered[(parseInt(n)+parseInt(purchase.data_exist))] == 1">
                                    <div class="form-check icheck-cyan d-inline">
                                        <input class="form-check-input" type="radio" :name="'building_kind['+(parseInt(n)+parseInt(purchase.data_exist))+']'" :id="'product_'+(parseInt(n)+parseInt(purchase.data_exist))" value="1">
                                        <label class="form-check-label" :for="'product_'+(parseInt(n)+parseInt(purchase.data_exist))">商品用建物</label>
                                    </div>
                                    <div class="form-check icheck-cyan d-inline">
                                        <input class="form-check-input" type="radio" :name="'building_kind['+(parseInt(n)+parseInt(purchase.data_exist))+']'" :id="'demolish_'+(parseInt(n)+parseInt(purchase.data_exist))" value="2">
                                        <label class="form-check-label" :for="'demolish_'+(parseInt(n)+parseInt(purchase.data_exist))">解体予定建物</label>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <th>第三者の占有</th>
                            <td>
                                <div class="form-check icheck-cyan d-inline">
                                    <input class="form-check-input" type="radio" :name="'third_person['+(parseInt(n)+parseInt(purchase.data_exist))+']'" :id="'third_person_none_'+(parseInt(n)+parseInt(purchase.data_exist))" value="1">
                                    <label class="form-check-label" :for="'third_person_none_'+(parseInt(n)+parseInt(purchase.data_exist))">無</label>
                                </div>
                                <div class="form-check icheck-cyan d-inline">
                                    <input class="form-check-input" type="radio" :name="'third_person['+(parseInt(n)+parseInt(purchase.data_exist))+']'" :id="'third_person_exist_'+(parseInt(n)+parseInt(purchase.data_exist))" value="2">
                                    <label class="form-check-label" :for="'third_person_exist_'+(parseInt(n)+parseInt(purchase.data_exist))">有</label>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>

                <div style="margin-top: 2em;" class="nav-buttons bottom form-inline justify-content-center mb-1">
                    <button :id="parseInt(n)+parseInt(purchase.data_exist)" name="submit" class="btn btn-primary mr-3" @click="purchaseContractorSubmit($event, parseInt(n)+parseInt(purchase.data_exist))" type="submit" :value="parseInt(n)+parseInt(purchase.data_exist)">買付作成</button>                 
                    <div class="form-check form-check-inline mr-0 icheck-cyan">
                        <input class="form-check-input" type="checkbox" 
                            @change="createPurchaseDoc($event)"
                            :name="'purchase_not_create_documents['+(parseInt(n)+parseInt(purchase.data_exist))+']'" 
                            :id="'purchase_not_create_documents_'+(parseInt(n)+parseInt(purchase.data_exist))" 
                            value="1">
                        <label class="form-check-label" :for="'purchase_not_create_documents_'+(parseInt(n)+parseInt(purchase.data_exist))">買付証明書を作成しない</label>
                    </div>
                </div>

            </div>
            <!--col-12-->
        </div>
    </div>
    <input type="hidden" name="x_submit" value="">
</form>
</div>