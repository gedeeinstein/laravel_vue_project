<script> @minify
// --------------------------------------------------------------------------
(function( $, io, document, window, undefined ){
    // ----------------------------------------------------------------------

    // ----------------------------------------------------------------------
    // Contract group delivery
    // ----------------------------------------------------------------------
    Vue.component( 'group-delivery', {
        // ------------------------------------------------------------------
        props: [ 'value', 'project', 'contract', 'target', 'disabled', 'completed' ],
        template: '#group-delivery',
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Reactive data
        // ------------------------------------------------------------------
        data: function(){
            // --------------------------------------------------------------
            var data = {
                entry: this.value,
                project: this.project,
                doc: this.target.doc,
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
                var prefix = 'new-contract-group-delivery-';
                return entry.id ? 'contract-group-delivery-' +entry.id+ '-' : prefix;
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
            // Find the purchase-target building
            // --------------------------------------------------------------
            building: function(){
                var buildings = io.get( this, 'target.buildings' );
                if( buildings && buildings.length ) return buildings[0];
            },
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // c_article8_contract
            // https://docs.google.com/spreadsheets/d/1wYcd6H7A__Hqa70ZOcsjWKtsEZaMsZN-jFAELNn_cuU/edit#gid=1171733632&range=X31:X33
            // --------------------------------------------------------------
            article8Contract: {
                get: function(){
                    let value = io.get( this.entry, 'c_article8_contract' );

                    if (value) {
                        return value;
                    }else {
                        let doc = this.doc;
                        let response = this.response;
                        let contractUpdate = response && 2 === response.contract_update;
                        // -----------------------------------------------------
                        if ( contractUpdate && doc && 1 === doc.gathering_request_third_party
                            && 1 === response.desired_contract_terms_an ) {
                            Vue.set( this.entry, 'c_article8_contract', 1 );
                            return 1;
                        }
                        // -----------------------------------------------------
                        else if ( contractUpdate && doc
                            && ( ( 1 === doc.gathering_request_third_party && 2 === response.desired_contract_terms_an )
                            || ( 2 === doc.gathering_request_third_party && 2 === response.desired_contract_terms_an )
                            || ( 3 === doc.gathering_request_third_party && 1 === response.desired_contract_terms_an ) ) ) {
                            Vue.set( this.entry, 'c_article8_contract', 2 );
                            return 2;
                        }
                        // -----------------------------------------------------
                        else if ( contractUpdate && doc && 2 === doc.gathering_request_third_party
                            && 1 === response.desired_contract_terms_an ) {
                            Vue.set( this.entry, 'c_article8_contract', 3 );
                            return 3;
                        }
                        // -----------------------------------------------------
                        else
                            return null;
                        // -----------------------------------------------------
                    }
                },
                set: function( value ){
                    Vue.set( this.entry, 'c_article8_contract', value );
                }
            },
            // --------------------------------------------------------------
        },
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Watchers
        // ------------------------------------------------------------------
        watch: {
            'entry.c_article8_contract': function( value ){
                if( 4 !== value ) this.entry.c_article8_contract_text = null;
            }
        },
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Component methods
        // ------------------------------------------------------------------
        methods: {
            // --------------------------------------------------------------
            // Enable other-contract input
            // --------------------------------------------------------------
            otherContract: function(){
                // ----------------------------------------------------------
                var vm = this; var entry = vm.entry;
                if( 4 === entry.c_article8_contract ) return;
                // ----------------------------------------------------------
                entry.c_article8_contract = 4;
                // ----------------------------------------------------------
                setTimeout( function(){
                    var $input = $( vm.$refs.otherContract ).find('textarea');
                    $input.trigger('focus');
                });
                // ----------------------------------------------------------
            }
            // --------------------------------------------------------------
        }
        // ------------------------------------------------------------------
    });
    // ----------------------------------------------------------------------
}( jQuery, _, document, window ));
// --------------------------------------------------------------------------
@endminify </script>
