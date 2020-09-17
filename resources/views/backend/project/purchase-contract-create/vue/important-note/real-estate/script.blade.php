<script> @minify
// --------------------------------------------------------------------------
(function( $, io, document, window, undefined ){
    // ----------------------------------------------------------------------

    // ----------------------------------------------------------------------
    // Contract real estate
    // ----------------------------------------------------------------------
    Vue.component( 'important-note-real-estate', {
        // ------------------------------------------------------------------
        props: [ 'value', 'target', 'disabled' ],
        template: '#important-note-real-estate',
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
                return entry.real_estate_related_status == true || false;
            },
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Find purchase target building kind value
            // --------------------------------------------------------------
            building_kind: function(){
                let building_kind = null;

                let target_buildings = io.get( this.target, 'buildings' );
                if (target_buildings.length > 0) {
                    target_buildings.forEach((target_building, i) => {
                        if (!building_kind)
                            building_kind = target_building.kind == 1 ? 1 : null
                    });
                }

                return building_kind
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