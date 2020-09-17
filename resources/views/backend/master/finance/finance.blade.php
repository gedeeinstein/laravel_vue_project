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
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">@lang('master.base.section')</a></li>
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">@lang('master.base.tabs.finance')</a></li>
    <li class="breadcrumb-item active">{{ $page_title }}</li>
</ol>
@endsection

@section('content')
<form id="master_finance_form" method="POST" data-parsley class="parsley-minimal" v-cloak>
    @csrf
    <!-- Purchaser Contractor Information -->
    @include('backend.master.finance.form.contractors')

    <!-- Withdrawal Account -->
    @include('backend.master.finance.form.account')

    <!-- Loan Information -->
    @include('backend.master.finance.form.loan')

    <!-- Withdrawal Information -->
    @include('backend.master.finance.form.withdrawal')

    <!-- Expense Section -->
    @include('backend.master.finance.form.expense')

    <!-- Start - Submit Button -->
    <div class="bottom mt-4 mb-5 text-center">
        <button type="submit" class="btn btn-wide btn-info px-4">
            <i v-if="!initial.submited" class="fas fa-save"></i>
            <i v-else class="fas fa-spinner fa-spin"></i>
            保存
        </button>
    </div>
    <!-- End - Submit Button -->
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
            // --------------------------------------------------------------
            // Basic Data Master Finance
            // ---------------------------------------------------------------
            let data = @json($data);
            let master = @json($master);
            let finance = this.formatFinanceData(data);
            // --------------------------------------------------------------
            let initial = {
                submited: false,
                loading: true,
                editable: true,
                update_url: '{{ $update_url }}',
                delete_url: '{{ $delete_url }}'
            };
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Return compiled data
            // --------------------------------------------------------------
            return {
                initial: initial,
                master: master,
                project: finance.project,
                finance: finance.finance,
                contractors: finance.contractors,
                accounts: finance.accounts,
                loans: finance.loans,
                return_bank: finance.return_bank,
                expenses: finance.expenses,
                kana: data.kana_index
            };
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
            // filter selected bank acount
            selected_banks: function () {
                let accounts = this.accounts.filter(function(account){
                    return !account.deleted;
                });
                let account_main = _.map(accounts, 'account_main');
                return this.master.banks.filter(function(bank){
                    return account_main.indexOf(bank.id) !== -1;
                });
            },
            // calculated loans
            calculated: function () {
                let loans = [];
                this.loans.data.forEach(function(data){
                    let total = {
                        loan_total_budget: 0, loan_total: 0,
                        repayments: [],
                        money_total: 0, book_price_total: 0, book_price_reference_total: 0,
                        loan_balance_money_budget: 0, loan_balance_money: 0
                    };
                    // ------------------------------------------------------
                    // Loan Moneys total
                    // ------------------------------------------------------
                    data.moneys.forEach(function(item){
                        total.loan_total_budget += item.loan_money;
                        if (item.loan_status == 4 || item.loan_status == 5) {
                            total.loan_total += item.loan_money;
                        }
                    });
                    // ------------------------------------------------------
                    // Repayments
                    // (B23-4(Total) ー 23-26 ("済" is selected in B23-25)) / B23-25(number other than "済" )
                    // ------------------------------------------------------
                    // calculate other money
                    let other_money = _.sumBy(data.repayment_others, 'money');
                    if (other_money) {
                        total.money_total += other_money;
                    }
                    // extract total not done
                    let not_done = _.filter(data.repayment_sales, function(o) {
                        return o.status!=3
                    }).length;
                    // calculate each repayment
                    let paid = 0;
                    data.repayment_sales.forEach(function(item){
                        // calculate book price
                        paid += item.status == 3 ? item.book_price : 0;
                        reference = (total.loan_total_budget-paid);
                        if (not_done != 0) {
                            reference = reference/not_done;
                        }
                        if(item.status != 3){
                            item.book_price_reference = reference;
                        }
                        // calculate total
                        total.money_total += Number(item.money);
                        total.book_price_total += Number(item.book_price);
                        total.book_price_reference_total += Number(item.book_price_reference);
                    });
                    // ------------------------------------------------------
                    // Total Balance
                    // ------------------------------------------------------
                    let paid_sales = _.sumBy(data.repayment_sales, function(item) { return item.status == 3 ? item.money : 0 });
                    let paid_other = _.sumBy(data.repayment_others, function(item) { return item.status == 3 ? item.money : 0 });
                    let total_paid = paid_sales+paid_other;
                    total.loan_balance_money_budget = (total.loan_total_budget-total.money_total);
                    total.loan_balance_money = (total.loan_total-total_paid);

                    // ------------------------------------------------------
                    loans.push(_.cloneDeep(total));
                });
                return loans;
            },
        },

        /*
        ## ------------------------------------------------------------------
        ## VUE WATCH
        ## vue reactive data watch
        ## ------------------------------------------------------------------
        */
        watch: {
            // watch loan data
            loans: {
                deep: true,
                handler: function( loan ){
                    let isdone = [];
                    this.loans.data.forEach((loan, index) => {
                        // check done repayment status
                        loan.repayment_sales.forEach(sales => {
                            if (sales.status == 3) {
                                isdone.push(sales.section_number);
                            }
                        });
                    });
                    this.loans.data.forEach((loan, index) => {
                        // if(isdone.length == 0) return;
                        // check for next repayment data
                        // if prev data is already done disable next repayment
                        loan.repayment_sales.forEach(sales => {
                            if (isdone.includes(sales.section_number) && sales.status != 3) {
                                sales.disabled = true;
                            }else {
                                sales.disabled = false;
                            }
                        });
                    });
                }
            },

        },

        /*
        ## ------------------------------------------------------------------
        ## VUE METHOD
        ## function associated with the vue instance
        ## ------------------------------------------------------------------
        */
        methods: {
            // --------------------------------------------------------------
            //  Add / Remove Account Main
            // --------------------------------------------------------------
            addAccount: function(){
                this.accounts.push({
                    id: null, account_main: null
                });
            },
            removeAccount: function(index){
                let data = this.accounts[index];
                let confirmed = false;
                // check is has data
                if (data.account_main) confirmed = confirm('@lang('label.confirm_delete')');
                else this.accounts.splice(index, 1);
                // if confirmed
                if (confirmed) {
                    Vue.set( data, 'deleted', true );
                }
                refreshTooltip();
            },

            // --------------------------------------------------------------
            //  Add / Remove Account Main
            // --------------------------------------------------------------
            addLoanMoney: function(moneys){
                moneys.push({
                    id: null, loan_money: null, loan_date: null,
                    loan_note: null, loan_status: null
                });
            },
            removeLoanMoney: function(moneys, index){
                let data = moneys[index];
                let confirmed = false;
                // check is has data
                if (data.id) confirmed = confirm('@lang('label.confirm_delete')');
                else moneys.splice(index, 1);
                // if confirmed
                if (confirmed) {
                    Vue.set( data, 'deleted', true );
                }
                refreshTooltip();
            },

            // --------------------------------------------------------------
            //  Add / Remove Other Repayment
            // --------------------------------------------------------------
            addOtherRepayment: function(loan){
                let repayment = _.cloneDeep(this.loans.default.repayment);
                repayment.data_type = 'others';
                repayment.section_number = '他';
                loan.repayment_others.push(repayment);
            },
            removeOtherRepayment: function(other, index){
                let data = other[index];
                let confirmed = false;
                // check is has data
                if (data.id) confirmed = confirm('@lang('label.confirm_delete')');
                else other.splice(index, 1);
                // if confirmed
                if (confirmed) {
                    Vue.set( data, 'deleted', true );
                }
                refreshTooltip();
            },

            // --------------------------------------------------------------
            //  Copy Book Price Reference
            // --------------------------------------------------------------
            copyReference: function(repayment){
                repayment.book_price = repayment.book_price_reference;
            },

            // --------------------------------------------------------------
            //  Add / Remove Loans Page
            // --------------------------------------------------------------
            addLoan: function(){
                let data = _.cloneDeep(this.loans.default);
                // check loans data
                let isdone = [];
                this.loans.data.forEach(loan => {
                    let done = loan.repayment_sales.filter(function(repayment){
                        return repayment.status == 3;
                    });
                    isdone.push(done);
                });
                console.log(isdone);
                this.loans.data.push(data);
            },
            removeLoan: function(page){
                let loan = this.loans.data[page];
                let defaultData = JSON.stringify(this.loans.default);
                let data = JSON.stringify(loan);
                let confirmed = false;
                let vm = this;
                // check if not inputed
                if (defaultData !== data) confirmed = confirm('@lang('label.confirm_delete')');
                else this.loans.data.splice(page, 1);
                // if confirmed
                if (confirmed) {
                    if (loan.id === null) {
                        this.loans.data.splice(page, 1);
                    }else{
                        // handle delete row request
                        let delete_row = axios.delete(vm.initial.delete_url, {
                            data: loan
                        });
                        // handle success response
                        delete_row.then(function (response) {
                            if (response.data.status == 'success') {
                                vm.loans.data.splice(page, 1);
                                $.toast({
                                    heading: '成功', icon: 'success',
                                    position: 'top-right', stack: false, hideAfter: 3000,
                                    text: response.data.message,
                                    position: { right: 18, top: 68 }
                                });
                            }
                        })
                        // handle error response
                        delete_row.catch(function (error) {
                            $.toast({
                                heading: '失敗', icon: 'error',
                                position: 'top-right', stack: false, hideAfter: 3000,
                                text: [error.response.data.message, error.response.data.error],
                                position: { right: 18, top: 68 }
                            });
                        });
                    }
                }
                refreshTooltip();
            },

            // --------------------------------------------------------------
            // Add And Remove Expense Row
            // --------------------------------------------------------------
            addExpenseRow: function(data){
                let row = _.cloneDeep(data[0]);
                let maxNumber = Math.max.apply(Math, data.map(function(row) { return row.number; }));
                row.number = maxNumber+1;
                row.id = null;
                row.decided = '';
                row.payperiod = '';
                row.payee = '';
                row.note = '';
                row.paid = '';
                row.date = '';
                row.bank = '';
                row.status = 0;
                if (!row.const_tax) {
                    row.taxfree = '';
                }
                data.push(row);
                refreshTooltip();
            },
            removeExpenseRow: function(rows, index){
                let vm = this;
                let data = rows[index];
                let confirmed = true;
                // check if user already input data
                let inputed = function(data){
                    return (data.decided || data.payperiod || data.payee || data.note || data.paid || data.date || data.bank);
                }
                if(inputed(data)) confirmed = confirm('@lang('label.confirm_delete')');
                if (confirmed) {
                    // check if data not saved
                    if (data.id == null) {
                        rows.splice(index, 1);
                        return refreshTooltip();
                    }else{
                        Vue.set( data, 'deleted', true );
                    }
                }
                refreshTooltip();
            },
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Copy Button Function
            // --------------------------------------------------------------
            copyExpense: function(row, type){
                // ----------------------------------------------------------
                switch (type) {
                    case 'budget':
                        row.decided = row.budget ? row.budget : row.decided;
                        break;
                    case 'decided':
                        row.paid = row.decided ? row.decided : row.paid;
                        break;
                    default:
                        break;
                }
                // ----------------------------------------------------------
            },

            // --------------------------------------------------------------
            //  Check Required Loan Data
            // --------------------------------------------------------------
            checkRequired: function(loans, page, type){
                let data = loans[page];
                switch (type) {
                    case 'unit':
                        return data.moneys.some(el => el.loan_money !== null);
                    case 'money':
                        return data.moneys.some(el => el.loan_money !== null || el.loan_date !== null );
                    default:
                        break;
                }
            },

            // --------------------------------------------------------------
            //  Check Required Loan Data
            // --------------------------------------------------------------
            datepickerChange: function(value){
                if (value != null) {
                    resetParsley();
                }
            },

            // --------------------------------------------------------------
            //  Reformat Master Finance Data
            // --------------------------------------------------------------
            formatFinanceData: function(data){
                // helper function check if exist property of object
                let isExist = function(prop){
                    return (prop === undefined) ? false : (prop === null) ? false : (prop.length == 0) ? false : true;
                };
                // ----------------------------------------------------------
                // Project & Finance Data
                // ----------------------------------------------------------
                let project = data.project;
                let finance = isExist(project.finance) ? project.finance : {
                    id: null, project_id: project.id
                };
                // ----------------------------------------------------------
                // Contractors Data
                // ----------------------------------------------------------
                let contractor_default = {
                    id: null, address: null, identification: null,
                    identification_attach: null, pj_lot_contractor_id: null
                }
                // if exist contractor data from database
                let contractors = isExist(finance.contractors) ? finance.contractors : [];
                // compare data contractors with purchuase contractor
                if (contractors.length == 0) {
                    data.contractors.forEach(function(item) {
                        // check if not exist contractor push data
                        if (!isExist(item.purchaser)) {
                            base = contractor_default;
                            base.pj_lot_contractor_id = item.id;
                            base.purchaser = item;
                            contractors.push(_.cloneDeep(base));
                        }
                    });
                }
                // check if contractors is empty push default data
                if (contractors.length === 0) {
                    base = contractor_default;
                    base.disabled = true;
                    contractors.push(base);
                }
                // ----------------------------------------------------------
                // Accounts Main
                // ----------------------------------------------------------
                let accounts = isExist(finance.accounts) ? finance.accounts : [
                    { id: null, account_main: null }
                ];
                // ----------------------------------------------------------
                // Loans Data
                // ----------------------------------------------------------
                // init default loans data
                let loans = {
                    default: {
                        id: null, mas_finance_id: finance.id, loan_lender: null,
                        loan_mortgage: 2, loan_account: null, loan_balance_money_budget: null,
                        loan_balance_money: null, loan_ratio: null, loan_period_date: null,
                        loan_type: null, loan_type_date: null, loan_total_budget: null, loan_total: null,
                        moneys: [{
                            id: null, loan_money: null, loan_date: null,
                            loan_note: null, loan_status: 1
                        }],
                        repayment: {
                            id: null, section_number: null, money: null, date: null,
                            account: null, note: null, status: 1,
                            book_price: null, book_price_reference: 0,
                            money_total: 0, book_price_total: 0, book_price_reference_total: 0,
                            data_type: null
                        },
                        repayment_sales: [],
                        repayment_others: []
                    },
                    data: []
                };
                // ----------------------------------------------------------
                // helper to generate repayment data
                let dataRepayment = function(type, no, repayment = null){
                    if (repayment == null) {
                        repayment = _.cloneDeep(loans.default.repayment);
                    }
                    repayment.data_type = type;
                    repayment.section_number = no;
                    return repayment;
                };
                let section_numbers = data.section_numbers;
                // ----------------------------------------------------------
                // check units data from database
                finance.units = isExist(finance.units) ? finance.units : [];
                // loop repyament unit data from database
                finance.units.forEach(function(unit) {
                    if (unit.moneys.length === 0) {
                        unit.moneys = _.cloneDeep(loans.default.moneys);
                    }
                    // SALES: if empty repayment sales
                    if (unit.repayment_sales.length === 0) {
                        // loop master setting section add repayment data
                        section_numbers.forEach(function(number){
                            let items = dataRepayment('sales', number);
                            unit.repayment_sales.push(_.cloneDeep(items));
                        });
                    }else{
                        // if there new sections
                        let repayment_number = _.map(unit.repayment_sales, 'section_number');
                        let new_items = section_numbers.filter(function(number){
                            return repayment_number.indexOf(number) === -1;
                        });
                        // push new items
                        new_items.forEach(function(number){
                            let items = dataRepayment('sales', number);
                            unit.repayment_sales.push(_.cloneDeep(items));
                        });
                    }
                    // OTHER: if empty repayment other
                    if (unit.repayment_others.length === 0) {
                        unit.repayment_others.push(dataRepayment('others', '他'));
                    }
                    // push loans data from database
                    loans.data.push(_.cloneDeep(unit));
                });
                // ----------------------------------------------------------
                // assign default data
                let loan_default = loans.default;
                // loop master setting section add repayment sales data
                section_numbers.forEach(function(number){
                    let items = dataRepayment('sales', number);
                    loan_default.repayment_sales.push(_.cloneDeep(items));
                });
                // add repayment other
                loan_default.repayment_others.push(dataRepayment('others', '他'));
                // ----------------------------------------------------------
                // check if data from database empty push default data
                if (loans.data.length === 0) {
                    loans.data.push(_.cloneDeep(loan_default));
                }

                // ----------------------------------------------------------
                // Return Bank
                // ----------------------------------------------------------
                let return_bank = isExist(finance.return_bank) ? finance.return_bank : {
                    id: null, amount: null, withdraw_bank: null, deposit_bank: null
                };
                // ----------------------------------------------------------
                // Return Bank
                // ----------------------------------------------------------
                let expenses = isExist(data.expenses) ? data.expenses : {
                    data: []
                };

                // ----------------------------------------------------------
                // return object data
                // ----------------------------------------------------------
                return {
                    project: project,
                    finance: finance,
                    contractors: contractors,
                    accounts: accounts,
                    loans: loans,
                    return_bank: return_bank,
                    expenses: expenses
                }
                // ----------------------------------------------------------
            },
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
                // ----------------------------------------------------------
                let data = {
                    finance_id: vm.finance.id,
                    contractors: vm.contractors,
                    accounts: vm.accounts,
                    loans: vm.loans.data,
                    return_bank: vm.return_bank,
                    expenses: vm.expenses,
                };
                // ----------------------------------------------------------

                // set loading state
                // ----------------------------------------------------------
                vm.initial.submited = true;

                // handle update request
                // ----------------------------------------------------------
                let url = vm.initial.update_url;
                let req_update = axios.post(url, data);
                // ----------------------------------------------------------

                // handle success response
                // ----------------------------------------------------------
                req_update.then(function (response) {
                    console.log(response)
                    if (response.data.status == 'success') {
                        // update vue data from response data
                        let data = response.data.data;
                        let finance = vm.formatFinanceData(data);

                        vm.finance = finance.finance;
                        vm.accounts = finance.accounts;
                        vm.loans = finance.loans;
                        vm.return_bank = finance.return_bank;
                        vm.expenses = finance.expenses;

                        Vue.nextTick(function () {
                            // show toast success message
                            $.toast({
                                heading: '成功', icon: 'success',
                                position: 'top-right', stack: false, hideAfter: 4000,
                                text: response.data.message,
                                position: { right: 18, top: 68 }
                            });
                        });
                    }
                })
                // ----------------------------------------------------------

                // handle error response
                // ----------------------------------------------------------
                req_update.catch(function (error) {
                    console.log(error.response)
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
                    vm.initial.submited = false;
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