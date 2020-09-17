@extends('backend._base.content_master')

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
    <form data-parsley class="parsley-minimal" v-cloak id="form">
        <!-- start profit distribution -->
        @include('backend.master.section-payoff.form.profit-distribution')
        <!-- end profit distribution -->

        <!-- start parcel settlement -->
        @include('backend.master.section-payoff.form.parcel-settlement')
        <!-- end parcel settlement -->

        <!-- start save and back button -->
        <div align="center" class="nav-buttons bottom">
            <a href="{{ route('master.section.list.index') }}">
                <button type="button" class="btn btn-wide btn-info px-4" data-id="B53-1">戻る</button>
            </a>
            <button type="submit" class="btn btn-wide btn-info px-4" data-id="B53-2">
                <i v-if="!initial.submited" class="fas fa-save"></i>
                <i v-else class="fas fa-spinner fa-spin"></i>
                保存
            </button>
        </div>
        <!-- end save and back button -->
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
            let initial = {
                submited: false,
                loading: true,
                is_visible: false,
                update_url: '{{ $update_url }}',
                reset_button_clicked: false, // to check save data with reset button or not
            };
            // -----------------------------------------------------------------
            let income = {
                sales: 0,
                brokerage: 0,
                fee: 0,
                purchase: 0,
                tax: 0,
                total: 0,
            };
            // --------------------------------------------------------------
            let outcome = {
                book: 0,
            };
            // -----------------------------------------------------------------
            let total = {
                profit: 0,
                adjust: 0,
                adjusted: 0,
            };
            // -----------------------------------------------------------------

            // --------------------------------------------------------------
            // Assign data from database
            // --------------------------------------------------------------
            let companies = @json( $companies );
            let project = @json( $project );
            // -----------------------------------------------------------------
            let purchase_sale = @json( $purchase_sale );
            let purchase_contract_mediations = @json( $purchase_contract_mediations );
            // -----------------------------------------------------------------
            let section = @json( $section );
            let section_payoffs = @json( $section_payoffs );
            // -----------------------------------------------------------------
            let sale_contract = @json( $sale_contract );
            let sale_deposits = sale_contract.deposits;
            let sale_mediations = sale_contract.mediations;
            let sale_fee = sale_contract.fee ?? sale_contract.initial_fee;
            let sale_fee_prices = sale_fee.prices ?? sale_fee.initial_prices;
            // --------------------------------------------------------------

            // B2-10 and B2-11 total calculation
            // -----------------------------------------------------------------
            total.adjusted = _.sumBy(section_payoffs, function(section_payoff) {
                return Number(section_payoff.adjusted)
            });
            total.adjust = _.sumBy(section_payoffs, function(section_payoff) {
                return Number(section_payoff.adjust)
            });
            // -----------------------------------------------------------------

            // --------------------------------------------------------------
            // Return compiled data
            // --------------------------------------------------------------
            return {
                // -------------------------------------------------------------
                initial: initial,
                income: income,
                outcome: outcome,
                total: total,
                // -------------------------------------------------------------
                companies: companies,
                project: project,
                // -------------------------------------------------------------
                purchase_sale: purchase_sale,
                purchase_contract_mediations: purchase_contract_mediations,
                // -------------------------------------------------------------
                section: section,
                section_payoffs: section_payoffs,
                sale_contract: sale_contract,
                sale_deposits: sale_deposits,
                sale_mediations: sale_mediations,
                sale_fee: sale_fee,
                sale_fee_prices: sale_fee_prices,
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
            // profit rate total calculation
            // -----------------------------------------------------------------
            profit_rate_total: function() {
                let total = _.sumBy(this.section_payoffs, function(section_payoff) {
                    return Number(section_payoff.profit_rate);
                });

                this.section_payoffs.forEach(function(section_payoff, index) {
                    section_payoff.profit_rate_total = total
                });

                return total
            },
            // -----------------------------------------------------------------

            // get sale contract deposit data
            // spec B52-4
            // -----------------------------------------------------------------
            deposit_total: function() {
                let total = _.sumBy(this.sale_deposits, function(sale_deposit) {
                    if (sale_deposit.status == 3) // sum if deposit status = 3
                        return Number(sale_deposit.price);
                });
                total = total ?? 0;

                let delivery_price = this.sale_contract.delivery_status == 3 ?
                    this.sale_contract.delivery_price : 0;

                total = parseFloat(total) + parseFloat(delivery_price); // sum deposit price and delivery price

                return total
            },
            // -----------------------------------------------------------------

            // get sale mediation data
            // spec B52-5
            // -----------------------------------------------------------------
            mediation_total: function() {
                let group_by_trader = _.groupBy(this.sale_mediations, 'trader'); // sale mediation group by trader

                let mediation_total = [];
                _.forEach(group_by_trader, function(mediations, index) {
                    let total = _.sumBy(mediations, function(mediation) {
                        if (mediation.balance == 2 && mediation.status == 3) // sum if mediation balance = 1 & mediation status = 3
                            return Number(mediation.reward);
                    });
                    total = total ?? 0;

                    // push object to mediation total
                    mediation_total.push({
                        company_id: mediations[0].trader,
                        total: total
                    })
                });

                return mediation_total
            },
            // -----------------------------------------------------------------

            // get sale fee price data
            // spec B52-6
            // -----------------------------------------------------------------
            sale_fee_price_total: function() {
                let vm = this
                let total = _.sumBy(this.sale_fee_prices, function(sale_fee_price) {
                    if (vm.sale_fee.balance == 2 && sale_fee_price.status == 3) // sum if sale fee balance = 1 & sale fee status = 3
                        return Number(sale_fee_price.price);
                });
                total = total ?? 0;

                return total
            },
            // -----------------------------------------------------------------

            // get purchase contract mediation data
            // spec B52-7
            // -----------------------------------------------------------------
            purchase_mediation_total: function() {
                let group_by_trader = _.groupBy(this.purchase_contract_mediations, 'trader_company_id');

                let mediation_total = [];
                _.forEach(group_by_trader, function(mediations, index) {
                    let total = _.sumBy(mediations, function(mediation) {
                        if (mediation.balance == 1 && mediation.status == 2) // sum if mediation balance = 1 & mediation status = 2
                            return Number(mediation.reward);
                    });
                    total = total ?? 0;

                    // push object to mediation total
                    mediation_total.push({
                        company_id: mediations[0].trader_company_id,
                        total: total
                    })
                });

                return mediation_total
            },
            // -----------------------------------------------------------------

            // get sale contract tax income data
            // spec B52-8
            // -----------------------------------------------------------------
            tax_total: function() {
                let total = 0;

                if (this.sale_contract.real_estate_tax_income_status == 3)
                    total = parseFloat(this.sale_contract.real_estate_tax_income) ?? 0;

                return total
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
            section_payoffs: {
                deep: true,
                immediate: true,
                handler: function (section_payoffs) {

                    section_payoffs.forEach((section_payoff, index) => {

                        // expense book distribution calculation
                        // spec B52-2
                        // -----------------------------------------------------
                        // let expense_book_distribution = this.section.book_price * section_payoff.profit_rate / 100;
                        let expense_book_distribution = this.section.book_price;
                        if (this.purchase_sale.company_id_organizer == section_payoff.company_id)
                            Vue.set(section_payoff, 'expense_book_distribution', expense_book_distribution);
                        else
                            Vue.set(section_payoff, 'expense_book_distribution', 0);
                        // -----------------------------------------------------
                        // expense book total calculation
                        // -----------------------------------------------------
                        this.outcome.book = _.sumBy(section_payoffs, function(section_payoff) {
                            return Number(section_payoff.expense_book_distribution)
                        });
                        // -----------------------------------------------------

                        // sales income distribution calculation
                        // spec 52-4
                        // -----------------------------------------------------
                        // let sales_income_distribution = this.deposit_total * section_payoff.profit_rate / 100;
                        let sales_income_distribution = this.deposit_total;
                        if (this.purchase_sale.company_id_organizer == section_payoff.company_id)
                            Vue.set(section_payoff, 'sales_income_distribution', sales_income_distribution);
                        else
                            Vue.set(section_payoff, 'sales_income_distribution', 0);
                        // -----------------------------------------------------
                        // total sales income calculation
                        // -----------------------------------------------------
                        this.income.sales = _.sumBy(section_payoffs, function(section_payoff) {
                            return Number(section_payoff.sales_income_distribution)
                        });
                        // -----------------------------------------------------

                        // sales brokarage income distribution calculation
                        // spec 52-5
                        // -----------------------------------------------------
                        if (this.mediation_total.length < 1) {
                            Vue.set(section_payoff, 'brokerage_income_distribution', 0);
                        }else {
                            this.mediation_total.forEach((mediation_total, i) => {
                                // let brokerage_income_distribution = mediation_total.total * section_payoff.profit_rate / 100;
                                let brokerage_income_distribution = mediation_total.total;
                                if (mediation_total.company_id == section_payoff.company_id)
                                    Vue.set(section_payoff, 'brokerage_income_distribution', brokerage_income_distribution);
                                else {
                                    if (!section_payoff.brokerage_income_distribution)
                                        Vue.set(section_payoff, 'brokerage_income_distribution', 0);
                                }
                            });
                        }
                        // -----------------------------------------------------
                        // total sales brokarage income calculation
                        // -----------------------------------------------------
                        this.income.brokerage = _.sumBy(section_payoffs, function(section_payoff) {
                            return Number(section_payoff.brokerage_income_distribution)
                        });
                        // -----------------------------------------------------

                        // sales fee income distribution calculation
                        // spec 52-6
                        // -----------------------------------------------------
                        // let fee_income_distribution = this.sale_fee_price_total * section_payoff.profit_rate / 100;
                        let fee_income_distribution = this.sale_fee_price_total;
                        if (this.sale_fee.receipt_company == section_payoff.company_id)
                            Vue.set(section_payoff, 'fee_income_distribution', fee_income_distribution);
                        else
                            Vue.set(section_payoff, 'fee_income_distribution', 0);
                        // -----------------------------------------------------
                        // total sales fee income calculation
                        // -----------------------------------------------------
                        this.income.fee = _.sumBy(section_payoffs, function(section_payoff) {
                            return Number(section_payoff.fee_income_distribution)
                        });
                        // -----------------------------------------------------
                        // sales brokarage income at purchase distribution calculation
                        // spec 52-7
                        // -----------------------------------------------------
                        if (this.purchase_mediation_total.length < 1) {
                            Vue.set(section_payoff, 'purchase_income_distribution', 0);
                        }else {
                            this.purchase_mediation_total.forEach((mediation_total, i) => {
                                // let purchase_income_distribution = mediation_total.total * section_payoff.profit_rate / 100;
                                let purchase_income_distribution = mediation_total.total;
                                if (mediation_total.company_id == section_payoff.company_id)
                                    Vue.set(section_payoff, 'purchase_income_distribution', purchase_income_distribution);
                                else {
                                    if (!section_payoff.purchase_income_distribution)
                                        Vue.set(section_payoff, 'purchase_income_distribution', 0);
                                }
                            });
                        }

                        // -----------------------------------------------------
                        // total sales brokarage income at purchase calculation
                        // -----------------------------------------------------
                        this.income.purchase = _.sumBy(section_payoffs, function(section_payoff) {
                            return Number(section_payoff.purchase_income_distribution)
                        });
                        // -----------------------------------------------------

                        // property tax income distribution calculation
                        // spec 52-8
                        // -----------------------------------------------------
                        // let tax_income_distribution = this.tax_total * section_payoff.profit_rate / 100;
                        let tax_income_distribution = this.tax_total;
                        if (this.purchase_sale.company_id_organizer == section_payoff.company_id)
                            Vue.set(section_payoff, 'tax_income_distribution', tax_income_distribution);
                        else
                            Vue.set(section_payoff, 'tax_income_distribution', 0);
                        // -----------------------------------------------------
                        // total property tax income calculation
                        // -----------------------------------------------------
                        this.income.tax = _.sumBy(section_payoffs, function(section_payoff) {
                            return Number(section_payoff.tax_income_distribution)
                        });
                        // -----------------------------------------------------

                        // total income distribution calculation
                        // spec 52-3
                        // -----------------------------------------------------
                        let total_income_distribution = 0;
                        total_income_distribution = section_payoff.sales_income_distribution
                                                    + section_payoff.brokerage_income_distribution
                                                    + section_payoff.fee_income_distribution
                                                    + section_payoff.purchase_income_distribution
                                                    + section_payoff.tax_income_distribution;
                        total_income_distribution = total_income_distribution ?? 0;
                        Vue.set(section_payoff, 'total_income_distribution', total_income_distribution)
                        // -----------------------------------------------------
                        // total income calculation
                        // -----------------------------------------------------
                        this.income.total = this.income.sales + this.income.brokerage
                                            + this.income.fee + this.income.purchase
                                            + this.income.tax;
                        this.income.total = this.income.total == 0 ?
                                            0 : this.income.total;
                        // -----------------------------------------------------

                        // profit distribution calculation
                        // spec 52-9
                        // -----------------------------------------------------
                        let profit_distribution = 0;
                        profit_distribution = section_payoff.total_income_distribution - section_payoff.expense_book_distribution;
                        profit_distribution = profit_distribution ?? 0;
                        Vue.set(section_payoff, 'profit', profit_distribution)
                        // -----------------------------------------------------
                        // profit total calculation
                        // -----------------------------------------------------
                        this.total.profit = this.income.total - this.outcome.book;
                        this.total.profit = this.total.profit == 0 ?
                                                  0 : this.total.profit;
                        // -----------------------------------------------------
                    });
                },
            },
        },

        /*
        ## ------------------------------------------------------------------
        ## VUE METHOD
        ## function associated with the vue instance
        ## ------------------------------------------------------------------
        */
        methods: {
            // set input visibility
            // -----------------------------------------------------------------
            setVisibility: function(){
                if (this.initial.is_visible) this.initial.is_visible = false;
                else this.initial.is_visible = true;
            },
            // -----------------------------------------------------------------

            // B2-10 and B2-11
            // -----------------------------------------------------------------
            calculation_adjust: function(section_payoffs) {
                // show alert if profit rate total is not 100
                // -------------------------------------------------------------
                if (this.profit_rate_total != 100) {
                    alert('配分算出は合計100%になるよう設定してください。');
                    return;
                }
                // -------------------------------------------------------------

                section_payoffs.forEach((section_payoff, index) => {
                    // adjusted distribution calculation
                    // spec 52-11
                    // -----------------------------------------------------
                    let adjusted_distribution = 0;
                    adjusted_distribution = (this.income.total - this.outcome.book)
                                            * section_payoff.profit_rate / 100;
                    adjusted_distribution = adjusted_distribution ?? 0;
                    Vue.set(section_payoff, 'adjusted', adjusted_distribution)
                    // -----------------------------------------------------
                    // adjusted total calculation
                    // -----------------------------------------------------
                    this.total.adjusted = _.sumBy(section_payoffs, function(section_payoff) {
                        return Number(section_payoff.adjusted)
                    });
                    // -----------------------------------------------------
                });

                section_payoffs.forEach((section_payoff, index) => {
                    // adjust distribution calculation
                    // spec 52-10
                    // -----------------------------------------------------
                    let adjust_distribution = this.total.profit.toFixed(2) - this.total.adjusted.toFixed(2);
                    if (this.purchase_sale.company_id_organizer == section_payoff.company_id)
                        Vue.set(section_payoff, 'adjust', adjust_distribution);
                    else
                        Vue.set(section_payoff, 'adjust', 0);
                    // -----------------------------------------------------
                    // adjust total calculation
                    // -----------------------------------------------------
                    this.total.adjust = _.sumBy(section_payoffs, function(section_payoff) {
                        return Number(section_payoff.adjust)
                    });
                    // -----------------------------------------------------
                });
            },
            // -----------------------------------------------------------------

            // reset B2-10 and B2-11 value
            // -----------------------------------------------------------------
            reset_adjust: function(section_payoffs){
                section_payoffs.forEach((section_payoff, index) => {
                    section_payoff.adjust = 0; section_payoff.adjusted = 0;
                    this.total.adjust = 0; this.total.adjusted = 0;
                    section_payoff.profit_rate = null;
                });

                this.initial.reset_button_clicked = true;
                $("#form").submit();
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
                // show alert if profit rate total is not 100
                // ignore if save data with reset button
                // -------------------------------------------------------------
                if (vm.profit_rate_total != 100 && !vm.initial.reset_button_clicked) {
                    alert('配分算出は合計100%になるよう設定してください。');
                    return;
                }
                // -------------------------------------------------------------

                // compile post data
                // ----------------------------------------------------------
                let data = {
                    section_payoffs: vm.section_payoffs,
                };
                // ----------------------------------------------------------

                // set loading state
                // ----------------------------------------------------------
                vm.initial.submited = true;

                // handle update request
                // ----------------------------------------------------------
                let url = vm.initial.url;
                let request = axios.post('{{ $update_url }}', data);
                // ----------------------------------------------------------

                // handle success response
                // ----------------------------------------------------------
                request.then(function(response) {

                    // update vue data with response data
                    // ---------------------------------------------------------
                    let data = response.data.data;
                    vm.section = data.section;
                    vm.section_payoffs = data.section_payoffs;
                    vm.total.adjusted = _.sumBy(vm.section_payoffs, function(section_payoff) {
                        return Number(section_payoff.adjusted)
                    });
                    vm.total.adjust = _.sumBy(vm.section_payoffs, function(section_payoff) {
                        return Number(section_payoff.adjust)
                    });
                    // ---------------------------------------------------------

                    Vue.nextTick(function () {
                        // show toast success message
                        $.toast({
                            heading: '成功', icon: 'success',
                            position: 'top-right', stack: false, hideAfter: 4000,
                            text: response.data.message,
                            position: { right: 18, top: 68 }
                        });

                        scrollTop(); // Add scroll-top
                    });
                });
                // ----------------------------------------------------------

                // handle error response
                // ----------------------------------------------------------
                request.catch(function(error) {
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
                request.finally(function(response) {
                    vm.initial.submited = false;
                    vm.initial.reset_button_clicked = false;
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
