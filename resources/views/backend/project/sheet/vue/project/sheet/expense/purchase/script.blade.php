<script> @minify
// --------------------------------------------------------------------------
(function( $, io, document, window, undefined ){
    // ----------------------------------------------------------------------

    // ----------------------------------------------------------------------
    // Expense purchase
    // ----------------------------------------------------------------------
    Vue.component( 'expense-purchase', {
        // ------------------------------------------------------------------
        props: [ 'value', 'sheet', 'project', 'expense', 'disabled' ],
        template: '#expense-purchase',
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Reactive data
        // ------------------------------------------------------------------
        data: function(){
            // --------------------------------------------------------------
            var data = {
                entry: this.value
            };
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            return data;
            // --------------------------------------------------------------
        },
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Computed properties
        // ------------------------------------------------------------------
        computed: {
            // --------------------------------------------------------------
            prefix: function(){
                var entry = this.entry;
                var prefix = 'new-expense-purchase-';
                return entry.id ? 'expense-purchase-' +entry.id+ '-' : prefix;
            },
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Find out if this section is disabled
            // --------------------------------------------------------------
            isDisabled: function(){
                return this.disabled || false;
            },
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Get purchase price per tsubo unit
            // --------------------------------------------------------------
            tsuboPrice: function(){
                // ----------------------------------------------------------
                var entry = this.entry;
                var project = io.get( this, 'project' );
                // ----------------------------------------------------------
                if( project && project.overall_area && entry.price ){
                    // ------------------------------------------------------
                    var area = Vue.filter('tsubo')( project.overall_area );
                    return Math.floor( entry.price / area );
                    // ------------------------------------------------------
                }
                // ----------------------------------------------------------
            },
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Total price
            // --------------------------------------------------------------
            totalPrice: function(){
                var total = io.get( this, 'expense.a.decided' ) || 0;
                return total;
            },
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Get total price per tsubo unit
            // --------------------------------------------------------------
            tsuboTotalPrice: function(){
                // ----------------------------------------------------------
                var project = io.get( this, 'project' );
                if( project && project.overall_area && this.totalPrice ){
                    // ------------------------------------------------------
                    var area = Vue.filter('tsubo')( project.overall_area );
                    return Math.floor( this.totalPrice / area );
                    // ------------------------------------------------------
                }
                // ----------------------------------------------------------
            },
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Total brokerage fee
            // --------------------------------------------------------------
            totalBrokerageFee: function(){
                var total = 0;
                var decided = io.get( this, 'expense.b.decided' ) || [];
                $.each( decided, function( i, expense ){ total += expense });
                return total;
            },
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Total of registration budget
            // --------------------------------------------------------------
            totalBudget: function(){
                // ----------------------------------------------------------
                var result = 0;
                var entry = this.entry;
                // ----------------------------------------------------------

                // ----------------------------------------------------------
                result += entry.price;
                // ----------------------------------------------------------
                if( 3 !== entry.brokerage_fee_type ) result += entry.brokerage_fee;
                // ----------------------------------------------------------

                // ----------------------------------------------------------
                return result;
                // ----------------------------------------------------------
            },
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Total of registration decided amount
            // --------------------------------------------------------------
            totalAmount: function( entry ){
                // ----------------------------------------------------------
                var result = 0;
                // ----------------------------------------------------------

                // ----------------------------------------------------------
                result += this.totalPrice;
                result += this.totalBrokerageFee;
                // ----------------------------------------------------------

                // ----------------------------------------------------------
                return result;
                // ----------------------------------------------------------
            },
            // --------------------------------------------------------------
        },
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Watchers
        // ------------------------------------------------------------------
        watch: {
            // --------------------------------------------------------------
            'entry.brokerage_fee_type': function( value ){
                var entry = this.entry;
                if( entry.brokerage_fee ){
                    // ------------------------------------------------------
                    var multiplier = 1;
                    var absoluteFee = Math.abs( entry.brokerage_fee );
                    if( 1 === value ) multiplier = -1;
                    entry.brokerage_fee = absoluteFee * multiplier;
                    // ------------------------------------------------------
                }
            },
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // When changed, emit total budget back to the parent
            // --------------------------------------------------------------
            totalBudget: { 
                immediate: true, handler: function(){
                    this.$emit( 'totalBudget', this.totalBudget, 'purchase' );
                }
            },
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // When changed, emit total amount back to the parent
            // --------------------------------------------------------------
            totalAmount: { 
                immediate: true, handler: function(){
                    this.$emit( 'totalAmount', this.totalAmount, 'purchase' );
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
            // Auto calculate purchase fee with spec formula
            // (( price * 0.03 ) ＋ 60000 ) * 1.1 ※ round down
            // http://bit.ly/2TYMAHN
            // --------------------------------------------------------------
            calculateBrokerageFee: function(){
                // ----------------------------------------------------------
                var entry = this.entry;
                var type = entry.brokerage_fee_type;
                // ----------------------------------------------------------
                if( entry.price ){
                    var multiplier = 1 === type ? -1 : 1;
                    var result = (( entry.price * 0.03 ) + 60000 ) * 1.1;
                    entry.brokerage_fee = Math.abs( Math.floor( result )) * multiplier;
                }
                // ----------------------------------------------------------
            },
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Keep brokerage-fee sign according to the fee-type
            // --------------------------------------------------------------
            updateBrokerageFee: function(){
                var type = this.entry.brokerage_fee_type;
                var multiplier = 1 === type ? -1 : 1;

                var fee = Math.abs( this.entry.brokerage_fee ) * multiplier;
                Vue.set( this.entry, 'brokerage_fee', fee );
            }
            // --------------------------------------------------------------
        }
        // ------------------------------------------------------------------
    });
    // ----------------------------------------------------------------------
}( jQuery, _, document, window ));
// --------------------------------------------------------------------------
@endminify </script>