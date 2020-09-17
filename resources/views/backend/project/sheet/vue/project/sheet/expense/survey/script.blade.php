<script> @minify
// --------------------------------------------------------------------------
(function( $, io, document, window, undefined ){
    // ----------------------------------------------------------------------

    // ----------------------------------------------------------------------
    // Expense survey
    // ----------------------------------------------------------------------
    Vue.component( 'expense-survey', {
        // ------------------------------------------------------------------
        props: [ 'value', 'sheet', 'project', 'expense', 'disabled' ],
        template: '#expense-survey',
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Reactive data
        // ------------------------------------------------------------------
        data: function(){
            // --------------------------------------------------------------
            var data = {
                entry: this.value,
                additionalKey: 0,
                rows: io.get( this, 'expense.g.rows' )
            };
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
            prefix: function(){
                var entry = this.entry;
                var prefix = 'new-expense-survey-';
                return entry.id ? 'expense-survey-' +entry.id+ '-' : prefix;
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
            // Total of fixed survey
            // --------------------------------------------------------------
            totalFixedSurvey: function(){
                // ----------------------------------------------------------
                var total = 0; var rows = this.rows;
                if( rows && rows.length ){
                    var entries = io.filter( rows, { name: '確定測量' });
                    if( entries ) io.map( entries, function( entry ){ total += entry.decided });
                } return total;
                // ----------------------------------------------------------
            },
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Total of divisional registration
            // --------------------------------------------------------------
            totalDivisionalRegistration: function(){
                // ----------------------------------------------------------
                var total = 0; var rows = this.rows;
                if( rows && rows.length ){
                    var entries = io.filter( rows, { name: '分筆登記' });
                    if( entries ) io.map( entries, function( entry ){ total += entry.decided });
                } return total;
                // ----------------------------------------------------------
            },
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Total of boundry restoration
            // --------------------------------------------------------------
            totalBoundryRestoration: function(){
                // ----------------------------------------------------------
                var total = 0; var rows = this.rows;
                if( rows && rows.length ){
                    var entries = io.filter( rows, { name: '境界杭復元' });
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
                result += entry.fixed_survey;
                result += entry.divisional_registration;
                result += entry.boundary_pile_restoration;
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
                result += this.totalFixedSurvey;
                result += this.totalDivisionalRegistration;
                result += this.totalBoundryRestoration;
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
                    this.$emit( 'totalBudget', this.totalBudget, 'survey' );
                }
            },
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // When changed, emit total amount back to the parent
            // --------------------------------------------------------------
            totalAmount: { 
                immediate: true, handler: function(){
                    this.$emit( 'totalAmount', this.totalAmount, 'survey' );
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