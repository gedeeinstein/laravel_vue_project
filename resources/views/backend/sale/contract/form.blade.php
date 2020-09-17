@extends('backend._base.content_sale')

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
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">基本データ</a></li>
    <li class="breadcrumb-item active">{{ $page_title }}</li>
</ol>
@endsection

@section('content')
    <form id="form" data-parsley class="parsley-minimal" v-cloak>
        <!-- Start - Terms Of Sale -->
        @include('backend.sale.contract.form.terms-of-sale')
        <!-- End - Terms Of Sale -->

        <!-- Start - Purchase Information -->
        @include('backend.sale.contract.form.purchase-information')
        <!-- End - Purchase Information -->

        <!-- Start - Contract Information -->
        <div class="card mb-1">
            <div class="card-header">契約情報</div>
            <div class="card-body">
                @include('backend.sale.contract.form.contract-information.table1')
                @include('backend.sale.contract.form.contract-information.table2')
                @include('backend.sale.contract.form.contract-information.table3')
                @include('backend.sale.contract.form.contract-information.table4')
                @include('backend.sale.contract.form.contract-information.table5')
                @include('backend.sale.contract.form.contract-information.table6')
                @include('backend.sale.contract.form.contract-information.table7')
                @include('backend.sale.contract.form.contract-information.table8')
                @include('backend.sale.contract.form.contract-information.table9')
            </div>
        </div>
        <!-- End - Contract Information -->

        <!-- Start - Save and Contract Creation Button -->
        <div class="nav-buttons bottom row form-inline" style="place-content: center;">
            <button type="submit" class="btn btn-wide btn-info px-4 mr-1" data-id="C27-1"
                :disabled="!initial.editable">
                <i v-if="!initial.submited" class="fas fa-save"></i>
                <i v-else class="fas fa-spinner fa-spin"></i>
                保存
            </button>
            <button @click="creation_clicked" type="submit"
                :disabled="!initial.editable"
                class="btn btn-wide btn-info px-4 mr-3" data-id="C27-2">
                <i v-if="!initial.submited" class="fas fa-save"></i>
                <i v-else class="fas fa-spinner fa-spin"></i>
                契約作成
            </button>
            <div class="form-check icheck-cyan">
                <input v-model="initial.intermediary"
                    class="form-check-input" type="checkbox"
                    id="intermediary" data-id="C27-3"
                >
                <label class="form-check-label" for="intermediary">販売契約書は仲介業者にて作成</label>
            </div>
        </div>
        <!-- End - Save and Contract Creation Button -->

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
@if(0) </script> @endif

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
            // --------------------------------------------------------------
            // Initial Data
            // --------------------------------------------------------------
            let initial                = @json( $initial );
            initial.submited           = false;
            initial.loading            = true;
            initial.counter            = -1; // for sale_contract.delivery calculation purpose
            initial.editable           = @json( $editable );
            initial.url                = @json( $url );
            initial.is_active          = false;
            initial.intermediary       = false;
            initial.creation_clicked   = false;
            initial.inspection_clicked = false;
            initial.abolished_clicked  = false;
            // -----------------------------------------------------------------
            let active_tab = @json( $active_tab ); // set purchase active tab
            // -----------------------------------------------------------------

            // --------------------------------------------------------------
            // Assign data from database
            // --------------------------------------------------------------
            let master = {
                values: @json( $master_values ),
                regions: @json( $master_regions ),
            };
            let users              = @json( $users );
            let role               = @json( $role );
            let companies          = @json( $companies );
            let banks              = @json( $banks );
            let bank_accounts      = @json( $bank_accounts );
            let kind_bank_accounts = @json( $kind_bank_accounts );
            let mas_section        = @json( $mas_section );
            let buildings          = @json( $buildings );
            let sale_contract      = @json( $sale_contract );
            let inspections        = @json( $inspections );
            // --------------------------------------------------------------

            // -----------------------------------------------------------------
            // set data for mas section relation
            // -----------------------------------------------------------------
            let setting      = mas_section.plan.setting;
            let section_sale = mas_section.sale;
            // -----------------------------------------------------------------

            // -----------------------------------------------------------------
            // set data for sale contract relation
            // -----------------------------------------------------------------
            let purchases   = sale_contract.purchases.length == 0 ?
                                [initial.purchases] : sale_contract.purchases;
            let deposits    = sale_contract.deposits.length == 0 ?
                                [initial.deposits] : sale_contract.deposits;
            let mediations  = sale_contract.mediations.length == 0 ?
                                [initial.mediations] : sale_contract.mediations;
            let fee         = sale_contract.fee ?? initial.fee;
            let prices      = sale_contract.fee ? fee.prices : [initial.prices];
            // -----------------------------------------------------------------

            // -----------------------------------------------------------------
            // set sale product residence data with mas lot building
            // -----------------------------------------------------------------
            let residences = [];
            if (sale_contract.residences.length == 0) {
                buildings.forEach((building, index) => {
                    residences.push(_.cloneDeep(initial.residences));
                    residences[index].mas_lot_building_id = building.id;
                });
            }else {
                residences = sale_contract.residences;
                // make sale contract residence and mas lot building equal
                // -------------------------------------------------------------
                if (buildings.length > sale_contract.residences.length) {
                    let total = buildings.length - sale_contract.residences.length;
                    for (var i = 0; i < total; i++) {
                        residences.push(_.cloneDeep(initial.residences));
                    }
                }
                // -------------------------------------------------------------
                else if (buildings.length == sale_contract.residences.length) {
                    residences = sale_contract.residences;
                }
            }

            // get sale product residence (mas lot building id)
            // and mas lot building (id)
            // -----------------------------------------------------------------
            let residence_ids = []; let building_ids  = [];

            buildings.forEach((building, index) => {
                building_ids.push(building.id);
            });
            residences.forEach((residence, index) => {
                residence_ids.push(residence.mas_lot_building_id);
            });

            // get different sale product residence (mas lot building id) and mas lot building (id)
            // set sale product residence (mas lot building id)
            // -----------------------------------------------------------------
            let difference_ids = _.difference(building_ids, residence_ids);
            let key = 0;

            residences.forEach((residence, index) => {
                if (!residence.mas_lot_building_id) {
                    residence.mas_lot_building_id = difference_ids[key];
                    key++;
                }
            });
            // -----------------------------------------------------------------

            // filter company data for suggestion input
            // -----------------------------------------------------------------
            let house_makers = _.filter(companies, function(company) {
                return company.kind_house_maker == 1;
            });

            let house_maker_id = sale_contract.house_maker ?? null;
            if (house_maker_id) {
                let selected = _.filter(companies, function(company) {
                    return company.id == house_maker_id;
                });
                Vue.set(sale_contract, 'house_maker', selected[0])
            }else {
                Vue.set(sale_contract, 'house_maker', null);
            }
            // -----------------------------------------------------------------
            let professions = _.filter(companies, function(company) {
                return company.kind_profession == 1;
            });

            let profession_id = sale_contract.register ?? null;
            if (profession_id) {
                let selected = _.filter(companies, function(company) {
                    return company.id == profession_id;
                });
                Vue.set(sale_contract, 'register', selected[0])
            }else {
                Vue.set(sale_contract, 'register', null);
            }
            // -----------------------------------------------------------------
            let contractors = _.filter(companies, function(company) {
                return company.kind_contractor == 1;
            });

            let contractor_id = sale_contract.outdoor_facility_manufacturer ?? null;
            if (contractor_id) {
                let selected = _.filter(companies, function(company) {
                    return company.id == contractor_id;
                });
                Vue.set(sale_contract, 'outdoor_facility_manufacturer', selected[0])
            }else {
                Vue.set(sale_contract, 'outdoor_facility_manufacturer', null);
            }
            // -----------------------------------------------------------------

            // set mediation trader for suggestion input
            // -----------------------------------------------------------------
            mediations.forEach((mediation, index) => {
                let trader_id = mediation.trader ?? null;
                if (trader_id) {
                    let selected = _.filter(companies, function(company) {
                        return company.id == trader_id;
                    });
                    Vue.set(mediation, 'trader', selected[0])
                }else {
                    Vue.set(mediation, 'trader', null);
                }
            });
            // -----------------------------------------------------------------

            // --------------------------------------------------------------
            // Return compiled data
            // --------------------------------------------------------------
            return {
                initial: initial,
                active_tab: active_tab,
                // -------------------------------------------------------------
                master:master,
                // -------------------------------------------------------------
                users: users,
                role: role,
                companies: companies,
                banks: banks,
                bank_accounts: bank_accounts,
                kind_bank_accounts:kind_bank_accounts,
                // -------------------------------------------------------------
                mas_section: mas_section,
                buildings: buildings,
                sale_contract: sale_contract,
                // -------------------------------------------------------------
                setting: setting,
                section_sale: section_sale,
                // -------------------------------------------------------------
                purchases: purchases,
                deposits: deposits,
                mediations: mediations,
                residences: residences,
                fee: fee,
                prices: prices,
                // -------------------------------------------------------------
                house_makers: house_makers,
                professions: professions,
                contractors: contractors,
                // -------------------------------------------------------------
                inspections: inspections,
                // -------------------------------------------------------------
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
            // refresh parsley form validation
            refreshParsley();

            // switch loading state
            this.initial.loading = false;

            // triger event on loaded
            $(document).trigger( 'vue-loaded', this );
        },

        /*
        ## ------------------------------------------------------------------
        ## VUE COMPUTED
        ## define a property with custom logic
        ## ------------------------------------------------------------------
        */
        computed: {
            // check abolished purchase
            // -----------------------------------------------------------------
            is_abolished: function() {
                let purchase = this.purchases[this.active_tab];
                return purchase.accept_result == 4;
            },
            // -----------------------------------------------------------------

            // -----------------------------------------------------------------
            // Master Data
            // -----------------------------------------------------------------

            // define parcel_cities from master data
            // -----------------------------------------------------------------
            master_parcel_cities: function () {
                return this.master.regions;
            },
            // -----------------------------------------------------------------

            // define use_types from master data
            // -----------------------------------------------------------------
            master_use_types: function () {
                let master_values = this.master.values;
                let data_types = _.filter(master_values, function (data) {
                    return data.type == 'building_usetype';
                });
                return data_types;
            },
            // -----------------------------------------------------------------

            // define building_structures from master data
            // -----------------------------------------------------------------
            master_building_structures: function () {
                let master_values = this.master.values;
                let building_structures = _.filter(master_values, function (data) {
                    return data.type == 'building_structure';
                });
                return building_structures;
            },
            // -----------------------------------------------------------------

            // set building address
            // C18-1 + C18-2 + C18-3 + C18-4 + "-" + C18-5 + "(" + C18-6 + "-" + C18-7 + "-" + C18-8 + ")"
            // -----------------------------------------------------------------
            building_addresses: function() {
                let buildings = this.buildings;
                let building_addresses = [];
                let parcel_city = '';
                let building_number_third = '';
                let address = '';

                buildings.forEach((building, index) => {
                    // set parcel city address
                    // ---------------------------------------------------------
                    if (building.parcel_city == 0 || building.parcel_city == -1) {
                        parcel_city == '';
                    }else {
                        parcel_city == this.master_parcel_cities[building.parcel_city].name;
                    }
                    // ---------------------------------------------------------

                    // set building number third
                    // ---------------------------------------------------------
                    building_number_third = building.building_number_third ?
                                            '-' + building.building_number_third : '';
                    // ---------------------------------------------------------

                    // set building address
                    // ---------------------------------------------------------
                    address = parcel_city + building.parcel_city_extra + building.parcel_town
                              + building.parcel_number_first + '-' + building.parcel_number_second
                              + '(' + building.building_number_first + '-' + building.building_number_second
                              + building_number_third + ')';
                    // ---------------------------------------------------------

                    building_addresses.push(address); // push address to array
                });

                return building_addresses;
            },
            // -----------------------------------------------------------------

            // building floor size calculation
            // -----------------------------------------------------------------
            building_floor_sizes: function() {
                let buildings = this.buildings;
                let total = [];

                buildings.forEach((building, index) => {
                    // get building floor size total
                    // ---------------------------------------------------------
                    total[index] = _.sumBy(building.building_floors, function(floor) {
                        return Number(floor.floor_size);
                    });
                    // ---------------------------------------------------------

                    total[index] = total[index] % 1 == 0 ? // if total is decimal, set 2 decimal place and round down value
                                   total[index] : _.floor(total[index], 2);
                });

                return total;
            },
            // -----------------------------------------------------------------

            // TODO: set purchase sale tab style
            // purchase sale tab style
            // -----------------------------------------------------------------
            tab_style: function() {
                let purchases   = this.purchases;
                let inspections = this.inspections;
                let style       = [];

                purchases.forEach((purchase, index) => {
                    // check if inspection is available for sale purchase
                    // ---------------------------------------------------------
                    if (inspections[purchase.id]) {
                        // check if axamination is 2 then push style
                        // -----------------------------------------------------
                        if (inspections[purchase.id].examination == 2) {
                            style.push([{
                                background: '#4A80C1',
                                color: '#ffffff'
                            }]);
                        }
                        // push null styling
                        // -----------------------------------------------------
                        else {
                            style.push([{}]);
                        }
                        // -----------------------------------------------------
                    }
                    // push null styling
                    // ---------------------------------------------------------
                    else {
                        style.push([{}]);
                    }
                    // ---------------------------------------------------------
                });

                return _.flatten(style);
            },
            // -----------------------------------------------------------------

            // check inspection request data based on active sale purchase
            // -----------------------------------------------------------------
            inspection_request_check: function() {
                let id     = false;

                if (this.inspections[this.purchases[this.active_tab].id]) {
                    if (this.inspections[this.purchases[this.active_tab].id].id)
                        id = true;
                    else
                        id = false;
                }

                return id;
            },
            // -----------------------------------------------------------------

            // active inspections
            // -----------------------------------------------------------------
            active_inspection: function() {
                return this.inspections[this.purchases[this.active_tab].id];
            },
            // -----------------------------------------------------------------

            // C23-5 calculation
            // formula C23-3 - B15-12
            // -----------------------------------------------------------------
            income: function() {
                let contract_price_building = this.sale_contract.contract_price_building ?? 0;
                return this.mas_section.book_price - contract_price_building
            },
            // -----------------------------------------------------------------

            // round down to 2 decimals mas_section.profit_decided
            // -----------------------------------------------------------------
            profit_decided: function() {
                return _.floor(this.mas_section.profit_decided, 2);
            },
            // -----------------------------------------------------------------

            // sale_contract.payment_date required
            // i. C17-7(SaleActivity【C1】) is "確定"
            // ii. C12-7(of SaleActivity【C1】) is "契済"
            // -----------------------------------------------------------------
            payment_date_required: function() {
                let section_sale = this.section_sale;
                return section_sale.sell_period_status == 1 || section_sale.rank == 3;
            },
            // -----------------------------------------------------------------

            // get real estate company
            // -----------------------------------------------------------------
            in_houses: function() {
                let in_houses = _.filter(this.companies, function(company) {
                    return company.kind_in_house == 1;
                });

                return in_houses;
            },
            // -----------------------------------------------------------------

            // get real estate company
            // -----------------------------------------------------------------
            real_estates: function() {
                let real_estates = _.filter(this.companies, function(company) {
                    return company.kind_real_estate_agent == 1;
                });

                return real_estates;
            },
            // -----------------------------------------------------------------

            // get kind_in_house and kind_real_estate_agent company
            // -----------------------------------------------------------------
            in_house_n_real_estates: function() {
                let companies = _.filter(this.companies, function(company) {
                    return company.kind_in_house == 1 && company.kind_real_estate_agent == 1;
                });

                return companies;
            },
            // -----------------------------------------------------------------

            // get mediation trader bank accounts
            // -----------------------------------------------------------------
            trader_bank_accounts: function() {
                let mediations = this.mediations;
                let bank_accounts = this.bank_accounts;
                let trader_bank_accounts = [];
                let accounts = [];

                mediations.forEach((mediation, index) => {
                    trader_bank_accounts = _.filter(bank_accounts, function(account) {
                        if (mediation.trader) {
                            return account.company.id == mediation.trader.id;
                        }else {
                            return [];
                        }
                    });

                    accounts.push(_.cloneDeep(trader_bank_accounts))

                });

                return accounts;
            },
            // -----------------------------------------------------------------

            // get sale mediation enable
            // -----------------------------------------------------------------
            mediation_enable: {
                get: function () {
                    let mediations = this.mediations;
                    let enable = _.filter(mediations, function(mediation) {
                        return mediation.enable == 2;
                    });

                    mediations.forEach((mediation, index) => {
                        if (enable.length > 0)
                            Vue.set(mediation, 'enable', 2)
                        else
                            Vue.set(mediation, 'enable', 1)
                    });

                    return enable.length > 0 ? 2 : 1;
                },
                set: function (value) {
                    let mediations = this.mediations;
                    mediations.forEach((mediation, index) => {
                        Vue.set(mediation, 'enable', value)
                    });
                }
            },
            // -----------------------------------------------------------------

            // mediation balance background
            // -----------------------------------------------------------------
            mediation_background: function() {
                let background = [];
                let mediations = this.mediations;

                mediations.forEach((mediation, index) => {
                    if (mediation.balance == 2) background[index] = '#ADD8E6';
                    else if (mediation.balance == 3) background[index] = '#FF0000';
                    else if (mediation.balance == 4) background[index] = '#7CFC00';
                    else background[index] = '';
                });

                return background;
            },
            // -----------------------------------------------------------------

            // mediation balance background
            // -----------------------------------------------------------------
            fee_background: function() {
                let background = '';
                let fee = this.fee;

                if (fee.balance == 2) background = '#ADD8E6';
                else if (fee.balance == 3) background = '#FF0000';
                else if (fee.balance == 4) background = '#7CFC00';
                else background = '';

                return background;
            },
            // -----------------------------------------------------------------

            // contract price calculation
            // formula ((C23-2×0.03)＋60000)×1.10 (税率10%)
            // -----------------------------------------------------------------
            contract_price: function() {
                let price = this.sale_contract.contract_price ?? 0;
                return _.floor(((price * 0.03) + 6000) * 1.1, 2)
            },
            // -----------------------------------------------------------------
        },

        /*
        ## ------------------------------------------------------------------
        ## VUE WATCH
        ## vue reactive data watch
        ## ------------------------------------------------------------------
        */
        watch: {
            // watch property for sale_contract.delivery_price calculation
            // --------------------------------------------------------------
            'sale_contract.contract_price': {
                handler: function (price) {
                    this.delivery_price_calculation();
                },
            },
            // --------------------------------------------------------------
            deposits: {
                deep: true,
                handler: function (deposits) {
                    this.delivery_price_calculation();
                },
            },
            // --------------------------------------------------------------
        },

        /*
        ## ------------------------------------------------------------------
        ## VUE METHOD
        ## function associated with the vue instance
        ## ------------------------------------------------------------------
        */
        methods: {
            // active inactive input
            // -----------------------------------------------------------------
            is_active: function() {
                let is_active = this.initial.is_active;
                if (is_active) this.initial.is_active = false;
                else this.initial.is_active           = true;
            },
            // -----------------------------------------------------------------

            // show active sale purchase data
            // -----------------------------------------------------------------
            show_purchase: function(index) {
                this.active_tab = index;
            },
            // -----------------------------------------------------------------

            // clear multiselect option
            // -----------------------------------------------------------------
            clear_select: function(target, index = 1) {
                if (target == 'house_maker') this.sale_contract.house_maker = null;
                if (target == 'register') this.sale_contract.register = null;
                if (target == 'outdoor_facility_manufacturer') this.sale_contract.outdoor_facility_manufacturer = null;
                if (target == 'trader') this.mediations[index].trader = null;
            },
            // -----------------------------------------------------------------

            // sale_contract.delivery_price calculation
            // formula C23-2 ー C23-15(total)
            // -----------------------------------------------------------------
            delivery_price_calculation: function() {
                if (this.initial.counter > 0 || !this.sale_contract.delivery_price) {
                    let result              = 0;
                    let contract_price      = this.sale_contract.contract_price ?? 0;
                    let deposit_price_total = _.sumBy(this.deposits, 'price');

                    result = contract_price - deposit_price_total;
                    Vue.set(this.sale_contract, 'delivery_price', result);
                }
                this.initial.counter++;
            },
            // -----------------------------------------------------------------

            // addable data
            // -----------------------------------------------------------------
            add_data: function(type) {
                if (type == "purchases") {
                    this.purchases.push(_.cloneDeep(this.initial.purchases));
                    this.active_tab = this.purchases.length - 1;
                }
                else if (type == "deposits")
                    this.deposits.push(_.cloneDeep(this.initial.deposits));
                else if (type == "mediations")
                    this.mediations.push(_.cloneDeep(this.initial.mediations));
                else if (type == "prices")
                    this.prices.push(_.cloneDeep(this.initial.prices));
            },
            // -----------------------------------------------------------------

            // removable data
            // -----------------------------------------------------------------
            remove_data: function(index, type) {
                var confirmed = false;

                if (type == "purchases") {
                    var id        = this.purchases[index].id;

                    if (id) {
                        confirmed   = confirm('@lang('label.confirm_remove_control')');
                        var data    = {data: {id: id}};
                        var url     = this.initial.url.remove_purchases;
                    }
                    else {
                        this.active_tab = this.active_tab == 0 ? ++this.active_tab : --this.active_tab;
                        this.purchases.splice(index, 1);
                    }
                } else if (type == "deposits") {
                    var id        = this.deposits[index].id;

                    if (id) {
                        confirmed   = confirm('@lang('label.confirm_remove_control')');
                        var data    = {data: {id: id}};
                        var url     = this.initial.url.remove_deposits;
                    }
                    else this.deposits.splice(index, 1);
                } else if (type == "mediations") {
                    var id        = this.mediations[index].id;

                    if (id) {
                        confirmed   = confirm('@lang('label.confirm_remove_control')');
                        var data    = {data: {id: id}};
                        var url     = this.initial.url.remove_mediations;
                    }
                    else this.mediations.splice(index, 1);
                }
                else if (type == "prices") {
                    var id        = this.prices[index].id;

                    if (id) {
                        confirmed   = confirm('@lang('label.confirm_remove_control')');
                        var data    = {data: {id: id}};
                        var url     = this.initial.url.remove_prices;
                    }
                    else this.prices.splice(index, 1);
                }

                if (confirmed) {
                    // handle delete request
                    // --------------------------------------------------------------
                    let request = axios.delete(url, data);
                    let vm      = this;
                    // --------------------------------------------------------------

                    // handle success response
                    // --------------------------------------------------------------
                    request.then(function (response) {
                        if (type == "purchases") {
                            vm.active_tab = vm.active_tab == 0 ? ++vm.active_tab : --vm.active_tab;
                            vm.purchases.splice(index, 1);
                        }
                        else if (type == "deposits")
                            vm.deposits.splice(index, 1);
                        else if (type == "mediations")
                            vm.mediations.splice(index, 1);
                        else if (type == "prices")
                            vm.prices.splice(index, 1);

                        Vue.nextTick(function () {
                            $.toast({
                                heading: '成功', icon: 'success',
                                position: 'top-right', stack: false, hideAfter: 3000,
                                text: response.data.message,
                                position: { right: 18, top: 68 }
                            });
                        });
                    })
                    // --------------------------------------------------------------

                    // handle error response
                    // --------------------------------------------------------------
                    request.catch(function (error) {
                        Vue.nextTick(function () {
                            $.toast({
                                heading: '失敗', icon: 'error',
                                position: 'top-right', stack: false, hideAfter: 3000,
                                text: [error.response.data.message, error.response.data.error],
                                position: { right: 18, top: 68 }
                            });
                        });
                    });
                    // --------------------------------------------------------------
                }
            },
            // -----------------------------------------------------------------

            // copy input value
            // -----------------------------------------------------------------
            copy_value: function(target, type, index = null) {
                if (type == "purchases") {
                    this.purchases.push(_.cloneDeep(this.purchases[index]));
                    this.active_tab = this.purchases.length - 1;
                    this.purchases[this.active_tab].id = null;
                }
                else if (type == "deposits")
                    target.date = this.sale_contract.contract_date;
                else if (type == "contract")
                    target.delivery_date = this.sale_contract.payment_date;
                else if (type == "mediations")
                    target.reward = this.contract_price;
            },
            // -----------------------------------------------------------------

            // contract create button clicked
            // -----------------------------------------------------------------
            creation_clicked: function() {
                this.initial.creation_clicked = true;
            },
            // -----------------------------------------------------------------

            // inspection request button clicked
            // -----------------------------------------------------------------
            inspection_request: function() {
                let confirmed = false;
                confirmed = confirm('本当にリクエストしますか？');

                if (confirmed) {
                    this.initial.inspection_clicked = true;
                    $('#form').submit(); // submit data
                    // // ---------------------------------------------------------
                }
            },
            // -----------------------------------------------------------------

            // abolished request button clicked
            // -----------------------------------------------------------------
            abolished_request: function() {
                let confirmed = false;
                confirmed = confirm('本当に廃止しますか？');

                if (confirmed) {
                    this.initial.abolished_clicked = true;
                    $('#form').submit(); // submit data
                }
            },
            // -----------------------------------------------------------------
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
        let $form = $('form[data-parsley]');
        let form = $form.parsley();
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // On form submit
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
                // -------------------------------------------------------------

                // suggestion input data
                // -------------------------------------------------------------
                vm.sale_contract.house_maker                   = vm.sale_contract.house_maker ?
                                                                 vm.sale_contract.house_maker.id : null;
                vm.sale_contract.register                      = vm.sale_contract.register ?
                                                                 vm.sale_contract.register.id : null;
                vm.sale_contract.outdoor_facility_manufacturer = vm.sale_contract.outdoor_facility_manufacturer ?
                                                                 vm.sale_contract.outdoor_facility_manufacturer.id : null;
                vm.mediations.forEach((mediation, index) => {
                    mediation.trader = mediation.trader ? mediation.trader.id : null;
                });
                // -------------------------------------------------------------

                let data = {
                    sale_contract : vm.sale_contract,
                    purchases     : vm.purchases,
                    deposits      : vm.deposits,
                    mediations    : vm.mediations,
                    residences    : vm.residences,
                    fee           : vm.fee,
                    prices        : vm.prices,
                    // ---------------------------------------------------------
                    inspections   : vm.inspections,
                    // ---------------------------------------------------------
                    purchase_active     : vm.active_tab,
                    inspection_clicked  : vm.initial.inspection_clicked,
                    abolished_clicked   : vm.initial.abolished_clicked,
                };
                // ----------------------------------------------------------

                // set loading state
                // ----------------------------------------------------------
                vm.initial.submited = true;

                // handle update request
                // ----------------------------------------------------------
                let url     = vm.initial.url.update;
                let request = axios.post(url, data);
                // ----------------------------------------------------------

                // handle success response
                // ----------------------------------------------------------
                request.then(function(response) {
                    let data           = response.data.data;
                    let initial        = data.initial;
                    let delivery_price = data.sale_contract.delivery_price;

                    // update vue data to response data
                    // ---------------------------------------------------------
                    vm.sale_contract   = data.sale_contract,
                    vm.purchases       = vm.sale_contract.purchases.length == 0 ?
                                          [initial.purchases] : vm.sale_contract.purchases;
                    vm.deposits        = vm.sale_contract.deposits.length == 0 ?
                                          [initial.deposits] : vm.sale_contract.deposits;
                    vm.mediations      = vm.sale_contract.mediations.length == 0 ?
                                          [initial.mediations] : vm.sale_contract.mediations;
                    vm.residences      = vm.sale_contract.residences.length == 0 ?
                                          [initial.residences] : vm.sale_contract.residences;
                    vm.fee             = vm.sale_contract.fee ?? initial.fee;
                    vm.prices          = vm.sale_contract.fee ? vm.fee.prices : [initial.prices];
                    vm.inspections     = data.inspections;

                    // filter company data for suggestion input
                    // -----------------------------------------------------------------
                    let house_maker_id = vm.sale_contract.house_maker ?? null;
                    if (house_maker_id) {
                        let selected = _.filter(vm.companies, function(company) {
                            return company.id == house_maker_id;
                        });
                        Vue.set(vm.sale_contract, 'house_maker', selected[0])
                    }else {
                        Vue.set(vm.sale_contract, 'house_maker', null);
                    }
                    // ---------------------------------------------------------
                    let profession_id = vm.sale_contract.register ?? null;
                    if (profession_id) {
                        let selected = _.filter(vm.companies, function(company) {
                            return company.id == profession_id;
                        });
                        Vue.set(vm.sale_contract, 'register', selected[0])
                    }else {
                        Vue.set(vm.sale_contract, 'register', null);
                    }
                    // ---------------------------------------------------------
                    let contractor_id = vm.sale_contract.outdoor_facility_manufacturer ?? null;
                    if (contractor_id) {
                        let selected = _.filter(vm.companies, function(company) {
                            return company.id == contractor_id;
                        });
                        Vue.set(vm.sale_contract, 'outdoor_facility_manufacturer', selected[0])
                    }else {
                        Vue.set(vm.sale_contract, 'outdoor_facility_manufacturer', null);
                    }
                    // ---------------------------------------------------------

                    // set mediation trader for suggestion input
                    // ---------------------------------------------------------
                    vm.mediations.forEach((mediation, index) => {
                        let trader_id = mediation.trader ?? null;
                        if (trader_id) {
                            let selected = _.filter(vm.companies, function(company) {
                                return company.id == trader_id;
                            });
                            Vue.set(mediation, 'trader', selected[0])
                        }else {
                            Vue.set(mediation, 'trader', null);
                        }
                    });
                    // ---------------------------------------------------------

                    Vue.nextTick(function () {
                        // set sale_contract.delivery_date
                        vm.sale_contract.delivery_price = delivery_price;

                        // show toast success message
                        $.toast({
                            heading: '成功', icon: 'success',
                            position: 'top-right', stack: false, hideAfter: 4000,
                            text: response.data.message,
                            position: { right: 18, top: 68 }
                        });

                        scrollTop(); // Add scroll-top
                    });

                    // redirent to sale contract create page
                    if (vm.initial.creation_clicked && !vm.initial.intermediary) {
                        // redirect to sale contract create
                    }else if (vm.initial.creation_clicked && vm.initial.intermediary) {
                        alert('仕入契約書は仲介業者にて作成」がチェックされているので作成できません。');
                    }
                });
                // ----------------------------------------------------------

                // handle error response
                // ----------------------------------------------------------
                request.catch(function (error) {
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
                request.finally(function () {
                    vm.initial.submited           = false;
                    vm.initial.creation_clicked   = false;
                    vm.initial.inspection_clicked = false;
                    vm.initial.abolished_clicked  = false;
                });
                // ----------------------------------------------------------
            }
            // --------------------------------------------------------------
        }).on('form:submit', function(){ return false });
    });

    // ----------------------------------------------------------------------
    // Custom function refresh validator
    // ----------------------------------------------------------------------
    var refreshParsley = function(){
        Vue.nextTick(function () {
            var $form = $('form[data-parsley]');
            $form.parsley().refresh();
        });
    }
    var resetParsley = function(){
        Vue.nextTick(function () {
            var $form = $('form[data-parsley]');
            $form.parsley().reset();
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
