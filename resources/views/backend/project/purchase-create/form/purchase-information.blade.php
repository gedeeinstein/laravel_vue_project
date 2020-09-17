<div class="cards-purchase">
   <div class="card" style="border-color:#191970; min-width:1100px;">
      <div class="card-header" style="color:#fff; background:#191970;">@lang('pj_purchase_response.purchase_no'){{ $purchase_target->purchase_number }}</div>
      <div class="card-body">
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
               @foreach ($purchase_target_contractors_group_by_name as $purchase_target_contractors_key => $purchase_target_contractors)
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
                  @if ($lot_kind == 'residential') @php $owners = $lot_kind . "_owners"; $purchase_create = $lot_kind . "_purchase_create"; $lot_a = "pj_lot_{$lot_kind}_a"; @endphp
                  @elseif ($lot_kind == 'road') @php $owners = $lot_kind . "_owners"; $purchase_create = $lot_kind . "_purchase_create"; $lot_a = "pj_lot_{$lot_kind}_a"; @endphp
                  @elseif ($lot_kind == 'building') @php $owners = $lot_kind . "_owners"; $purchase_create = $lot_kind . "_purchase_create"; $lot_a = "pj_lot_{$lot_kind}_a"; @endphp
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
                                     <input class="form-control form-control-w-sm form-control-sm" name="" type="text"
                                         value="{{ $purchase_target_contractor[$lot_kind]->building_floors->sum('floor_size') }}" readonly="readonly"> m<sup>2</sup>
                                     <span>(<input class="form-control form-control-w-xs form-control-sm" name="" type="text"
                                         value="{{ $purchase_target_contractor[$lot_kind]->building_floor_count }}" readonly="readonly">階建)</span>
                                 @else
                                     <input class="form-control form-control-w-sm form-control-sm" name="" type="text"
                                         value="{{ number_format($purchase_target_contractor[$lot_kind]->parcel_size) }}" readonly="readonly"> m<sup>2</sup>
                                 @endif
                             </div>
                          </td>
                          <td>
                             <div class="form-group">
                                <div class="form-check icheck-cyan form-check-inline">
                                   <input v-model="purchase_target_contractors_group_by_name[{{ $purchase_target_contractors_key }}][{{ $purchase_target_contractor_key }}].{{ $purchase_create }}.purchase_equity" class="form-check-input" type="radio" name="" id="{{ $lot_kind }}_purchase_equity_1_{{ $purchase_target_contractors_key }}_{{ $purchase_target_contractor_key }}" value="1"
                                      data-id="A81-9">
                                   <label class="form-check-label" for="{{ $lot_kind }}_purchase_equity_1_{{ $purchase_target_contractors_key }}_{{ $purchase_target_contractor_key }}">全部</label>
                                </div>
                                <div class="form-check icheck-cyan form-check-inline">
                                   <input v-model="purchase_target_contractors_group_by_name[{{ $purchase_target_contractors_key }}][{{ $purchase_target_contractor_key }}].{{ $purchase_create }}.purchase_equity" class="form-check-input" type="radio" name="" id="{{ $lot_kind }}_purchase_equity_2_{{ $purchase_target_contractors_key }}_{{ $purchase_target_contractor_key }}" value="2">
                                   <label class="form-check-label" for="{{ $lot_kind }}_purchase_equity_2_{{ $purchase_target_contractors_key }}_{{ $purchase_target_contractor_key }}">一部</label>
                                </div>
                             </div>
                             <input v-if="purchase_target_contractors_group_by_name[{{ $purchase_target_contractors_key }}][{{ $purchase_target_contractor_key }}].{{ $purchase_create }}.purchase_equity == 2" v-model="purchase_target_contractors_group_by_name[{{ $purchase_target_contractors_key }}][{{ $purchase_target_contractor_key }}].{{ $purchase_create }}.purchase_equity_text" class="form-control form-control-w-xl" name="" type="text" value=""
                                data-id="A81-10" data-parsley-trigger="keyup" data-parsley-maxlength="128">
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
         <table class="table table-bordered table-small buypurchase_table">
            <tr>
               <th><span class="text-danger">※</span>買付価格</th>
               <td>
                  <template>
                     <currency-input class="form-control w-100" :name="name" :id="name" v-model="purchase_target.purchase_price"
                        :currency="null" :precision="{min: 0, max: 9}" :allow-negative="false" readonly="readonly"
                        data-parsley-decimal-maxlength="[12,4]" data-parsley-trigger="change focusout" data-parsley-no-focus />
                  </template>
               </td>
               <th>内手付金</th>
               <td>
                  <template>
                     <currency-input class="form-control w-100" :name="name" :id="name" v-model="purchase_target.purchase_deposit"
                        :currency="null" :precision="{min: 0, max: 9}" :allow-negative="false" readonly="readonly"
                        data-parsley-decimal-maxlength="[12,4]" data-parsley-trigger="change focusout" data-parsley-no-focus />
                  </template>
               </td>
            </tr>
         </table>
      </div>
   </div>
</div>
