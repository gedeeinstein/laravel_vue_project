{{-- looping with contractor --}}
{{----------------------------------------------------------------------------}}
<div class="row pt-1">
    <div class="col-12">
        <div class="card card-project">
            <div class="card-header">@lang('pj_purchase_response.purchase_information')</div>
            <div class="form-group row p-1 mb-0">
                <div class="col-12" style="border-color:#191970; min-width:1100px;">
                    <div class="card-subheader01" style="color:#fff; background:#191970;">
                        <div class="form-check icheck-cyan form-check-inline">
                            <strong>@lang('pj_purchase_response.purchase_no'){{ $purchase_target->purchase_number }}</strong>
                        </div>
                    </div>
                    <div>
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
                                            <div class="form-check icheck-cyan form-check-inline">
                                                <input @if ($purchase_target_contractors_group_by_name[$purchase_target_contractors_key][$purchase_target_contractor_key]->$purchase_create->purchase_equity == 1) checked @endif
                                                class="form-check-input" type="radio" name="" id="{{ $lot_kind }}_purchase_equity_1_{{ $purchase_target_contractors_key }}_{{ $purchase_target_contractor_key }}" value="1" data-id="A81-9" disabled>
                                                <label class="form-check-label" for="{{ $lot_kind }}_purchase_equity_1_{{ $purchase_target_contractors_key }}_{{ $purchase_target_contractor_key }}">全部</label>
                                            </div>
                                            <div class="form-check icheck-cyan form-check-inline">
                                                <input @if ($purchase_target_contractors_group_by_name[$purchase_target_contractors_key][$purchase_target_contractor_key]->$purchase_create->purchase_equity == 2) checked @endif
                                                class="form-check-input" type="radio" name="" id="{{ $lot_kind }}_purchase_equity_2_{{ $purchase_target_contractors_key }}_{{ $purchase_target_contractor_key }}" value="2" disabled>
                                                <label class="form-check-label" for="{{ $lot_kind }}_purchase_equity_2_{{ $purchase_target_contractors_key }}_{{ $purchase_target_contractor_key }}">一部</label>
                                            </div>
                                        </div>
                                        <input class="form-control form-control-w-xl" name="" type="text" data-id="A81-10" data-parsley-trigger="keyup" readonly
                                        @if ($purchase_target_contractors_group_by_name[$purchase_target_contractors_key][$purchase_target_contractor_key]->$purchase_create->purchase_equity == 2)
                                        value="{{ $purchase_target_contractors_group_by_name[$purchase_target_contractors_key][$purchase_target_contractor_key]->$purchase_create->purchase_equity_text }}"
                                        @endif >
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
                    </div>
                    <!--border border-info-->
                </div>
                <!--col-12-->
            </div>
            <!--row-->
        </div>
        <!--card-->
    </div>
</div>
{{----------------------------------------------------------------------------}}
