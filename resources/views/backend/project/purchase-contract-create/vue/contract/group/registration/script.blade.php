<script> @minify
// --------------------------------------------------------------------------
(function( $, io, document, window, undefined ){
    // ----------------------------------------------------------------------

    // ----------------------------------------------------------------------
    // Contract group registration
    // ----------------------------------------------------------------------
    Vue.component( 'group-registration', {
        // ------------------------------------------------------------------
        props: [ 'value', 'project', 'contract', 'target', 'disabled', 'completed' ],
        template: '#group-registration',
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Reactive data
        // ------------------------------------------------------------------
        data: function(){
            // --------------------------------------------------------------
            var data = {
                entry: this.value,
                project: this.project,
                response: this.target.response,
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
                var prefix = 'new-contract-group-registration-';
                return entry.id ? 'contract-group-registration-' +entry.id+ '-' : prefix;
            },
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Find out if this group is completed
            // --------------------------------------------------------------
            isCompleted: function(){ return this.completed },
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Find out if this section is disabled
            // --------------------------------------------------------------
            isDisabled: function(){
                return this.disabled || this.isCompleted;
            },
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Default liquidation type selected at the purchase-doc
            // --------------------------------------------------------------
            defaultLiquidation: function(){
                return io.get( this, 'target.doc.contract_c' );
            },
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Default retribution type selected at the purchase-doc
            // --------------------------------------------------------------
            defaultRetribution: function(){
                return io.get( this, 'target.doc.contract_d' );
            },
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Get city-planning land-type from purchase-sale
            // --------------------------------------------------------------
            landType: function(){
                return io.get( this.project, 'purchase_sale.project_urbanization_area_sub' );
            },
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // c_article6_1_contract
            // https://docs.google.com/spreadsheets/d/1wYcd6H7A__Hqa70ZOcsjWKtsEZaMsZN-jFAELNn_cuU/edit#gid=1171733632&range=X27:X28
            // --------------------------------------------------------------
            article61Contract: {
                get: function(){
                    let value = io.get( this.entry, 'c_article6_1_contract' );

                    if (value) {
                        return value;
                    }else {
                        let response = this.response;
                        let contractUpdate = response && 2 === response.contract_update;
                        // -----------------------------------------------------
                        if ( contractUpdate && ( 1 === response.desired_contract_terms_h
                            || 2 === response.desired_contract_terms_i ) ) {
                            Vue.set( this.entry, 'c_article6_1_contract', 1 );
                            return 1;
                        }
                        // -----------------------------------------------------
                        else if ( contractUpdate && ( 1 === response.desired_contract_terms_i
                            || 2 === response.desired_contract_terms_h ) ) {
                            Vue.set( this.entry, 'c_article6_1_contract', 2 );
                            return 2;
                        }
                        // -----------------------------------------------------
                        else
                            return null;
                        // -----------------------------------------------------
                    }
                },
                set: function( value ){
                    Vue.set( this.entry, 'c_article6_1_contract', value );
                }
            },
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // c_article6_2_contract
            // https://docs.google.com/spreadsheets/d/1wYcd6H7A__Hqa70ZOcsjWKtsEZaMsZN-jFAELNn_cuU/edit#gid=1171733632&range=X29:X30
            // --------------------------------------------------------------
            article62Contract: {
                get: function(){
                    let value = io.get( this.entry, 'c_article6_2_contract' );

                    if (value) {
                        return value;
                    }else {
                        let response = this.response;
                        let contractUpdate = response && 2 === response.contract_update;
                        // -----------------------------------------------------
                        if ( contractUpdate && ( 1 === response.desired_contract_terms_j
                            || 2 === response.desired_contract_terms_k ) ) {
                            Vue.set( this.entry, 'c_article6_2_contract', 1 );
                            return 1;
                        }
                        // -----------------------------------------------------
                        else if ( contractUpdate && ( 1 === response.desired_contract_terms_k
                            || 2 === response.desired_contract_terms_j ) ) {
                            Vue.set( this.entry, 'c_article6_2_contract', 2 );
                            return 2;
                        }
                        // -----------------------------------------------------
                        else
                            return null;
                        // -----------------------------------------------------
                    }
                },
                set: function( value ){
                    Vue.set( this.entry, 'c_article6_2_contract', value );
                }
            },
            // --------------------------------------------------------------
        },
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Watchers
        // ------------------------------------------------------------------
        watch: {},
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Component methods
        // ------------------------------------------------------------------
        methods: {}
        // ------------------------------------------------------------------
    });
    // ----------------------------------------------------------------------
}( jQuery, _, document, window ));
// --------------------------------------------------------------------------
@endminify </script>
