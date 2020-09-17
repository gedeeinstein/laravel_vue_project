<script> @minify
// --------------------------------------------------------------------------
(function( $, io, document, window, undefined ){
    // ----------------------------------------------------------------------

    // ----------------------------------------------------------------------
    // Expense tax
    // ----------------------------------------------------------------------
    Vue.component( 'expense-tax', {
        // ------------------------------------------------------------------
        props: [ 'value', 'sheet', 'project', 'expense', 'disabled' ],
        template: '#expense-tax',
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Reactive data
        // ------------------------------------------------------------------
        data: function(){
            // --------------------------------------------------------------
            var data = {
                entry: this.value,
                additionalKey: 0,
                rows: io.get( this, 'expense.e.rows' )
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
                var prefix = 'new-expense-tax-';
                return entry.id ? 'expense-tax-' +entry.id+ '-' : prefix;
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
            // Total of annual tax
            // --------------------------------------------------------------
            totalAnnual: function(){
                // ----------------------------------------------------------
                var total = 0; var rows = this.rows;
                if( rows && rows.length ){
                    var entries = io.filter( rows, { name: '翌年固都税' });
                    if( entries ) io.map( entries, function( entry ){ total += entry.decided });
                } return total;
                // ----------------------------------------------------------
            },
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Total of tax budget
            // --------------------------------------------------------------
            totalBudget: function(){
                var entry = this.entry; var result = 0;
                // ----------------------------------------------------------
                result += entry.property_acquisition_tax;
                result += entry.the_following_year_the_city_tax;
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
            // Total of tax decided amount
            // --------------------------------------------------------------
            totalAmount: function( entry ){
                var entry = this.entry; var result = 0;
                // ----------------------------------------------------------
                result += this.totalAnnual;
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
                    this.$emit( 'totalBudget', this.totalBudget, 'tax' );
                }
            },
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // When changed, emit total amount back to the parent
            // --------------------------------------------------------------
            totalAmount: { 
                immediate: true, handler: function(){
                    this.$emit( 'totalAmount', this.totalAmount, 'tax' );
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
            // Auto calculate property acquisition tax
            // S1-1 * S1-2 * 0.014 (round down)
            // https://bit.ly/2wdIJxF
            // --------------------------------------------------------------
            calculateAcquisitionTax: function(){
                var entry = this.entry; var project = this.project;
                if( project ){
                    var area = project.overall_area;
                    var fixedTax = project.fixed_asset_tax_route_value;
                    var result = Math.floor( area * fixedTax * 0.014 );
                    entry.property_acquisition_tax = result;
                }
            },
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Auto calculate property the following year tax / annual tax
            // S1-1 * S1-2 * 0.03 / 2 (round down)
            // https://bit.ly/2WsLC8r
            // --------------------------------------------------------------
            calculateAnnualTax: function(){
                var entry = this.entry; var project = this.project;
                if( project ){
                    var area = project.overall_area;
                    var fixedTax = project.fixed_asset_tax_route_value;
                    var result = Math.floor(( area * fixedTax * 0.03 ) / 2 );
                    entry.the_following_year_the_city_tax = result;
                }
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