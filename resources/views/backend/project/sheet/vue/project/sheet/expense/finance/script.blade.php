<script> @minify
// --------------------------------------------------------------------------
(function( $, io, document, window, undefined ){
    // ----------------------------------------------------------------------

    // ----------------------------------------------------------------------
    // Expense finance
    // ----------------------------------------------------------------------
    Vue.component( 'expense-finance', {
        // ------------------------------------------------------------------
        props: [ 'value', 'sheet', 'project', 'expense', 'disabled' ],
        template: '#expense-finance',
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Reactive data
        // ------------------------------------------------------------------
        data: function(){
            // --------------------------------------------------------------
            var data = {
                entry: this.value,
                additionalKey: 0,
                preset: {
                    bankingFee: 50000,
                    loanRatio: @json( $loanRatio )
                },
                rows: io.get( this, 'expense.d.rows' )
            };
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // console.log( data.rows );
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
                var prefix = 'new-expense-finance-';
                return entry.id ? 'expense-finance-' +entry.id+ '-' : prefix;
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
            // Total of interest rate
            // --------------------------------------------------------------
            totalInterest: function(){
                // ----------------------------------------------------------
                var total = 0; var rows = this.rows;
                if( rows && rows.length ){
                    var entries = io.filter( rows, { display_name: '金利負担' });
                    if( entries ) io.map( entries, function( entry ){ total += entry.decided });
                } return total;
                // ----------------------------------------------------------
            },
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Total of banking fee
            // --------------------------------------------------------------
            totalBanking: function(){
                // ----------------------------------------------------------
                var total = 0; var rows = this.rows;
                if( rows && rows.length ){
                    var entries = io.filter( rows, { display_name: '銀行手数料' });
                    if( entries ) io.map( entries, function( entry ){ total += entry.decided });
                } return total;
                // ----------------------------------------------------------
            },
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Total of stamp fee
            // --------------------------------------------------------------
            totalStamp: function(){
                // ----------------------------------------------------------
                var total = 0; var rows = this.rows;
                if( rows && rows.length ){
                    var entries = io.filter( rows, { display_name: '印紙(手形)' });
                    if( entries ) io.map( entries, function( entry ){ total += entry.decided });
                } return total;
                // ----------------------------------------------------------
            },
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Total of finance budget
            // --------------------------------------------------------------
            totalBudget: function(){
                var entry = this.entry; var result = 0;
                // ----------------------------------------------------------
                result += entry.total_interest_rate;
                result += entry.banking_fee + entry.stamp;
                // ----------------------------------------------------------
                // Add additional costs
                // ----------------------------------------------------------
                var entries = io.get( entry, 'additional.entries' );
                if( entries && entries.length ) $.each( entries, function( a, additional ){
                    if( additional.value ) result += additional.value;
                });
                // ----------------------------------------------------------
                return result;
                // ----------------------------------------------------------
            },
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Total of finance decided amount
            // --------------------------------------------------------------
            totalAmount: function( entry ){
                var entry = this.entry; var result = 0;
                // ----------------------------------------------------------
                result += this.totalInterest;
                result += this.totalBanking + this.totalStamp;
                // ----------------------------------------------------------
                // Add additional costs
                // ----------------------------------------------------------
                var entries = io.get( entry, 'additional.entries' );
                if( entries && entries.length ) $.each( entries, function( a, additional ){
                    if( additional.total ) result += additional.total;
                });
                // ----------------------------------------------------------
                // console.log( result );
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
            // When changed, emit total budget back to the parent
            // --------------------------------------------------------------
            totalBudget: { 
                immediate: true, handler: function(){
                    this.$emit( 'totalBudget', this.totalBudget, 'finance' );
                }
            },
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // When changed, emit total amount back to the parent
            // --------------------------------------------------------------
            totalAmount: { 
                immediate: true, handler: function(){
                    this.$emit( 'totalAmount', this.totalAmount, 'finance' );
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
            // Auto calculate finance intereset rate
            // S12-4 * S13-2 * 0.01 (round down)
            // http://bit.ly/2Qr4m4e
            // --------------------------------------------------------------
            calculateInterest: function(){
                var entry = this.entry;
                var mortgage = io.get( this.sheet, 'stock.registers.mortgage_setting_plan' );
                if( mortgage ){
                    var expectedRate = entry.expected_interest_rate;
                    var result = Math.floor( mortgage * expectedRate * 0.01 );
                    entry.total_interest_rate = result;
                }
            },
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Replace finance banking-fee with static default value
            // http://bit.ly/3a29xQ5
            // --------------------------------------------------------------
            calculateBankingFee: function(){
                this.entry.banking_fee = this.preset.bankingFee;
            },
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Calculate finance stamp value based on preset values
            // http://bit.ly/3b9f8UE
            // --------------------------------------------------------------
            calculateStampFee: function(){
                // ----------------------------------------------------------
                var entry = this.entry; var stamp = 0;
                var price = io.get( this.sheet, 'stock.procurements.price' );
                // ----------------------------------------------------------
                if( price ){
                    if( price > 100000 ) stamp = 200;
                    if( price > 1000000 ) stamp = 400;
                    if( price > 2000000 ) stamp = 600;
                    if( price > 3000000 ) stamp = 1000;
                    if( price > 5000000 ) stamp = 2000;
                    if( price > 10000000 ) stamp = 4000;
                    if( price > 20000000 ) stamp = 6000;
                    if( price > 30000000 ) stamp = 10000;
                    if( price > 50000000 ) stamp = 20000;
                    if( price > 100000000 ) stamp = 40000;
                    if( price > 200000000 ) stamp = 60000;
                    if( price > 300000000 ) stamp = 100000;
                    if( price > 500000000 ) stamp = 150000;
                    if( price > 1000000000 ) stamp = 200000;
                }
                entry.stamp = stamp;
            },
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Add new additional entry
            // --------------------------------------------------------------
            addAdditional: function(){
                // ----------------------------------------------------------
                var entry = this.entry;
                var entries = io.get( entry, 'additional.entries' );
                if( !entries ) Vue.set( entry.additional, 'entries', []);                
                // ----------------------------------------------------------
                entries = io.get( entry, 'additional.entries' );
                if( io.isArray( entries )){
                    this.additionalKey++; // Change the key to force the vue update
                    entries.push({
                        pj_stock_cost_parent_id: entry.additional.id,
                        name: null, value: null, memo: null
                    });
                }
                // ----------------------------------------------------------
            },
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Delete the additional entry from the list
            // --------------------------------------------------------------
            removeAdditional: function( index ){
                var entries = io.get( this, 'entry.additional.entries' );
                if( entries ){
                    entries.splice( index, 1 ); // This will delete item
                    this.additionalKey++; // Change the key to force the vue update
                }
            }
            // --------------------------------------------------------------
        }
        // ------------------------------------------------------------------
    });
    // ----------------------------------------------------------------------
}( jQuery, _, document, window ));
// --------------------------------------------------------------------------
@endminify </script>