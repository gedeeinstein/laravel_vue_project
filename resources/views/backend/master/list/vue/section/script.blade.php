<script> @minify
// --------------------------------------------------------------------------
(function( $, io, document, window, undefined ){
    // ----------------------------------------------------------------------

    // ----------------------------------------------------------------------
    var toast = {
        heading: 'error', icon: 'error',
        stack: false, hideAfter: 3000,
        position: { right: 18, top: 68 }
    };
    // ----------------------------------------------------------------------

    // ----------------------------------------------------------------------
    // Section entry component
    // ----------------------------------------------------------------------
    Vue.component( 'section-entry', {
        // ------------------------------------------------------------------
        props: [ 'value', 'index' ],
        template: '#section-entry',
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // On mounted
        // ------------------------------------------------------------------
        mounted: function(){
            $('[data-toggle="tooltip"]').tooltip();
        },
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Reactive data
        // ------------------------------------------------------------------
        data: function(){
            // --------------------------------------------------------------
            var data = {
                entry: this.value,
                memo: { edit: false, create: false },
                status: { loading: false },
                project: this.value.project || {},
                user_list: @json( $user_list ),
            };
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            console.log( data );
            return data;
            // --------------------------------------------------------------
        },
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Computed properties
        // ------------------------------------------------------------------
        computed: {
            // --------------------------------------------------------------
            // Disabled state
            // --------------------------------------------------------------
            isDisabled: function(){ return this.status.loading },
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Project Title
            // --------------------------------------------------------------
            projectTitle: function(){
                return io.get( this.project, 'title' );
            },
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // PJ URL List
            // --------------------------------------------------------------
            sheetURL: function(){
                return io.get( this.project, 'url.sheet' );
            },
            expenseURL: function(){
                return io.get( this.project, 'url.expense' );
            },
            masFinanceURL: function(){
                return io.get( this.project, 'url.finance' );
            },
            masBasicURL: function(){
                return io.get( this.project, 'url.basic' );
            },
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Organizer company abbreviation
            // https://bit.ly/37RJJWE 
            // https://bit.ly/3en9aS5 - company.kind_In_house_abbreviation (G128-1)
            // --------------------------------------------------------------
            organizerAbbr: function(){
                return io.get( this.project, 'purchase_sale.organizer.kind_in_house_abbreviation' );
            },
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Sales person / Buyer staffs
            // https://bit.ly/3fXUNnZ
            // --------------------------------------------------------------
            buyerStaffs: function(){
                // ----------------------------------------------------------
                var result = [];
                var staffs = io.get( this.project, 'purchase_sale.buyer_staffs' );
                // ----------------------------------------------------------
                if( staffs && staffs.length ) $.each( staffs, function( i, staff ){
                    var user = io.get( staff, 'user' );
                    if( user ) result.push( user );
                }); 
                // ----------------------------------------------------------
                return result;
                // ----------------------------------------------------------
            },
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            bookPriceTotal: function(){
                // ----------------------------------------------------------
                return io.get( this.entry, 'book_price_total' );
                // ----------------------------------------------------------
            },
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            totalSalesPrice: function(){
                // ----------------------------------------------------------
                var sales_decision_price = io.get( this.entry, 'contract.contract_price' );
                var sales_price = io.get( this.entry, 'price_budget' );
                var result = sales_decision_price + sales_price;
                // ----------------------------------------------------------
                return result;
                // ----------------------------------------------------------
            },
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            bookProfit: function(){
                var book_price = io.get( this.entry, 'book_price_total' );
                var sales_price = io.get( this.entry, 'price_budget' );
                var result = sales_price - book_price;
                // ----------------------------------------------------------
                return result;
                // ----------------------------------------------------------
            },
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            loanBalaceMoney: function(){
                // ----------------------------------------------------------
                return io.get( this.project, 'finance.units.loan_balance_money' );
                // ----------------------------------------------------------
            },
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            loanDateRange: function(){
                var oneDay = 24 * 60 * 60 * 1000; // hours*minutes*seconds*milliseconds
                var loanDate = io.get( this.project, 'finance.units.moneys.loan_date' );
                // ----------------------------------------------------------
                if(loanDate != null) {
                    var currentDate = new Date(loanDate);
                    var diffDays = Math.round(Math.abs((currentDate - loanDate) / oneDay));
                    // ----------------------------------------------------------
                    return diffDays;
                    // ----------------------------------------------------------
                } else {
                    return null;
                }
            },
            // --------------------------------------------------------------

        },
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Component methods
        // ------------------------------------------------------------------
        methods: {
            // --------------------------------------------------------------
            // Append new memo
            // --------------------------------------------------------------
            toggleCreateMemo: function(e){
                // ----------------------------------------------------------
                var vm = this;
                vm.memo.create = !vm.memo.create; // Toggle memo create mode
                // ----------------------------------------------------------
                
                // ----------------------------------------------------------
                // If user toggle the create memo, remove new entries
                // ----------------------------------------------------------
                if( !vm.memo.create ){
                    var found = io.findIndex( vm.entry.memos, { create: true });
                    if( found >= 0 ) vm.entry.memos.splice( found, 1 );
                    return;
                }
                // ----------------------------------------------------------
                
                // ----------------------------------------------------------
                var init = @json( $template->memo );
                init.create = true;
                init.project_id = vm.entry.id;
                // ----------------------------------------------------------
                vm.entry.memos.push( init );
                // ----------------------------------------------------------

                // ----------------------------------------------------------
                var $container = $(e.target).closest('.entry-detail');
                setTimeout( function(){
                    $container.find('.memo-entry.create input').focus();
                })
                // ----------------------------------------------------------
            },
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Toggle memo creation
            // --------------------------------------------------------------
            toggleEditMemo: function(){
                var vm = this;
                vm.memo.edit = !vm.memo.edit;
                // ----------------------------------------------------------

                // ----------------------------------------------------------
                // If user exit the edit memo, remove new entries
                // ----------------------------------------------------------
                if( !vm.memo.edit ){
                    vm.memo.create = false;
                    var found = io.findIndex( vm.entry.memos, { create: true });
                    if( found >= 0 ) vm.entry.memos.splice( found, 1 );
                    return;
                }
                // ----------------------------------------------------------
            },
            // --------------------------------------------------------------
            // --------------------------------------------------------------
            // Update inspection status
            // --------------------------------------------------------------
            // update: function(){
            //     // ----------------------------------------------------------
            //     var vm = this;
            //     var entry = this.entry;
            //     // ----------------------------------------------------------

            //     // ----------------------------------------------------------
            //     var url = '{{ route('project.inspection.update') }}/' +entry.id;
            //     var request = axios.post( url, { updates: entry });
            //     // ----------------------------------------------------------
            //     vm.status.loading = true;
            //     // ----------------------------------------------------------

            //     // ----------------------------------------------------------
            //     // On request success
            //     // ----------------------------------------------------------
            //     request.then( function( response ){
            //         console.log( response );
            //         // ------------------------------------------------------
            //         var option = io.clone( toast );
            //         var alert = io.get( response, 'data.alert' );
            //         io.assign( option, alert );
            //         // ------------------------------------------------------

            //         // ------------------------------------------------------
            //         var status = io.get( response, 'data.status' );
            //         // ------------------------------------------------------

            //         // ------------------------------------------------------
            //         if( 'success' == status ) vm.$emit( 'updated' ); // Emit an update event
            //         // ------------------------------------------------------

            //         // ------------------------------------------------------
            //         $.toast( option ); // Display the notification
            //         // ------------------------------------------------------
            //     });
            //     // ----------------------------------------------------------


            //     // ----------------------------------------------------------
            //     // When request failed
            //     // ----------------------------------------------------------
            //     request.catch( function( response ){
            //         var alert = io.get( response, 'data.alert' );
            //         if( alert ){
            //             var option = io.clone( toast );
            //             io.assign( option, alert );
            //             $.toast( option );
            //         }
            //     });
            //     // ----------------------------------------------------------
            //     request.finally( function(){ vm.status.loading = false });
            //     // ----------------------------------------------------------
            // }
            // --------------------------------------------------------------
        }
        // ------------------------------------------------------------------
    });
    // ----------------------------------------------------------------------
}( jQuery, _, document, window ));
// --------------------------------------------------------------------------
@endminify </script>