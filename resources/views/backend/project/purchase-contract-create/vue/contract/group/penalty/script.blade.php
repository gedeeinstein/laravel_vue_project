<script> @minify
// --------------------------------------------------------------------------
(function( $, io, document, window, undefined ){
    // ----------------------------------------------------------------------

    // ----------------------------------------------------------------------
    // Contract group penalty
    // ----------------------------------------------------------------------
    Vue.component( 'group-penalty', {
        // ------------------------------------------------------------------
        props: [ 'value', 'project', 'contract', 'target', 'disabled', 'completed' ],
        template: '#group-penalty',
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Reactive data
        // ------------------------------------------------------------------
        data: function(){
            // --------------------------------------------------------------
            var data = {
                entry: this.value,
                project: this.project,
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
                var prefix = 'new-contract-group-penalty-';
                return entry.id ? 'contract-group-penalty-' +entry.id+ '-' : prefix;
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
            // Penalty option model
            // --------------------------------------------------------------
            penaltyOption: {
                get: function(){
                    return io.get( this.entry, 'c_article12_contract' );
                },
                set: function( value ){
                    Vue.set( this.entry, 'c_article12_contract', value );
                    if( 3 !== value ) Vue.set( this.entry, 'c_article12_contract_text', null );
                }
            }
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
        methods: {
            // --------------------------------------------------------------
            // Activate other-option input and focus to it
            // --------------------------------------------------------------
            otherOption: function(){
                var vm = this;
                Vue.set( this.entry, 'c_article12_contract', 3 );
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