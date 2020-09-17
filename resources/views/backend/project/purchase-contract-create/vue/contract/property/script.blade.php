<script> @minify
// --------------------------------------------------------------------------
(function( $, io, document, window, undefined ){
    // ----------------------------------------------------------------------

    // ----------------------------------------------------------------------
    // Contract property
    // ----------------------------------------------------------------------
    Vue.component( 'contract-property', {
        // ------------------------------------------------------------------
        props: [ 'value', 'project', 'contract', 'target', 'buildings', 'disabled' ],
        template: '#contract-property',
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
                noteDisplay: false,
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
                var prefix = 'new-contract-property-';
                return entry.id ? 'contract-property-' +entry.id+ '-' : prefix;
            },
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Find out if this section is disabled
            // --------------------------------------------------------------
            isDisabled: function(){
                return this.disabled || this.isCompleted;
            },
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Find out if this group is completed
            // --------------------------------------------------------------
            isCompleted: function(){
                return 1 === this.entry.property_description_status;
            },
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Get product type based on [A4] Building 
            // and purchase-contract kind, unregistered, unregistered-kind
            // https://bit.ly/3aU9BCq
            // --------------------------------------------------------------
            propertyType: function(){
                // ----------------------------------------------------------
                var buildings = io.get( this, 'buildings.length' );
                if( !buildings ) return 1;
                else {
                    // ------------------------------------------------------
                    var kinds = [];
                    var product = false, demolish = false;
                    // ------------------------------------------------------
                    kinds.push( io.get( this, 'contract.contract_building_kind' ));
                    kinds.push( io.get( this, 'contract.contract_building_unregistered' ));
                    kinds.push( io.get( this, 'contract.contract_building_unregistered_kind' ));
                    // ------------------------------------------------------
                    console.log( kinds );
                    io.each( kinds, function( kind ){
                        kind = io.parseInt( kind );
                        if( 1 === kind ) product = true;
                        else if( 2 === kind ) demolish = true;
                    });
                    // ------------------------------------------------------
                    if( product && !demolish ) return 2;
                    if( !product && demolish ) return 3;
                    if( product && demolish ) return 4;
                    // ------------------------------------------------------
                }
            },
            // --------------------------------------------------------------


            // --------------------------------------------------------------
            // Get selected property-demolition value from purchase-doc
            // --------------------------------------------------------------
            defaultDemolition: function(){
                return io.get( this, 'target.doc.properties_description_b' );
            },
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // property_description_dismantling
            // https://docs.google.com/spreadsheets/d/1wYcd6H7A__Hqa70ZOcsjWKtsEZaMsZN-jFAELNn_cuU/edit#gid=1171733632&range=X35
            // --------------------------------------------------------------
            propertyDescriptionDismantling: {
                get: function(){
                    let value = io.get( this.entry, 'property_description_dismantling' );

                    if (value) {
                        return value;
                    }else {
                        let response = this.response;
                        let contractUpdate = response && 2 === response.contract_update;
                        // -----------------------------------------------------
                        if ( contractUpdate && 1 === response.desired_contract_terms_n ) {
                            Vue.set( this.entry, 'property_description_dismantling', 1 );
                            return 1;
                        }
                        // -----------------------------------------------------
                        else if ( contractUpdate && 2 === response.desired_contract_terms_n ) {
                            Vue.set( this.entry, 'property_description_dismantling', 2 );
                            return 2;
                        }
                        // -----------------------------------------------------
                        else
                            return null;
                        // -----------------------------------------------------
                    }
                },
                set: function( value ){
                    Vue.set( this.entry, 'property_description_dismantling', value );
                }
            },
            // --------------------------------------------------------------

        },
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Watchers
        // ------------------------------------------------------------------
        watch: {
            // --------------------------------------------------------------
            'entry.property_description_dismantling': function( value ){
                if( 1 === value ){
                    var entry = this.entry;
                    entry.property_description_transfer = null;
                    entry.property_description_removal_by_buyer = null;
                    entry.property_description_cooler_removal_by_buyer = null;
                }
            },
            // --------------------------------------------------------------
            propertyType: {
                immediate: true,
                handler: function( value ){
                    Vue.set( this.entry, 'property_description_product', value );
                }
            }
            // --------------------------------------------------------------
        },
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Component methods
        // ------------------------------------------------------------------
        methods: {

        }
        // ------------------------------------------------------------------
    });
    // ----------------------------------------------------------------------
}( jQuery, _, document, window ));
// --------------------------------------------------------------------------
@endminify </script>
