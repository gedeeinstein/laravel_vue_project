@extends('backend._base.content_project')
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
@section('breadcrumbs')
<ol class="breadcrumb float-sm-right">
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="fas fa-tachometer-alt mr-1"></i> @lang('label.dashboard')</a></li>
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">仕入</a></li>
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">仕入営業</a></li>
    <li class="breadcrumb-item active">{{ $page_title }}</li>
</ol>
@endsection
@section('content')
<h5 class="my-4">
    <span class="text-danger">※</span>@lang('project_purchases_sales.is_required')
    <span class="text-info">※</span>@lang('project_purchases_sales.not_entered')
</h5>
<form action="{{ $form_action }}" method="POST" data-parsley class="parsley-minimal">
    @csrf
    @include('backend.project.purchases-sales.form.whereabouts')
    <div class="row">
        @include('backend.project.purchases-sales.form.inquiry')
        @include('backend.project.purchases-sales.form.type')
    </div>
    <div class="row">
        @include('backend.project.purchases-sales.form.area')
        @include('backend.project.purchases-sales.form.amount')
    </div>
    <div class="row">
        @include('backend.project.purchases-sales.form.person-in-charge')
        @include('backend.project.purchases-sales.form.planning')
    </div>
    <div class="card card-project">
        <div class="card-header">
            <strong>@lang('project_purchases_sales.information')</strong>
        </div>
        <div class="card-body">
            @include('backend.project.purchases-sales.form.residential')
            @include('backend.project.purchases-sales.form.road')
            @include('backend.project.purchases-sales.form.building')
        </div>
    </div>
    <div class="incomplete_memo form-row p-2 bg-light">
        <div class="col-auto form-text">
            <div class="icheck-cyan d-inline mr-3">
                <input v-model="stat_check.status" class="form-check-input" type="radio" id="stat_check_1" value="1" :disabled="!initial.editable">
                <label class="form-check-label" for="stat_check_1">完</label>
            </div>
            <div class="icheck-cyan d-inline mr-3">
                <input v-model="stat_check.status" class="form-check-input" type="radio" id="stat_check_2" value="2" :disabled="!initial.editable">
                <label class="form-check-label" for="stat_check_2">未</label>
            </div>
        </div>
        <div class="col-auto form-text">
            <span class="">未完メモ：</span>
        </div>
        <div class="col-6">
            <input v-model="stat_check.memo" class="form-control" type="text" placeholder="未完となっている項目や理由を記入してください"
                :disabled="!initial.editable" data-parsley-trigger="keyup" data-parsley-maxlength="128">
        </div>
    </div>
    @if ($editable)
    <div class="mt-4 mb-4 text-center">
        <div class="row mx-n1 justify-content-center">
            <div class="px-1 col-auto">
                <button type="submit" id="input-submit" class="btn btn-wide btn-info" :disabled="status.loading">
                <i v-if="!status.loading" class="fas fa-save"></i>
                <i v-else class="fas fa-spinner fa-spin"></i>
                <span>更新</span>
                </button>
            </div>
        </div>
    </div>
    @endif
</form>
@endsection
@if(0) <script> @endif
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
                    $result = $group.children( formResult );
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
    @if(0)
</script> @endif
@push('vue-scripts')
<script>
    mixin = {
        computed: {
             // --------------------------------------------------------------
             // Master Data
             // --------------------------------------------------------------
             // define parcel_cities from master data
             master_parcel_cities: function () {
                 return this.master.regions;
             },
             master_values: function () {
                 return this.master.values;
             },
         },
        mounted() {
             let vm = this;
             // --------------------------------------------------------------
             // Set mounted status
             // --------------------------------------------------------------
             setTimeout(function () {
                 vm.status.mounted = true
             }, 1600);
             // --------------------------------------------------------------
             // Refresh form validation
             // --------------------------------------------------------------
             refreshParsley();
             // Trigger custom vue loaded event for jQuery
             // and other plugins to listen to
             // --------------------------------------------------------------
             // switch loading state
             vm.initial.loading = false;

             $(document).trigger('vue-loaded', this);

         },
         data: function () {
            // ------------------------------------------------------------------
            // initial data
            // ------------------------------------------------------------------
            let initial             = @json( $initial );
            initial.loading         = true;
            initial.editable        = @json( $editable );
            initial.radio_editable  = @json( $radio_editable );
            // -----------------------------------------------------------------
            let master = {
                values: @json( $master_values ),
                regions: @json( $master_regions ),
            }
            // -----------------------------------------------------------------
            let project = @json( $project );
            let purchase_sales = @json( $purchaseSale ) ?? initial.purchase_sale;
            purchase_sales.project_id = project.id;
            // -----------------------------------------------------------------
            let kind_in_house = @json( $kindinhouse );
            let kind_real_estate = @json( $kindrealestate );
            let organizers_real_estates = @json( $organizerrealestate );
            let buyer_staff_with_user_id = @json( $buyerStaffWithUserId );
            let stock_procurement = @json( $stock_procurement );
            let user_real_estate_notary_registration = @json( $organizerrealestate );
            let users = @json( $users );
            // -----------------------------------------------------------------
            purchase_sales.offer_route = kind_real_estate.filter(function(real_estate) {
                return real_estate.id == purchase_sales.offer_route
            })

            if (purchase_sales.offer_route)
                purchase_sales.offer_route = purchase_sales.offer_route.length == 0 ? kind_real_estate[0] : purchase_sales.offer_route[0];
            else
                purchase_sales.offer_route = kind_real_estate[0];
            // -----------------------------------------------------------------
            let purchaseSaleBuyerStaff = [
                {id: 1, user_id: null, pj_purchase_sale_id: null}
            ];
            // -----------------------------------------------------------------
            let purchaseSaleBuyerStaffMemo = [
                {id: 1, content: '', pj_purchase_sale_id: null}
            ];
            // -----------------------------------------------------------------
            let residentials = {
                data: []
            };
            // -----------------------------------------------------------------
            let roads = {
                data: []
            };
            // -----------------------------------------------------------------
            let buildings = {
                data: []
            };
            // -----------------------------------------------------------------
            let property                = initial.property;
            let residential_purchase    = initial.residential_purchase;
            let road_purchase           = initial.road_purchase;
            // -----------------------------------------------------------------
            let property_owners = [
                { id: 1, name: null }
            ];
            // -----------------------------------------------------------------
            let stat_check = {
                project_id: null, screen: 'pj_purchase_sales',
                status: 2, memo: '',
            };
            // -----------------------------------------------------------------
            let users_real_estate_notary_registration = []
            // -----------------------------------------------------------------

            // -----------------------------------------------------------------
            // assign data from controller
            // -----------------------------------------------------------------
            let db_purchaseSaleBuyerStaff       = @json($purchaseSaleBuyerStaff);
            let db_purchaseSaleBuyerStaffMemo   = @json($purchaseSaleBuyerStaffMemo);
            let db_property         = @json( $property );
            let db_property_owners  = @json( $property_owners );
            let db_residential      = @json( $residentials );
            let db_road             = @json( $roads );
            let db_building         = @json( $buildings );
            let db_stat_check       = @json( $stat_check );
            // -----------------------------------------------------------------

            // -----------------------------------------------------------------
            // assign data from controller to initial data
            // -----------------------------------------------------------------
            if (db_purchaseSaleBuyerStaff) Object.assign(purchaseSaleBuyerStaff, db_purchaseSaleBuyerStaff); // assign
            if (db_purchaseSaleBuyerStaffMemo) Object.assign(purchaseSaleBuyerStaffMemo, db_purchaseSaleBuyerStaffMemo); // assign
            if (db_property_owners != null) Object.assign(property_owners, db_property_owners)

            // property
            // -----------------------------------------------------------------
            if (db_property != null) {
                // assign property value
                Object.assign(property, db_property);
                // assign owner value
                if (db_property.owners.length != 0) {
                    // Object.assign(property_owners, db_property.owners);
                    delete property.owners;
                }
            }
            // -----------------------------------------------------------------

            // residential
            // -----------------------------------------------------------------
            if (db_residential.length != 0) {
                Object.assign(residentials.data, db_residential);
                residentials.data.forEach((residential, index) => {
                  if (residential.residential_purchase == null) {
                    residential.residential_purchase = _.cloneDeep(residential_purchase)
                  }
                });
            }else{
                // residentials.data.push(_.cloneDeep(residentials.default));
            }
            // -----------------------------------------------------------------

            // road
            // -----------------------------------------------------------------
            if (db_road.length != 0) {
                Object.assign(roads.data, db_road);
                roads.data.forEach((road, index) => {
                  if (road.road_purchase == null) {
                    road.road_purchase = _.cloneDeep(road_purchase)
                  }
                });
            }else{
                // roads.data.push(_.cloneDeep(roads.default));
            }
            // -----------------------------------------------------------------

            // building
            // -----------------------------------------------------------------
            if (db_building.length != 0) {
                Object.assign(buildings.data, db_building);
            }else{
                // buildings.data.push(_.cloneDeep(buildings.default));
            }
            // -----------------------------------------------------------------

            // stat_check
            // -----------------------------------------------------------------
            if (db_stat_check != null){
                Object.assign(stat_check, db_stat_check);
            }
            // -----------------------------------------------------------------

            // -----------------------------------------------------------------
            // assign property and stock_procurement value to purchase sales
            // -----------------------------------------------------------------
            purchase_sales.purchase_price = (stock_procurement != null) && stock_procurement.price
            purchase_sales.registry_size = property.registry_size
            purchase_sales.survey_size = property.survey_size
            // -----------------------------------------------------------------

            // -----------------------------------------------------------------
            // set data for A61-3 select box
            // -----------------------------------------------------------------
            if (purchase_sales.company_id_organizer) {
                users_real_estate_notary_registration = users.filter(function(user) {
                    return user.company_id == purchase_sales.company_id_organizer && user.real_estate_notary_registration == 1
                })
            }
            // -----------------------------------------------------------------

            return {
                initial: initial,
                status: {mounted: false, loading: false, deleting: false},
                project: project,
                purchase_sales: purchase_sales,
                kind_in_house: kind_in_house,
                kind_real_estate: kind_real_estate,
                organizers_real_estates: organizers_real_estates,
                buyer_staff_with_user_id: buyer_staff_with_user_id,
                buyerStaff: purchaseSaleBuyerStaff,
                PJMemos: purchaseSaleBuyerStaffMemo,
                master: master,
                property: property,
                property_owners: property_owners,
                residentials: residentials,
                roads: roads,
                buildings: buildings,
                stat_check: stat_check,
                users: users,
                users_real_estate_notary_registration: users_real_estate_notary_registration
            };
        },

        watch: {
            // purchase_sale
            // -----------------------------------------------------------------
            purchase_sales: {
                deep: true,
                handler: function( purchase_sales ){
                    if (purchase_sales.offer_date || purchase_sales.offer_route) resetParsley();
                    refreshParsley();

                    this.purchaseUrbanization();
                }
            },
            // -----------------------------------------------------------------

            // purchase_sale.project_urbanization_area
            // -----------------------------------------------------------------
            'purchase_sales.project_urbanization_area': function() {
                if (this.purchase_sales.project_urbanization_area != 3) {
                    this.purchase_sales.project_urbanization_area_sub       = null;
                    this.purchase_sales.project_urbanization_area_status    = null;
                    this.purchase_sales.project_urbanization_area_date      = null;
                }

                this.purchaseUrbanization();
            },
            // -----------------------------------------------------------------

            // residential
            // -----------------------------------------------------------------
            residentials: {
                deep: true,
                handler: function( residentials ){
                    this.purchaseUrbanization();
                }
            },
            // -----------------------------------------------------------------

            // road
            // -----------------------------------------------------------------
            roads: {
                deep: true,
                handler: function( roads ){
                    this.purchaseUrbanization();
                }
            },
            // -----------------------------------------------------------------
        },

        methods: {
            getCompanyUser: function(value){
              this.users_real_estate_notary_registration = this.users.filter(function(user) {
                   return user.company_id == value && user.real_estate_notary_registration == 1
               })
            },
            // copy value from project title field
            // -----------------------------------------------------------------
            copyField: function () {
                // get residential parcel data
                let parcel_city = null;
                let city_extra_or_town = this.residentials.data[0].parcel_city_extra ?? this.residentials.data[0].parcel_town;
                city_extra_or_town = city_extra_or_town ?? '';

                if (this.residentials.data[0].parcel_city == -1) parcel_city = 'その他'
                if (this.residentials.data[0].parcel_city == 0) parcel_city = ''
                if (this.residentials.data[0].parcel_city > 0) parcel_city = this.master_parcel_cities[this.residentials.data[0].parcel_city]
                this.purchase_sales.project_address = parcel_city + city_extra_or_town
                this.purchase_sales.project_address_extra = this.residentials.data[0].parcel_number_first + "番" + this.residentials.data[0].parcel_number_second
            },
            // -----------------------------------------------------------------

            // addable input method for Buyer users
            // push with content and id too.
            // -----------------------------------------------------------------
            addInput: function () {
                this.buyerStaff.push({id: null, user_id: ''});
            },
            // -----------------------------------------------------------------

            // addable input method for Buyer users
            // -----------------------------------------------------------------
            removeInput: function (index) {
                let id = this.buyerStaff[index].id;
                let confirmed = false;

                if (id) confirmed = confirm('続けていたしますか？');
                else this.buyerStaff.splice(index, 1);

                if (confirmed) {
                    // --------------------------------------------------------------
                    // handle delete request
                    // --------------------------------------------------------------
                    let remove_buyer_staff = axios.delete('{{ $delete_buyer_staff }}', {
                        data: this.buyerStaff[index]
                    });
                    let vm = this
                    // ---------------------------------------------------------

                    // ---------------------------------------------------------
                    // handle success response
                    // ---------------------------------------------------------
                    remove_buyer_staff.then(function (response) {
                      vm.buyerStaff.splice(index, 1);
                      $.toast({
                        heading: '成功', icon: 'success',
                        position: 'top-right', stack: false, hideAfter: 3000,
                        text: response.data.message,
                        position: { right: 18, top: 68 }
                      });
                    })
                    //----------------------------------------------------------

                    // ---------------------------------------------------------
                    // handle error response
                    // ---------------------------------------------------------
                    remove_buyer_staff.catch(function (error) {
                      if (error.response.status == 422){ var error_message = [error.response.data.message]; }
                      if (error.response.status == 500){ var error_message = [error.response.data.message, error.response.data.error]; }
                        $.toast({
                            heading: '失敗', icon: 'error',
                            position: 'top-right', stack: false, hideAfter: 3000,
                            text: error_message,
                            position: { right: 18, top: 68 }
                        });
                    });
                    // ---------------------------------------------------------
                }
            },
            // -----------------------------------------------------------------

            // addable input method for PJMemos users
            // -----------------------------------------------------------------
            addMemo: function () {
                this.PJMemos.push({id: null, content: ''});
            },
            // -----------------------------------------------------------------

            // remove input method for PJMemos users
            // -----------------------------------------------------------------
            removeMemo: function (index) {
                let id = this.PJMemos[index].id;
                let confirmed = false;

                if (id) confirmed = confirm('続けていたしますか？');
                else this.PJMemos.splice(index, 1);

                if (confirmed) {
                    // ---------------------------------------------------------
                    // handle delete request
                    // ---------------------------------------------------------
                    let remove_memo = axios.delete('{{ $delete_memo }}', {
                        data: this.PJMemos[index]
                    });
                    let vm = this;
                    // ---------------------------------------------------------

                    // ---------------------------------------------------------
                    // handle success response
                    // ---------------------------------------------------------
                    remove_memo.then(function (response) {
                      vm.PJMemos = response.data.data.PJMemos;
                      $.toast({
                        heading: '成功', icon: 'success',
                        position: 'top-right', stack: false, hideAfter: 3000,
                        text: response.data.message,
                        position: { right: 18, top: 68 }
                      });
                    })
                    //----------------------------------------------------------

                    // ---------------------------------------------------------
                    // handle error response
                    // ---------------------------------------------------------
                    remove_memo.catch(function (error) {
                      if (error.response.status == 422){ var error_message = [error.response.data.message]; }
                      if (error.response.status == 500){ var error_message = [error.response.data.message, error.response.data.error]; }
                        $.toast({
                            heading: '失敗', icon: 'error',
                            position: 'top-right', stack: false, hideAfter: 3000,
                            text: error_message,
                            position: { right: 18, top: 68 }
                        });
                    });
                    // --------------------------------------------------------------
                }
            },
            // -----------------------------------------------------------------

            // residentials and roads urbanization
            // -----------------------------------------------------------------
            purchaseUrbanization: function() {

                // set value for residential purchase
                // -------------------------------------------------------------
                this.residentials.data.forEach((residential, i) => {
                    if (residential.residential_purchase.urbanization_area_same == true) {
                        residential.residential_purchase.urbanization_area = this.purchase_sales.project_urbanization_area
                        if (residential.residential_purchase.urbanization_area == 3) {
                            residential.residential_purchase.urbanization_area_sub  = this.purchase_sales.project_urbanization_area_sub
                            residential.residential_purchase.urbanization_area_date = this.purchase_sales.project_urbanization_area_date
                        }
                        else {
                            residential.residential_purchase.urbanization_area_sub      = null;
                            residential.residential_purchase.urbanization_area_number   = null;
                            residential.residential_purchase.urbanization_area_date     = null;
                        }
                    }
                    else if (residential.residential_purchase.urbanization_area != 3) {
                        residential.residential_purchase.urbanization_area_sub      = null;
                        residential.residential_purchase.urbanization_area_number   = null;
                        residential.residential_purchase.urbanization_area_date     = null;
                    }
                });
                // -------------------------------------------------------------

                // set value for road purchase
                // -------------------------------------------------------------
                this.roads.data.forEach((road, i) => {
                    if (road.road_purchase.urbanization_area_same == true) {
                        road.road_purchase.urbanization_area = this.purchase_sales.project_urbanization_area
                        if (road.road_purchase.urbanization_area == 3) {
                            road.road_purchase.urbanization_area_sub    = this.purchase_sales.project_urbanization_area_sub
                            road.road_purchase.urbanization_area_date   = this.purchase_sales.project_urbanization_area_date
                        }
                        else {
                            road.road_purchase.urbanization_area_sub    = null;
                            road.road_purchase.urbanization_area_number = null;
                            road.road_purchase.urbanization_area_date   = null;
                        }
                    }
                    else if (road.road_purchase.urbanization_area != 3) {
                        road.road_purchase.urbanization_area_sub    = null;
                        road.road_purchase.urbanization_area_number = null;
                        road.road_purchase.urbanization_area_date   = null;
                    }
                });
                // -------------------------------------------------------------
            }
            // -----------------------------------------------------------------
        },
    };


    // ----------------------------------------------------------------------
    // After Vue has been mounted
    // ----------------------------------------------------------------------
    $(document).on('vue-loaded', function (event, vm) {

        let $form = $('form[data-parsley]');
        let form = $form.parsley();
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // On form submit
        // ------------------------------------------------------------------
        form.on('form:validated', function () {
            // --------------------------------------------------------------
            let valid = form.isValid();
            if (!valid) setTimeout(function () {
                let $errors = $('.parsley-errors-list.filled').first();
                animateScroll($errors);
            });
            else {
                // ----------------------------------------------------------
                vm.status.loading = true;
                // ----------------------------------------------------------

                // get id on offer route object before save and update data
                vm.purchase_sales.offer_route = vm.purchase_sales.offer_route.id ?? null

                // compile data
                // ----------------------------------------------------------
                let data = {
                    entry: vm.purchase_sales,
                    buyerStaff: vm.buyerStaff,
                    PJMemos: vm.PJMemos,
                    stat_check: vm.stat_check,
                    residentials: vm.residentials.data,
                    roads: vm.roads.data
                };
                // ----------------------------------------------------------

                // set update url and request
                // ----------------------------------------------------------
                let request = null;
                let url = '{{ $form_action }}';
                request = axios.post(url, data);
                // ----------------------------------------------------------

                // ----------------------------------------------------------
                // If request succeed
                // ----------------------------------------------------------
                request.then(function (response) {
                    if (response.data.status == 'success') {
                        Vue.nextTick(function() {
                            $.toast({
                                heading: '成功', icon: 'success',
                                position: 'top-right', stack: false, hideAfter: 4000,
                                text: response.data.message,
                                position: { right: 18, top: 68 }
                            });
                        });
                    }else if (response.data.status == 'warning') {
                        Vue.nextTick(function() {
                            $.toast({
                                heading: false, icon: 'warning',
                                position: 'top-right', stack: false, hideAfter: 4000,
                                text: response.data.message,
                                position: { right: 18, top: 68 }
                            });
                        })
                    }

                   // update vue data with response controller
                   // ----------------------------------------------------------
                   let data = response.data.data;
                   vm.status.loading    = false;
                   vm.purchase_sales    = data.purchaseSale;
                   vm.buyerStaff        = data.buyerStaff;
                   vm.PJMemos           = data.PJMemos;
                   vm.stat_check        = data.stat_check;
                   vm.residentials.data = data.residentials;
                   vm.roads.data        = data.roads;
                   // ----------------------------------------------------------

                   // change offer route value to object for multi select
                   // ----------------------------------------------------------
                   vm.purchase_sales.offer_route = vm.kind_real_estate.filter(function(real_estate) {
                        return real_estate.id == vm.purchase_sales.offer_route
                    })
                   vm.purchase_sales.offer_route = vm.purchase_sales.offer_route[0]
                   // ----------------------------------------------------------
                });
                // ----------------------------------------------------------

                // --------------------------------------------------------------
                // error response
                // --------------------------------------------------------------
                request.catch(function (error) {
                    Vue.nextTick(function() {
                        $.toast({
                           heading: '失敗', icon: 'error',
                           position: 'top-right', stack: false, hideAfter: 4000,
                           text: [error.response.data.message],
                           position: { right: 18, top: 68 }
                        });
                    })
                });
                // --------------------------------------------------------------

                // --------------------------------------------------------------
                // always execute function
                // --------------------------------------------------------------
                request.finally(function () {
                    vm.status.loading = false
                });
                // --------------------------------------------------------------
            }
        }).on('form:submit', function () {
            return false
        });
    });

    var refreshParsley = function () {
        setTimeout(function () {
            let $form = $('form[data-parsley]');
            $form.parsley().refresh();
        });
    }
    var resetParsley = function(){
        Vue.nextTick(function () {
            var $form = $('form[data-parsley]');
            $form.parsley().reset();
        });
    }
    // ----------------------------------------------------------------------
    // Scroll page to a target destination with predefined animation
    // ----------------------------------------------------------------------
    let animateScroll = function (scroll, duration) {

        duration = duration || 800;
        let offset = 160;
        if (!_.isInteger(scroll)) {
            let $target = $(scroll);
            if ($target.length) scroll = $target.offset().top;
        }
        let $html = $('html');
        let scrolltop = scroll - offset;
        if (scrolltop <= 0) scrolltop = 0;
        anime({
            targets: $html.get()[0], scrollTop: scrolltop,
            duration: duration, easing: 'linear'
        });
    };
</script>
@endpush
