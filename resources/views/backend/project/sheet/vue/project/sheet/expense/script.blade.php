<script> @minify
// --------------------------------------------------------------------------
(function( $, io, document, window, undefined ){
    // ----------------------------------------------------------------------

    // ----------------------------------------------------------------------
    // Sheet expense
    // ----------------------------------------------------------------------
    Vue.component( 'sheet-expense', {
        // ------------------------------------------------------------------
        props: [ 'value', 'sheet', 'sheetValues', 'project', 'disabled' ],
        template: '#sheet-expense',
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Reactive data
        // ------------------------------------------------------------------
        data: function(){
            // --------------------------------------------------------------
            var data = {
                entry: this.value,
                total: {
                    budget: {
                        purchase: 0, registration: 0, finance: 0, tax: 0,
                        survey: 0, construction: 0, other: 0
                    },
                    amount: {
                        purchase: 0, registration: 0, finance: 0, tax: 0,
                        survey: 0, construction: 0, other: 0
                    }
                },
                expense: @json( $expense, JSON_NUMERIC_CHECK )
            };
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Create additional total placeholder
            // --------------------------------------------------------------
            // $.each( data.entry, function( i, section ){
            //     var additionals = io.get( section, 'additional.entries' );
            //     if( additionals ) $.each( additionals, function( a, additional ){
            //         if( !additional.total ) Vue.set( additional, 'total', null );
            //     });
            // });
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // console.log( data.entry );
            return data;
            // --------------------------------------------------------------
        },
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Computed properties
        // ------------------------------------------------------------------
        computed: {
            // --------------------------------------------------------------
            // Find out if this section is disabled
            // --------------------------------------------------------------
            isDisabled: function(){
                return this.disabled || false;
            },
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Grnad total of expense budget
            // --------------------------------------------------------------
            totalBudget: function(){
                // ----------------------------------------------------------
                var result = 0;
                var total = this.total.budget;
                // ----------------------------------------------------------
                result += total.purchase + total.registration;
                result += total.finance + total.tax + total.construction;
                result += total.survey + total.other;
                // ----------------------------------------------------------
                return result;
                // ----------------------------------------------------------
            },
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Grnad total of expense budget in tsubo unit
            // --------------------------------------------------------------
            totalBudgetTsubo: function(){
                // ----------------------------------------------------------
                var total = this.totalBudget;
                var result = 0; var entry = this.entry;
                var checklist = io.get( this, 'sheet.checklist' );
                // ----------------------------------------------------------
                if( checklist && checklist.effective_area ){
                    var area = Vue.filter('tsubo')( checklist.effective_area );
                    var result = Math.floor( total / area );
                }
                // ----------------------------------------------------------
                return result;
                // ----------------------------------------------------------
            },
            // --------------------------------------------------------------


            // --------------------------------------------------------------
            // Grand total of decided amount
            // --------------------------------------------------------------
            totalAmount: function(){
                // ----------------------------------------------------------
                var result = 0;
                var total = this.total.amount;
                // ----------------------------------------------------------
                // console.log( total.finance );
                result += total.purchase + total.registration;
                result += total.finance + total.tax + total.construction;
                result += total.survey + total.other;
                // ----------------------------------------------------------
                return result;
                // ----------------------------------------------------------
            },
            // --------------------------------------------------------------


            // --------------------------------------------------------------
            // Grand total of decided amount in tsubo unit
            // --------------------------------------------------------------
            totalAmountTsubo: function(){
                // ----------------------------------------------------------
                var total = this.totalAmount;
                var result = 0; var entry = this.entry;
                var checklist = io.get( this, 'sheet.checklist' );
                // ----------------------------------------------------------
                if( checklist && checklist.effective_area ){
                    var area = Vue.filter('tsubo')( checklist.effective_area );
                    var result = Math.floor( total / area );
                }
                // ----------------------------------------------------------
                return result;
                // ----------------------------------------------------------
            }
            // --------------------------------------------------------------
        },
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Watchers
        // ------------------------------------------------------------------
        watch: {
            // --------------------------------------------------------------
            totalBudget: { 
                immediate: true, handler: function( value ){ 
                    Vue.set( this.entry, 'total_budget_cost', value );
                }
            },
            // --------------------------------------------------------------
            totalBudgetTsubo: { 
                immediate: true, handler: function( value ){ 
                    Vue.set( this.entry, 'total_budget_cost_tsubo', value );
                }
            },
            // --------------------------------------------------------------
            totalAmount: { 
                immediate: true, handler: function( value ){ 
                    Vue.set( this.entry, 'total_decision_cost', value );
                }
            },
            // --------------------------------------------------------------
            totalAmountTsubo: { 
                immediate: true, handler: function( value ){ 
                    Vue.set( this.entry, 'total_decision_cost_tsubo', value );
                }
            }
            // --------------------------------------------------------------
        },
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Component methods
        // ------------------------------------------------------------------
        methods: {
            // --------------------------------------------------------------
            updateTotalBudget: function( total, section ){
                Vue.set( this.total.budget, section, total );
            },
            // --------------------------------------------------------------
            updateTotalAmount: function( total, section ){
                // console.log( total, section );
                Vue.set( this.total.amount, section, total );
            },
            // --------------------------------------------------------------
        }
        // ------------------------------------------------------------------
    });
    // ----------------------------------------------------------------------
}( jQuery, _, document, window ));
// --------------------------------------------------------------------------
@endminify </script>