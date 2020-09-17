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
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">@lang('project.base.tabs.expense')</a></li>
    <li class="breadcrumb-item active">{{ $page_title }}</li>
</ol>
@endsection

@section('content')
<form id="expense_form" method="POST" data-parsley class="parsley-minimal" v-cloak>
    @csrf
        <!-- Start - Caption -->
        <ul class="px-4">
            <li>状況が「保」「確」の場合、背景色がオレンジになります。</li>
        </ul>
        <!-- End - Caption -->

        <!-- Start - Expense Section -->
        @include('backend.project.expense.form.section-a')
        @include('backend.project.expense.form.section-b')
        @include('backend.project.expense.form.section-c')
        @include('backend.project.expense.form.section-d')
        @include('backend.project.expense.form.section-e')
        @include('backend.project.expense.form.section-f')
        @include('backend.project.expense.form.section-g')
        @include('backend.project.expense.form.section-h')
        @include('backend.project.expense.form.section-i')
        @include('backend.project.expense.form.section-j')
        <!-- End - Expense Section -->

    <div class="bottom mt-4 mb-5 text-center">
        <button type="submit" class="btn btn-wide btn-info px-4">
            <i v-if="!initial.submited" class="fas fa-save"></i>
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
            // --------------------------------------------------------------
            // Basic Data Project Expense
            // --------------------------------------------------------------
            let initial = {
                submited: false,
                loading: true,
                editable: true,
                tax: @json( $taxes ),
                update_url: '{{ $update_url }}',
                delete_url: '{{ $delete_url }}'
            };
            // --------------------------------------------------------------
            let master = @json( $master )
            // --------------------------------------------------------------
            let project = @json( $project );
            let expense = @json( $expense );
            let sections = @json( $sections );
            // --------------------------------------------------------------
            if (expense == null) expense = {
                id: null, project_id: project.id,
                mediation_note: null, mediation_sell_note: null,
                fee_note: null, total_note: null, total_note_tsubo: null
            }
            // --------------------------------------------------------------
            let default_row = {
                id: null, pj_expense_id: expense.id,
                decided: null, payperiod: null, payee: null,
                note: null, paid: null, date: null,
                bank: null, taxfree: null, status: null,
                data_kind: null, screen_name: null, screen_index: null,
            }
            // --------------------------------------------------------------

            // compile data to match vue binding
            sections = this.compileSections(sections);
            console.log( sections );

            // --------------------------------------------------------------
            // Return compiled data
            // --------------------------------------------------------------
            return {
                initial: initial,
                master: master,
                project: project,
                expense: expense,
                sections: sections,
                default_row: default_row,
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
            // --------------------------------------------------------------
            // Section A calculated total taxes
            // --------------------------------------------------------------
            total_a: function () {
                let tax = this.initial.tax;
                let data = this.sections.a.data;
                // init total data
                let total = {
                    budget: 0, decided: 0, paid: 0, balance: 0,
                    budget_tax: 0, decided_tax: 0, paid_tax: 0, balance_tax: 0,
                }
                // ----------------------------------------------------------
                // Calculate dynamic data
                // ----------------------------------------------------------
                let vm = this;
                data.forEach(function(item){
                    total.budget += vm.calculateTotalTax(item, 'budget');
                    total.budget_tax += vm.calculateTax(item, 'budget');
                    total.decided += vm.calculateTotalTax(item, 'decided');
                    total.decided_tax += vm.calculateTax(item, 'decided');
                    total.paid += vm.calculateTotalPaidTax(item, 'paid');
                    total.paid_tax += vm.calculatePaidTax(item, 'paid');
                });
                // ----------------------------------------------------------
                // calculate total balance
                // ----------------------------------------------------------
                total.balance = total.decided - total.paid;
                total.balance_tax = total.decided_tax - total.paid_tax;
                // ----------------------------------------------------------
                return total;
            },
            // --------------------------------------------------------------
            // Section B calculated total taxes
            // --------------------------------------------------------------
            total_b: function () {
                let tax = this.initial.tax;
                let data = this.sections.b.data;
                // init total data
                let total = {
                    budget: 0, decided: 0, paid: 0, balance: 0,
                    budget_tax: 0, decided_tax: 0, paid_tax: 0, balance_tax: 0,
                }
                // ----------------------------------------------------------
                // Calculate dynamic data
                // ----------------------------------------------------------
                let vm = this;
                data.forEach(function(item){
                    total.budget += vm.calculateTotalTax(item, 'budget');
                    total.budget_tax += vm.calculateTax(item, 'budget');
                    total.decided += vm.calculateTotalTax(item, 'decided');
                    total.decided_tax += vm.calculateTax(item, 'decided');
                    total.paid += vm.calculateTotalPaidTax(item, 'paid', 2);
                    total.paid_tax += vm.calculatePaidTax(item, 'paid', 2);
                });
                // ----------------------------------------------------------
                // calculate total balance
                // ----------------------------------------------------------
                total.balance = total.decided - total.paid;
                total.balance_tax = total.decided_tax - total.paid_tax;
                // ----------------------------------------------------------
                return total;
            },
            // --------------------------------------------------------------
            // Section C calculated total taxes
            // --------------------------------------------------------------
            total_c: function () {
                // ----------------------------------------------------------
                let tax = this.initial.tax;
                let data = this.sections.c;
                // init total data
                // ----------------------------------------------------------
                let total = {
                    budget: 0, decided: 0, paid: 0, balance: 0,
                    budget_tax: 0, decided_tax: 0, paid_tax: 0, balance_tax: 0,
                }
                // ----------------------------------------------------------
                // calculate basic data teaxes
                // ----------------------------------------------------------
                budget = (data.register_budget*tax/100)+data.register_budget;
                budget += this.calculateTotalTax(data.data.row2, 'budget');
                budget += this.calculateTotalTax(data.data.row3, 'budget');
                budget_tax = (data.register_budget*tax/100);
                budget_tax += this.calculateTax(data.data.row2, 'budget');
                budget_tax += this.calculateTax(data.data.row3, 'budget');
                total.budget = budget;
                total.budget_tax = budget_tax;
                // ----------------------------------------------------------
                decided = this.calculateTotalTax(data.data.row1_0, 'decided');
                decided += this.calculateTotalTax(data.data.row1_1, 'decided');
                decided += this.calculateTotalTax(data.data.row2, 'decided');
                decided += this.calculateTotalTax(data.data.row3, 'decided');
                decided_tax = this.calculateTax(data.data.row1_0, 'decided');
                decided_tax += this.calculateTax(data.data.row1_1, 'decided');
                decided_tax += this.calculateTax(data.data.row2, 'decided');
                decided_tax += this.calculateTax(data.data.row3, 'decided');
                total.decided = decided;
                total.decided_tax = decided_tax;
                // ----------------------------------------------------------
                paid = this.calculateTotalPaidTax(data.data.row1_0, 'paid');
                paid += this.calculateTotalPaidTax(data.data.row1_1, 'paid');
                paid += this.calculateTotalPaidTax(data.data.row2, 'paid');
                paid += this.calculateTotalPaidTax(data.data.row3, 'paid');
                paid_tax = this.calculatePaidTax(data.data.row1_0, 'paid');
                paid_tax += this.calculatePaidTax(data.data.row1_1, 'paid');
                paid_tax += this.calculatePaidTax(data.data.row2, 'paid');
                paid_tax += this.calculatePaidTax(data.data.row3, 'paid');
                total.paid = paid;
                total.paid_tax = paid_tax;
                // ----------------------------------------------------------
                // Calculate other data taxes
                // ----------------------------------------------------------
                let vm = this;
                data.other = data.other || [];
                data.other.forEach(function(item){
                    total.budget += vm.calculateTotalTax(item, 'budget');
                    total.budget_tax += vm.calculateTax(item, 'budget');
                    total.decided += vm.calculateTotalTax(item, 'decided');
                    total.decided_tax += vm.calculateTax(item, 'decided');
                    total.paid += vm.calculateTotalPaidTax(item, 'paid');
                    total.paid_tax += vm.calculatePaidTax(item, 'paid');
                });
                // ----------------------------------------------------------
                // calculate total balance
                // ----------------------------------------------------------
                total.balance = total.decided - total.paid;
                total.balance_tax = total.decided_tax - total.paid_tax;
                // ----------------------------------------------------------
                return total;
            },
            // --------------------------------------------------------------
            // Section D calculated total taxes
            // --------------------------------------------------------------
            total_d: function () {
                let tax = this.initial.tax;
                let data = this.sections.d.data;
                // init total data
                let total = {
                    budget: 0, decided: 0, paid: 0, balance: 0,
                    budget_tax: 0, decided_tax: 0, paid_tax: 0, balance_tax: 0,
                }
                // ----------------------------------------------------------
                // Calculate dynamic data
                // ----------------------------------------------------------
                let vm = this;
                data.forEach(function(item){
                    total.budget += vm.calculateTotalTax(item, 'budget');
                    total.budget_tax += vm.calculateTax(item, 'budget');
                    total.decided += vm.calculateTotalTax(item, 'decided');
                    total.decided_tax += vm.calculateTax(item, 'decided');
                    total.paid += vm.calculateTotalPaidTax(item, 'paid');
                    total.paid_tax += vm.calculatePaidTax(item, 'paid');
                });
                // ----------------------------------------------------------
                // calculate total balance
                // ----------------------------------------------------------
                total.balance = total.decided - total.paid;
                total.balance_tax = total.decided_tax - total.paid_tax;
                // ----------------------------------------------------------
                return total;
            },
            // --------------------------------------------------------------
            // Section E calculated total taxes
            // --------------------------------------------------------------
            total_e: function () {
                let tax = this.initial.tax;
                let data = this.sections.e.data;
                // init total data
                let total = {
                    budget: 0, decided: 0, paid: 0, balance: 0,
                    budget_tax: 0, decided_tax: 0, paid_tax: 0, balance_tax: 0,
                }
                // ----------------------------------------------------------
                // Calculate dynamic data
                // ----------------------------------------------------------
                let vm = this;
                data.forEach(function(item){
                    total.budget += vm.calculateTotalTax(item, 'budget');
                    total.budget_tax += vm.calculateTax(item, 'budget');
                    total.decided += vm.calculateTotalTax(item, 'decided');
                    total.decided_tax += vm.calculateTax(item, 'decided');
                    total.paid += vm.calculateTotalPaidTax(item, 'paid');
                    total.paid_tax += vm.calculatePaidTax(item, 'paid');
                });
                // ----------------------------------------------------------
                // calculate total balance
                // ----------------------------------------------------------
                total.balance = total.decided - total.paid;
                total.balance_tax = total.decided_tax - total.paid_tax;
                // ----------------------------------------------------------
                return total;
            },
            // --------------------------------------------------------------
            // Section F calculated total taxes
            // --------------------------------------------------------------
            total_f: function () {
                let tax = this.initial.tax;
                let data = this.sections.f.data;
                // init total data
                let total = {
                    budget: 0, decided: 0, paid: 0, balance: 0,
                    budget_tax: 0, decided_tax: 0, paid_tax: 0, balance_tax: 0,
                }
                // ----------------------------------------------------------
                // Calculate dynamic data
                // ----------------------------------------------------------
                let vm = this;
                data.forEach(function(item){
                    total.budget += vm.calculateTotalTax(item, 'budget');
                    total.budget_tax += vm.calculateTax(item, 'budget');
                    total.decided += vm.calculateTotalTax(item, 'decided');
                    total.decided_tax += vm.calculateTax(item, 'decided');
                    total.paid += vm.calculateTotalPaidTax(item, 'paid');
                    total.paid_tax += vm.calculatePaidTax(item, 'paid');
                });
                // ----------------------------------------------------------
                // calculate total balance
                // ----------------------------------------------------------
                total.balance = total.decided - total.paid;
                total.balance_tax = total.decided_tax - total.paid_tax;
                // ----------------------------------------------------------
                return total;
            },
            // --------------------------------------------------------------
            // Section G calculated total taxes
            // --------------------------------------------------------------
            total_g: function () {
                let tax = this.initial.tax;
                let data = this.sections.g.data;
                // init total data
                let total = {
                    budget: 0, decided: 0, paid: 0, balance: 0,
                    budget_tax: 0, decided_tax: 0, paid_tax: 0, balance_tax: 0,
                }
                // ----------------------------------------------------------
                // Calculate dynamic data
                // ----------------------------------------------------------
                let vm = this;
                data.forEach(function(item){
                    total.budget += vm.calculateTotalTax(item, 'budget');
                    total.budget_tax += vm.calculateTax(item, 'budget');
                    total.decided += vm.calculateTotalTax(item, 'decided');
                    total.decided_tax += vm.calculateTax(item, 'decided');
                    total.paid += vm.calculateTotalPaidTax(item, 'paid');
                    total.paid_tax += vm.calculatePaidTax(item, 'paid');
                });
                // ----------------------------------------------------------
                // calculate total balance
                // ----------------------------------------------------------
                total.balance = total.decided - total.paid;
                total.balance_tax = total.decided_tax - total.paid_tax;
                // ----------------------------------------------------------
                return total;
            },
            // --------------------------------------------------------------
            // Section H calculated total taxes
            // --------------------------------------------------------------
            total_h: function () {
                let tax = this.initial.tax;
                let data = this.sections.h.data;
                // init total data
                let total = {
                    budget: 0, decided: 0, paid: 0, balance: 0,
                    budget_tax: 0, decided_tax: 0, paid_tax: 0, balance_tax: 0,
                }
                // ----------------------------------------------------------
                // Calculate dynamic data
                // ----------------------------------------------------------
                let vm = this;
                data.forEach(function(item){
                    total.budget += vm.calculateTotalTax(item, 'budget');
                    total.budget_tax += vm.calculateTax(item, 'budget');
                    total.decided += vm.calculateTotalTax(item, 'decided');
                    total.decided_tax += vm.calculateTax(item, 'decided');
                    total.paid += vm.calculateTotalPaidTax(item, 'paid');
                    total.paid_tax += vm.calculatePaidTax(item, 'paid');
                });
                // ----------------------------------------------------------
                // calculate total balance
                // ----------------------------------------------------------
                total.balance = total.decided - total.paid;
                total.balance_tax = total.decided_tax - total.paid_tax;
                // ----------------------------------------------------------
                return total;
            },
            // --------------------------------------------------------------
            // Section I calculated total taxes
            // --------------------------------------------------------------
            total_i: function () {
                let tax = this.initial.tax;
                let data = this.sections.i;
                let vm = this;
                // init total data
                let total = {
                    budget: 0, decided: 0, paid: 0, balance: 0,
                    budget_tax: 0, decided_tax: 0, paid_tax: 0, balance_tax: 0,
                }
                // ----------------------------------------------------------
                // calculate sale mediation taxes
                // ----------------------------------------------------------
                total.budget += vm.calculateTotalTax(data.mediations, 'budget');
                total.budget_tax += vm.calculateTax(data.mediations, 'budget');
                total.decided += vm.calculateTotalTax(data.mediations, 'decided');
                total.decided_tax += vm.calculateTax(data.mediations, 'decided');
                total.paid += vm.calculateTotalPaidTax(data.mediations, 'paid', 2);
                total.paid_tax += vm.calculatePaidTax(data.mediations, 'paid', 2);
                // ----------------------------------------------------------
                // calculate sale fee taxes
                // ----------------------------------------------------------
                total.budget += vm.calculateTotalTax(data.fees, 'budget');
                total.budget_tax += vm.calculateTax(data.fees, 'budget');
                total.decided += vm.calculateTotalTax(data.fees, 'decided');
                total.decided_tax += vm.calculateTax(data.fees, 'decided');
                total.paid += vm.calculateTotalPaidTax(data.fees, 'paid');
                total.paid_tax += vm.calculatePaidTax(data.fees, 'paid');

                // ----------------------------------------------------------
                // calculate total balance
                // ----------------------------------------------------------
                total.balance = total.decided - total.paid;
                total.balance_tax = total.decided_tax - total.paid_tax;
                // ----------------------------------------------------------
                return total;
            },
            // --------------------------------------------------------------
            // Section J calculated total of all expense
            // --------------------------------------------------------------
            total_j: function () {
                let tax = this.initial.tax;
                let data = this.sections.j;
                // init total data
                let total = {
                    expense_budget: 0, expense_decided: 0, expense_paid: 0, expense_balance: 0,
                    expense_tax_exlude_budget: 0, expense_tax_exlude_decided: 0, expense_tax_exlude_paid: 0,
                    expense_budget_tax: 0, expense_decided_tax: 0, expense_paid_tax: 0,
                    expense_budget_tsubo: 0, expense_decided_tsubo: 0, expense_paid_tsubo: 0, expense_balance_tsubo : 0
                }
                // collect all section total
                let section_total = [
                    this.total_a, this.total_b, this.total_c, this.total_d, this.total_e,
                    this.total_f, this.total_g, this.total_h, this.total_i
                ];

                // ----------------------------------------------------------
                // calculate total data expense
                // ----------------------------------------------------------
                section_total.forEach(function(value){
                    total.expense_budget += value.budget;
                    total.expense_decided += value.decided;
                    total.expense_paid += value.paid;

                    total.expense_budget_tax += value.budget_tax;
                    total.expense_decided_tax += value.decided_tax;
                    total.expense_paid_tax += value.paid_tax;
                });

                // ----------------------------------------------------------
                // calculate total balance
                // ----------------------------------------------------------
                total.expense_balance = total.expense_decided - total.expense_paid;

                // ----------------------------------------------------------
                // calculate tax excluded
                // ----------------------------------------------------------
                total.expense_tax_exlude_budget = total.expense_budget - total.expense_budget_tax;
                total.expense_tax_exlude_decided = total.expense_decided - total.expense_decided_tax;
                total.expense_tax_exlude_paid = total.expense_paid - total.expense_paid_tax;

                // ----------------------------------------------------------
                // calculate tsubo
                // ----------------------------------------------------------
                let paid_tsubo = data.total_tsubo;
                let balance_tsubo = window.tsubo(paid_tsubo);
                total.expense_paid_tsubo = paid_tsubo;
                total.expense_balance_tsubo = balance_tsubo;
                total.expense_budget_tsubo = (total.expense_budget%balance_tsubo);
                total.expense_decided_tsubo = (total.expense_decided%balance_tsubo);

                return total;
            },
        },

        /*
        ## ------------------------------------------------------------------
        ## VUE WATCH
        ## vue reactive data watch
        ## ------------------------------------------------------------------
        */
        watch: {
            // --------------------------------------------------------------
            // Watch total values to update the expense data
            // --------------------------------------------------------------
            total_j: {
                deep: true, immediate: true,
                handler: function( value ){
                    // ------------------------------------------------------
                    // Update total decided
                    // ------------------------------------------------------
                    var totalDecided = _.get( value, 'expense_decided' );
                    this.expense.total_decided = totalDecided;
                    // ------------------------------------------------------
                }
            }
            // --------------------------------------------------------------
        },

        /*
        ## ------------------------------------------------------------------
        ## VUE METHOD
        ## function associated with the vue instance
        ## ------------------------------------------------------------------
        */
        methods: {
            // --------------------------------------------------------------
            // Add And Remove Expense Row
            // --------------------------------------------------------------
            addExpenseRow: function(data){
                let row = _.cloneDeep(data[0]);
                let maxNumber = Math.max.apply(Math, data.map(function(row) { return row.number; }));
                row.number = maxNumber+1;
                // ----------------------------------------------------------
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
                // ----------------------------------------------------------
                data.push(row);
                refreshTooltip();
            },
            removeExpenseRow: function(rows, index){
                let vm = this;
                let data = rows[index];
                let confirmed = true;
                // check if user already input data
                // ----------------------------------------------------------
                let inputed = function(data){
                    return (data.decided || data.payperiod || data.payee || data.note || data.paid || data.date || data.bank);
                }
                // ----------------------------------------------------------
                if(inputed(data)) confirmed = confirm('@lang('label.confirm_delete')');
                // ----------------------------------------------------------
                if (confirmed) {
                    // check if data not saved
                    if (data.id == null) {
                        rows.splice(index, 1);
                        return refreshTooltip();
                    }

                    // handle delete row request
                    // ------------------------------------------------------
                    let delete_row = axios.delete(vm.initial.delete_url, {
                        data: data
                    });
                    // ------------------------------------------------------

                    // handle success response
                    // ------------------------------------------------------
                    delete_row.then(function (response) {
                        if (response.data.status == 'success') {
                            rows.splice(index, 1);
                            $.toast({
                                heading: '成功', icon: 'success',
                                position: 'top-right', stack: false, hideAfter: 3000,
                                text: response.data.message,
                                position: { right: 18, top: 68 }
                            });
                        }
                    })
                    // ------------------------------------------------------

                    // handle error response
                    // ------------------------------------------------------
                    delete_row.catch(function (error) {
                        $.toast({
                            heading: '失敗', icon: 'error',
                            position: 'top-right', stack: false, hideAfter: 3000,
                            text: [error.response.data.message, error.response.data.error],
                            position: { right: 18, top: 68 }
                        });
                    });
                    // ------------------------------------------------------
                }
                // ----------------------------------------------------------
                refreshTooltip();
            },
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Copy Button Function
            // --------------------------------------------------------------
            copyExpense: function(row, type){
                // ----------------------------------------------------------
                switch (type) {
                    // copy data A163-101 to A163-102
                    case 'budget':
                        row.decided = row.budget ? row.budget : row.decided;
                        break;
                    // copy data A163-2 to A163-5
                    case 'decided':
                        row.paid = row.decided ? row.decided : row.paid;
                        break;
                    default:
                        break;
                }
                // ----------------------------------------------------------
            },

            // --------------------------------------------------------------
            // Compile Sections Data
            // --------------------------------------------------------------
            compileSections: function(sections){
                // ----------------------------------------------------------
                // update nested object if undefined set default value
                let setDefault = function(object, path = '', value = 0) {
                    return _.update(object, path, function(val) { return val ? val : value; });
                }

                // ----------------------------------------------------------
                // Section A
                // ----------------------------------------------------------
                setDefault(sections, 'a.procurement.price', 0);

                // ----------------------------------------------------------
                // Section B
                // ----------------------------------------------------------
                setDefault(sections, 'b.procurement.brokerage_fee', 0);

                return sections;
            },

            // --------------------------------------------------------------
            // Calculate Tax Helper
            // --------------------------------------------------------------
            calculateTotalTax: function(data, prop, nested = false){
                let total = 0;
                let tax = this.initial.tax;
                total = _.sumBy(data, function(value) {
                    let num = Number(value[prop]);
                    return Math.floor((num*tax/100)+num);
                });
                return total;
            },
            calculateTax: function(data, prop, nested = false){
                let total = 0;
                let tax = this.initial.tax;
                total = _.sumBy(data, function(value) {
                    let num = Number(value[prop]);
                    return Math.floor((num*tax/100));
                });
                return total;
            },
            calculateTotalPaidTax: function(data, prop, status = 3){
                let total = 0;
                let tax = this.initial.tax;
                let filtered = _.filter(data, function(value){
                    return value.status == status;
                });
                total = _.sumBy(filtered, function(value) {
                    let num = Number(value[prop]);
                    return Math.floor((num*tax/100)+num);
                });
                return total;
            },
            calculatePaidTax: function(data, prop, status = 3){
                let total = 0;
                let tax = this.initial.tax;
                let filtered = _.filter(data, function(value){
                    return value.status == status;
                });
                total = _.sumBy(filtered, function(value) {
                    let num = Number(value[prop]);
                    return Math.floor((num*tax/100));
                });
                return total;
            },
            // --------------------------------------------------------------

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
                // Format data and remove unnecessary post data
                // ----------------------------------------------------------
                let project = _.cloneDeep(vm.project);
                let sections = _.cloneDeep(vm.sections);

                // delete unnecessary items to compact request
                // ----------------------------------------------------------
                delete project.sheets;
                delete sections.a;
                delete sections.b;
                delete sections.d;
                delete sections.i;
                delete sections.j;

                // compile post data
                // ----------------------------------------------------------
                // console.log( sections );
                let data = {
                    project: project,
                    expense: vm.expense,
                    sections: sections,
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
                        vm.expense = data.expense;

                        // update section data
                        sections = vm.compileSections(data.sections);
                        vm.sections = sections;

                        Vue.nextTick(function () {
                            // show toast success message
                            $.toast({
                                heading: '成功', icon: 'success',
                                position: 'top-right', stack: false, hideAfter: 4000,
                                text: response.data.message,
                                position: { right: 18, top: 68 }
                            });
                        });
                    }else if(response.data.status == 'warning'){
                        // show toast warning message
                        $.toast({
                            heading: false, icon: 'warning',
                            position: 'top-right', stack: false, hideAfter: 4000,
                            text: response.data.message,
                            position: { right: 18, top: 68 }
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