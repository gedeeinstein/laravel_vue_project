@extends('backend._base.content_project')

@section('breadcrumbs')
<ol class="breadcrumb float-sm-right">
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="fas fa-tachometer-alt mr-1"></i> @lang('label.dashboard')</a></li>
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">仕入</a></li>
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">@lang('project.base.tabs.purchase.index')</a></li>
    <li class="breadcrumb-item active">{{ $page_title }}</li>
</ol>
@endsection

@if($pj_property == null)
@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="row p-2" style="margin:0 10%;">
                <h4>仕入地番ごとの情報が作成されておりません。アシストAを入力してください。</h4>
            </div>
        </div>
    </div>
</div>
@endsection
@else
@section('preloader')
    <transition name="preloader">
        <div class="preloader preloader-fullscreen d-flex justify-content-center align-items-center" v-if="initial.loading">
            <div class="sk-folding-cube">
                <div class="sk-cube1 sk-cube"></div>
                <div class="sk-cube2 sk-cube"></div>
                <div class="sk-cube4 sk-cube"></div>
                <div class="sk-cube3 sk-cube"></div>
            </div>
        </div>
    </transition>
@endsection
@section('content')

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="row p-2">
                    <div class="col-6">
                    <form id="save_count" method="POST" data-parsley class="parsley-minimal">
                        @csrf
                        <input type="hidden" v-model="project_id">
                        <div class="form-group row">
                            <label for="" class="col-3 col-form-label"><span class="text-danger">※</span>買付数</label>
                            <template>
                            <currency-input  class="form-control col-3 input-integer" 
                                    v-model="purchase.count" id="count" type="text"
                                    :data-parsley-required="count == null" 
                                    data-parsley-trigger="change focusout"
                                    data-parsley-errors-container="#count_validation"
                                    :currency="null"
                                    :precision="{ min: 0, max: 0 }"
                                    :allow-negative="false" 
                                    @change="managePurchaseNo()" >
                            </template>
                            <button type="submit" class="btn btn-wide btn-primary px-4">
                                <i v-if="!initial.submitedCount" class="fas fa-save"></i>
                                <i v-else class="fas fa-spinner fa-spin"></i>
                                反映する
                            </button>
                            <!-- <button id="count_button" class="btn btn-primary" data-id="A71-2">反映する</button> -->
                        </div>
                        <div class="form-group row" style="margin-top:-15px;">
                            <span class="col-3 col-form-label"></span>
                            <span id="count_validation"></span>
                        </div>
                    </form>
                        <div class="form-group row">
                            <label for="" class="col-3 col-form-label">仕入予定価格</label>
                            <template>
                            <currency-input class="form-control col-3" 
                                            v-model="price_tsubo_unit" 
                                            :currency="null"
                                            :precision="{ min: 0, max: 0 }"
                                            :allow-negative="false" 
                                            readonly="readonly">           
                            </template>
                                <!-- <input class="form-control" v-model="price_tsubo_unit" type="text" value="{{number_format($price_tsubo_unit)}}" readonly="readonly"> -->
                                <div class="input-group-append">
                                    <div class="input-group-text">円</div>
                                </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- list of project owner with custom contractor -->
    @include('backend.project.purchase.form.create-contractor')

    <!-- list of custom contractor with contraction who want to purchase -->
    @include('backend.project.purchase.form.purchase')

    <div class="row">
        <div class="col-12">
            <table class="table-bordered table-small buypurchase_table ml-1 mb-2">
                <tbody><tr>
                    <th width="150px;">仕入予定価格</th>
                    <td width="150px;">
                        <template>
                        <currency-input style="min-width:80%;" id="money" class="form-control form-control-w-lg form-control-sm" 
                                v-model="price_tsubo_unit" 
                                name="purchase_schedule_price" type="text"
                                readonly="readonly"
                                :currency="null"
                                :precision="{ min: 0, max: 0 }"
                                :allow-negative="false" >
                        </template>
                     円</td>
                    <th width="150px;">買付価格合計</th>
                    <td width="150px;">
                        <template>
                        <currency-input style="min-width:80%;" v-model="total_price" id="total_price" class="form-control form-control-w-lg form-control-sm" 
                                name="purchase_price" type="text"
                                readonly="readonly"
                                :currency="null"
                                :precision="{ min: 0, max: 0 }"
                                :allow-negative="false" >
                        </template>
                     円</td>
                </tr>
            </tbody></table>
        </div>
    </div>
<form id="save_all_data" method="POST" data-parsley class="parsley-minimal">
    <div style="margin-top: 2em;" class="nav-buttons bottom text-center">
        <button type="submit" class="btn btn-wide btn-primary px-4">
            <i v-if="!initial.submitedAll" class="fas fa-save"></i>
            <i v-else class="fas fa-spinner fa-spin"></i>
            保存
        </button>
    </div>
</form>
@endsection

@push('vue-scripts')
<script>
    mixin = {
        /*
        ## ------------------------------------------------------------------
        ## VUE DATA
        ## vue data binding, difine a properties
        ## ------------------------------------------------------------------
        */
        data: function(){
            // Basic Data Assist A Init
            // --------------------------------------------------------------
            let initial = {
                submitedCount: false,
                submitedContractor: false,
                submitedAll: false,
                loading: true,
                update_url_count: '{{ $update_url["save_count"] }}',
                update_url_contractor: '{{ $update_url["create_contractor"] }}',
                update_url_all: '{{ $update_url["save_purchase"] }}',
            }

            let project_id          = '{{ $project_id }}'
            let price_tsubo_unit    = '{{ $price_tsubo_unit }}'
            let first_price         = '{{ $first_price }}'
            // data for purchase count form
            let purchase            = @json( $purchase );
            // data for create contractor form
            let property_owners     = @json( $property_owner );
            let kind_datas          = @json( $kind_data );
            let property_count      = @json( $property_count );
            let contractor          = @json( $contractor );
            let contractor_same_as_owner = @json( $contractor_same_as_owner );
            let pj_property         = @json( $pj_property );
            let kind                = @json( $kind );
            // data for create purchase card
            let contractors_list    = @json( $contractors_list );
            let have_common         = @json( $have_common );

            // Return compiled data
            // --------------------------------------------------------------
            return {
                initial: initial,
                project_id: project_id,
                purchase: purchase,
                property_owners: property_owners,
                kind_datas: kind_datas,
                property_count: property_count,
                contractor: contractor,
                contractor_same_as_owner: contractor_same_as_owner,
                pj_property: pj_property,
                kind: kind,
                contractors_list: contractors_list,
                new_contractor: [],
                exist_unregistered: [],
                total_price: 0,
                price_tsubo_unit: price_tsubo_unit,
                first_price: first_price,
                have_common: have_common,
            }
            // --------------------------------------------------------------
        },

        /*
        ## ------------------------------------------------------------------
        ## VUE MOUNTED
        ## vue on mounted state
        ## ------------------------------------------------------------------
        */
        mounted: function(){
            // set 
            this.initial.loading = false;
            // refresh parsley form validation
            refreshParsley();
            // triger event on loaded
            $(document).trigger( 'vue-loaded', this );
            // calculate total price
            this.total_price = calcTotalPrice();
            // triger delete addon purchase
            for(var i=1; i<=this.purchase.data_exist; i++) {
                $('.addon-form_'+i).each(function() {
                    deleteAddonPurchase($(this).prop('id'), false);
                });
            }
            refreshAllPropertyData();
            $('.exist_unregistered').each(function() {
                var target_data = $(this).attr('eu-id');
                if($(this).is(':checked')) {
                    $('#target_building_'+target_data).show();
                } else {
                    $('#target_building_'+target_data).hide();
                }
            });
        },

        /*
        ## ------------------------------------------------------------------
        ## VUE COMPUTED
        ## define a property with custom logic
        ## ------------------------------------------------------------------
        */
        computed: {
            
        },

        /*
        ## ------------------------------------------------------------------
        ## VUE WATCH
        ## vue reactive data watch
        ## ------------------------------------------------------------------
        */
        watch: {
            
        },

        /*
        ## ------------------------------------------------------------------
        ## VUE METHOD
        ## function associated with the vue instance
        ## ------------------------------------------------------------------
        */
        methods: {
            copyPriceTsubo() {
                this.first_price = this.price_tsubo_unit;
                this.total_price = this.price_tsubo_unit;
                $('input[name="purchase_price[1]"]').val(this.price_tsubo_unit);
                $('#total_price').val(this.price_tsubo_unit);
            },
            kindDisplay(event) {
                $('.exist_unregistered').each(function() {
                    var target_data = $(this).attr('eu-id');
                    if($(this).is(':checked')) {
                        $('#target_building_'+target_data).show();
                    } else {
                        $('#target_building_'+target_data).hide();
                    }
                });
            },
            // method for create contractor
            contractorAsOwner(key, kindKey, kindKey2, event) {
                refreshParsley();
                resetParsley();
                if(event.target.checked) {
                    if(kindKey == 'building') {
                        this.contractor[key].building[kindKey2] = this.property_owners[key].name;
                        this.contractor_same_as_owner[key].building[kindKey2] = 1;
                    } else
                    if(kindKey == 'residential') {
                        this.contractor[key].residential[kindKey2] = this.property_owners[key].name;
                        this.contractor_same_as_owner[key].residential[kindKey2] = 1;
                    } else
                    if(kindKey == 'road') {
                        this.contractor[key].road[kindKey2] = this.property_owners[key].name;
                        this.contractor_same_as_owner[key].road[kindKey2] = 1;
                    }
                } else {
                    if(kindKey == 'building') {
                        this.contractor_same_as_owner[key].building[kindKey2] = 0;
                    } else
                    if(kindKey == 'residential') {
                        this.contractor_same_as_owner[key].residential[kindKey2] = 0;
                    } else
                    if(kindKey == 'road') {
                        this.contractor_same_as_owner[key].road[kindKey2] = 0;
                    }
                }
            },
            addPurchaseContractor: function(row) {
                var i = parseInt($('.addon-form_'+row).length)+1;
                let purchase_contractor_form_addon = $('#purchase_contractor_form_'+row).clone();
		        purchase_contractor_form_addon.css('display','block');
                let formatHtml = purchase_contractor_form_addon.html();
                formatHtml = formatHtml.replace('id="olo"','id="olo_'+row+'_'+i+'"');
                formatHtml = formatHtml.replace('class="addon-form ','class="addon-form_'+row+' ');
                formatHtml = formatHtml.replace('name="target_contractor_copy"','name="target_contractor['+row+']['+i+']"');
                formatHtml = formatHtml.replace('class="purchase_contractor_form"','class="purchase_contractor_form_'+row+'_'+i+'"');
                formatHtml = formatHtml.replace('contractor_select_copy"','contractor_select_copy_row_'+row+'_inc_'+i+'"');
                formatHtml = formatHtml.replace('class="remove"','class="remove_'+row+'_'+i+'"');
                formatHtml = formatHtml.replace('class="remove"','class="remove_'+row+'_'+i+'"');
                formatHtml = formatHtml.replace('class="remove"','class="remove_'+row+'_'+i+'"');
                formatHtml = formatHtml.replace('class="remove"','class="remove_'+row+'_'+i+'"');
                formatHtml = formatHtml.replace('class="remove"','class="remove_'+row+'_'+i+'"');
                formatHtml = formatHtml.replace('class="remove"','class="remove_'+row+'_'+i+'"');
                formatHtml = formatHtml.replace('class="button"','class="button_'+row+'_'+i+'"');
                purchase_contractor_form_addon.html(formatHtml);
                $('#purchase-addon-container_'+row).append(purchase_contractor_form_addon);
                insertPropertyDataJs(row, i);
                deleteAddonPurchase('olo_'+row+'_'+i, false);
            },
            calcPrice: function() {
                this.total_price = calcTotalPrice();
            },
            copyContractor: function(key, kindKey, kindKey2, event) {
                refreshParsley();
                resetParsley();
                let contractor_code = $(event.target).attr('class').substring($(event.target).attr('class').indexOf('copyx_')+6);
                let prev_code = parseInt(contractor_code)-1;
                let f_key = $('.input_contractor_'+prev_code).attr('key-id');
                let f_kindKey = $('.input_contractor_'+prev_code).attr('kindKey-id');
                let f_kindKey2 = $('.input_contractor_'+prev_code).attr('kindKey2-id');
                if(kindKey == 'building') {
                    if(f_kindKey == 'building') {
                        this.contractor[key].building[kindKey2] = this.contractor[f_key].building[f_kindKey2];
                    } else
                    if(f_kindKey == 'residential') {
                        this.contractor[key].building[kindKey2] = this.contractor[f_key].residential[f_kindKey2];
                    } else
                    if(f_kindKey == 'road') {
                        this.contractor[key].building[kindKey2] = this.contractor[f_key].road[f_kindKey2];
                    }
                    if($('#property_owners_'+key).val() == $('.input_contractor_'+prev_code).val()) {
                        this.contractor_same_as_owner[key].building[kindKey2] = 1;
                    } else {
                        this.contractor_same_as_owner[key].building[kindKey2] = 0;
                    }
                } else
                if(kindKey == 'residential') {
                    if(f_kindKey == 'building') {
                        this.contractor[key].residential[kindKey2] = this.contractor[f_key].building[f_kindKey2];
                    } else
                    if(f_kindKey == 'residential') {
                        this.contractor[key].residential[kindKey2] = this.contractor[f_key].residential[f_kindKey2];
                    } else
                    if(f_kindKey == 'road') {
                        this.contractor[key].residential[kindKey2] = this.contractor[f_key].road[f_kindKey2];
                    }
                    if($('#property_owners_'+key).val() == $('.input_contractor_'+prev_code).val()) {
                        this.contractor_same_as_owner[key].residential[kindKey2] = 1;
                    } else {
                        this.contractor_same_as_owner[key].residential[kindKey2] = 0;
                    }
                } else
                if(kindKey == 'road') {
                    if(f_kindKey == 'building') {
                        this.contractor[key].road[kindKey2] = this.contractor[f_key].building[f_kindKey2];
                    } else
                    if(f_kindKey == 'residential') {
                        this.contractor[key].road[kindKey2] = this.contractor[f_key].residential[f_kindKey2];
                    } else
                    if(f_kindKey == 'road') {
                        this.contractor[key].road[kindKey2] = this.contractor[f_key].road[f_kindKey2];
                    }
                    if($('#property_owners_'+key).val() == $('.input_contractor_'+prev_code).val()) {
                        this.contractor_same_as_owner[key].road[kindKey2] = 1;
                    } else {
                        this.contractor_same_as_owner[key].road[kindKey2] = 0;
                    }
                }
                Vue.nextTick(function () {
                    $('#copy_contractor_'+key+'_'+kindKey+'_'+kindKey2).val($('.input_contractor_'+prev_code).val());
                    if($('#property_owners_'+key).val() == $('.input_contractor_'+prev_code).val()) {
                        $('#contractor-same-to-owner_'+key+'_'+kindKey+'_'+kindKey2).prop('checked', true);
                        $('#copy_contractor_'+key+'_'+kindKey+'_'+kindKey2).prop('readonly', true);
                    } else {
                        $('#contractor-same-to-owner_'+key+'_'+kindKey+'_'+kindKey2).prop('checked', false);
                        $('#copy_contractor_'+key+'_'+kindKey+'_'+kindKey2).prop('readonly', false);
                    }
                });
            },
            purchaseContractorSubmit: function(event, key) {
                $('input[name="x_submit"]').val(event.target.id);
                if($('#purchase_not_create_documents_'+key).prop('checked')) {
                    // display alert when not create documnet checked
                    event.preventDefault();
                    alert('「買付証明書を作成しない」がチェックされているので作成できません。');
                } else {
                    var alertx = false;
                    var msg = '';
                    var used_contractor = ',';
                    var contractor = 0;
                    // check all select contractor form
                    $('.contractor_select:visible').each(function() {
                        if($(this).val() == null || $(this).val() == 0) {
                            // if there is no selected conteactor dispay alert message
                            msg = '仕入買付情報に未入力の契約者が存在します。';
                            alertx = true;
                        } else
                        if(used_contractor.indexOf(String($(this).val())) > 0) {
                            // if there are duplicate contractor display alert message
                            msg = '仕入買付情報の契約者が重複しています。';
                            alertx = true;
                        } else
                        if($(this).val() != null && $(this).val() != 0) {
                            contractor++;
                        }
                        used_contractor += String($(this).val())+',';
                    });
                    // check purchase price input
                    var total_price = 0;
                    $('.purchase_price').each(function() {
                        if($(this).val() == '') {
                            // if there is no input show alert message
                            msg = '買付価格をすべて0にすることはできません。';
                            alertx = true;
                        } else {
                            total_price += parseInt($(this).val().replace(/,/g,''));
                        }
                    });
                    // check if total all price = 0
                    if(total_price == 0) {
                        msg = '買付価格をすべて0にすることはできません。';
                        alertx = true;
                    }
                    // check if not all contractor list selected
                    if(contractor != _.size(this.contractors_list) && !alertx) {
                        msg = '仕入買付情報の契約者に全ての契約者を入力してください。';
                        alertx = true;
                    }
                    // if there is condition above avoid saving
                    if(alertx) {
                        event.preventDefault();
                        alert(msg);
                    }
                }
            },
            createPurchaseDoc: function(event) {
                if(!$('#'+event.target.id).prop('checked')) {
                    if(confirm('買付情報を変更すると契約書の内容が変わる場合があります。本当に変更してよろしいですか？')) {
                        $('#'+event.target.id).prop('checked', false);
                    } else {
                        $('#'+event.target.id).prop('checked', true);
                    }
                }
            },
            insertPropertyData: function(n, event) {
                Vue.nextTick(function () {
                    var contractor_name = $('#'+event.target.id+' option:selected').text();
                    if(contractor_name != '') {
                        var contractor_count = 0;
                        $('.contractor_field').each(function() {
                            if($(this).val() == contractor_name) {
                                contractor_count++;
                            }
                        });
                        $('#data_property_'+n).attr('rowspan', 2);
                        $('tr[row-id="data_property_copy_'+n+'"] tr').remove();
                        $('tr[row-id="data_property_copy_'+n+'"] .remove').hide();
                        let button_purchase = $('tr[row-id="data_property_copy_'+n+'"] .button').first().clone();
                        // $('tr[row-id="data_property_copy_'+n+'"]').empty();
                        $('tr[row-id="data_property_copy_'+n+'"] .button').hide();
                        $('.contractor_field').each(function() {
                            if($(this).val() == contractor_name) {
                                var code = $(this).attr('data-id').substring(14);
                                var code1 = code.substring(0, code.indexOf('_'));
                                
                                // $('input[data-id="kind_'+n+'"]').val($('input[data-id="kind_'+code+'"]').val());
                                let purchase_contractor_form_addon = $('tr[row-id="'+$(this).attr('data-id')+'"]').first().clone();
                                purchase_contractor_form_addon.find('[el-copy="property_data_'+code1+'"]').empty();
                                purchase_contractor_form_addon.find('[el-copy="property_data_'+code1+'"]').append($('[el-id="property_data_'+code1+'"]').first().clone());
                                purchase_contractor_form_addon.append(button_purchase);
                                purchase_contractor_form_addon.find('.button').attr('rowspan', contractor_count);
                                purchase_contractor_form_addon.find('.button').attr('style', "min-width:51px");
                                purchase_contractor_form_addon.find('.button i').prop('id', 'add_purchase_row_'+n);
                                $('tr[row-id="data_property_copy_'+n+'"]').prepend(purchase_contractor_form_addon);
                            }
                        });
                        setTimeout(() => {
                            var eki = 0;
                            $('.build_'+n+':visible').remove();
                            $('tr[row-id="data_property_copy_'+n+'"]').find('.kind').each(function() {
                                if($(this).val() == '建物') {
                                    var building_data = $('tr[row-id="data_property_copy_'+n+'"]').find('.build').eq(eki).val();
                                    var build_no = building_data.substring(building_data.indexOf('('));
                                    var build_only_no = 'x'+build_no.substring(1, build_no.length-1)+'z';
                                    eki++;
                                    var build_count = $('.build_'+n).length-1;
                                    let building_number = $('#building_number_row_'+n).last().clone();
                                    building_number.css('display','');
                                    let formatHtml = building_number.html();
                                    var build_countx = build_count-1;
                                    formatHtml = formatHtml.replace('id="b_product"','id="b_product_'+n+'_'+build_count+'"');
                                    formatHtml = formatHtml.replace('id="b_demolish"','id="b_demolish_'+n+'_'+build_count+'"');
                                    formatHtml = formatHtml.replace('番','xd');
                                    formatHtml = formatHtml.replace('番','xd');
                                    formatHtml = formatHtml.replace('class="building-number-input-a ','class="building-number-input-a '+build_only_no+' ');
                                    formatHtml = formatHtml.replace('class="building-number-input-b ','class="building-number-input-b '+build_only_no+' ');
                                    formatHtml = formatHtml.replace('for="b_product"','for="b_product_'+n+'_'+build_count+'"');
                                    formatHtml = formatHtml.replace('for="b_demolish"','for="b_demolish_'+n+'_'+build_count+'"');
                                    formatHtml = formatHtml.replace('name="building_no_copy"','name="building_no['+n+']['+build_count+']"');
                                    formatHtml = formatHtml.replace('name="building_no_copy"','name="building_no['+n+']['+build_count+']"');
                                    formatHtml = formatHtml.replace('id="b_product_'+n+'_'+build_countx+'"','id="b_product_'+n+'_'+build_count+'"');
                                    formatHtml = formatHtml.replace('id="b_demolish_'+n+'_'+build_countx+'"','id="b_demolish_'+n+'_'+build_count+'"');
                                    formatHtml = formatHtml.replace('for="b_product_'+n+'_'+build_countx+'"','for="b_product_'+n+'_'+build_count+'"');
                                    formatHtml = formatHtml.replace('for="b_demolish_'+n+'_'+build_countx+'"','for="b_demolish_'+n+'_'+build_count+'"');
                                    formatHtml = formatHtml.replace('name="building_no['+n+']['+build_countx+']"','name="building_no['+n+']['+build_count+']"');
                                    formatHtml = formatHtml.replace('name="building_no['+n+']['+build_countx+']"','name="building_no['+n+']['+build_count+']"');
                                    formatHtml = formatHtml.replace('name="building_no_valx"','name="building_no_val"');
                                    building_number.html(formatHtml);
                                    $('#purchase_card_second_'+n).prepend(building_number);
                                    building_number.find('input[name="building_no_val"]').val(build_no);
                                    if($('.building-number-input-a.'+build_only_no).first().is('checked')) {
                                        $('.building-number-input-a.'+build_only_no).prop('checked', true);
                                    } else {
                                        $('.building-number-input-b.'+build_only_no).prop('checked', true);
                                    }
                                }
                            });
                            $('.purchase_contractor_form_'+n).find('tr').each(function() {
                                if($(this).attr('row-id').indexOf('building') !== -1) {
                                    var building_data = $(this).find('.build').val();
                                    var build_no = building_data.substring(building_data.indexOf('('));
                                    var build_only_no = 'x'+build_no.substring(1, build_no.length-1)+'z';
                                    var build_count = $('.build_'+n).length-1;
                                    var build_countx = build_count-1;
                                    let building_number = $('#building_number_row_'+n).first().clone();
                                    building_number.css('display','');
                                    let formatHtml = building_number.html();
                                    formatHtml = formatHtml.replace('class="parcel_th"','class="parcel_th '+$(this).attr('row-id')+'"');
                                    formatHtml = formatHtml.replace('class="parcel_th ','class="parcel_th '+$(this).attr('row-id')+' x');
                                    formatHtml = formatHtml.replace('id="b_product"','id="b_product_'+n+'_'+build_count+'"');
                                    formatHtml = formatHtml.replace('id="b_demolish"','id="b_demolish_'+n+'_'+build_count+'"');
                                    formatHtml = formatHtml.replace('番','xd');
                                    formatHtml = formatHtml.replace('番','xd');
                                    formatHtml = formatHtml.replace('class="building-number-input-a ','class="building-number-input-a '+build_only_no+' ');
                                    formatHtml = formatHtml.replace('class="building-number-input-b ','class="building-number-input-b '+build_only_no+' ');
                                    formatHtml = formatHtml.replace('for="b_product"','for="b_product_'+n+'_'+build_count+'"');
                                    formatHtml = formatHtml.replace('for="b_demolish"','for="b_demolish_'+n+'_'+build_count+'"');
                                    formatHtml = formatHtml.replace('name="building_no_copy"','name="building_no['+n+']['+build_count+']"');
                                    formatHtml = formatHtml.replace('name="building_no_copy"','name="building_no['+n+']['+build_count+']"');
                                    formatHtml = formatHtml.replace('id="b_product_'+n+'_'+build_countx+'"','id="b_product_'+n+'_'+build_count+'"');
                                    formatHtml = formatHtml.replace('id="b_demolish_'+n+'_'+build_countx+'"','id="b_demolish_'+n+'_'+build_count+'"');
                                    formatHtml = formatHtml.replace('for="b_product_'+n+'_'+build_countx+'"','for="b_product_'+n+'_'+build_count+'"');
                                    formatHtml = formatHtml.replace('for="b_demolish_'+n+'_'+build_countx+'"','for="b_demolish_'+n+'_'+build_count+'"');
                                    formatHtml = formatHtml.replace('name="building_no['+n+']['+build_countx+']"','name="building_no['+n+']['+build_count+']"');
                                    formatHtml = formatHtml.replace('name="building_no['+n+']['+build_countx+']"','name="building_no['+n+']['+build_count+']"');
                                    formatHtml = formatHtml.replace('name="building_no_valx"','name="building_no_val"');
                                    building_number.html(formatHtml);
                                    $('#purchase_card_second_'+n).prepend(building_number);
                                    building_number.find('input[name="building_no_val"]').val(build_no);
                                    if($('.building-number-input-a.'+build_only_no).first().is('checked')) {
                                        $('.building-number-input-a.'+build_only_no).prop('checked', true);
                                    } else {
                                        $('.building-number-input-b.'+build_only_no).prop('checked', true);
                                    }
                                }
                            });
                            $('.building-number-input-a').on('click', function() {
                                var building_num = $(this).prop('class').substring($(this).prop('class').indexOf('x'), $(this).prop('class').indexOf('z')+1);
                                $('.building-number-input-a.'+building_num).prop('checked', true);
                            });
                            $('.building-number-input-b').on('click', function() {
                                var building_num = $(this).prop('class').substring($(this).prop('class').indexOf('x'), $(this).prop('class').indexOf('z')+1);
                                $('.building-number-input-b.'+building_num).prop('checked', true);
                            });
                        }, 50);
                        addPurchaseContractorJs(n);
                    } else {
                        setTimeout(() => {
                            var eki = 0;
                            $('.build_'+n+':visible').remove();
                            $('tr[row-id="data_property_copy_'+n+'"]').find('.kind').each(function() {
                                if($(this).val() == '建物') {
                                    var building_data = $('tr[row-id="data_property_copy_'+n+'"]').find('.build').eq(eki).val();
                                    var build_no = building_data.substring(building_data.indexOf('('));
                                    var build_only_no = 'x'+build_no.substring(1, build_no.length-1)+'z';
                                    eki++;
                                    var build_count = $('.build_'+n).length-1;
                                    let building_number = $('#building_number_row_'+n).last().clone();
                                    building_number.css('display','');
                                    let formatHtml = building_number.html();
                                    var build_countx = build_count-1;
                                    formatHtml = formatHtml.replace('id="b_product"','id="b_product_'+n+'_'+build_count+'"');
                                    formatHtml = formatHtml.replace('id="b_demolish"','id="b_demolish_'+n+'_'+build_count+'"');
                                    formatHtml = formatHtml.replace('番','xd');
                                    formatHtml = formatHtml.replace('番','xd');
                                    formatHtml = formatHtml.replace('class="building-number-input-a ','class="building-number-input-a '+build_only_no+' ');
                                    formatHtml = formatHtml.replace('class="building-number-input-b ','class="building-number-input-b '+build_only_no+' ');
                                    formatHtml = formatHtml.replace('for="b_product"','for="b_product_'+n+'_'+build_count+'"');
                                    formatHtml = formatHtml.replace('for="b_demolish"','for="b_demolish_'+n+'_'+build_count+'"');
                                    formatHtml = formatHtml.replace('name="building_no_copy"','name="building_no['+n+']['+build_count+']"');
                                    formatHtml = formatHtml.replace('name="building_no_copy"','name="building_no['+n+']['+build_count+']"');
                                    formatHtml = formatHtml.replace('id="b_product_'+n+'_'+build_countx+'"','id="b_product_'+n+'_'+build_count+'"');
                                    formatHtml = formatHtml.replace('id="b_demolish_'+n+'_'+build_countx+'"','id="b_demolish_'+n+'_'+build_count+'"');
                                    formatHtml = formatHtml.replace('for="b_product_'+n+'_'+build_countx+'"','for="b_product_'+n+'_'+build_count+'"');
                                    formatHtml = formatHtml.replace('for="b_demolish_'+n+'_'+build_countx+'"','for="b_demolish_'+n+'_'+build_count+'"');
                                    formatHtml = formatHtml.replace('name="building_no['+n+']['+build_countx+']"','name="building_no['+n+']['+build_count+']"');
                                    formatHtml = formatHtml.replace('name="building_no['+n+']['+build_countx+']"','name="building_no['+n+']['+build_count+']"');
                                    formatHtml = formatHtml.replace('name="building_no_valx"','name="building_no_val"');
                                    building_number.html(formatHtml);
                                    $('#purchase_card_second_'+n).prepend(building_number);
                                    building_number.find('input[name="building_no_val"]').val(build_no);
                                    if($('.building-number-input-a.'+build_only_no).first().is('checked')) {
                                        $('.building-number-input-a.'+build_only_no).prop('checked', true);
                                    } else {
                                        $('.building-number-input-b.'+build_only_no).prop('checked', true);
                                    }
                                }
                            });
                            $('.purchase_contractor_form_'+n).find('tr').each(function() {
                                if($(this).attr('row-id').indexOf('building') !== -1) {
                                    var building_data = $(this).find('.build').val();
                                    var build_no = building_data.substring(building_data.indexOf('('));
                                    var build_only_no = 'x'+build_no.substring(1, build_no.length-1)+'z';
                                    var build_count = $('.build_'+n).length-1;
                                    var build_countx = build_count-1;
                                    let building_number = $('#building_number_row_'+n).first().clone();
                                    building_number.css('display','');
                                    let formatHtml = building_number.html();
                                    formatHtml = formatHtml.replace('class="parcel_th"','class="parcel_th '+$(this).attr('row-id')+'"');
                                    formatHtml = formatHtml.replace('class="parcel_th ','class="parcel_th '+$(this).attr('row-id')+' x');
                                    formatHtml = formatHtml.replace('id="b_product"','id="b_product_'+n+'_'+build_count+'"');
                                    formatHtml = formatHtml.replace('id="b_demolish"','id="b_demolish_'+n+'_'+build_count+'"');
                                    formatHtml = formatHtml.replace('番','xd');
                                    formatHtml = formatHtml.replace('番','xd');
                                    formatHtml = formatHtml.replace('class="building-number-input-a ','class="building-number-input-a '+build_only_no+' ');
                                    formatHtml = formatHtml.replace('class="building-number-input-b ','class="building-number-input-b '+build_only_no+' ');
                                    formatHtml = formatHtml.replace('for="b_product"','for="b_product_'+n+'_'+build_count+'"');
                                    formatHtml = formatHtml.replace('for="b_demolish"','for="b_demolish_'+n+'_'+build_count+'"');
                                    formatHtml = formatHtml.replace('name="building_no_copy"','name="building_no['+n+']['+build_count+']"');
                                    formatHtml = formatHtml.replace('name="building_no_copy"','name="building_no['+n+']['+build_count+']"');
                                    formatHtml = formatHtml.replace('id="b_product_'+n+'_'+build_countx+'"','id="b_product_'+n+'_'+build_count+'"');
                                    formatHtml = formatHtml.replace('id="b_demolish_'+n+'_'+build_countx+'"','id="b_demolish_'+n+'_'+build_count+'"');
                                    formatHtml = formatHtml.replace('for="b_product_'+n+'_'+build_countx+'"','for="b_product_'+n+'_'+build_count+'"');
                                    formatHtml = formatHtml.replace('for="b_demolish_'+n+'_'+build_countx+'"','for="b_demolish_'+n+'_'+build_count+'"');
                                    formatHtml = formatHtml.replace('name="building_no['+n+']['+build_countx+']"','name="building_no['+n+']['+build_count+']"');
                                    formatHtml = formatHtml.replace('name="building_no['+n+']['+build_countx+']"','name="building_no['+n+']['+build_count+']"');
                                    formatHtml = formatHtml.replace('name="building_no_valx"','name="building_no_val"');
                                    building_number.html(formatHtml);
                                    $('#purchase_card_second_'+n).prepend(building_number);
                                    building_number.find('input[name="building_no_val"]').val(build_no);
                                    if($('.building-number-input-a.'+build_only_no).first().is('checked')) {
                                        $('.building-number-input-a.'+build_only_no).prop('checked', true);
                                    } else {
                                        $('.building-number-input-b.'+build_only_no).prop('checked', true);
                                    }
                                }
                            });
                            $('.building-number-input-a').on('click', function() {
                                var building_num = $(this).prop('class').substring($(this).prop('class').indexOf('x'), $(this).prop('class').indexOf('z')+1);
                                $('.building-number-input-a.'+building_num).prop('checked', true);
                            });
                            $('.building-number-input-b').on('click', function() {
                                var building_num = $(this).prop('class').substring($(this).prop('class').indexOf('x'), $(this).prop('class').indexOf('z')+1);
                                $('.building-number-input-b.'+building_num).prop('checked', true);
                            });
                        }, 50);
                        
                        $('tr[row-id="data_property_copy_'+n+'"] tr').remove();
                        $('tr[row-id="data_property_copy_'+n+'"] .remove').show();
                        $('tr[row-id="data_property_copy_'+n+'"] .button').show();
                    }
                });
            },
            insertPropertyData2: function(n, inc, event) {
                Vue.nextTick(function () {
                    var contractor_name = $(event.target).children('option:selected').text();
                    if(contractor_name != '') {
                        var contractor_count = 0;
                        $('.contractor_field').each(function() {
                            if($(this).val() == contractor_name) {
                                contractor_count++;
                            }
                        });
                        // $('#data_property_copy_'+n+':visible').attr('rowspan', 2);
                        $('#purchase_contractor_form_'+n+' .remove_'+n+'_'+inc).hide();
                        let button_purchase = $('#purchase_contractor_form_'+n+' .button_'+n+'_'+inc).first().clone();
                        $('#purchase_contractor_form_'+n+' .button_'+n+'_'+inc).hide();
                        // $('tr[row-id="data_property_copy_copy_'+n+'"]:visible').empty();
                        // $(event.target).parent().parent().parent().find('tr').each(function() {
                        //     if($(event.target).attr('row-id').indexOf('building') > 0) {
                        //         $('.parcel_th.'+$(event.target).attr('row-id')).last().parent().remove();
                        //     }
                        // });
                        $(event.target).parent().parent().parent().find('tr').remove();
                        var x = 0;
                        var i = parseInt($('.addon-form_'+n).length)+1;
                        $('.contractor_field').each(function() {
                            if($(this).val() == contractor_name) {
                                x++;
                                var code = $(this).attr('data-id').substring(14);
                                var code1 = code.substring(0, code.indexOf('_'));
                                // $('#purchase_contractor_form_'+n+' tr').remove();
                                // $('input[data-id="kind_'+n+'"]').val($('input[data-id="kind_'+code+'"]').val());
                                let purchase_contractor_form_addon = $('tr[row-id="'+$(this).attr('data-id')+'"]').first().clone();
                                let formatHtml = purchase_contractor_form_addon.html();
                                formatHtml = formatHtml.replace('class="remove"','class="remove_'+n+'_'+inc+'"');
                                formatHtml = formatHtml.replace('class="remove"','class="remove_'+n+'_'+inc+'"');
                                formatHtml = formatHtml.replace('class="remove"','class="remove_'+n+'_'+inc+'"');
                                formatHtml = formatHtml.replace('class="remove"','class="remove_'+n+'_'+inc+'"');
                                formatHtml = formatHtml.replace('class="remove"','class="remove_'+n+'_'+inc+'"');
                                formatHtml = formatHtml.replace('class="remove"','class="remove_'+n+'_'+inc+'"');
                                purchase_contractor_form_addon.html(formatHtml);
                                purchase_contractor_form_addon.find('[el-copy="property_data_'+code1+'"]').empty();
                                purchase_contractor_form_addon.find('[el-copy="property_data_'+code1+'"]').append($('[el-id="property_data_'+code1+'"]').first().clone());
                                if(x==1) {
                                    purchase_contractor_form_addon.append(button_purchase);
                                    purchase_contractor_form_addon.find('.button_'+n+'_'+inc).attr('rowspan', contractor_count);
                                    purchase_contractor_form_addon.find('.button_'+n+'_'+inc).attr('style', "min-width:51px");
                                    purchase_contractor_form_addon.find('.button_'+n+'_'+inc+' i').prop('id', 'olo_parent_'+n+'_'+i);
                                    x++;
                                }
                                $('#purchase_contractor_form_'+n+' .purchase_contractor_form_'+n+'_'+inc).parent().append(purchase_contractor_form_addon);
                            }
                        });
                        setTimeout(() => {
                            var eki = 0;
                            $('.build_'+n+':visible').remove();
                            $('tr[row-id="data_property_copy_'+n+'"]').find('.kind').each(function() {
                                if($(this).val() == '建物') {
                                    var building_data = $('tr[row-id="data_property_copy_'+n+'"]').find('.build').eq(eki).val();
                                    var build_no = building_data.substring(building_data.indexOf('('));
                                    var build_only_no = 'x'+build_no.substring(1, build_no.length-1)+'z';
                                    eki++;
                                    var build_count = $('.build_'+n).length-1;
                                    let building_number = $('#building_number_row_'+n).last().clone();
                                    building_number.css('display','');
                                    let formatHtml = building_number.html();
                                    var build_countx = build_count-1;
                                    formatHtml = formatHtml.replace('id="b_product"','id="b_product_'+n+'_'+build_count+'"');
                                    formatHtml = formatHtml.replace('id="b_demolish"','id="b_demolish_'+n+'_'+build_count+'"');
                                    formatHtml = formatHtml.replace('番','xd');
                                    formatHtml = formatHtml.replace('番','xd');
                                    formatHtml = formatHtml.replace('class="building-number-input-a ','class="building-number-input-a '+build_only_no+' ');
                                    formatHtml = formatHtml.replace('class="building-number-input-b ','class="building-number-input-b '+build_only_no+' ');
                                    formatHtml = formatHtml.replace('for="b_product"','for="b_product_'+n+'_'+build_count+'"');
                                    formatHtml = formatHtml.replace('for="b_demolish"','for="b_demolish_'+n+'_'+build_count+'"');
                                    formatHtml = formatHtml.replace('name="building_no_copy"','name="building_no['+n+']['+build_count+']"');
                                    formatHtml = formatHtml.replace('name="building_no_copy"','name="building_no['+n+']['+build_count+']"');
                                    formatHtml = formatHtml.replace('id="b_product_'+n+'_'+build_countx+'"','id="b_product_'+n+'_'+build_count+'"');
                                    formatHtml = formatHtml.replace('id="b_demolish_'+n+'_'+build_countx+'"','id="b_demolish_'+n+'_'+build_count+'"');
                                    formatHtml = formatHtml.replace('for="b_product_'+n+'_'+build_countx+'"','for="b_product_'+n+'_'+build_count+'"');
                                    formatHtml = formatHtml.replace('for="b_demolish_'+n+'_'+build_countx+'"','for="b_demolish_'+n+'_'+build_count+'"');
                                    formatHtml = formatHtml.replace('name="building_no['+n+']['+build_countx+']"','name="building_no['+n+']['+build_count+']"');
                                    formatHtml = formatHtml.replace('name="building_no['+n+']['+build_countx+']"','name="building_no['+n+']['+build_count+']"');
                                    formatHtml = formatHtml.replace('name="building_no_valx"','name="building_no_val"');
                                    building_number.html(formatHtml);
                                    $('#purchase_card_second_'+n).prepend(building_number);
                                    building_number.find('input[name="building_no_val"]').val(build_no);
                                    if($('.building-number-input-a.'+build_only_no).first().is('checked')) {
                                        $('.building-number-input-a.'+build_only_no).prop('checked', true);
                                    } else {
                                        $('.building-number-input-b.'+build_only_no).prop('checked', true);
                                    }
                                }
                            });
                            $('.purchase_contractor_form_'+n).find('tr').each(function() {
                                if($(this).attr('row-id').indexOf('building') !== -1) {
                                    var building_data = $(this).find('.build').val();
                                    var build_no = building_data.substring(building_data.indexOf('('));
                                    var build_only_no = 'x'+build_no.substring(1, build_no.length-1)+'z';
                                    var build_count = $('.build_'+n).length-1;
                                    var build_countx = build_count-1;
                                    let building_number = $('#building_number_row_'+n).first().clone();
                                    building_number.css('display','');
                                    let formatHtml = building_number.html();
                                    formatHtml = formatHtml.replace('class="parcel_th"','class="parcel_th '+$(this).attr('row-id')+'"');
                                    formatHtml = formatHtml.replace('class="parcel_th ','class="parcel_th '+$(this).attr('row-id')+' x');
                                    formatHtml = formatHtml.replace('id="b_product"','id="b_product_'+n+'_'+build_count+'"');
                                    formatHtml = formatHtml.replace('id="b_demolish"','id="b_demolish_'+n+'_'+build_count+'"');
                                    formatHtml = formatHtml.replace('番','xd');
                                    formatHtml = formatHtml.replace('番','xd');
                                    formatHtml = formatHtml.replace('class="building-number-input-a ','class="building-number-input-a '+build_only_no+' ');
                                    formatHtml = formatHtml.replace('class="building-number-input-b ','class="building-number-input-b '+build_only_no+' ');
                                    formatHtml = formatHtml.replace('for="b_product"','for="b_product_'+n+'_'+build_count+'"');
                                    formatHtml = formatHtml.replace('for="b_demolish"','for="b_demolish_'+n+'_'+build_count+'"');
                                    formatHtml = formatHtml.replace('name="building_no_copy"','name="building_no['+n+']['+build_count+']"');
                                    formatHtml = formatHtml.replace('name="building_no_copy"','name="building_no['+n+']['+build_count+']"');
                                    formatHtml = formatHtml.replace('id="b_product_'+n+'_'+build_countx+'"','id="b_product_'+n+'_'+build_count+'"');
                                    formatHtml = formatHtml.replace('id="b_demolish_'+n+'_'+build_countx+'"','id="b_demolish_'+n+'_'+build_count+'"');
                                    formatHtml = formatHtml.replace('for="b_product_'+n+'_'+build_countx+'"','for="b_product_'+n+'_'+build_count+'"');
                                    formatHtml = formatHtml.replace('for="b_demolish_'+n+'_'+build_countx+'"','for="b_demolish_'+n+'_'+build_count+'"');
                                    formatHtml = formatHtml.replace('name="building_no['+n+']['+build_countx+']"','name="building_no['+n+']['+build_count+']"');
                                    formatHtml = formatHtml.replace('name="building_no['+n+']['+build_countx+']"','name="building_no['+n+']['+build_count+']"');
                                    formatHtml = formatHtml.replace('name="building_no_valx"','name="building_no_val"');
                                    building_number.html(formatHtml);
                                    $('#purchase_card_second_'+n).prepend(building_number);
                                    building_number.find('input[name="building_no_val"]').val(build_no);
                                    if($('.building-number-input-a.'+build_only_no).first().is('checked')) {
                                        $('.building-number-input-a.'+build_only_no).prop('checked', true);
                                    } else {
                                        $('.building-number-input-b.'+build_only_no).prop('checked', true);
                                    }
                                }
                            });
                            $('.building-number-input-a').on('click', function() {
                                var building_num = $(this).prop('class').substring($(this).prop('class').indexOf('x'), $(this).prop('class').indexOf('z')+1);
                                $('.building-number-input-a.'+building_num).prop('checked', true);
                            });
                            $('.building-number-input-b').on('click', function() {
                                var building_num = $(this).prop('class').substring($(this).prop('class').indexOf('x'), $(this).prop('class').indexOf('z')+1);
                                $('.building-number-input-b.'+building_num).prop('checked', true);
                            });
                        }, 50);
                        deleteAddonPurchase('olo_parent_'+n+'_'+i, true);
                    } else {
                        setTimeout(() => {
                        var eki = 0;
                            $('.build_'+n+':visible').remove();
                            $('tr[row-id="data_property_copy_'+n+'"]').find('.kind').each(function() {
                                if($(this).val() == '建物') {
                                    var building_data = $('tr[row-id="data_property_copy_'+n+'"]').find('.build').eq(eki).val();
                                    var build_no = building_data.substring(building_data.indexOf('('));
                                    var build_only_no = 'x'+build_no.substring(1, build_no.length-1)+'z';
                                    eki++;
                                    var build_count = $('.build_'+n).length-1;
                                    let building_number = $('#building_number_row_'+n).last().clone();
                                    building_number.css('display','');
                                    let formatHtml = building_number.html();
                                    var build_countx = build_count-1;
                                    formatHtml = formatHtml.replace('id="b_product"','id="b_product_'+n+'_'+build_count+'"');
                                    formatHtml = formatHtml.replace('id="b_demolish"','id="b_demolish_'+n+'_'+build_count+'"');
                                    formatHtml = formatHtml.replace('番','xd');
                                    formatHtml = formatHtml.replace('番','xd');
                                    formatHtml = formatHtml.replace('class="building-number-input-a ','class="building-number-input-a '+build_only_no+' ');
                                    formatHtml = formatHtml.replace('class="building-number-input-b ','class="building-number-input-b '+build_only_no+' ');
                                    formatHtml = formatHtml.replace('for="b_product"','for="b_product_'+n+'_'+build_count+'"');
                                    formatHtml = formatHtml.replace('for="b_demolish"','for="b_demolish_'+n+'_'+build_count+'"');
                                    formatHtml = formatHtml.replace('name="building_no_copy"','name="building_no['+n+']['+build_count+']"');
                                    formatHtml = formatHtml.replace('name="building_no_copy"','name="building_no['+n+']['+build_count+']"');
                                    formatHtml = formatHtml.replace('id="b_product_'+n+'_'+build_countx+'"','id="b_product_'+n+'_'+build_count+'"');
                                    formatHtml = formatHtml.replace('id="b_demolish_'+n+'_'+build_countx+'"','id="b_demolish_'+n+'_'+build_count+'"');
                                    formatHtml = formatHtml.replace('for="b_product_'+n+'_'+build_countx+'"','for="b_product_'+n+'_'+build_count+'"');
                                    formatHtml = formatHtml.replace('for="b_demolish_'+n+'_'+build_countx+'"','for="b_demolish_'+n+'_'+build_count+'"');
                                    formatHtml = formatHtml.replace('name="building_no['+n+']['+build_countx+']"','name="building_no['+n+']['+build_count+']"');
                                    formatHtml = formatHtml.replace('name="building_no['+n+']['+build_countx+']"','name="building_no['+n+']['+build_count+']"');
                                    formatHtml = formatHtml.replace('name="building_no_valx"','name="building_no_val"');
                                    building_number.html(formatHtml);
                                    $('#purchase_card_second_'+n).prepend(building_number);
                                    building_number.find('input[name="building_no_val"]').val(build_no);
                                    if($('.building-number-input-a.'+build_only_no).first().is('checked')) {
                                        $('.building-number-input-a.'+build_only_no).prop('checked', true);
                                    } else {
                                        $('.building-number-input-b.'+build_only_no).prop('checked', true);
                                    }
                                }
                            });
                            $('.purchase_contractor_form_'+n).find('tr').each(function() {
                                if($(this).attr('row-id').indexOf('building') !== -1) {
                                    var building_data = $(this).find('.build').val();
                                    var build_no = building_data.substring(building_data.indexOf('('));
                                    var build_only_no = 'x'+build_no.substring(1, build_no.length-1)+'z';
                                    var build_count = $('.build_'+n).length-1;
                                    var build_countx = build_count-1;
                                    let building_number = $('#building_number_row_'+n).first().clone();
                                    building_number.css('display','');
                                    let formatHtml = building_number.html();
                                    formatHtml = formatHtml.replace('class="parcel_th"','class="parcel_th '+$(this).attr('row-id')+'"');
                                    formatHtml = formatHtml.replace('class="parcel_th ','class="parcel_th '+$(this).attr('row-id')+' x');
                                    formatHtml = formatHtml.replace('id="b_product"','id="b_product_'+n+'_'+build_count+'"');
                                    formatHtml = formatHtml.replace('id="b_demolish"','id="b_demolish_'+n+'_'+build_count+'"');
                                    formatHtml = formatHtml.replace('番','xd');
                                    formatHtml = formatHtml.replace('番','xd');
                                    formatHtml = formatHtml.replace('class="building-number-input-a ','class="building-number-input-a '+build_only_no+' ');
                                    formatHtml = formatHtml.replace('class="building-number-input-b ','class="building-number-input-b '+build_only_no+' ');
                                    formatHtml = formatHtml.replace('for="b_product"','for="b_product_'+n+'_'+build_count+'"');
                                    formatHtml = formatHtml.replace('for="b_demolish"','for="b_demolish_'+n+'_'+build_count+'"');
                                    formatHtml = formatHtml.replace('name="building_no_copy"','name="building_no['+n+']['+build_count+']"');
                                    formatHtml = formatHtml.replace('name="building_no_copy"','name="building_no['+n+']['+build_count+']"');
                                    formatHtml = formatHtml.replace('id="b_product_'+n+'_'+build_countx+'"','id="b_product_'+n+'_'+build_count+'"');
                                    formatHtml = formatHtml.replace('id="b_demolish_'+n+'_'+build_countx+'"','id="b_demolish_'+n+'_'+build_count+'"');
                                    formatHtml = formatHtml.replace('for="b_product_'+n+'_'+build_countx+'"','for="b_product_'+n+'_'+build_count+'"');
                                    formatHtml = formatHtml.replace('for="b_demolish_'+n+'_'+build_countx+'"','for="b_demolish_'+n+'_'+build_count+'"');
                                    formatHtml = formatHtml.replace('name="building_no['+n+']['+build_countx+']"','name="building_no['+n+']['+build_count+']"');
                                    formatHtml = formatHtml.replace('name="building_no['+n+']['+build_countx+']"','name="building_no['+n+']['+build_count+']"');
                                    formatHtml = formatHtml.replace('name="building_no_valx"','name="building_no_val"');
                                    building_number.html(formatHtml);
                                    $('#purchase_card_second_'+n).prepend(building_number);
                                    building_number.find('input[name="building_no_val"]').val(build_no);
                                    if($('.building-number-input-a.'+build_only_no).first().is('checked')) {
                                        $('.building-number-input-a.'+build_only_no).prop('checked', true);
                                    } else {
                                        $('.building-number-input-b.'+build_only_no).prop('checked', true);
                                    }
                                }
                            });
                            $('.building-number-input-a').on('click', function() {
                                var building_num = $(this).prop('class').substring($(this).prop('class').indexOf('x'), $(this).prop('class').indexOf('z')+1);
                                $('.building-number-input-a.'+building_num).prop('checked', true);
                            });
                            $('.building-number-input-b').on('click', function() {
                                var building_num = $(this).prop('class').substring($(this).prop('class').indexOf('x'), $(this).prop('class').indexOf('z')+1);
                                $('.building-number-input-b.'+building_num).prop('checked', true);
                            });
                        }, 50);
                        $(event.target).parent().parent().parent().find('tr').remove();
                        $('#purchase_contractor_form_'+n+' .remove_'+n+'_'+inc).show();
                        $('#purchase_contractor_form_'+n+' .button_'+n+'_'+inc).show();
                    }
                });
            },  
            displayAllContraction: function(event) {
                event.preventDefault();
                var contractor_all = ',';
                var contractor_list = [];
                var contractor_list_count = [];
                if($('.contractor_select option').last().text() != '') {
                    $('.purchase_contractor_form_1:visible').remove();
                    $('.contractor_field').each(function() {
                        
                        if(contractor_all.indexOf($(this).val()) == -1) {
                            contractor_all += $(this).val()+',';
                            contractor_list.push($(this).val());
                            contractor_list_count.push(1);

                            if(contractor_list.length > 1) {
                                var row = 1;
                                var i = parseInt($('.addon-form_'+row).length)+1;
                                let purchase_contractor_form_addon = $('#purchase_contractor_form_'+row).clone();
                                purchase_contractor_form_addon.css('display','block');
                                let formatHtml = purchase_contractor_form_addon.html();
                                formatHtml = formatHtml.replace('id="olo"','id="olo_'+row+'_'+i+'"');
                                formatHtml = formatHtml.replace('class="addon-form ','class="addon-form_'+row+' ');
                                formatHtml = formatHtml.replace('name="target_contractor_copy"','name="target_contractor['+row+']['+i+']"');
                                formatHtml = formatHtml.replace('class="purchase_contractor_form"','class="purchase_contractor_form_'+row+'_'+i+'"');
                                formatHtml = formatHtml.replace('contractor_select_copy"','contractor_select_copy_row_'+row+'_inc_'+i+'"');
                                formatHtml = formatHtml.replace('class="remove"','class="remove_'+row+'_'+i+'"');
                                formatHtml = formatHtml.replace('class="remove"','class="remove_'+row+'_'+i+'"');
                                formatHtml = formatHtml.replace('class="remove"','class="remove_'+row+'_'+i+'"');
                                formatHtml = formatHtml.replace('class="remove"','class="remove_'+row+'_'+i+'"');
                                formatHtml = formatHtml.replace('class="remove"','class="remove_'+row+'_'+i+'"');
                                formatHtml = formatHtml.replace('class="remove"','class="remove_'+row+'_'+i+'"');
                                formatHtml = formatHtml.replace('class="button"','class="button_'+row+'_'+i+'"');
                                purchase_contractor_form_addon.html(formatHtml);
                                $('#purchase-addon-container_'+row).append(purchase_contractor_form_addon);
                                insertPropertyDataJs(row, i);
                                // $('#olo_'+row+'_'+i).removeClass('text-danger');
                                deleteAddonPurchase('olo_'+row+'_'+i, false);
                            }

                        } else {
                            for(var i=0; i<contractor_list.length; i++) {
                                if(contractor_list[i] == $(this).val()) {
                                    contractor_list_count[i]++;
                                }
                            }
                        }
                    });
                }
                for(var i=0; i<contractor_list.length; i++) {
                    let select_el = $('.contractor_select').eq(i);
                    select_el.prop('selectedIndex', i);
                    var contractor_name = select_el.children('option').eq(i+1).text();
                    var contractor_count = 0;
                    var n = 1;
                    if(i == 0) {
                        if(contractor_name != '') {
                            $('.contractor_field').each(function() {
                                if($(this).val() == contractor_name) {
                                    contractor_count++;
                                }
                            });
                            $('#data_property_'+n).attr('rowspan', 2);
                            $('tr[row-id="data_property_copy_'+n+'"] tr').remove();
                            $('tr[row-id="data_property_copy_'+n+'"] .remove').hide();
                            let button_purchase = $('tr[row-id="data_property_copy_'+n+'"] .button').first().clone();
                            // $('tr[row-id="data_property_copy_'+n+'"]').empty();
                            $('tr[row-id="data_property_copy_'+n+'"] .button').hide();
                            $('.contractor_field').each(function() {
                                if($(this).val() == contractor_name) {
                                    var code = $(this).attr('data-id').substring(14);
                                    var code1 = code.substring(0, code.indexOf('_'));
                                    
                                    // $('input[data-id="kind_'+n+'"]').val($('input[data-id="kind_'+code+'"]').val());
                                    let purchase_contractor_form_addon = $('tr[row-id="'+$(this).attr('data-id')+'"]').first().clone();
                                    purchase_contractor_form_addon.find('[el-copy="property_data_'+code1+'"]').empty();
                                    purchase_contractor_form_addon.find('[el-copy="property_data_'+code1+'"]').append($('[el-id="property_data_'+code1+'"]').first().clone());
                                    purchase_contractor_form_addon.append(button_purchase);
                                    purchase_contractor_form_addon.find('.button').attr('rowspan', contractor_count);
                                    purchase_contractor_form_addon.find('.button').attr('style', "min-width:51px");
                                    purchase_contractor_form_addon.find('.button i').prop('id', 'add_purchase_row_'+n);
                                    $('tr[row-id="data_property_copy_'+n+'"]').prepend(purchase_contractor_form_addon);
                                }
                            });
                            addPurchaseContractorJs(n);
                        } else {
                            $('.build_'+n+':visible').remove();
                            $('tr[row-id="data_property_copy_'+n+'"] tr').remove();
                            $('tr[row-id="data_property_copy_'+n+'"] .remove').show();
                            $('tr[row-id="data_property_copy_'+n+'"] .button').show();
                        }
                    } else {
                        if(contractor_name != '') {
                            var ix = parseInt($('.addon-form_'+n).length);
                            var inc = i;
                            
                            $('.contractor_field').each(function() {
                                if($(this).val() == contractor_name) {
                                    contractor_count++;
                                }
                            });
                            // $('#data_property_copy_'+n+':visible').attr('rowspan', 2);
                            let button_purchase = $('#purchase_contractor_form_'+n+' .button_'+n+'_'+inc).first().clone();
                            $('#purchase_contractor_form_'+n+' .remove_'+n+'_'+inc).hide();
                            $('#purchase_contractor_form_'+n+' .button_'+n+'_'+inc).hide();
                            
                            // $('tr[row-id="data_property_copy_copy_'+n+'"]:visible').empty();
                            var x = 0;
                            
                            $('.contractor_field').each(function() {
                                if($(this).val() == contractor_name) {
                                    x++;
                                    var code = $(this).attr('data-id').substring(14);
                                    var code1 = code.substring(0, code.indexOf('_'));
                                    // $('#purchase_contractor_form_'+n+' tr').remove();
                                    // $('input[data-id="kind_'+n+'"]').val($('input[data-id="kind_'+code+'"]').val());
                                    let purchase_contractor_form_addon = $('tr[row-id="'+$(this).attr('data-id')+'"]').first().clone();
                                    let formatHtml = purchase_contractor_form_addon.html();
                                    formatHtml = formatHtml.replace('class="remove"','class="remove_'+n+'_'+inc+'"');
                                    formatHtml = formatHtml.replace('class="remove"','class="remove_'+n+'_'+inc+'"');
                                    formatHtml = formatHtml.replace('class="remove"','class="remove_'+n+'_'+inc+'"');
                                    formatHtml = formatHtml.replace('class="remove"','class="remove_'+n+'_'+inc+'"');
                                    formatHtml = formatHtml.replace('class="remove"','class="remove_'+n+'_'+inc+'"');
                                    formatHtml = formatHtml.replace('class="remove"','class="remove_'+n+'_'+inc+'"');
                                    purchase_contractor_form_addon.html(formatHtml);
                                    purchase_contractor_form_addon.find('[el-copy="property_data_'+code1+'"]').empty();
                                    purchase_contractor_form_addon.find('[el-copy="property_data_'+code1+'"]').append($('[el-id="property_data_'+code1+'"]').first().clone());
                                    if(x==1) {
                                        purchase_contractor_form_addon.append(button_purchase);
                                        purchase_contractor_form_addon.find('.button_'+n+'_'+inc).attr('rowspan', contractor_count);
                                        purchase_contractor_form_addon.find('.button_'+n+'_'+inc).attr('style', "min-width:51px");
                                        purchase_contractor_form_addon.find('.button_'+n+'_'+inc+' i').prop('id', 'olo_parent_'+n+'_'+i);
                                        x++;
                                    }
                                    $('#purchase_contractor_form_'+n+' .purchase_contractor_form_'+n+'_'+inc).parent().append(purchase_contractor_form_addon);
                                }
                            });
                            // $('#olo_parent_'+n+'_'+ix).removeClass('text-danger');
                            deleteAddonPurchase('olo_parent_'+n+'_'+inc, true);

                        } else {
                            // $('.build_'+n+':visible').remove();
                            $(this).parent().parent().parent().find('tr').remove();
                            $('#purchase_contractor_form_'+n+' .remove_'+n+'_'+inc).show();
                            $('#purchase_contractor_form_'+n+' .button_'+n+'_'+inc).show();
                        }
                    }
                    setTimeout(() => {
                        var eki = 0;
                        $('.build_'+n+':visible').remove();
                        $('tr[row-id="data_property_copy_'+n+'"]').find('.kind').each(function() {
                            if($(this).val() == '建物') {
                                var building_data = $('tr[row-id="data_property_copy_'+n+'"]').find('.build').eq(eki).val();
                                var build_no = building_data.substring(building_data.indexOf('('));
                                var build_only_no = 'x'+build_no.substring(1, build_no.length-1)+'z';
                                eki++;
                                var build_count = $('.build_'+n).length-1;
                                let building_number = $('#building_number_row_'+n).last().clone();
                                building_number.css('display','');
                                let formatHtml = building_number.html();
                                var build_countx = build_count-1;
                                formatHtml = formatHtml.replace('id="b_product"','id="b_product_'+n+'_'+build_count+'"');
                                formatHtml = formatHtml.replace('id="b_demolish"','id="b_demolish_'+n+'_'+build_count+'"');
                                formatHtml = formatHtml.replace('番','xd');
                                formatHtml = formatHtml.replace('番','xd');
                                formatHtml = formatHtml.replace('class="building-number-input-a ','class="building-number-input-a '+build_only_no+' ');
                                formatHtml = formatHtml.replace('class="building-number-input-b ','class="building-number-input-b '+build_only_no+' ');
                                formatHtml = formatHtml.replace('for="b_product"','for="b_product_'+n+'_'+build_count+'"');
                                formatHtml = formatHtml.replace('for="b_demolish"','for="b_demolish_'+n+'_'+build_count+'"');
                                formatHtml = formatHtml.replace('name="building_no_copy"','name="building_no['+n+']['+build_count+']"');
                                formatHtml = formatHtml.replace('name="building_no_copy"','name="building_no['+n+']['+build_count+']"');
                                formatHtml = formatHtml.replace('id="b_product_'+n+'_'+build_countx+'"','id="b_product_'+n+'_'+build_count+'"');
                                formatHtml = formatHtml.replace('id="b_demolish_'+n+'_'+build_countx+'"','id="b_demolish_'+n+'_'+build_count+'"');
                                formatHtml = formatHtml.replace('for="b_product_'+n+'_'+build_countx+'"','for="b_product_'+n+'_'+build_count+'"');
                                formatHtml = formatHtml.replace('for="b_demolish_'+n+'_'+build_countx+'"','for="b_demolish_'+n+'_'+build_count+'"');
                                formatHtml = formatHtml.replace('name="building_no['+n+']['+build_countx+']"','name="building_no['+n+']['+build_count+']"');
                                formatHtml = formatHtml.replace('name="building_no['+n+']['+build_countx+']"','name="building_no['+n+']['+build_count+']"');
                                formatHtml = formatHtml.replace('name="building_no_valx"','name="building_no_val"');
                                building_number.html(formatHtml);
                                building_number.find('input[name="building_no_val"]').val(build_no);
                                $('#purchase_card_second_'+n).prepend(building_number);
                                if($('.building-number-input-a.'+build_only_no).first().is('checked')) {
                                    $('.building-number-input-a.'+build_only_no).prop('checked', true);
                                } else {
                                    $('.building-number-input-b.'+build_only_no).prop('checked', true);
                                }
                            }
                        });
                        $('.purchase_contractor_form_'+n).find('tr').each(function() {
                            if($(this).attr('row-id').indexOf('building') !== -1) {
                                var building_data = $(this).find('.build').val();
                                var build_no = building_data.substring(building_data.indexOf('('));
                                var build_only_no = 'x'+build_no.substring(1, build_no.length-1)+'z';
                                var build_count = $('.build_'+n).length-1;
                                var build_countx = build_count-1;
                                let building_number = $('#building_number_row_'+n).first().clone();
                                building_number.css('display','');
                                let formatHtml = building_number.html();
                                formatHtml = formatHtml.replace('class="parcel_th"','class="parcel_th '+$(this).attr('row-id')+'"');
                                formatHtml = formatHtml.replace('class="parcel_th ','class="parcel_th '+$(this).attr('row-id')+' x');
                                formatHtml = formatHtml.replace('id="b_product"','id="b_product_'+n+'_'+build_count+'"');
                                formatHtml = formatHtml.replace('id="b_demolish"','id="b_demolish_'+n+'_'+build_count+'"');
                                formatHtml = formatHtml.replace('番','xd');
                                formatHtml = formatHtml.replace('番','xd');
                                formatHtml = formatHtml.replace('class="building-number-input-a ','class="building-number-input-a '+build_only_no+' ');
                                formatHtml = formatHtml.replace('class="building-number-input-b ','class="building-number-input-b '+build_only_no+' ');
                                formatHtml = formatHtml.replace('for="b_product"','for="b_product_'+n+'_'+build_count+'"');
                                formatHtml = formatHtml.replace('for="b_demolish"','for="b_demolish_'+n+'_'+build_count+'"');
                                formatHtml = formatHtml.replace('name="building_no_copy"','name="building_no['+n+']['+build_count+']"');
                                formatHtml = formatHtml.replace('name="building_no_copy"','name="building_no['+n+']['+build_count+']"');
                                formatHtml = formatHtml.replace('id="b_product_'+n+'_'+build_countx+'"','id="b_product_'+n+'_'+build_count+'"');
                                formatHtml = formatHtml.replace('id="b_demolish_'+n+'_'+build_countx+'"','id="b_demolish_'+n+'_'+build_count+'"');
                                formatHtml = formatHtml.replace('for="b_product_'+n+'_'+build_countx+'"','for="b_product_'+n+'_'+build_count+'"');
                                formatHtml = formatHtml.replace('for="b_demolish_'+n+'_'+build_countx+'"','for="b_demolish_'+n+'_'+build_count+'"');
                                formatHtml = formatHtml.replace('name="building_no['+n+']['+build_countx+']"','name="building_no['+n+']['+build_count+']"');
                                formatHtml = formatHtml.replace('name="building_no['+n+']['+build_countx+']"','name="building_no['+n+']['+build_count+']"');
                                formatHtml = formatHtml.replace('name="building_no_valx"','name="building_no_val"');
                                building_number.html(formatHtml);
                                building_number.find('input[name="building_no_val"]').val(build_no);
                                $('#purchase_card_second_'+n).prepend(building_number);
                                if($('.building-number-input-a.'+build_only_no).first().is('checked')) {
                                    $('.building-number-input-a.'+build_only_no).prop('checked', true);
                                } else {
                                    $('.building-number-input-b.'+build_only_no).prop('checked', true);
                                }
                            }
                        });
                        $('.building-number-input-a').on('click', function() {
                            var building_num = $(this).prop('class').substring($(this).prop('class').indexOf('x'), $(this).prop('class').indexOf('z')+1);
                            $('.building-number-input-a.'+building_num).prop('checked', true);
                        });
                        $('.building-number-input-b').on('click', function() {
                            var building_num = $(this).prop('class').substring($(this).prop('class').indexOf('x'), $(this).prop('class').indexOf('z')+1);
                            $('.building-number-input-b.'+building_num).prop('checked', true);
                        });
                    }, 50);
                }
                let select_el = $('.contractor_select').eq(contractor_list.length);
                select_el.prop('selectedIndex', contractor_list.length);
            },
            managePurchaseNo: function() {
                if( $('#count').val() < this.purchase.data_exist ) {
                    var i = 0;
                    this.purchase.data_exist = $('#count').val();
                    $('.purchase_no').each(function() {
                        i++;
                        if(i > $('#count').val()) {
                            $(this).remove();
                        }
                    });
                    this.total_price = calcTotalPrice();
                }
            }
        }
    }

    /*
    ## ----------------------------------------------------------------------
    ## VUE LOADED EVENT
    ## Handle submit data and form validation
    ## ----------------------------------------------------------------------
    */
    $(document).on('vue-loaded', function( event, vm ){
        // init parsley form validation
        // ------------------------------------------------------------------
        let $form = $('#save_count');
        let form = $form.parsley();
        
        // ------------------------------------------------------------------
        // On purchase count form submit
        // ------------------------------------------------------------------
        form.on( 'form:validated', function(){
            // on form not valid
            // --------------------------------------------------------------
            let valid = form.isValid();
            if( !valid ) setTimeout( function(){
                var $errors = $('.parsley-errors-list.filled').first();
                animateScroll( $errors );
            });
            // --------------------------------------------------------------

            // on valid form
            // --------------------------------------------------------------
            else {
                
                // compile post data
                // ----------------------------------------------------------
                let data = {
                    project_id: vm.project_id,
                    count: vm.purchase.count,
                };
                // ----------------------------------------------------------

                // set loading state
                // ----------------------------------------------------------
                vm.initial.submitedCount = true;

                // handle update request
                // ----------------------------------------------------------
                let url = vm.initial.update_url_count;
                let req_update = axios.post(url, data);
                // ----------------------------------------------------------

                // handle success response
                // ----------------------------------------------------------
                req_update.then(function (response) {
                    // show toast success message
                    $.toast({
                        heading: '成功', icon: 'success',
                        position: 'top-right', stack: false, hideAfter: 4000,
                        text: '編集した内容を保存しました。',
                        position: { right: 18, top: 68 }
                    });  
                })
                // ----------------------------------------------------------

                // handle error response
                // ----------------------------------------------------------
                req_update.catch(function (error) {
                    $.toast({
                        heading: '失敗', icon: 'error',
                        position: 'top-right', stack: false, hideAfter: 4000,
                        text: [error.response.data.message, error.response.data.error],
                        position: { right: 18, top: 68 }
                    });
                });
                // ----------------------------------------------------------

                // always execute function
                // ----------------------------------------------------------
                req_update.finally(function () {
                    vm.initial.submitedCount = false;
                });
                // ----------------------------------------------------------
            }
            // --------------------------------------------------------------
        }).on('form:submit', function(){ return false });

        // init parsley form validation
        // ------------------------------------------------------------------
        let $form_contractor = $('#save_contractor_data');
        let form_contractor = $form_contractor.parsley();
        
        // ------------------------------------------------------------------
        // On contractor form submit
        // ------------------------------------------------------------------
        form_contractor.on( 'form:validated', function(){
            // on form not valid
            // --------------------------------------------------------------
            let valid = form_contractor.isValid();
            if( !valid ) setTimeout( function(){
                var $errors = $('.parsley-errors-list.filled').first();
                animateScroll( $errors );
            });
            // --------------------------------------------------------------

            // on valid form
            // --------------------------------------------------------------
            else {
                // compile post data
                // ----------------------------------------------------------
                let data = {
                    project_id: vm.project_id,
                    property_id: vm.pj_property.id,
                    contractor_name: vm.contractor,
                    contractor_same_to_owner: vm.contractor_same_as_owner,
                    common: vm.kind,
                    property_owners: vm.property_owners,
                    have_common: vm.have_common,
                };
                // ----------------------------------------------------------

                // set loading state
                // ----------------------------------------------------------
                vm.initial.submitedContractor = true;

                // handle update request
                // ----------------------------------------------------------
                let url = vm.initial.update_url_contractor;
                let req_update = axios.post(url, data);
                // ----------------------------------------------------------

                // handle success response
                // ----------------------------------------------------------
                req_update.then(function (response) {
                    // show toast success message
                    $.toast({
                        heading: '成功', icon: 'success',
                        position: 'top-right', stack: false, hideAfter: 4000,
                        text: '編集した内容を保存しました。',
                        position: { right: 18, top: 68 }
                    }); 
                })
                // ----------------------------------------------------------

                // handle error response
                // ----------------------------------------------------------
                req_update.catch(function (error) {
                    $.toast({
                        heading: '失敗', icon: 'error',
                        position: 'top-right', stack: false, hideAfter: 4000,
                        text: [error.response.data.message, error.response.data.error],
                        position: { right: 18, top: 68 }
                    });
                });
                // ----------------------------------------------------------

                // always execute function
                // ----------------------------------------------------------
                req_update.finally(function () {
                    vm.initial.submitedContractor = false;
                    axios.get('{{ $api_url }}')
                        .then(res => {
                            vm.contractors_list = res.data.contractors_list;
                        }).then(res => {
                            refreshAllPropertyData();
                        }).catch(e => {});
                });
                // ----------------------------------------------------------
                
            }
            // --------------------------------------------------------------
        }).on('form:submit', function(){ return false });

        // init parsley form validation
        // ------------------------------------------------------------------
        let $form_purchase_card = $('#save_contractor');
        let form_purchase_card = $form_purchase_card.parsley();
        
        // ------------------------------------------------------------------
        // On contractor form submit
        // ------------------------------------------------------------------
        form_purchase_card.on( 'form:validated', function(){
            vm.initial.submitedAll = true;
            var alertx = false;
            var msg = '';
            var used_contractor = ',';
            var contractor = 0;
            // check all select contractor form
            $('.contractor_select:visible').each(function() {
                if($(this).val() == null || $(this).val() == 0) {
                    // if there is no selected conteactor dispay alert message
                    msg = '仕入買付情報に未入力の契約者が存在します。';
                    alertx = true;
                } else
                if(used_contractor.indexOf(String($(this).val())) > 0) {
                    // if there are duplicate contractor display alert message
                    msg = '仕入買付情報の契約者が重複しています。';
                    alertx = true;
                } else
                if($(this).val() != null && $(this).val() != 0) {
                    contractor++;
                }
                used_contractor += String($(this).val())+',';
            });
            // check purchase price input
            var total_price = 0;
            $('.purchase_price').each(function() {
                if($(this).val() == '') {
                    // if there is no input show alert message
                    msg = '買付価格をすべて0にすることはできません。';
                    alertx = true;
                } else {
                    total_price += parseInt($(this).val().replace(/,/g,''));
                }
            });
            // check if total all price = 0
            if(total_price == 0) {
                msg = '買付価格をすべて0にすることはできません。';
                alertx = true;
            }
            // check if not all contractor list selected
            if(contractor != _.size(vm.contractors_list) && !alertx) {
                msg = '仕入買付情報の契約者に全ての契約者を入力してください。';
                alertx = true;
            }

            // on form not valid
            // --------------------------------------------------------------
            let valid = form_purchase_card.isValid();
            if( !valid ) {
                setTimeout( function(){
                var $errors = $('.parsley-errors-list.filled').first();
                animateScroll( $errors );
                vm.initial.submitedAll = false;
            });
            } else
            if( alertx ) {
                alert(msg);
                vm.initial.submitedAll = false;
            }
            // --------------------------------------------------------------

            // on valid form
            // --------------------------------------------------------------
            else {
                // compile post data
                // ----------------------------------------------------------
                let datas = {
                    count: vm.purchase.count,
                    project_id: vm.project_id,
                    property_id: vm.pj_property.id,
                    contractor_name: vm.contractor,
                    contractor_same_to_owner: vm.contractor_same_as_owner,
                    common: vm.kind,
                    property_owners: vm.property_owners,
                    have_common: vm.have_common,
                };
                // ----------------------------------------------------------

                // set loading state
                // ----------------------------------------------------------
                vm.initial.submitedAll = true;

                // handle update request
                // ----------------------------------------------------------
                let url1 = vm.initial.update_url_count;
                let req_update1 = axios.post(url1, datas);

                // handle update request
                // ----------------------------------------------------------
                let url2 = vm.initial.update_url_contractor;
                let req_update = axios.post(url2, datas);
                // ----------------------------------------------------------

                // handle error response
                // ----------------------------------------------------------
                req_update.catch(function (error) {
                    $.toast({
                        heading: '失敗', icon: 'error',
                        position: 'top-right', stack: false, hideAfter: 4000,
                        text: [error.response.data.message, error.response.data.error],
                        position: { right: 18, top: 68 }
                    });
                });
                // ----------------------------------------------------------

                // always execute function
                // ----------------------------------------------------------
                req_update.finally(function () {
                    vm.initial.submitedContractor = false;
                    axios.get('{{ $api_url }}')
                        .then(res => {
                            vm.contractors_list = res.data.contractors_list;
                        }).then(res => {
                            var alertx = false;
                            var msg = '';
                            var used_contractor = ',';
                            var contractor = 0;
                            // check all select contractor form
                            $('.contractor_select:visible').each(function() {
                                if($(this).val() == null || $(this).val() == 0) {
                                    // if there is no selected conteactor dispay alert message
                                    msg = '仕入買付情報に未入力の契約者が存在します。';
                                    alertx = true;
                                } else
                                if(used_contractor.indexOf(String($(this).val())) > 0) {
                                    // if there are duplicate contractor display alert message
                                    msg = '仕入買付情報の契約者が重複しています。';
                                    alertx = true;
                                } else
                                if($(this).val() != null && $(this).val() != 0) {
                                    contractor++;
                                }
                                used_contractor += String($(this).val())+',';
                            });
                            // check purchase price input
                            var total_price = 0;
                            $('.purchase_price').each(function() {
                                if($(this).val() == '') {
                                    // if there is no input show alert message
                                    msg = '買付価格をすべて0にすることはできません。';
                                    alertx = true;
                                } else {
                                    total_price += parseInt($(this).val().replace(/,/g,''));
                                }
                            });
                            // check if total all price = 0
                            if(total_price == 0) {
                                msg = '買付価格をすべて0にすることはできません。';
                                alertx = true;
                            }
                            // check if not all contractor list selected
                            if(contractor != _.size(vm.contractors_list) && !alertx) {
                                msg = '仕入買付情報の契約者に全ての契約者を入力してください。';
                                alertx = true;
                            }
                            // if there is condition above avoid saving
                            if(alertx) {
                                alert(msg);
                            } else {

                                setTimeout(() => {
                                    $.ajax({
                                        url: vm.initial.update_url_all,
                                        type: "POST",
                                        data: $('#save_contractor').serialize(),
                                        error: function() {
                                            // handle error response
                                            // ----------------------------------------------------------
                                            req_update1.catch(function (error) {
                                                $.toast({
                                                    heading: '失敗', icon: 'error',
                                                    position: 'top-right', stack: false, hideAfter: 4000,
                                                    text: [error.response.datas.message, error.response.datas.error],
                                                    position: { right: 18, top: 68 }
                                                });
                                            });
                                            // ----------------------------------------------------------
                                        },
                                        success: function(res) {
                                            // ----------------------------------------------------------
                                            window.location.href = res.newUrl;
                                            // ----------------------------------------------------------
                                        },
                                        complete: function(res){
                                            console.log(res);
                                            // always execute function
                                            // ----------------------------------------------------------
                                            req_update1.finally(function () {
                                                vm.initial.submitedAll = false;
                                            });
                                            // ----------------------------------------------------------
                                        }
                                    });
                                }, 500);
                            }
                        }).catch(e => {});
                        vm.initial.submitedAll = false;
                });
        
            }
            // --------------------------------------------------------------
        }).on('form:submit', function(){ return false });
        
        // init parsley form validation
        // ------------------------------------------------------------------
        let $form_all = $('#save_all_data');
        let form_all = $form_all.parsley();
        
        // ------------------------------------------------------------------
        // On contractor form submit
        // ------------------------------------------------------------------
        form_all.on( 'form:validated', function(){

            var alertx = false;
            var msg = '';
            var used_contractor = ',';
            var contractor = 0;
            // check all select contractor form
            $('.contractor_select:visible').each(function() {
                if($(this).val() == null || $(this).val() == 0) {
                    // if there is no selected conteactor dispay alert message
                    msg = '仕入買付情報に未入力の契約者が存在します。';
                    alertx = true;
                } else
                if(used_contractor.indexOf(String($(this).val())) > 0) {
                    // if there are duplicate contractor display alert message
                    msg = '仕入買付情報の契約者が重複しています。';
                    alertx = true;
                } else
                if($(this).val() != null && $(this).val() != 0) {
                    contractor++;
                }
                used_contractor += String($(this).val())+',';
            });
            // check purchase price input
            var total_price = 0;
            $('.purchase_price').each(function() {
                if($(this).val() == '') {
                    // if there is no input show alert message
                    msg = '買付価格をすべて0にすることはできません。';
                    alertx = true;
                } else {
                    total_price += parseInt($(this).val().replace(/,/g,''));
                }
            });
            // check if total all price = 0
            if(total_price == 0) {
                msg = '買付価格をすべて0にすることはできません。';
                alertx = true;
            }
            // check if not all contractor list selected
            if(contractor != _.size(vm.contractors_list) && !alertx) {
                msg = '仕入買付情報の契約者に全ての契約者を入力してください。';
                alertx = true;
            }

            // on form not valid
            // --------------------------------------------------------------
            let valid = form_all.isValid();
            if( !valid ) {
                setTimeout( function(){
                var $errors = $('.parsley-errors-list.filled').first();
                animateScroll( $errors );
            });
            } else
            if( alertx ) {
                event.preventDefault();
                alert(msg);
            }
            // --------------------------------------------------------------

            // on valid form
            // --------------------------------------------------------------
            else {
                // compile post data
                // ----------------------------------------------------------
                let datas = {
                    count: vm.purchase.count,
                    project_id: vm.project_id,
                    property_id: vm.pj_property.id,
                    contractor_name: vm.contractor,
                    contractor_same_to_owner: vm.contractor_same_as_owner,
                    common: vm.kind,
                    property_owners: vm.property_owners,
                    have_common: vm.have_common,
                };
                // ----------------------------------------------------------

                // set loading state
                // ----------------------------------------------------------
                vm.initial.submitedAll = true;

                // handle update request
                // ----------------------------------------------------------
                let url1 = vm.initial.update_url_count;
                let req_update1 = axios.post(url1, datas);

                // handle update request
                // ----------------------------------------------------------
                let url2 = vm.initial.update_url_contractor;
                let req_update = axios.post(url2, datas);
                // ----------------------------------------------------------

                // handle error response
                // ----------------------------------------------------------
                req_update.catch(function (error) {
                    $.toast({
                        heading: '失敗', icon: 'error',
                        position: 'top-right', stack: false, hideAfter: 4000,
                        text: [error.response.data.message, error.response.data.error],
                        position: { right: 18, top: 68 }
                    });
                });
                // ----------------------------------------------------------

                // always execute function
                // ----------------------------------------------------------
                req_update.then(function () {
                    vm.initial.submitedContractor = false;
                    axios.get('{{ $api_url }}')
                        .then(res => {
                            vm.contractors_list = res.data.contractors_list;
                        }).then(res => {
                            var alertx = false;
                            var msg = '';
                            var used_contractor = ',';
                            var contractor = 0;
                            // check all select contractor form
                            $('.contractor_select:visible').each(function() {
                                if($(this).val() == null || $(this).val() == 0) {
                                    // if there is no selected conteactor dispay alert message
                                    msg = '仕入買付情報に未入力の契約者が存在します。';
                                    alertx = true;
                                } else
                                if(used_contractor.indexOf(String($(this).val())) > 0) {
                                    // if there are duplicate contractor display alert message
                                    msg = '仕入買付情報の契約者が重複しています。';
                                    alertx = true;
                                } else
                                if($(this).val() != null && $(this).val() != 0) {
                                    contractor++;
                                }
                                used_contractor += String($(this).val())+',';
                            });
                            // check purchase price input
                            var total_price = 0;
                            $('.purchase_price').each(function() {
                                if($(this).val() == '') {
                                    // if there is no input show alert message
                                    msg = '買付価格をすべて0にすることはできません。';
                                    alertx = true;
                                } else {
                                    total_price += parseInt($(this).val().replace(/,/g,''));
                                }
                            });
                            // check if total all price = 0
                            if(total_price == 0) {
                                msg = '買付価格をすべて0にすることはできません。';
                                alertx = true;
                            }
                            // check if not all contractor list selected
                            if(contractor != _.size(vm.contractors_list) && !alertx) {
                                msg = '仕入買付情報の契約者に全ての契約者を入力してください。';
                                alertx = true;
                            }
                            // if there is condition above avoid saving
                            if(alertx) {
                                event.preventDefault();
                                alert(msg);
                            } else {
                                // ----------------------------------------------------------
                                // var url = window.location.href
                                // var arr = url.split("/");
                                // var resultUrl = arr[0] + "//" + arr[2] + "/" + arr[3]
                                $.ajax({
                                    url: vm.initial.update_url_all,
                                    type: "POST",
                                    data: $('#save_contractor').serialize(),
                                    error: function() {
                                        // handle error response
                                        // ----------------------------------------------------------
                                        req_update1.catch(function (error) {
                                            $.toast({
                                                heading: '失敗', icon: 'error',
                                                position: 'top-right', stack: false, hideAfter: 4000,
                                                text: [error.response.datas.message, error.response.datas.error],
                                                position: { right: 18, top: 68 }
                                            });
                                        });
                                        // ----------------------------------------------------------
                                    },
                                    success: function() {
                                        // handle success response
                                        // ----------------------------------------------------------
                                        req_update1.then(function (response) {
                                            // show toast success message
                                            $.toast({
                                                heading: '成功', icon: 'success',
                                                position: 'top-right', stack: false, hideAfter: 4000,
                                                text: '編集した内容を保存しました。',
                                                position: { right: 18, top: 68 }
                                            }); 
                                        });
                                        // ----------------------------------------------------------
                                    },
                                    complete: function(){
                                        // always execute function
                                        // ----------------------------------------------------------
                                        req_update1.finally(function () {
                                            vm.initial.submitedAll = false;
                                        });
                                        // ----------------------------------------------------------
                                    }
                                });
                            }
                        }).then(res => {
                            refreshAllPropertyData();
                        }).catch(e => {});
                        vm.initial.submitedAll = false;
                });
        
            }
            // --------------------------------------------------------------
        }).on('form:submit', function(){ return false });
        
    });

    function deleteAddonPurchase(el, addon) {
        if(addon) {
            $('#'+el).on('click', function() {
                $(this).parent().parent().parent().remove();
                setTimeout(() => {
                    refreshAllPropertyData();
                }, 100);
            });
        } else {
            $('#'+el).on('click', function() {
                $(this).parent().parent().remove();
                setTimeout(() => {
                    refreshAllPropertyData();
                }, 100);
            });
        }
    }

    function calcTotalPrice() {
        var total = 0;
        $('.purchase_price').each(function() {
            if($(this).val() != null && $(this).val() != '') {
                total += parseInt($(this).val().replace(/,/g,''));
            }
        });
        return total;
    }

    function addPurchaseContractorJs(x) {
        $('#add_purchase_row_'+x).on('click', function(e) {
            var row = e.target.id.substring(17);
            var i = parseInt($('.addon-form_'+row).length)+1;
            let purchase_contractor_form_addon = $('#purchase_contractor_form_'+row).clone();
            purchase_contractor_form_addon.css('display','block');
            let formatHtml = purchase_contractor_form_addon.html();
            formatHtml = formatHtml.replace('id="olo"','id="olo_'+row+'_'+i+'"');
            formatHtml = formatHtml.replace('class="addon-form ','class="addon-form_'+row+' ');
            formatHtml = formatHtml.replace('name="target_contractor_copy"','name="target_contractor['+row+']['+i+']"');
            formatHtml = formatHtml.replace('class="purchase_contractor_form"','class="purchase_contractor_form_'+row+'_'+i+'"');
            formatHtml = formatHtml.replace('contractor_select_copy"','contractor_select_copy_row_'+row+'_inc_'+i+'"');
            formatHtml = formatHtml.replace('class="remove"','class="remove_'+row+'_'+i+'"');
            formatHtml = formatHtml.replace('class="remove"','class="remove_'+row+'_'+i+'"');
            formatHtml = formatHtml.replace('class="remove"','class="remove_'+row+'_'+i+'"');
            formatHtml = formatHtml.replace('class="remove"','class="remove_'+row+'_'+i+'"');
            formatHtml = formatHtml.replace('class="remove"','class="remove_'+row+'_'+i+'"');
            formatHtml = formatHtml.replace('class="remove"','class="remove_'+row+'_'+i+'"');
            formatHtml = formatHtml.replace('class="button"','class="button_'+row+'_'+i+'"');
            purchase_contractor_form_addon.html(formatHtml);
            $('#purchase-addon-container_'+row).append(purchase_contractor_form_addon);
            insertPropertyDataJs(row, i);
            deleteAddonPurchase('olo_'+row+'_'+i, false);
        });
    }

    function insertPropertyDataJs(row, i) {
        $('.contractor_select_copy_row_'+row+'_inc_'+i).on('change', function(e) {
            var n = e.target.id.substring(25);
            var inc = $(this).prop('class').substring($(this).prop('class').indexOf('inc_')+4);
            var contractor_name = $(this).children('option:selected').text();
            if(contractor_name != '') {
                var contractor_count = 0;
                $('.contractor_field').each(function() {
                    if($(this).val() == contractor_name) {
                        contractor_count++;
                    }
                });
                // $('#data_property_copy_'+n+':visible').attr('rowspan', 2);
                $('#purchase_contractor_form_'+n+' .remove_'+n+'_'+inc).hide();
                let button_purchase = $('#purchase_contractor_form_'+n+' .button_'+n+'_'+inc).first().clone();
                $('#purchase_contractor_form_'+n+' .button_'+n+'_'+inc).hide();
                // $('tr[row-id="data_property_copy_copy_'+n+'"]:visible').empty();
                $(this).parent().parent().parent().find('tr').each(function() {
                    if($(this).attr('row-id').indexOf('building') > 0) {
                        $('.parcel_th.'+$(this).attr('row-id')).last().parent().remove();
                    }
                });
                $(this).parent().parent().parent().find('tr').remove();
                var x = 0;
                var i = parseInt($('.addon-form_'+n).length)+1;
                $('.contractor_field').each(function() {
                    if($(this).val() == contractor_name) {
                        x++;
                        var code = $(this).attr('data-id').substring(14);
                        var code1 = code.substring(0, code.indexOf('_'));
                        // $('input[data-id="kind_'+n+'"]').val($('input[data-id="kind_'+code+'"]').val());
                        let purchase_contractor_form_addon = $('tr[row-id="'+$(this).attr('data-id')+'"]').first().clone();
                        let formatHtml = purchase_contractor_form_addon.html();
                        formatHtml = formatHtml.replace('class="remove"','class="remove_'+n+'_'+inc+'"');
                        formatHtml = formatHtml.replace('class="remove"','class="remove_'+n+'_'+inc+'"');
                        formatHtml = formatHtml.replace('class="remove"','class="remove_'+n+'_'+inc+'"');
                        formatHtml = formatHtml.replace('class="remove"','class="remove_'+n+'_'+inc+'"');
                        formatHtml = formatHtml.replace('class="remove"','class="remove_'+n+'_'+inc+'"');
                        formatHtml = formatHtml.replace('class="remove"','class="remove_'+n+'_'+inc+'"');
                        purchase_contractor_form_addon.html(formatHtml);
                        purchase_contractor_form_addon.find('[el-copy="property_data_'+code1+'"]').empty();
                        purchase_contractor_form_addon.find('[el-copy="property_data_'+code1+'"]').append($('[el-id="property_data_'+code1+'"]').first().clone());
                        if(x==1) {
                            purchase_contractor_form_addon.append(button_purchase);
                            purchase_contractor_form_addon.find('.button_'+n+'_'+inc).attr('rowspan', contractor_count);
                            purchase_contractor_form_addon.find('.button_'+n+'_'+inc).attr('style', "min-width:51px");
                            purchase_contractor_form_addon.find('.button_'+n+'_'+inc+' i').prop('id', 'olo_parent_'+n+'_'+i);
                            x++;
                        }
                        $('#purchase_contractor_form_'+n+' .purchase_contractor_form_'+n+'_'+inc).parent().append(purchase_contractor_form_addon);
                    }
                });
                setTimeout(() => {
                    var eki = 0;
                    $('.build_'+n+':visible').remove();
                    $('tr[row-id="data_property_copy_'+n+'"]').find('.kind').each(function() {
                        if($(this).val() == '建物') {
                            var building_data = $('tr[row-id="data_property_copy_'+n+'"]').find('.build').eq(eki).val();
                            var build_no = building_data.substring(building_data.indexOf('('));
                            var build_only_no = 'x'+build_no.substring(1, build_no.length-1)+'z';
                            eki++;
                            var build_count = $('.build_'+n).length-1;
                            let building_number = $('#building_number_row_'+n).last().clone();
                            building_number.css('display','');
                            let formatHtml = building_number.html();
                            var build_countx = build_count-1;
                            formatHtml = formatHtml.replace('id="b_product"','id="b_product_'+n+'_'+build_count+'"');
                            formatHtml = formatHtml.replace('id="b_demolish"','id="b_demolish_'+n+'_'+build_count+'"');
                            formatHtml = formatHtml.replace('番','xd');
                            formatHtml = formatHtml.replace('番','xd');
                            formatHtml = formatHtml.replace('class="building-number-input-a ','class="building-number-input-a '+build_only_no+' ');
                            formatHtml = formatHtml.replace('class="building-number-input-b ','class="building-number-input-b '+build_only_no+' ');
                            formatHtml = formatHtml.replace('for="b_product"','for="b_product_'+n+'_'+build_count+'"');
                            formatHtml = formatHtml.replace('for="b_demolish"','for="b_demolish_'+n+'_'+build_count+'"');
                            formatHtml = formatHtml.replace('name="building_no_copy"','name="building_no['+n+']['+build_count+']"');
                            formatHtml = formatHtml.replace('name="building_no_copy"','name="building_no['+n+']['+build_count+']"');
                            formatHtml = formatHtml.replace('id="b_product_'+n+'_'+build_countx+'"','id="b_product_'+n+'_'+build_count+'"');
                            formatHtml = formatHtml.replace('id="b_demolish_'+n+'_'+build_countx+'"','id="b_demolish_'+n+'_'+build_count+'"');
                            formatHtml = formatHtml.replace('for="b_product_'+n+'_'+build_countx+'"','for="b_product_'+n+'_'+build_count+'"');
                            formatHtml = formatHtml.replace('for="b_demolish_'+n+'_'+build_countx+'"','for="b_demolish_'+n+'_'+build_count+'"');
                            formatHtml = formatHtml.replace('name="building_no['+n+']['+build_countx+']"','name="building_no['+n+']['+build_count+']"');
                            formatHtml = formatHtml.replace('name="building_no['+n+']['+build_countx+']"','name="building_no['+n+']['+build_count+']"');
                            formatHtml = formatHtml.replace('name="building_no_valx"','name="building_no_val"');
                            building_number.html(formatHtml);
                            $('#purchase_card_second_'+n).prepend(building_number);
                            building_number.find('input[name="building_no_val"]').val(build_no);
                            if($('.building-number-input-a.'+build_only_no).first().is('checked')) {
                                $('.building-number-input-a.'+build_only_no).prop('checked', true);
                            } else {
                                $('.building-number-input-b.'+build_only_no).prop('checked', true);
                            }
                        }
                    });
                    $('.purchase_contractor_form_'+n).find('tr').each(function() {
                        if($(this).attr('row-id').indexOf('building') !== -1) {
                            var building_data = $(this).find('.build').val();
                            var build_no = building_data.substring(building_data.indexOf('('));
                            var build_only_no = 'x'+build_no.substring(1, build_no.length-1)+'z';
                            var build_count = $('.build_'+n).length-1;
                            var build_countx = build_count-1;
                            let building_number = $('#building_number_row_'+n).first().clone();
                            building_number.css('display','');
                            let formatHtml = building_number.html();
                            formatHtml = formatHtml.replace('class="parcel_th"','class="parcel_th '+$(this).attr('row-id')+'"');
                            formatHtml = formatHtml.replace('class="parcel_th ','class="parcel_th '+$(this).attr('row-id')+' x');
                            formatHtml = formatHtml.replace('id="b_product"','id="b_product_'+n+'_'+build_count+'"');
                            formatHtml = formatHtml.replace('id="b_demolish"','id="b_demolish_'+n+'_'+build_count+'"');
                            formatHtml = formatHtml.replace('番','xd');
                            formatHtml = formatHtml.replace('番','xd');
                            formatHtml = formatHtml.replace('class="building-number-input-a ','class="building-number-input-a '+build_only_no+' ');
                            formatHtml = formatHtml.replace('class="building-number-input-b ','class="building-number-input-b '+build_only_no+' ');
                            formatHtml = formatHtml.replace('for="b_product"','for="b_product_'+n+'_'+build_count+'"');
                            formatHtml = formatHtml.replace('for="b_demolish"','for="b_demolish_'+n+'_'+build_count+'"');
                            formatHtml = formatHtml.replace('name="building_no_copy"','name="building_no['+n+']['+build_count+']"');
                            formatHtml = formatHtml.replace('name="building_no_copy"','name="building_no['+n+']['+build_count+']"');
                            formatHtml = formatHtml.replace('id="b_product_'+n+'_'+build_countx+'"','id="b_product_'+n+'_'+build_count+'"');
                            formatHtml = formatHtml.replace('id="b_demolish_'+n+'_'+build_countx+'"','id="b_demolish_'+n+'_'+build_count+'"');
                            formatHtml = formatHtml.replace('for="b_product_'+n+'_'+build_countx+'"','for="b_product_'+n+'_'+build_count+'"');
                            formatHtml = formatHtml.replace('for="b_demolish_'+n+'_'+build_countx+'"','for="b_demolish_'+n+'_'+build_count+'"');
                            formatHtml = formatHtml.replace('name="building_no['+n+']['+build_countx+']"','name="building_no['+n+']['+build_count+']"');
                            formatHtml = formatHtml.replace('name="building_no['+n+']['+build_countx+']"','name="building_no['+n+']['+build_count+']"');
                            formatHtml = formatHtml.replace('name="building_no_valx"','name="building_no_val"');
                            building_number.html(formatHtml);
                            $('#purchase_card_second_'+n).prepend(building_number);
                            building_number.find('input[name="building_no_val"]').val(build_no);
                            if($('.building-number-input-a.'+build_only_no).first().is('checked')) {
                                $('.building-number-input-a.'+build_only_no).prop('checked', true);
                            } else {
                                $('.building-number-input-b.'+build_only_no).prop('checked', true);
                            }
                        }
                    });
                    $('.building-number-input-a').on('click', function() {
                        var building_num = $(this).prop('class').substring($(this).prop('class').indexOf('x'), $(this).prop('class').indexOf('z')+1);
                        $('.building-number-input-a.'+building_num).prop('checked', true);
                    });
                    $('.building-number-input-b').on('click', function() {
                        var building_num = $(this).prop('class').substring($(this).prop('class').indexOf('x'), $(this).prop('class').indexOf('z')+1);
                        $('.building-number-input-b.'+building_num).prop('checked', true);
                    });
                }, 50);
                deleteAddonPurchase('olo_parent_'+n+'_'+i, true);
            } else {
                setTimeout(() => {
                    var eki = 0;
                    $('.build_'+n+':visible').remove();
                    $('tr[row-id="data_property_copy_'+n+'"]').find('.kind').each(function() {
                        if($(this).val() == '建物') {
                            var building_data = $('tr[row-id="data_property_copy_'+n+'"]').find('.build').eq(eki).val();
                            var build_no = building_data.substring(building_data.indexOf('('));
                            var build_only_no = 'x'+build_no.substring(1, build_no.length-1)+'z';
                            eki++;
                            var build_count = $('.build_'+n).length-1;
                            let building_number = $('#building_number_row_'+n).last().clone();
                            building_number.css('display','');
                            let formatHtml = building_number.html();
                            var build_countx = build_count-1;
                            formatHtml = formatHtml.replace('id="b_product"','id="b_product_'+n+'_'+build_count+'"');
                            formatHtml = formatHtml.replace('id="b_demolish"','id="b_demolish_'+n+'_'+build_count+'"');
                            formatHtml = formatHtml.replace('番','xd');
                            formatHtml = formatHtml.replace('番','xd');
                            formatHtml = formatHtml.replace('class="building-number-input-a ','class="building-number-input-a '+build_only_no+' ');
                            formatHtml = formatHtml.replace('class="building-number-input-b ','class="building-number-input-b '+build_only_no+' ');
                            formatHtml = formatHtml.replace('for="b_product"','for="b_product_'+n+'_'+build_count+'"');
                            formatHtml = formatHtml.replace('for="b_demolish"','for="b_demolish_'+n+'_'+build_count+'"');
                            formatHtml = formatHtml.replace('name="building_no_copy"','name="building_no['+n+']['+build_count+']"');
                            formatHtml = formatHtml.replace('name="building_no_copy"','name="building_no['+n+']['+build_count+']"');
                            formatHtml = formatHtml.replace('id="b_product_'+n+'_'+build_countx+'"','id="b_product_'+n+'_'+build_count+'"');
                            formatHtml = formatHtml.replace('id="b_demolish_'+n+'_'+build_countx+'"','id="b_demolish_'+n+'_'+build_count+'"');
                            formatHtml = formatHtml.replace('for="b_product_'+n+'_'+build_countx+'"','for="b_product_'+n+'_'+build_count+'"');
                            formatHtml = formatHtml.replace('for="b_demolish_'+n+'_'+build_countx+'"','for="b_demolish_'+n+'_'+build_count+'"');
                            formatHtml = formatHtml.replace('name="building_no['+n+']['+build_countx+']"','name="building_no['+n+']['+build_count+']"');
                            formatHtml = formatHtml.replace('name="building_no['+n+']['+build_countx+']"','name="building_no['+n+']['+build_count+']"');
                            formatHtml = formatHtml.replace('name="building_no_valx"','name="building_no_val"');
                            building_number.html(formatHtml);
                            $('#purchase_card_second_'+n).prepend(building_number);
                            building_number.find('input[name="building_no_val"]').val(build_no);
                            if($('.building-number-input-a.'+build_only_no).first().is('checked')) {
                                $('.building-number-input-a.'+build_only_no).prop('checked', true);
                            } else {
                                $('.building-number-input-b.'+build_only_no).prop('checked', true);
                            }
                        }
                    });
                    $('.purchase_contractor_form_'+n).find('tr').each(function() {
                        if($(this).attr('row-id').indexOf('building') !== -1) {
                            var building_data = $(this).find('.build').val();
                            var build_no = building_data.substring(building_data.indexOf('('));
                            var build_only_no = 'x'+build_no.substring(1, build_no.length-1)+'z';
                            var build_count = $('.build_'+n).length-1;
                            var build_countx = build_count-1;
                            let building_number = $('#building_number_row_'+n).first().clone();
                            building_number.css('display','');
                            let formatHtml = building_number.html();
                            formatHtml = formatHtml.replace('class="parcel_th"','class="parcel_th '+$(this).attr('row-id')+'"');
                            formatHtml = formatHtml.replace('class="parcel_th ','class="parcel_th '+$(this).attr('row-id')+' x');
                            formatHtml = formatHtml.replace('id="b_product"','id="b_product_'+n+'_'+build_count+'"');
                            formatHtml = formatHtml.replace('id="b_demolish"','id="b_demolish_'+n+'_'+build_count+'"');
                            formatHtml = formatHtml.replace('番','xd');
                            formatHtml = formatHtml.replace('番','xd');
                            formatHtml = formatHtml.replace('class="building-number-input-a ','class="building-number-input-a '+build_only_no+' ');
                            formatHtml = formatHtml.replace('class="building-number-input-b ','class="building-number-input-b '+build_only_no+' ');
                            formatHtml = formatHtml.replace('for="b_product"','for="b_product_'+n+'_'+build_count+'"');
                            formatHtml = formatHtml.replace('for="b_demolish"','for="b_demolish_'+n+'_'+build_count+'"');
                            formatHtml = formatHtml.replace('name="building_no_copy"','name="building_no['+n+']['+build_count+']"');
                            formatHtml = formatHtml.replace('name="building_no_copy"','name="building_no['+n+']['+build_count+']"');
                            formatHtml = formatHtml.replace('id="b_product_'+n+'_'+build_countx+'"','id="b_product_'+n+'_'+build_count+'"');
                            formatHtml = formatHtml.replace('id="b_demolish_'+n+'_'+build_countx+'"','id="b_demolish_'+n+'_'+build_count+'"');
                            formatHtml = formatHtml.replace('for="b_product_'+n+'_'+build_countx+'"','for="b_product_'+n+'_'+build_count+'"');
                            formatHtml = formatHtml.replace('for="b_demolish_'+n+'_'+build_countx+'"','for="b_demolish_'+n+'_'+build_count+'"');
                            formatHtml = formatHtml.replace('name="building_no['+n+']['+build_countx+']"','name="building_no['+n+']['+build_count+']"');
                            formatHtml = formatHtml.replace('name="building_no['+n+']['+build_countx+']"','name="building_no['+n+']['+build_count+']"');
                            formatHtml = formatHtml.replace('name="building_no_valx"','name="building_no_val"');
                            building_number.html(formatHtml);
                            $('#purchase_card_second_'+n).prepend(building_number);
                            building_number.find('input[name="building_no_val"]').val(build_no);
                            if($('.building-number-input-a.'+build_only_no).first().is('checked')) {
                                $('.building-number-input-a.'+build_only_no).prop('checked', true);
                            } else {
                                $('.building-number-input-b.'+build_only_no).prop('checked', true);
                            }
                        }
                    });
                    $('.building-number-input-a').on('click', function() {
                        var building_num = $(this).prop('class').substring($(this).prop('class').indexOf('x'), $(this).prop('class').indexOf('z')+1);
                        $('.building-number-input-a.'+building_num).prop('checked', true);
                    });
                    $('.building-number-input-b').on('click', function() {
                        var building_num = $(this).prop('class').substring($(this).prop('class').indexOf('x'), $(this).prop('class').indexOf('z')+1);
                        $('.building-number-input-b.'+building_num).prop('checked', true);
                    });
                }, 50);
                $(this).parent().parent().parent().find('tr').remove();
                $('#purchase_contractor_form_'+n+' .remove_'+n+'_'+inc).show();
                $('#purchase_contractor_form_'+n+' .button_'+n+'_'+inc).show();
            }
        });
    }

    function refreshAllPropertyData() {
        $('.contractor_select.asc').each(function() {
            var contractor_name = $('#'+$(this).prop('id')+' option:selected').text();
            var n = $(this).attr('code-id');
            if(contractor_name != '') {
                var contractor_count = 0;
                $('.contractor_field').each(function() {
                    if($(this).val() == contractor_name) {
                        contractor_count++;
                    }
                });
                $('#data_property_'+n).attr('rowspan', 2);
                $('tr[row-id="data_property_copy_'+n+'"] tr').remove();
                $('tr[row-id="data_property_copy_'+n+'"] .remove').hide();
                let button_purchase = $('tr[row-id="data_property_copy_'+n+'"] .button').first().clone();
                // $('tr[row-id="data_property_copy_'+n+'"]').empty();
                $('tr[row-id="data_property_copy_'+n+'"] .button').hide();
                $('.contractor_field').each(function() {
                    if($(this).val() == contractor_name) {
                        var code = $(this).attr('data-id').substring(14);
                        var code1 = code.substring(0, code.indexOf('_'));
                        
                        // $('input[data-id="kind_'+n+'"]').val($('input[data-id="kind_'+code+'"]').val());
                        let purchase_contractor_form_addon = $('tr[row-id="'+$(this).attr('data-id')+'"]').first().clone();
                        purchase_contractor_form_addon.find('[el-copy="property_data_'+code1+'"]').empty();
                        purchase_contractor_form_addon.find('[el-copy="property_data_'+code1+'"]').append($('[el-id="property_data_'+code1+'"]').first().clone());
                        purchase_contractor_form_addon.append(button_purchase);
                        purchase_contractor_form_addon.find('.button').attr('rowspan', contractor_count);
                        purchase_contractor_form_addon.find('.button').attr('style', "min-width:51px");
                        purchase_contractor_form_addon.find('.button i').prop('id', 'add_purchase_row_'+n);
                        $('tr[row-id="data_property_copy_'+n+'"]').prepend(purchase_contractor_form_addon);
                    }
                });
                var eki = 0;
                $('.build_'+n+':visible').remove();
                $('tr[row-id="data_property_copy_'+n+'"]').find('.kind').each(function() {
                    if($(this).val() == '建物') {
                        var building_data = $('tr[row-id="data_property_copy_'+n+'"]').find('.build').eq(eki).val();
                        var build_no = building_data.substring(building_data.indexOf('('));
                        var build_only_no = 'x'+build_no.substring(1, build_no.length-1)+'z';
                        eki++;
                        var build_count = $('.build_'+n).length-1;
                        let building_number = $('#building_number_row_'+n).last().clone();
                        building_number.css('display','');
                        let formatHtml = building_number.html();
                        var build_countx = build_count-1;
                        formatHtml = formatHtml.replace('id="b_product"','id="b_product_'+n+'_'+build_count+'"');
                        formatHtml = formatHtml.replace('id="b_demolish"','id="b_demolish_'+n+'_'+build_count+'"');
                        formatHtml = formatHtml.replace('番','xd');
                        formatHtml = formatHtml.replace('番','xd');
                        formatHtml = formatHtml.replace('class="building-number-input-a ','class="building-number-input-a '+build_only_no+' ');
                        formatHtml = formatHtml.replace('class="building-number-input-b ','class="building-number-input-b '+build_only_no+' ');
                        formatHtml = formatHtml.replace('for="b_product"','for="b_product_'+n+'_'+build_count+'"');
                        formatHtml = formatHtml.replace('for="b_demolish"','for="b_demolish_'+n+'_'+build_count+'"');
                        formatHtml = formatHtml.replace('name="building_no_copy"','name="building_no['+n+']['+build_count+']"');
                        formatHtml = formatHtml.replace('name="building_no_copy"','name="building_no['+n+']['+build_count+']"');
                        formatHtml = formatHtml.replace('id="b_product_'+n+'_'+build_countx+'"','id="b_product_'+n+'_'+build_count+'"');
                        formatHtml = formatHtml.replace('id="b_demolish_'+n+'_'+build_countx+'"','id="b_demolish_'+n+'_'+build_count+'"');
                        formatHtml = formatHtml.replace('for="b_product_'+n+'_'+build_countx+'"','for="b_product_'+n+'_'+build_count+'"');
                        formatHtml = formatHtml.replace('for="b_demolish_'+n+'_'+build_countx+'"','for="b_demolish_'+n+'_'+build_count+'"');
                        formatHtml = formatHtml.replace('name="building_no['+n+']['+build_countx+']"','name="building_no['+n+']['+build_count+']"');
                        formatHtml = formatHtml.replace('name="building_no['+n+']['+build_countx+']"','name="building_no['+n+']['+build_count+']"');
                        formatHtml = formatHtml.replace('name="building_no_valx"','name="building_no_val"');
                        building_number.html(formatHtml);
                        building_number.find('input[name="building_no_val"]').val(build_no);
                        if($('input[name="building_no_data['+n+']['+build_count+']"]').val() == 1) {
                            building_number.find('.building-number-input-a.'+build_only_no).prop('checked', true);
                        } else {
                            building_number.find('.building-number-input-b.'+build_only_no).prop('checked', true);
                        }
                        $('#purchase_card_second_'+n).prepend(building_number);
                        
                        $('.building-number-input-a.'+build_only_no).on('click', function(e) {
                            var n_index = e.target.id.indexOf('_', 4)+1;
                            var count_index = e.target.id.indexOf('_', n_index)+1;
                            var n_data = e.target.id.substring(n_index, count_index-1);
                            var count_data = e.target.id.substring(count_index);
                            $('input[name="building_no_data['+n_data+']['+count_data+']"]').val(1);
                        });
                        $('.building-number-input-b.'+build_only_no).on('click', function(e) {
                            var n_index = e.target.id.indexOf('_', 4)+1;
                            var count_index = e.target.id.indexOf('_', n_index)+1;
                            var n_data = e.target.id.substring(n_index, count_index-1);
                            var count_data = e.target.id.substring(count_index);
                            $('input[name="building_no_data['+n_data+']['+count_data+']"]').val(2);
                        });
                    }
                });
                addPurchaseContractorJs(n);
            } else {
                $('tr[row-id="data_property_copy_'+n+'"] tr').remove();
                $('tr[row-id="data_property_copy_'+n+'"] .remove').show();
                $('tr[row-id="data_property_copy_'+n+'"] .button').show();
            }
        });
        $('.contractor_select.desc').each(function() {
            var n = $(this).attr('code-id');
            var inc = $(this).prop('class').substring($(this).prop('class').indexOf('inc_')+4);
            var contractor_name = $(this).children('option:selected').text();
            if(contractor_name != '') {
                var contractor_count = 0;
                $('.contractor_field').each(function() {
                    if($(this).val() == contractor_name) {
                        contractor_count++;
                    }
                });
                // $('#data_property_copy_'+n+':visible').attr('rowspan', 2);
                $('#purchase_contractor_form_'+n+' .remove_'+n+'_'+inc).hide();
                let button_purchase = $('#purchase_contractor_form_'+n+' .button_'+n+'_'+inc).first().clone();
                $('#purchase_contractor_form_'+n+' .button_'+n+'_'+inc).hide();
                $(this).parent().parent().parent().find('tr').each(function() {
                    if($(this).attr('row-id').indexOf('building') > -1) {
                        $('.parcel_th.'+$(this).attr('row-id')).last().parent().remove();
                    }
                });
                $(this).parent().parent().parent().find('tr').remove();
                // $('tr[row-id="data_property_copy_copy_'+n+'"]:visible').empty();
                var x = 0;
                var i = parseInt($('.addon-form_'+n).length)+1;
                $('.contractor_field').each(function() {
                    if($(this).val() == contractor_name) {
                        x++;
                        var code = $(this).attr('data-id').substring(14);
                        var code1 = code.substring(0, code.indexOf('_'));
                        // $('#purchase_contractor_form_'+n+' tr').remove();
                        // $('input[data-id="kind_'+n+'"]').val($('input[data-id="kind_'+code+'"]').val());
                        let purchase_contractor_form_addon = $('tr[row-id="'+$(this).attr('data-id')+'"]').first().clone();
                        let formatHtml = purchase_contractor_form_addon.html();
                        formatHtml = formatHtml.replace('class="remove"','class="remove_'+n+'_'+inc+'"');
                        formatHtml = formatHtml.replace('class="remove"','class="remove_'+n+'_'+inc+'"');
                        formatHtml = formatHtml.replace('class="remove"','class="remove_'+n+'_'+inc+'"');
                        formatHtml = formatHtml.replace('class="remove"','class="remove_'+n+'_'+inc+'"');
                        formatHtml = formatHtml.replace('class="remove"','class="remove_'+n+'_'+inc+'"');
                        formatHtml = formatHtml.replace('class="remove"','class="remove_'+n+'_'+inc+'"');
                        purchase_contractor_form_addon.html(formatHtml);
                        purchase_contractor_form_addon.find('[el-copy="property_data_'+code1+'"]').empty();
                        purchase_contractor_form_addon.find('[el-copy="property_data_'+code1+'"]').append($('[el-id="property_data_'+code1+'"]').first().clone());
                        if(x==1) {
                            purchase_contractor_form_addon.append(button_purchase);
                            purchase_contractor_form_addon.find('.button_'+n+'_'+inc).attr('rowspan', contractor_count);
                            purchase_contractor_form_addon.find('.button_'+n+'_'+inc).attr('style', "min-width:51px");
                            purchase_contractor_form_addon.find('.button_'+n+'_'+inc+' i').prop('id', 'olo_parent_'+n+'_'+i);
                            x++;
                        }
                        $('#purchase_contractor_form_'+n+' .purchase_contractor_form_'+n+'_'+inc).parent().append(purchase_contractor_form_addon);
                    }
                });
                // $('.build_'+n+':visible').remove();
                $(this).parent().parent().parent().find('tr').each(function() {
                    if($(this).attr('row-id').indexOf('building') !== -1) {
                        var building_data = $(this).find('.build').val();
                        var build_no = building_data.substring(building_data.indexOf('('));
                        var build_only_no = 'x'+build_no.substring(1, build_no.length-1)+'z';
                        var build_count = $('.build_'+n).length-1;
                        var build_countx = build_count-1;
                        let building_number = $('#building_number_row_'+n).first().clone();
                        building_number.css('display','');
                        let formatHtml = building_number.html();
                        formatHtml = formatHtml.replace('class="parcel_th"','class="parcel_th '+$(this).attr('row-id')+'"');
                        formatHtml = formatHtml.replace('class="parcel_th ','class="parcel_th '+$(this).attr('row-id')+' x');
                        formatHtml = formatHtml.replace('id="b_product"','id="b_product_'+n+'_'+build_count+'"');
                        formatHtml = formatHtml.replace('id="b_demolish"','id="b_demolish_'+n+'_'+build_count+'"');
                        formatHtml = formatHtml.replace('番','xd');
                        formatHtml = formatHtml.replace('番','xd');
                        formatHtml = formatHtml.replace('class="building-number-input-a ','class="building-number-input-a '+build_only_no+' ');
                        formatHtml = formatHtml.replace('class="building-number-input-b ','class="building-number-input-b '+build_only_no+' ');
                        formatHtml = formatHtml.replace('for="b_product"','for="b_product_'+n+'_'+build_count+'"');
                        formatHtml = formatHtml.replace('for="b_demolish"','for="b_demolish_'+n+'_'+build_count+'"');
                        formatHtml = formatHtml.replace('name="building_no_copy"','name="building_no['+n+']['+build_count+']"');
                        formatHtml = formatHtml.replace('name="building_no_copy"','name="building_no['+n+']['+build_count+']"');
                        formatHtml = formatHtml.replace('id="b_product_'+n+'_'+build_countx+'"','id="b_product_'+n+'_'+build_count+'"');
                        formatHtml = formatHtml.replace('id="b_demolish_'+n+'_'+build_countx+'"','id="b_demolish_'+n+'_'+build_count+'"');
                        formatHtml = formatHtml.replace('for="b_product_'+n+'_'+build_countx+'"','for="b_product_'+n+'_'+build_count+'"');
                        formatHtml = formatHtml.replace('for="b_demolish_'+n+'_'+build_countx+'"','for="b_demolish_'+n+'_'+build_count+'"');
                        formatHtml = formatHtml.replace('name="building_no['+n+']['+build_countx+']"','name="building_no['+n+']['+build_count+']"');
                        formatHtml = formatHtml.replace('name="building_no['+n+']['+build_countx+']"','name="building_no['+n+']['+build_count+']"');
                        formatHtml = formatHtml.replace('name="building_no_valx"','name="building_no_val"');
                        building_number.html(formatHtml);
                        building_number.find('input[name="building_no_val"]').val(build_no);
                        if($('input[name="building_no_data['+n+']['+build_count+']"]').val() == 1) {
                            building_number.find('.building-number-input-a.'+build_only_no).prop('checked', true);
                        } else {
                            building_number.find('.building-number-input-b.'+build_only_no).prop('checked', true);
                        }
                        $('#purchase_card_second_'+n).prepend(building_number);

                        $('.building-number-input-a.'+build_only_no).on('click', function(e) {
                            var n_index = e.target.id.indexOf('_', 4)+1;
                            var count_index = e.target.id.indexOf('_', n_index)+1;
                            var n_data = e.target.id.substring(n_index, count_index-1);
                            var count_data = e.target.id.substring(count_index);
                            $('input[name="building_no_data['+n_data+']['+count_data+']"]').val(1);
                        });
                        $('.building-number-input-b.'+build_only_no).on('click', function(e) {
                            var n_index = e.target.id.indexOf('_', 4)+1;
                            var count_index = e.target.id.indexOf('_', n_index)+1;
                            var n_data = e.target.id.substring(n_index, count_index-1);
                            var count_data = e.target.id.substring(count_index);
                            $('input[name="building_no_data['+n_data+']['+count_data+']"]').val(2);
                        });
                    }
                });
                deleteAddonPurchase('olo_parent_'+n+'_'+i, true);
            } else {
                $('#purchase_contractor_form_'+n+' .remove_'+n+'_'+inc).show();
                $('#purchase_contractor_form_'+n+' .button_'+n+'_'+inc).show();
            }
        });
        $('.building-number-input-a').on('click', function() {
            var building_num = $(this).prop('class').substring($(this).prop('class').indexOf('x'), $(this).prop('class').indexOf('z')+1);
            $('.building-number-input-a.'+building_num).prop('checked', true);
        });
        $('.building-number-input-b').on('click', function() {
            var building_num = $(this).prop('class').substring($(this).prop('class').indexOf('x'), $(this).prop('class').indexOf('z')+1);
            $('.building-number-input-b.'+building_num).prop('checked', true);
        });
    }

    // ----------------------------------------------------------------------
    // Custom function refresh validator
    // ----------------------------------------------------------------------
    var refreshParsley = function(){
        Vue.nextTick(function () {
            var $form = $('#save_count');
            $form.parsley().refresh();
            var $form_contractor = $('#save_contractor_data');
            $form_contractor.parsley().refresh();
        });
    }
    var resetParsley = function(){
        Vue.nextTick(function () {
            var $form = $('#save_count');
            $form.parsley().reset();
            var $form_contractor = $('#save_contractor_data');
            $form_contractor.parsley().reset();
        });
    }
    var refreshTooltip = function(){
        $('[data-toggle="tooltip"]').tooltip('hide');
        Vue.nextTick(function () {
            $('[data-toggle="tooltip"]').tooltip()
        });
    }
    // ----------------------------------------------------------------------
    var animateScroll = function( scroll, duration ){
        duration = duration || 800;
        var offset = 160;
        // ------------------------------------------------------------------
        if( !_.isInteger( scroll )){
            var $target = $( scroll );
            if( $target.length ) scroll = $target.offset().top;
        }
        // ------------------------------------------------------------------
        var $html = $('html');
        var scrolltop = scroll - offset;
        if( scrolltop <= 0 ) scrolltop = 0;
        // ------------------------------------------------------------------
        anime({
            targets: $html.get()[0], scrollTop: scrolltop,
            duration: duration, easing: 'linear'
        });
        // ------------------------------------------------------------------
    }
    // ----------------------------------------------------------------------

    @push('extend-parsley')
        // ------------------------------------------------------------------
        options.successClass = false;
        // ------------------------------------------------------------------
        // Exluded elements
        // ------------------------------------------------------------------
        options.excluded = 'input[type=button], input[type=submit], input[type=reset], '+
            'input[type=hidden], input.parsley-excluded, [data-parsley-excluded]';
        // ------------------------------------------------------------------
        // Finding error container
        // ------------------------------------------------------------------
        options.errorsContainer = function( field ){
            // --------------------------------------------------------------
            var formResult = '.form-result';
            var $element = $( field.$element );
            var $result = $element.siblings( formResult );
            // --------------------------------------------------------------
            if( $result.length ) return $result;
            else {
                // ----------------------------------------------------------
                var $parent = $element.parent();
                if( $parent.is('.input-group')){
                    $result = $parent.siblings( formResult );
                    if( $result.length ) return $result;
                }
                // ----------------------------------------------------------
                var $row = $element.closest('.row');
                $result = $row.siblings('.form-result');
                // ----------------------------------------------------------
                if( $result.length ) return $result;
                else {
                    // ------------------------------------------------------
                    var $group = $element.closest('.form-group');
                    $result = $group.next( formResult );
                    // ------------------------------------------------------
                    if( $result.length ) return $result;
                    // ------------------------------------------------------
                }
                // ----------------------------------------------------------
            }
            // --------------------------------------------------------------
        }
        // ------------------------------------------------------------------
    @endpush
</script>
@endpush
@endif