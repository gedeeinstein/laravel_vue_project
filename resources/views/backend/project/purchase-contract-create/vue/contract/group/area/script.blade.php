<script> @minify
// --------------------------------------------------------------------------
(function( $, io, document, window, undefined ){
    // ----------------------------------------------------------------------

    // ----------------------------------------------------------------------
    // Contract group area
    // ----------------------------------------------------------------------
    Vue.component( 'group-area', {
        // ------------------------------------------------------------------
        props: [ 'value', 'project', 'contract', 'target', 'disabled', 'completed' ],
        template: '#group-area',
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
                var prefix = 'new-contract-group-area-';
                return entry.id ? 'contract-group-area-' +entry.id+ '-' : prefix;
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
            // Default transaction type selected at the purchase-doc
            // --------------------------------------------------------------
            defaultTransaction: function(){
                return io.get( this, 'target.doc.contract_a' );
            },
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // c_article4_contract
            // https://docs.google.com/spreadsheets/d/1wYcd6H7A__Hqa70ZOcsjWKtsEZaMsZN-jFAELNn_cuU/edit#gid=1171733632&range=X21:X22
            // --------------------------------------------------------------
            article4Contract: {
                get: function(){
                    let value = io.get( this.entry, 'c_article4_contract' );

                    if (value) {
                        return value;
                    }else {
                        let response = this.response;
                        let contractUpdate = response && 2 === response.contract_update;
                        // -----------------------------------------------------
                        if ( contractUpdate && ( 1 === response.desired_contract_terms_b
                            || 2 === response.desired_contract_terms_c ) ) {
                            Vue.set( this.entry, 'c_article4_contract', 1 );
                            return 1;
                        }
                        // -----------------------------------------------------
                        else if ( contractUpdate && ( 2 === response.desired_contract_terms_b
                                || 1 === response.desired_contract_terms_c ) ) {
                            Vue.set( this.entry, 'c_article4_contract', 2 );
                            return 2;
                        }
                        // -----------------------------------------------------
                        else
                            return null;
                        // -----------------------------------------------------
                    }
                },
                set: function( value ){
                    Vue.set( this.entry, 'c_article4_contract', value );
                }
            },
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Sub-contract model
            // --------------------------------------------------------------
            subContract: {
                get: function(){
                    return io.get( this.entry, 'c_article4_sub_contract' );
                },
                set: function( value ){
                    Vue.set( this.entry, 'c_article4_sub_contract', value );
                    if( 3 !== value ) Vue.set( this.entry, 'c_article4_sub_text_contract', null );
                }
            }
            // --------------------------------------------------------------
        },
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Watchers
        // ------------------------------------------------------------------
        watch: {
            // --------------------------------------------------------------
            'entry.c_article4_contract': function(){
                var entry = this.entry;
                entry.c_article4_sub_contract = null;
                entry.c_article4_sub_text_contract = null;
                entry.c_article4_clearing_standard_area = null;
                entry.c_article4_clearing_standard_area_cost = null;
                entry.c_article4_clearing_standard_area_remarks = null;
            }
            // --------------------------------------------------------------
        },
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Component methods
        // ------------------------------------------------------------------
        methods: {
            // --------------------------------------------------------------
            // Activate custom sub-contract input and focus to it
            // --------------------------------------------------------------
            customSubContract: function(){
                var vm = this;
                Vue.set( this.entry, 'c_article4_sub_contract', 3 );
                setTimeout( function(){
                    $( vm.$refs.customInput ).find('input').trigger('focus');
                });
            },
            // --------------------------------------------------------------
        }
        // ------------------------------------------------------------------
    });
    // ----------------------------------------------------------------------
}( jQuery, _, document, window ));
// --------------------------------------------------------------------------
@endminify </script>
