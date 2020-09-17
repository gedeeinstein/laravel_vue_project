<table class="table table-bordered table-small table-parcel-list mt-0">
    <thead>
        <tr>
            <th width="130px">@lang('pj_purchase_response.contractor')</th>
            <th width="100px">@lang('pj_purchase_response.owner')</th>
            <th width="60px">@lang('pj_purchase_response.classification')</th>
            <th width="260px">@lang('pj_purchase_response.location')</th>
            <th width="120px">@lang('pj_purchase_response.equity')</th>
            <th width="110px">@lang('pj_purchase_response.type')</th>
            <th width="160px">@lang('pj_purchase_response.land_area')</th>
            <th width="130px">@lang('pj_purchase_response.purchase_equity')</th>
        </tr>
    </thead>
    <tbody>
        @php $builinc = 0; @endphp
        @foreach ($purchase_target_contractors_group_by_name[$index] as $purchase_target_contractors_key => $purchase_target_contractors)
        @php $inc = 0; @endphp
        <tr>
            <td @if ($purchase_target_contractors['rowspan'] > 1) rowspan="{{ $purchase_target_contractors['rowspan'] }}" @endif >
            <div class="form-group">
                <input style="min-width: 100%" class="form-control form-control-w-lg form-control-sm" name="" type="text" value="{{ $purchase_target_contractors[0]->name }}" readonly="readonly">
            </div>
            </td>
            @foreach ($purchase_target_contractors as $purchase_target_contractor_key => $purchase_target_contractor)
            {{-- lot residential, road and building --}}
            {{-------------------------------------------------}}
            @foreach ($lot_kinds as $key => $lot_kind)
            @if (!empty($purchase_target_contractor[$lot_kind]))
            @if ($lot_kind == 'residential') @php $owners = $lot_kind . "_owners"; $purchase_create = $lot_kind . "_purchase_create"; @endphp
            @elseif ($lot_kind == 'road') @php $owners = $lot_kind . "_owners"; $purchase_create = $lot_kind . "_purchase_create"; @endphp
            @elseif ($lot_kind == 'building') @php $owners = $lot_kind . "_owners"; $purchase_create = $lot_kind . "_purchase_create"; @endphp
            @endif
            @foreach ($purchase_target_contractor[$lot_kind]->$owners as $owner_key => $owner)
            @if ($owner->pj_property_owners_id == $purchase_target_contractor->pj_property_owner_id)
            @php $inc++; @endphp
            @if( $inc <= $purchase_target_contractors['rowspan'])
            @if($inc > 1)
        <tr>
            @endif
            <td>
                <div class="form-group">
                    <input style="min-width: 100%" class="form-control form-control-w-lg form-control-sm" name="" type="text" value="{{ $owner->property_owner->name }}" readonly="readonly">
                </div>
            </td>
            <td>
                <div class="form-group">
                    <input style="min-width: 100%" class="form-control form-control-w-lg form-control-sm" name="" type="text"
                    @if ($lot_kind == 'residential') value="宅地"
                    @elseif ($lot_kind == 'road') value="道路"
                    @elseif ($lot_kind == 'building') value="[建物"
                    @endif
                    readonly="readonly">
                </div>
            </td>
            <td>
                <div class="form-group">
                    @php
                    if($purchase_target_contractor[$lot_kind]->parcel_city == -1) {
                        $parcel_city_name  = 'その他';
                        $parcel_city_extra = $purchase_target_contractor[$lot_kind]->parcel_city_extra;
                    } else {
                        $parcel_city_name  = $parcel_city[$purchase_target_contractor[$lot_kind]->parcel_city];
                        $parcel_city_extra = '';
                    }
                    if ($lot_kind == 'building') {
                        $building_number_third = $purchase_target_contractor[$lot_kind]->building_number_third ?
                                                 "の" . $purchase_target_contractor[$lot_kind]->building_number_third : "";
                        $building_no_val[$builinc] = "(". $purchase_target_contractor[$lot_kind]->building_number_first ."番". $purchase_target_contractor[$lot_kind]->building_number_second . $building_number_third .")";
                        $builinc++;
                    }
                    @endphp
                    <input style="min-width: 100%" class="form-control form-control-w-xxxl form-control-sm" name="" type="text"
                    @if ($lot_kind == 'building')
                        @php
                            $building_number_third = $purchase_target_contractor[$lot_kind]->building_number_third ?
                                                     "の" . $purchase_target_contractor[$lot_kind]->building_number_third : "";
                        @endphp
                    value="{{ $parcel_city_name ."". $parcel_city_extra ."". $purchase_target_contractor[$lot_kind]->parcel_town ."". $purchase_target_contractor[$lot_kind]->parcel_number_first ."番". $purchase_target_contractor[$lot_kind]->parcel_number_second ." (". $purchase_target_contractor[$lot_kind]->building_number_first ."番". $purchase_target_contractor[$lot_kind]->building_number_second . $building_number_third .")" }}"
                    @else value="{{ $parcel_city_name ."". $parcel_city_extra ."". $purchase_target_contractor[$lot_kind]->parcel_town ."". $purchase_target_contractor[$lot_kind]->parcel_number_first ."番". $purchase_target_contractor[$lot_kind]->parcel_number_second }}"
                    @endif
                    readonly="readonly">
                </div>
            </td>
            <td>
                <div class="form-group">
                    <input class="form-control form-control-w-xs form-control-sm" name="" type="text" value="{{ $owner->share_denom }}" readonly="readonly">
                    分の
                    <input class="form-control form-control-w-xs form-control-sm" name="" type="text" value="{{ $owner->share_number }}" readonly="readonly">
                </div>
            </td>
            <td>
                <div class="form-group">
                    <input style="min-width: 100%" class="form-control form-control-w-lg form-control-sm" name="" type="text"
                    @if ($lot_kind == 'building') value="{{ $building_usetype[$purchase_target_contractor[$lot_kind]->building_usetype] }}"
                    @else value=""
                    @endif
                    readonly="readonly">
                </div>
            </td>
            <td>
                <div class="form-group">
                    @if ($lot_kind == 'building')
                    <input class="form-control form-control-w-sm" name="" type="text"
                        value="{{ $purchase_target_contractor[$lot_kind]->building_floors->sum('floor_size') }}" readonly="readonly"> m<sup>2</sup>
                    <span>(<input class="form-control form-control-w-xs" name="" type="text"
                        value="{{ $purchase_target_contractor[$lot_kind]->building_floor_count }}" readonly="readonly">階建)</span>
                    @else
                    <input class="form-control form-control-w-sm form-control-sm" name="" type="text"
                        value="{{ number_format($purchase_target_contractor[$lot_kind]->parcel_size) }}" readonly="readonly"> m<sup>2</sup>
                    @endif
                </div>
            </td>
            <td>
                <div class="form-group">
                    <input style="min-width: 100%;" class="form-control form-control-w-lg form-control-sm" name="" type="text"
                    @if ($purchase_target_contractors_group_by_name[$index][$purchase_target_contractors_key][$purchase_target_contractor_key]->$purchase_create->purchase_equity == 1) value="全部"
                    @elseif ($purchase_target_contractors_group_by_name[$index][$purchase_target_contractors_key][$purchase_target_contractor_key]->$purchase_create->purchase_equity == 2) value="一部"
                    @endif
                    readonly="readonly">
                </div>
                @if ($purchase_target_contractors_group_by_name[$index][$purchase_target_contractors_key][$purchase_target_contractor_key]->$purchase_create->purchase_equity == 2)
                <div class="form-group">
                    <input style="min-width: 100%;" class="form-control form-control-w-lg form-control-sm" name="" type="text" value="{{ $purchase_target_contractors_group_by_name[$index][$purchase_target_contractors_key][$purchase_target_contractor_key]->$purchase_create->purchase_equity_text }}"
                        readonly="readonly">
                </div>
                @endif
            </td>
            @endif
            @endif
            @endforeach
            @if ($purchase_target_contractors['rowspan'] > 1)
        </tr>
        @endif
        @endif
        @endforeach
        {{-------------------------------------------------}}
        @endforeach
        @endforeach
    </tbody>
</table>
<div class="headding01"
    style=" background: #005AA0;
            color: #fff;
            text-align: center;
            padding: 0.3em 0.6em;
            margin: 0.5em 0;"> @lang('project_purchase_contract.building_information')</div>
<table class="table table-bordered table-small table-parcel-list mt-3">
   <tbody>
    @foreach($buildings_no[$index] as $noKey => $building_no)
      <tr>
        <td width="170px" class="parcel_th">
            @if($buildings_no[$index]->count() == 1)
            <div class="form-group">
                {{-- <input v-model="purchase_targets[{{ $index }}].purchase_contract.contract_building_number" class="form-control form-control-w-lg" name="" type="text" value="{{$building_no_val[0]}}" readonly="readonly" data-id="A121-11"
                :disabled="!default_value.editable"> --}}
                <input class="form-control form-control-w-lg" name="" type="text" value="{{$building_no_val[$noKey]}}" readonly="readonly" data-id="A121-11"
                :disabled="!default_value.editable">
            </div>
            @else
            <div class="form-group">
                <input type="hidden" v-model="purchase_targets[{{ $index }}].purchase_contract.contract_building_number" class="form-control form-control-w-lg" name="" type="text" value="{{$building_no_val[$noKey]}}" readonly="readonly" data-id="A121-11"
                    :disabled="!default_value.editable">
                <input style="min-width:100%;" class="form-control form-control-w-lg form-control-sm" name="building_no_val" type="text" value="{{$building_no_val[$noKey]}}" readonly="readonly" data-id="A72-8">
            </div>
            @endif
        </td>
        <td width="100%">
           <div class="form-group">
           @if($buildings_no[$index]->count() == 1)
              <div class="icheck-cyan d-inline">
                 <input v-model="purchase_targets[{{ $index }}].purchase_contract.contract_building_kind" class="form-check-input" type="radio" name="contract_building_kind-{{ $index }}-{{ $noKey }}" id="contract-building-kind-1-{{ $index }}-{{ $noKey }}" value="1" data-id="A121-12"
                 :disabled="!default_value.editable">
                 <label
                 @if (count($purchase_target->purchase_target_buildings) > 0)
                   @if ($building_no->kind == 1) style="background: #FFFF00;" @endif
                 @endif class="form-check-label" for="contract-building-kind-1-{{ $index }}-{{ $noKey }}">@lang('project_purchase_contract.product_building')
                 </label>
              </div>
              <div class="icheck-cyan d-inline">
                 <input v-model="purchase_targets[{{ $index }}].purchase_contract.contract_building_kind" class="form-check-input" type="radio" name="contract_building_kind-{{ $index }}-{{ $noKey }}" id="contract-building-kind-2-{{ $index }}-{{ $noKey }}" value="2"
                 :disabled="!default_value.editable">
                 <label
                 @if (count($purchase_target->purchase_target_buildings) > 0)
                   @if ($building_no->kind == 2) style="background: #FFFF00;" @endif
                 @endif class="form-check-label" for="contract-building-kind-2-{{ $index }}-{{ $noKey }}">@lang('project_purchase_contract.building_demolished')
                 </label>
              </div>
           </div>
           @else
           <div class="form-check icheck-cyan d-inline">
                <input disabled="true" class="form-check-input" type="radio" name="building_no_copy_{{$index}}_{{$noKey}}" id="b_product_{{$index}}_{{$noKey}}" value="1" @if($building_no->kind == 1) checked="true" @endif>
                <label @if ($building_no->kind == 1) style="background: #FFFF00;" @endif class="form-check-label" for="b_product_{{$index}}_{{$noKey}}">商品用建物</label>
            </div>
            <div class="form-check icheck-cyan d-inline">
                <input disabled="true" class="form-check-input" type="radio" name="building_no_copy_{{$index}}_{{$noKey}}" id="b_demolish_{{$index}}_{{$noKey}}" value="2" @if($building_no->kind == 2) checked="true" @endif>
                <label @if ($building_no->kind == 2) style="background: #FFFF00;" @endif class="form-check-label" for="b_demolish_{{$index}}_{{$noKey}}">解体予定建物</label>
            </div>
            @endif
        </td>
      </tr>
      @endforeach
      <tr>
         <td width="160px" class="parcel_th">
            <div class="form-group">
               <div class="form-check form-check-inline mr-0">
                  <input v-model="purchase_targets[{{ $index }}].purchase_contract.contract_building_unregistered" class="form-check-input" type="checkbox"
                  :disabled="!default_value.editable">
                  <label class="form-check-label" for="">@lang('project_purchase_contract.unregistered_building')</label>
               </div>
            </div>
         </td>
         <td>
            <div v-if="purchase_targets[{{ $index }}].purchase_contract.contract_building_unregistered == 1" class="form-group">
               <div class="icheck-cyan d-inline">
                  <input v-model="purchase_targets[{{ $index }}].purchase_contract.contract_building_unregistered_kind" class="form-check-input" type="radio" name="contract-building-unregistered" id="contract-building-unregistered-kind-1-{{ $index }}" value="1"
                  :disabled="!default_value.editable">
                  <label
                  @if (count($purchase_target->purchase_target_buildings) > 0)
                    @if ($target_building[$index]->kind == 1) style="background: #FFFF00;" @endif
                  @endif class="form-check-label" for="contract-building-unregistered-kind-1-{{ $index }}">@lang('project_purchase_contract.product_building')</label>
               </div>
               <div class="icheck-cyan d-inline">
                  <input v-model="purchase_targets[{{ $index }}].purchase_contract.contract_building_unregistered_kind" class="form-check-input" type="radio" name="contract-building-unregistered" id="contract-building-unregistered-kind-2-{{ $index }}" value="2"
                  :disabled="!default_value.editable">
                  <label
                  @if (count($purchase_target->purchase_target_buildings) > 0)
                    @if ($target_building[$index]->kind == 2) style="background: #FFFF00;" @endif
                  @endif class="form-check-label" for="contract-building-unregistered-kind-2-{{ $index }}">@lang('project_purchase_contract.building_demolished')</label>
               </div>
            </div>
         </td>
      </tr>
   </tbody>
</table>
