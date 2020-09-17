<script> @minify
// --------------------------------------------------------------------------
(function( $, io, document, window, undefined ){
    // ----------------------------------------------------------------------

    // ----------------------------------------------------------------------
    // Expense registration
    // ----------------------------------------------------------------------
    Vue.component( 'expense-registration', {
        // ------------------------------------------------------------------
        props: [ 'value', 'sheet', 'project', 'expense', 'disabled' ],
        template: '#expense-registration',
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
                    loss: 15000,
                    loanMoney: @json( $loanMoney ),
                    contract: @json( $contract ),
                },
                rows: io.get( this, 'expense.c.rows' )
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
                var prefix = 'new-expense-registration-';
                return entry.id ? 'expense-registration-' +entry.id+ '-' : prefix;
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
            // Total of ownership-transfer fee
            // --------------------------------------------------------------
            totalOwnership: function(){
                // ----------------------------------------------------------
                var total = 0; var rows = this.rows;
                if( rows && rows.length ){
                    var entries = io.filter( rows, { name: '司法書士登記' });
                    if( entries ) io.map( entries, function( entry ){ total += entry.decided });
                } return total;
                // ----------------------------------------------------------
            },
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Total of mortgage fee
            // --------------------------------------------------------------
            totalMortgage: function(){
                // ----------------------------------------------------------
                var total = 0; var rows = this.rows;
                if( rows && rows.length ){
                    var entries = io.filter( rows, { name: '固都税日割分' });
                    if( entries ) io.map( entries, function( entry ){ total += entry.decided });
                } return total;
                // ----------------------------------------------------------
            },
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Total of loss fee
            // --------------------------------------------------------------
            totalLoss: function(){
                // ----------------------------------------------------------
                var total = 0; var rows = this.rows;
                if( rows && rows.length ){
                    var entries = io.filter( rows, { name: '滅失登記' });
                    if( entries ) io.map( entries, function( entry ){ total += entry.decided });
                } return total;
                // ----------------------------------------------------------
            },
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Total of registration budget
            // --------------------------------------------------------------
            totalBudget: function(){
                var entry = this.entry; var result = 0;
                // ----------------------------------------------------------
                result += entry.transfer_of_ownership + entry.mortgage_setting;
                result += entry.fixed_assets_tax + entry.loss;
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
            // Total of registration decided amount
            // --------------------------------------------------------------
            totalAmount: function( entry ){
                var entry = this.entry; var result = 0;
                // ----------------------------------------------------------
                result += this.totalOwnership + this.totalMortgage;
                result += this.preset.loanMoney + this.totalLoss;
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
                    this.$emit( 'totalBudget', this.totalBudget, 'registration' );
                }
            },
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // When changed, emit total amount back to the parent
            // --------------------------------------------------------------
            totalAmount: { 
                immediate: true, handler: function(){
                    this.$emit( 'totalAmount', this.totalAmount, 'registration' );
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
            // Auto calculate registration ownership transfer fee
            // ( S1-1 * S1-2 * 0.0015 ) + 50000 (round down)
            // http://bit.ly/3a4fKLe
            // --------------------------------------------------------------
            calculateOwnershipTransfer: function(){
                if( this.project ){
                    // ------------------------------------------------------
                    var entry = this.entry;
                    var project = this.project;
                    // ------------------------------------------------------
                    var area = io.get( this.project, 'overall_area' );
                    var tax = io.get( this.project, 'fixed_asset_tax_route_value' );
                    if( area && tax ){
                        var result = Math.floor(( area * tax * 0.0015 ) + 50000 );
                        entry.transfer_of_ownership = result;
                    }
                    // ------------------------------------------------------
                }
            },
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Auto calculate registration mortgage plan
            // ( S12-4 * 0.004 ) + 30000 (round down)
            // http://bit.ly/2vrxS2u
            // --------------------------------------------------------------
            calculateMortgage: function(){
                var entry = this.entry;
                var plan = io.get( entry, 'mortgage_setting_plan' );
                if( plan ){
                    var result = Math.floor(( plan * 0.004 ) + 30000 );
                    entry.mortgage_setting = result;
                }
            },
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Auto calculate registration asset tax
            // S1-1 * S1-2 * 0.014 / 356 * [A](Number of days remaining between the scheduled
            // settlement date (S12-6) and the end of the year)
            // http://bit.ly/3d8KKf3
            // https://trello.com/c/dUrs1WU7/146-cf5pjsheet-stockinga1-customer-feedback
            // --------------------------------------------------------------
            calculateAssetTax: function(){
                // ----------------------------------------------------------
                var entry = this.entry; var project = this.project;
                var taxDate = entry.fixed_assets_tax_date;
                // ----------------------------------------------------------
                if( project && io.isFunction( moment ) && taxDate ){
                    // ------------------------------------------------------
                    var today = moment();
                    var taxDate = moment( taxDate );
                    var endofyear = moment( today.year() + '/12/31', 'YYYY/MM/DD' );
                    var remaining = Math.abs( endofyear.diff( taxDate, 'days' ));
                    // ------------------------------------------------------
                    var area = project.overall_area;                    // S1-1
                    var tax = project.fixed_asset_tax_route_value;      // S1-2
                    // ------------------------------------------------------
                    var areaTax = area * tax * 0.014;
                    var result = Math.floor(( areaTax / 365 ) * remaining );
                    entry.fixed_assets_tax = result;
                    // ------------------------------------------------------
                }
                // ----------------------------------------------------------
            },
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Replace registration loss with static default value
            // http://bit.ly/2U0p6ls
            // --------------------------------------------------------------
            calculateLoss: function(){ this.entry.loss = this.preset.loss },
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
            },
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // --------------------------------------------------------------
            // additionalTotal: function( total ){
            //     console.log( total );
            // }
            // --------------------------------------------------------------
        }
        // ------------------------------------------------------------------
    });
    // ----------------------------------------------------------------------
}( jQuery, _, document, window ));
// --------------------------------------------------------------------------
@endminify </script>