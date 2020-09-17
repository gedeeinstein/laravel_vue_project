<script> @minify
// --------------------------------------------------------------------------
(function( $, io, document, window, undefined ){
    // ----------------------------------------------------------------------

    // ----------------------------------------------------------------------
    // Sale plan entry
    // ----------------------------------------------------------------------
    Vue.component( 'plan-entry', {
        // ------------------------------------------------------------------
        props: [ 'value', 'index', 'sale', 'sheet', 'disabled' ],
        template: '#plan-entry',
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Reactive data
        // ------------------------------------------------------------------
        data: function(){
            // --------------------------------------------------------------
            var data = {
                status: { loading: false },
                entry: this.value,
                sectionKey: 0,
                format: { created: 'YYYY年MM月DD日 HH:mm' }
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
                var prefix = 'new-sale-plan-';
                return entry.id ? 'sale-plan-' +entry.id+ '-' : prefix;
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
            // Get secton reference-area total
            // --------------------------------------------------------------
            referenceAreaTotal: function(){
                var result = null; var entry = this.entry;
                if( entry.sections && entry.sections.length ){
                    result = round( io.sumBy( entry.sections, 'reference_area' ), 4 );
                } return result;
            },
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Get secton planned-area total
            // --------------------------------------------------------------
            plannedAreaTotal: function(){
                var result = null; var entry = this.entry;
                if( entry.sections && entry.sections.length ){
                    result = round( io.sumBy( entry.sections, 'planned_area' ), 4 );
                } return result;
            },
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Get secton unit-selling-price total
            // --------------------------------------------------------------
            unitSellingPriceTotal: function(){
                var result = null; var entry = this.entry;
                if( entry.sections && entry.sections.length ){
                    result = round( io.sumBy( entry.sections, 'unit_selling_price' ), 4 );
                } return result;
            },
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Get gross profit plan
            // https://bit.ly/2KaYRDs
            // Main: [S32-13] - [S34-1] 円
            // Additional: [S33-1] / [S32-13] * 100
            // --------------------------------------------------------------
            grossProfitPlan: function(){
                var vm = this; var result = {};
                var entry = vm.entry; var expense = io.get( vm, 'sheet.stock');
                // ----------------------------------------------------------
                if( expense && expense.total_budget_cost ){
                    if( vm.unitSellingPriceTotal ){
                        result.main = vm.unitSellingPriceTotal - expense.total_budget_cost;
                    }
                    // ----------------------------------------------------------
                    if( result.main && vm.unitSellingPriceTotal ){
                        result.additional = round( result.main / vm.unitSellingPriceTotal * 100, 2 );
                    }
                }
                // ----------------------------------------------------------
                return result;
                // ----------------------------------------------------------
            },
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Get plan gross profit total plan
            // https://bit.ly/34FGcsE
            // Main: [S33-1] + sum([S32-3])
            // * sum([S32-3]):
            // - If [S32-4] =「収」: plus
            // - If [S32-4] =「支」: minus
            // - If [S32-4] =「無」: zero
            // Additonal: S33-2 / S32-13 * 100
            // --------------------------------------------------------------
            grossProfitTotal: function(){
                var vm = this; var result = {}; 
                var entry = vm.entry; var sections = io.get( entry, 'sections' );
                // ----------------------------------------------------------
                if( entry.gross_profit_plan && sections ){
                    var sum = 0;
                    // ------------------------------------------------------
                    $.each( sections, function( i, section ){
                        if( section.brokerage_fee && section.brokerage_fee_type ){
                            // ----------------------------------------------
                            var type = section.brokerage_fee_type;  // S32-4
                            var absoluteFee = Math.abs( section.brokerage_fee );
                            // ----------------------------------------------
                            if( 3 !== type ){
                                if( 1 === type ) sum += absoluteFee;
                                else if( 2 === type ) sum += absoluteFee * -1;
                            }
                            // ----------------------------------------------
                        }
                    });
                    // ------------------------------------------------------
                    result.main = entry.gross_profit_plan + sum;
                    // ------------------------------------------------------
                }
                // ----------------------------------------------------------
                if( result.main && vm.unitSellingPriceTotal ){
                    result.additional = round( result.main / vm.unitSellingPriceTotal * 100, 2 );
                }
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
            referenceAreaTotal: function( value ){ 
                this.entry.reference_area_total = value;
            },
            // --------------------------------------------------------------
            plannedAreaTotal: function( value ){ 
                this.entry.planned_area_total = value;
            },
            // --------------------------------------------------------------
            unitSellingPriceTotal: function( value ){ 
                this.entry.unit_selling_price_total = value;
            },
            // --------------------------------------------------------------
            grossProfitPlan: function( value ){
                var entry = this.entry;
                if( value.main ) Vue.set( entry, 'gross_profit_plan', value.main );
                if( value.additional ) Vue.set( entry, 'gross_profit_plan_percentage', value.additional );
            },
            // --------------------------------------------------------------
            grossProfitTotal: function( value ){
                var entry = this.entry;
                if( value.main ) Vue.set( entry, 'gross_profit_total_plan', value.main );
                if( value.additional ) Vue.set( entry, 'gross_profit_total_plan_percentage', value.additional );
            }
            // --------------------------------------------------------------
        },
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Component methods
        // ------------------------------------------------------------------
        methods: {
            // --------------------------------------------------------------
            // Mark current plan for reporting
            // --------------------------------------------------------------
            markExport: function(){
                // ----------------------------------------------------------
                var vm = this;
                var entry = vm.entry; var index = vm.index;
                var plans = io.get( vm, 'sale.plans' );
                // ----------------------------------------------------------
                if( plans && plans.length ){
                    // ------------------------------------------------------
                    if( entry.export ) $.each( plans, function( planIndex, plan ){
                        var checked = index == planIndex;
                        Vue.set( plan, 'export', checked );
                    });
                    // ------------------------------------------------------
                    else { // If unchecked
                        // --------------------------------------------------
                        // If the first-plan get unchecked, negate and keep it checked
                        // If other than the first-plan, check the first plan
                        // --------------------------------------------------
                        if( index == 0 ) vm.$nextTick( function(){ Vue.set( entry, 'export', true )});
                        else Vue.set( plans[0], 'export', true );
                        // --------------------------------------------------
                    }
                    // ------------------------------------------------------
                }
                // ----------------------------------------------------------
            },
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Delete the section entry from the list
            // --------------------------------------------------------------
            removeSection: function( index ){
                var sections = io.get( this, 'entry.sections' );
                if( sections ){
                    sections.splice( index, 1 ); // This will delete item
                    this.sectionKey++; // Change the key to force the vue update
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