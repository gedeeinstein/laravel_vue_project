<script> @minify
// --------------------------------------------------------------------------
(function( $, io, document, window, undefined ){
    // ----------------------------------------------------------------------

    // ----------------------------------------------------------------------
    // Contract group boundary
    // ----------------------------------------------------------------------
    Vue.component( 'group-boundary', {
        // ------------------------------------------------------------------
        props: [ 'value', 'project', 'contract', 'target', 'disabled', 'completed' ],
        template: '#group-boundary',
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
                var prefix = 'new-contract-group-boundary-';
                return entry.id ? 'contract-group-boundary-' +entry.id+ '-' : prefix;
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
            // c_article5_fixed_survey_contract
            // https://docs.google.com/spreadsheets/d/1wYcd6H7A__Hqa70ZOcsjWKtsEZaMsZN-jFAELNn_cuU/edit#gid=1171733632&range=W23:W26
            // --------------------------------------------------------------
            article5FixedSurveyContract: {
                get: function(){
                    let value = io.get( this.entry, 'c_article5_fixed_survey_contract' );

                    if (value) {
                        return value;
                    }else {
                        let response = this.response;
                        let contractUpdate = response && 2 === response.contract_update;
                        // -----------------------------------------------------
                        if ( contractUpdate && ( 1 === response.desired_contract_terms_d
                            || 2 === response.desired_contract_terms_d
                            || 1 === response.desired_contract_terms_e
                            || 2 === response.desired_contract_terms_e ) ) {
                            Vue.set( this.entry, 'c_article5_fixed_survey_contract', 2 );
                            return 2;
                        }
                        // -----------------------------------------------------
                        else if ( contractUpdate && ( 1 === response.desired_contract_terms_f
                                || 2 === response.desired_contract_terms_f
                                || 2 === response.desired_contract_terms_g ) ) {
                             Vue.set( this.entry, 'c_article5_fixed_survey_contract', 3 );
                             return 3;
                        }
                        // -----------------------------------------------------
                        else
                            return null;
                        // -----------------------------------------------------
                    }
                },
                set: function( value ){
                    Vue.set( this.entry, 'c_article5_fixed_survey_contract', value );
                }
            },
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // c_article5_burden_contract
            // https://docs.google.com/spreadsheets/d/1wYcd6H7A__Hqa70ZOcsjWKtsEZaMsZN-jFAELNn_cuU/edit#gid=1171733632&range=X23:X24
            // --------------------------------------------------------------
            article5BurdenContract: {
                get: function(){
                    let value = io.get( this.entry, 'c_article5_burden_contract' );

                    if (value) {
                        return value;
                    }else {
                        let response = this.response;
                        let contractUpdate = response && 2 === response.contract_update;
                        // -----------------------------------------------------
                        if ( contractUpdate && ( 1 === response.desired_contract_terms_d
                            || 2 === response.desired_contract_terms_d ) ) {
                            Vue.set( this.entry, 'c_article5_burden_contract', 1 );
                            return 1;
                        }
                        // -----------------------------------------------------
                        else if ( contractUpdate && ( 1 === response.desired_contract_terms_e
                            || 2 === response.desired_contract_terms_e ) ) {
                            Vue.set( this.entry, 'c_article5_burden_contract', 2 );
                            return 2;
                        }
                        // -----------------------------------------------------
                        else
                            return null;
                        // -----------------------------------------------------
                    }
                },
                set: function( value ){
                    Vue.set( this.entry, 'c_article5_burden_contract', value );
                }
            },
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // c_article5_burden2_contract
            // https://docs.google.com/spreadsheets/d/1wYcd6H7A__Hqa70ZOcsjWKtsEZaMsZN-jFAELNn_cuU/edit#gid=1171733632&range=X23:X24
            // --------------------------------------------------------------
            article5Burden2Contract: {
                get: function(){
                    let value = io.get( this.entry, 'c_article5_burden2_contract' );

                    if (value) {
                        return value;
                    }else {
                        let response = this.response;
                        let contractUpdate = response && 2 === response.contract_update;
                        // -----------------------------------------------------
                        if ( contractUpdate && ( 1 === response.desired_contract_terms_d
                            || 2 === response.desired_contract_terms_e ) ) {
                            Vue.set( this.entry, 'c_article5_burden2_contract', 1 );
                            return 1;
                        }
                        // -----------------------------------------------------
                        else if ( contractUpdate && ( 1 === response.desired_contract_terms_e
                            || 2 === response.desired_contract_terms_d ) ) {
                            Vue.set( this.entry, 'c_article5_burden2_contract', 2 );
                            return 2;
                        }
                        // -----------------------------------------------------
                        else
                            return null;
                        // -----------------------------------------------------
                    }
                },
                set: function( value ){
                    Vue.set( this.entry, 'c_article5_burden2_contract', value );
                }
            },
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Survey option model
            // https://docs.google.com/spreadsheets/d/1wYcd6H7A__Hqa70ZOcsjWKtsEZaMsZN-jFAELNn_cuU/edit#gid=1171733632&range=X25:X26
            // --------------------------------------------------------------
            surveyOption: {
                get: function(){
                    let value = io.get( this.entry, 'c_article5_fixed_survey_options_contract' );

                    if (value) {
                        return value;
                    }else {
                        let response = this.response;
                        let contractUpdate = response && 2 === response.contract_update;
                        // -----------------------------------------------------
                        if ( contractUpdate && 1 === response.desired_contract_terms_f ) {
                            Vue.set( this.entry, 'c_article5_fixed_survey_options_contract', 3 );
                            return 3;
                        }
                        // -----------------------------------------------------
                        else if ( contractUpdate && ( 2 === response.desired_contract_terms_f
                                || 2 === response.desired_contract_terms_g ) ) {
                             Vue.set( this.entry, 'c_article5_fixed_survey_options_contract', 4 );
                             return 4;
                        }
                        // -----------------------------------------------------
                        else
                            return null;
                        // -----------------------------------------------------
                    }
                },
                set: function( value ){
                    Vue.set( this.entry, 'c_article5_fixed_survey_options_contract', value );
                    if( 5 !== value ) Vue.set( this.entry, 'c_article5_fixed_survey_options_other_contract', null );
                }
            },
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Land survey status
            // --------------------------------------------------------------
            landSurvey: function(){
                var option = 2 === this.surveyOption;
                var contract = 3 === io.get( this.entry, 'c_article5_fixed_survey_contract' );
                return option && contract;
            }
            // --------------------------------------------------------------
        },
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Watchers
        // ------------------------------------------------------------------
        watch: {
            // --------------------------------------------------------------
            // Reset land-survey and other option when survey option is changed
            // --------------------------------------------------------------
            'entry.c_article5_fixed_survey_options_contract': function(){
                var entry = this.entry;
                entry.c_article5_fixed_survey_options_other_contract = null;
                entry.c_article5_land_surveying = null;
            },
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Reset almost all article-5 field when fixed-survey is changed
            // --------------------------------------------------------------
            'entry.c_article5_fixed_survey_contract': function(){
                var entry = this.entry;
                entry.c_article5_fixed_survey_options_contract = null;
                entry.c_article5_burden_contract = null;
                entry.c_article5_burden2_contract = null;
                entry.c_article5_date_contract = null;
                entry.c_article5_creator_contract = null;
            }
            // --------------------------------------------------------------
        },
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Component methods
        // ------------------------------------------------------------------
        methods: {
            // --------------------------------------------------------------
            // Activate other-option input and focus to it
            // --------------------------------------------------------------
            otherOption: function(){
                var vm = this;
                Vue.set( this.entry, 'c_article5_fixed_survey_options_contract', 5 );
                setTimeout( function(){
                    $( vm.$refs.otherInput ).find('input').trigger('focus');
                });
            }
            // --------------------------------------------------------------
        }
        // ------------------------------------------------------------------
    });
    // ----------------------------------------------------------------------
}( jQuery, _, document, window ));
// --------------------------------------------------------------------------
@endminify </script>
