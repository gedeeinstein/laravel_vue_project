<table class="table table-bordered table-small table-parcel-list mt-0">
    <thead>
        <tr>
            <th width="110px" class="parcel_contractor">契約者</th>
            <th width="110px"class="parcel_owner">所有者</th>
            <th width="70px" class="parcel_type">分類</th>
            <th width="250px" class="parcel_address_join">所在/地番(家屋番号)</th>
            <th width="120px" class="parcel_share">持分</th>
            <th width="120px" class="building_usetype">種類</th>
            <th width="180px" class="parcel_size">地積(登記)/床面積</th>
            <th width="100px" class="parcel_ctl">購入持分</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($purchase_target_contractors_group_by_name as $lot_contractors_key => $lot_contractors)
        @php $inc = 0; @endphp
        <tr>
            <!-- start - contractor name -->
            <td @if ($lot_contractors['rowspan'] > 1) rowspan="{{ $lot_contractors['rowspan'] }}" @endif >
                <div class="form-group">
                    <input style="min-width: 100%" class="form-control form-control-w-lg form-control-sm"
                        type="text" value="{{ $lot_contractors[0]->name }}" readonly="readonly"
                    >
                </div>
            </td>
            <!-- end - contractor name -->

            @foreach ($lot_contractors as $lot_contractor_key => $lot_contractor)
                <!-- start - residential, road and building -->
                @foreach ($lot_kinds as $key => $lot_kind)
                    @if (!empty($lot_contractor[$lot_kind]))
                        @if ($lot_kind == 'residential') @php $owners = $lot_kind . "_owners"; $purchase_create = $lot_kind . "_purchase_create"; @endphp
                        @elseif ($lot_kind == 'road') @php $owners = $lot_kind . "_owners"; $purchase_create = $lot_kind . "_purchase_create"; @endphp
                        @elseif ($lot_kind == 'building') @php $owners = $lot_kind . "_owners"; $purchase_create = $lot_kind . "_purchase_create"; @endphp
                        @endif

                        @foreach ($lot_contractor[$lot_kind]->$owners as $owner_key => $owner)
                            @if ($owner->pj_property_owners_id == $lot_contractor->pj_property_owner_id)
                                @php $inc++; @endphp
                                @if( $inc <= $lot_contractors['rowspan'])

                                @if($inc > 1)
                            <tr>
                                @endif

                                <!-- start - owner name -->
                                <td>
                                    <div class="form-group">
                                        <input style="min-width: 100%" class="form-control form-control-w-lg form-control-sm"
                                            type="text" value="{{ $owner->property_owner->name }}" readonly="readonly"
                                        >
                                    </div>
                                </td>
                                <!-- end - owner name -->

                                <!-- start - lot type -->
                                <td>
                                    <div class="form-group">
                                        <input style="min-width: 100%" class="form-control form-control-w-lg form-control-sm"
                                            @if ($lot_kind == 'residential') value="宅地"
                                            @elseif ($lot_kind == 'road') value="道路"
                                            @elseif ($lot_kind == 'building') value="建物"
                                            @endif readonly="readonly" type="text"
                                        >
                                    </div>
                                </td>
                                <!-- end - lot type -->

                                <!-- start - lot address -->
                                <td>
                                    <div class="form-group">
                                        @php
                                        if($lot_contractor[$lot_kind]->parcel_city == -1) {
                                            $parcel_city_name  = '';
                                            $parcel_city_extra = $lot_contractor[$lot_kind]->parcel_city_extra;
                                        } else {
                                            $parcel_city_name  = $parcel_city[$lot_contractor[$lot_kind]->parcel_city];
                                            $parcel_city_extra = '';
                                        }
                                        @endphp
                                        <input style="min-width: 100%" class="form-control form-control-w-xxxl form-control-sm" type="text"
                                        @if ($lot_kind == 'building')
                                            @php
                                                $building_number_third = $lot_contractor[$lot_kind]->building_number_third ?
                                                                         "の" . $lot_contractor[$lot_kind]->building_number_third : "";
                                            @endphp
                                        value="{{ $parcel_city_name ."". $parcel_city_extra ."". $lot_contractor[$lot_kind]->parcel_town ."". $lot_contractor[$lot_kind]->parcel_number_first ."番". $lot_contractor[$lot_kind]->parcel_number_second ." (". $lot_contractor[$lot_kind]->building_number_first ."番". $lot_contractor[$lot_kind]->building_number_second . $building_number_third .")" }}"
                                    @else value="{{ $parcel_city_name ."". $parcel_city_extra ."". $lot_contractor[$lot_kind]->parcel_town ."". $lot_contractor[$lot_kind]->parcel_number_first ."番". $lot_contractor[$lot_kind]->parcel_number_second }}"
                                        @endif
                                        readonly="readonly">
                                    </div>
                                </td>
                                <!-- end - lot address -->

                                <!-- start - owner share -->
                                <td>
                                    <div class="form-group">
                                        <input class="form-control form-control-w-xs form-control-sm" type="text" value="{{ $owner->share_denom }}" readonly="readonly">
                                        分の
                                        <input class="form-control form-control-w-xs form-control-sm" type="text" value="{{ $owner->share_number }}" readonly="readonly">
                                    </div>
                                </td>
                                <!-- end - owner share -->

                                <!-- start - building use type -->
                                <td>
                                    <div class="form-group">
                                        <input style="min-width: 100%" class="form-control form-control-w-lg form-control-sm" type="text"
                                        @if ($lot_kind == 'building') value="{{ $building_usetype[$lot_contractor[$lot_kind]->building_usetype] }}"
                                        @else value=""
                                        @endif
                                        readonly="readonly">
                                    </div>
                                </td>
                                <!-- end - building use type -->

                                <!-- start - lot size -->
                                <td>
                                    <div class="form-group">
                                        @if ($lot_kind == 'building')
                                        <input class="form-control form-control-w-sm" type="text"
                                            value="{{ $lot_contractor[$lot_kind]->building_floors->sum('floor_size') }}" readonly="readonly"> m<sup>2</sup>
                                        <span>(<input class="form-control form-control-w-xs" type="text"
                                            value="{{ $lot_contractor[$lot_kind]->building_floor_count }}" readonly="readonly">階建)</span>
                                        @else
                                        <input class="form-control form-control-w-sm form-control-sm" type="text"
                                            value="{{ number_format($lot_contractor[$lot_kind]->parcel_size) }}" readonly="readonly"> m<sup>2</sup>
                                        @endif
                                    </div>
                                </td>
                                <!-- end - lot size -->

                                <!-- start - lot equity -->
                                <td>
                                    {{ $purchase_target_contractors_group_by_name[$lot_contractors_key][$lot_contractor_key]->$purchase_create->purchase_equity == 1 ? '全部' : '一部' }}
                                    @if ($purchase_target_contractors_group_by_name[$lot_contractors_key][$lot_contractor_key]->$purchase_create->purchase_equity == 2)
                                        <br><input class="form-control form-control-sm" type="text" value="{{ $purchase_target_contractors_group_by_name[$lot_contractors_key][$lot_contractor_key]->$purchase_create->purchase_equity_text }}" readonly="readonly">
                                    @endif
                                </td>
                                <!-- end - lot equity -->

                                @endif
                            @endif
                        @endforeach
                        @if ($lot_contractors['rowspan'] > 1)
                        </tr>
                        @endif
                    @endif
                @endforeach
            <!-- end - residential, road and building -->
            @endforeach
        @endforeach
    </tbody>
</table>

<table v-if="contract.seller == 2"
    class="table table-bordered table-small table-parcel-list mt-0">
    <tbody>
        <tr>
            <th>売主</th>
            <td>
                <div>

                    <!-- start - company information -->
                    <template v-if="!master_value[companies[contract.seller_broker_company_id].license_authorizer_id].value
                                    || !companies[contract.seller_broker_company_id].license_update
                                    || !companies[contract.seller_broker_company_id].license_number">
                        @{{ companies[contract.seller_broker_company_id].name }}

                        <!-- start - url to company edit page -->
                        <a :href="'/company/' + companies[contract.seller_broker_company_id].id + '/edit'" target="_blank">
                            (免許番号を入力してください)
                        </a>
                        <!-- end - url to company edit page -->

                    </template>
                    <template v-else>
                        @{{ companies[contract.seller_broker_company_id].name }}
                        (@{{ master_value[companies[contract.seller_broker_company_id].license_authorizer_id].value }}
                        @{{ companies[contract.seller_broker_company_id].license_update }}
                        @{{ companies[contract.seller_broker_company_id].license_number }})
                    </template>
                    <!-- end - company information -->

                    <br>

                    <!-- start - entry.broker_housebuilder_by_seller -->
                    <select v-model="entry.broker_housebuilder_by_seller"
                        class="form-control form-control-sm">
                        <option value="0"></option>
                        <option v-for="user in companies[contract.seller_broker_company_id].users"
                            :value="user.id">@{{ user.last_name }} @{{ user.first_name }} ((@{{ master_value[user.real_estate_notary_prefecture_id].value }})@{{ user.real_estate_notary_number }})
                        </option>
                    </select>
                    <!-- end - entry.broker_housebuilder_by_seller -->

                    <!-- start - url to user edit page -->
                    <template v-if="users[entry.broker_housebuilder_by_seller] && !users[entry.broker_housebuilder_by_seller].real_estate_notary_number">
                        <a :href="'/company/' + companies[contract.seller_broker_company_id].id + '/user/' + entry.broker_housebuilder_by_seller + '/edit'" target="_blank">
                            宅建士を入力してください
                        </a>
                    </template>
                    <!-- end - url to user edit page -->

                </div>
            </td>
        </tr>
    </tbody>
</table>

<template v-if="purchase_contract_mediations.length > 0">
    <div class="headding01"
        style=" background: #005AA0;
                color: #fff;
                text-align: center;
                padding: 0.3em 0.6em;
                margin: 0.5em 0;"> 仲介情報</div>
    <table class="table table-bordered table-small table-parcel-list mt-0">
        <thead>
            <tr>
                <th>仲介業者</th>
                <th>免許番号</th>
                <th>宅建士</th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="mediation in purchase_contract_mediations">
                <template v-if="mediation.trader_company_id">

                    <!-- start - trader company name -->
                    <td>
                        <div>@{{ companies[mediation.trader_company_id].name }}</div>
                    </td>
                    <!-- end - trader company name -->

                    <!-- start - company information -->
                    <td>
                        <template v-if="!master_value[companies[mediation.trader_company_id].license_authorizer_id].value
                                        || !companies[mediation.trader_company_id].license_update
                                        || !companies[mediation.trader_company_id].license_number">

                            <!-- start - url to company edit page -->
                            <a :href="'/company/' + companies[mediation.trader_company_id].id + '/edit'" target="_blank">
                                (免許番号を入力してください)
                            </a>
                            <!-- start - url to company edit page -->

                        </template>
                        <template v-else>
                                @{{ master_value[companies[mediation.trader_company_id].license_authorizer_id].value }}
                                @{{ companies[mediation.trader_company_id].license_update }}
                                @{{ companies[mediation.trader_company_id].license_number }}
                        </template>
                    </td>
                    <!-- end - company information -->

                    <td>

                        <!-- start - mediation.trader_company_user -->
                        <select v-model="mediation.trader_company_user"
                            class="form-control form-control-sm w-100">
                            <option value="0"></option>
                            <option v-for="user in companies[mediation.trader_company_id].users"
                                :value="user.id">@{{ user.last_name }} @{{ user.first_name }} ((@{{ master_value[user.real_estate_notary_prefecture_id].value }})@{{ user.real_estate_notary_number }})
                            </option>
                        </select>
                        <!-- end - mediation.trader_company_user -->

                        <!-- start - url to user edit page -->
                        <template v-if="users[mediation.trader_company_user] && !users[mediation.trader_company_user].real_estate_notary_number">
                            <a :href="'/company/' + companies[mediation.trader_company_id].id + '/user/' + mediation.trader_company_user + '/edit'" target="_blank">
                                宅建士を入力してください
                            </a>
                        </template>
                        <!-- end - url to user edit page -->

                    </td>
                </template>
            </tr>
        </tbody>
    </table>
</template>
