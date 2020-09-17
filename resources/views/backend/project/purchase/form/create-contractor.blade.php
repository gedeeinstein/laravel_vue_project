<h6>仕入地番ごと契約者</h6>
<form id="save_contractor_data" method="POST" data-parsley class="parsley-minimal">
@csrf
<input type="hidden" name="project_id" value="{{$project_id}}">
<input type="hidden" name="property_id" value="{{ $pj_property->id }}">
<input type="hidden" v-model="pj_property.id">
<table id="table-contractor" class="table table-bordered table-small table-parcel-list mt-0">
    <thead>
        <tr>
            <th width="110px" class="parcel_owner">所有者</th>
            <th width="230px" class="parcel_contractor">契約者</th>
            <th width="80px" class="parcel_type">分類</th>
            <th width="270px" class="parcel_address_join">所在/地番(家屋番号)</th>
            <th width="120px" class="parcel_share">持分</th>
            <th width="120px" class="building_usetype">種類</th>
            <th width="180px" class="parcel_size">地積(登記)/床面積</th>
        </tr>
    </thead>
    <tbody>
        @php $incCopy = 0; @endphp
        @foreach($property_owner as $key => $propertyOwner)
        @if(array_key_exists($key, $kind_data))
        @php $inc = 0; @endphp
        <tr>
            <td rowspan="{{$property_count[$key]+1}}">
                <div class="form-group" el-id="property_data_{{$key}}">
                    <input style="min-width:100%;" id="property_owners_{{$key}}" class="form-control form-control-w-lg form-control-sm" v-model="property_owners[{{$key}}].name" type="text" readonly="readonly">
                    <input type="hidden" v-model="property_owners[{{$key}}].id">
                </div>
            </td>
        @foreach($kind_data[$key] as $kindKey => $kindData)
        @foreach($kindData as $kindKey2 => $kindDatas)
        @php $inc++; $incCopy++; @endphp

        <tr row-id="property_data_{{$key}}_{{$kindKey}}_{{$kindKey2}}">
            <td style="min-width:110px" class="remove">
                <div class="form-group" el-copy="property_data_{{$key}}">
                    <input id="copy_contractor_{{$key}}_{{$kindKey}}_{{$kindKey2}}"
                        data-id="property_data_{{$key}}_{{$kindKey}}_{{$kindKey2}}"
                        key-id="{{$key}}" kindKey-id="{{$kindKey}}" kindKey2-id="{{$kindKey2}}"
                        :data-parsley-required="copy_contractor_{{$key}}_{{$kindKey}}_{{$kindKey2}} == null"
                        data-parsley-trigger="change focusout"
                        data-parsley-errors-container="#contractor_validation_{{$key}}_{{$kindKey}}_{{$kindKey2}}"
                        class="form-control form-control-w-lg form-control-sm contractor_field input_contractor_{{$incCopy}}"
                        v-model="contractor[{{$key}}].{{$kindKey}}[{{$kindKey2}}]" type="text"
                        :readonly="contractor_same_as_owner[{{$key}}].{{$kindKey}}[{{$kindKey2}}] == 1" >
                    <div class="form-check form-check-inline mr-0 icheck-cyan">
                        <input @click="contractorAsOwner( {{$key}}, '{{$kindKey}}', '{{$kindKey2}}', $event )" class="form-check-input" type="checkbox" name="contractor_same_to_owner[{{$key}}][{{$kindKey}}][{{$kindKey2}}]" id="contractor-same-to-owner_{{$key}}_{{$kindKey}}_{{$kindKey2}}" v-model="contractor_same_as_owner[{{$key}}].{{$kindKey}}[{{$kindKey2}}]" :checked="contractor_same_as_owner[{{$key}}].{{$kindKey}}[{{$kindKey2}}] == 1">
                        <label class="form-check-label" for="contractor-same-to-owner_{{$key}}_{{$kindKey}}_{{$kindKey2}}">所有者と同じ</label>
                    </div>
                    @if($incCopy > 1)
                    <span><i @click="copyContractor({{$key}}, '{{$kindKey}}', {{$kindKey2}}, $event)" id="copy_contractor_{{$key}}_{{$kindKey}}_{{$kindKey2}}" class="copy_xxxx_button far fa-copy cur-pointer text-secondary copyx_{{$incCopy}}" data-toggle="tooltip" title="" data-original-title="所有者コピー"></i></span>
                    @endif
                </div>
                <span id="contractor_validation_{{$key}}_{{$kindKey}}_{{$kindKey2}}" class="form-group">

                </span>
                <input type="hidden" v-model="have_common[{{$key}}].{{$kindKey}}[{{$kindKey2}}]">
            </td>
            <td style="min-width:81px" class="remove">
                <div class="form-group">
                    <input style="min-width:100%;" class="kind form-control form-control-w-sm form-control-sm" name="kind[{{$key}}][{{$kindKey}}][{{$kindKey2}}]" type="text" value="{{$ja_kind[$kindKey]}}" readonly="readonly" data-id="kind_{{$key}}_{{$kindKey}}_{{$kindKey2}}">
                    @php
                        $params = 'pj_lot_'.$kindKey.'_a_id';
                    @endphp
                    <input type="hidden" :value="kind[{{$key}}].{{$kindKey}}.{{$params}}">
                </div>
            </td>
            <td style="min-width:271px" class="remove">
                <div class="form-group">
                    @php
                        if($kindDatas->parcel_city == -1) {
                            $parcel_city_name = $kindDatas->parcel_city_extra;
                        } else {
                            $parcel_city_name = $parcel_city[$kindDatas->parcel_city];
                        }
                    @endphp
                    @if($kindKey == "building")
                        @php
                            $building_number_third = $kindDatas->building_number_third ?
                                                     'の' . $kindDatas->building_number_third : '';
                        @endphp
                    <input style="min-width:100%;" class="build form-control form-control-w-xxxl form-control-sm" name="location_lot_number[{{$key}}][{{$kindKey}}][{{$kindKey2}}]" type="text" value="{{$parcel_city_name}}{{$kindDatas->parcel_town}}{{$kindDatas->parcel_number_first}}番{{$kindDatas->parcel_number_second}}({{$kindDatas->building_number_first}}番{{$kindDatas->building_number_second}}{{$building_number_third}})" readonly="readonly" data-id="A72-5">
                    @else
                    <input style="min-width:100%;" class="form-control form-control-w-xxxl form-control-sm" name="location_lot_number[{{$key}}][{{$kindKey}}][{{$kindKey2}}]" type="text" value="{{$parcel_city_name}}{{$kindDatas->parcel_town}}{{$kindDatas->parcel_number_first}}番{{$kindDatas->parcel_number_second}}" readonly="readonly" data-id="A72-5">
                    @endif
                </div>
            </td>
            <td style="min-width:121px" class="remove">
                <div class="form-group">
                    <input class="form-control form-control-w-xs form-control-sm" name="denom[{{$key}}][{{$kindKey}}][{{$kindKey2}}]" type="text" value="{{$kind[$key][$kindKey][$kindKey2]->share_denom}}" readonly="readonly" data-id="A72-6">
                    分の
                    <input class="form-control form-control-w-xs form-control-sm" name="number[{{$key}}][{{$kindKey}}][{{$kindKey2}}]" type="text" value="{{$kind[$key][$kindKey][$kindKey2]->share_number}}" readonly="readonly" data-id="A72-7">
                </div>
            </td>
            <td style="min-width:121px" class="remove">
                <div class="form-group">
                    @php
                        if($kindKey == "building") {
                            $building_usetype_val = $building_usetype[$kindDatas->building_usetype];
                        } else {
                            $building_usetype_val = null;
                        }
                    @endphp
                    <input style="min-width:100%;" class="form-control form-control-w-lg form-control-sm" name="building_usetype[{{$key}}][{{$kindKey}}][{{$kindKey2}}]" type="text" value="{{$building_usetype_val}}" readonly="readonly" data-id="A72-8">
                </div>
            </td>
            <td style="min-width:181px" class="remove">
                <div class="form-group">
                    @if($kindKey == "building")
                    <input class="form-control form-control-w-sm form-control-sm" name="building_floor_size[{{$key}}][{{$kindKey}}][{{$kindKey2}}]" type="text" value="{{number_format($building_floor_size[$key][$kindKey2])}}" readonly="readonly"> m<sup>2</sup>
                    <span>(<input class="form-control form-control-w-xs form-control-sm" name="building_floor_count[{{$key}}][{{$kindKey}}][{{$kindKey2}}]" type="text" value="{{$kindDatas->building_floor_count}}" readonly="readonly">階建)</span>
                    @else
                    <input class="form-control form-control-w-sm form-control-sm" name="building_floor_size[{{$key}}][{{$kindKey}}][{{$kindKey2}}]" type="text" value="{{number_format($kindDatas->parcel_size)}}" readonly="readonly" data-id="A72-9"> m<sup>2</sup>
                    @endif
                </div>
            </td>
        </tr>
        @endforeach
        @endforeach
        @endif
        @endforeach
    </tbody>
</table>

<div class="nav-buttons bottom form-inline justify-content-center mb-1">
    <button type="submit" class="btn btn-wide btn-primary px-4">
        <i v-if="!initial.submitedContractor" class="fas fa-save"></i>
        <i v-else class="fas fa-spinner fa-spin"></i>
        契約者保存
    </button>
</div>
<div class="row justify-content-center mb-3">
    <div class="text-danger">契約者を入力した場合クリックしてください。</div>
</div>
</form>
