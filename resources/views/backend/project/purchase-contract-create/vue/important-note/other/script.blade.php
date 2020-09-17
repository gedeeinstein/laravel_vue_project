<script> @minify
// --------------------------------------------------------------------------
(function( $, io, document, window, undefined ){
    // ----------------------------------------------------------------------

    // ----------------------------------------------------------------------
    // Contract real estate
    // ----------------------------------------------------------------------
    Vue.component( 'important-note-other', {
        // ------------------------------------------------------------------
        props: [ 'value', 'disabled' ],
        template: '#important-note-other',
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Reactive data
        // ------------------------------------------------------------------
        data: function(){
            // --------------------------------------------------------------
            var data = {
                entry: this.value
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
            // Find out if this section is disabled
            // --------------------------------------------------------------
            isDisabled: function(){
                return this.disabled || false;
            },
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Find out if this group is completed
            // --------------------------------------------------------------
            isCompleted: function(){
                var entry = this.entry;
                return entry.other_important_matters_document_status == true || false;
            },
            // --------------------------------------------------------------
        },
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Watchers
        // ------------------------------------------------------------------
        watch: {
            // --------------------------------------------------------------

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
