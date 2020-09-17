<script> @minify
// --------------------------------------------------------------------------
(function( $, io, document, window, undefined ){
    // ----------------------------------------------------------------------

    // ----------------------------------------------------------------------
    var toast = {
        heading: '失敗', icon: 'error',
        stack: false, hideAfter: 3000,
        position: { right: 18, top: 68 }
    };
    // ----------------------------------------------------------------------

    // ----------------------------------------------------------------------
    // Additional expense
    // ----------------------------------------------------------------------
    Vue.component( 'expense-additional', {
        // ------------------------------------------------------------------
        props: [ 'value', 'section', 'expenseRows', 'disabled' ],
        template: '#expense-additional',
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Reactive data
        // ------------------------------------------------------------------
        data: function(){
            // --------------------------------------------------------------
            var data = {
                status: { loading: false },
                entry: this.value,
                unremovable: false,
            };
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Get total decided from expense-rows
            // --------------------------------------------------------------
            var entry = data.entry;
            var total = 0; var rows = this.expenseRows;
            // --------------------------------------------------------------
            if( rows && rows.length ){
                // ----------------------------------------------------------
                var additionals = io.filter( rows, { additional_id: entry.id });
                if( additionals.length ) {
                    // ------------------------------------------------------
                    io.map( additionals, function( additional ){ total += additional.decided });
                    Vue.set( entry, 'total', total ); // Set the decided total
                    // ------------------------------------------------------
                    
                    // ------------------------------------------------------
                    // Set as unremovable if this additional expense 
                    // has decided amount in pj-expense
                    // ------------------------------------------------------
                    if( total ) data.unremovable = true; 
                    // ------------------------------------------------------
                }
            }
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
                var prefix = 'new-expense-additional-';
                return entry.id ? 'expense-additional-' +entry.id+ '-' : prefix;
            },
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Find out if this section is disabled
            // --------------------------------------------------------------
            isDisabled: function(){
                return this.disabled || this.status.loading || false;
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
        methods: {
            // --------------------------------------------------------------
            // Remove additional expense entry
            // --------------------------------------------------------------
            remove: function(){
                // ----------------------------------------------------------
                var vm = this; var entry = this.entry; 
                var dialog = false; var confirmed = true;
                var additional = io.get( this, 'section.additional' );
                var entries = io.get( this.section, 'additional.entries' );
                // ----------------------------------------------------------

                // ----------------------------------------------------------
                // Display notification if this item is unremovable 
                // (has total amount decided in pj-expense)
                // ----------------------------------------------------------
                if( this.unremovable ){
                    var alert = $.extend({}, toast );
                    alert.text = "支出の部で、決定総額が入力されているため削除できません。";
                    $.toast( alert ); return;
                }
                // ----------------------------------------------------------

                // ----------------------------------------------------------
                if( !entry.id ){
                    // ------------------------------------------------------
                    var hasValue = !!entry.value;
                    var hasName = !!io.trim( entry.name );
                    var hasMemo = !!io.trim( entry.memo );
                    // ------------------------------------------------------
                    dialog = hasValue || hasName || hasMemo;
                    // ------------------------------------------------------
                    if( dialog ) confirmed = confirm('@lang('label.confirm_delete')');
                    if( confirmed && entries ){
                        vm.$emit( 'remove', entry );
                    }
                    // ------------------------------------------------------
                }
                // ----------------------------------------------------------

                // ----------------------------------------------------------
                // If additional entry has ID, reflect the removal to the database
                // ----------------------------------------------------------
                else if( entries ){
                    confirmed = confirm('@lang('label.confirm_delete')');
                    if( confirmed ){
                        // --------------------------------------------------
                        vm.status.loading = true;
                        var option = $.extend({}, toast );
                        // --------------------------------------------------

                        // --------------------------------------------------
                        var url = '/project/sheet/expense/' +entry.id;
                        // --------------------------------------------------
                        var request = axios.delete( url );
                        request.then( function( response ){
                            // ----------------------------------------------
                            var alert = io.get( response, 'data.alert' );
                            var status = io.get( response, 'data.status' );
                            // ----------------------------------------------
                            if( 'success' == status ){
                                vm.$emit( 'remove', entry );
                            }
                            // ----------------------------------------------
                            if( alert ) option = $.extend( option, alert );
                            $.toast( option );
                            // ----------------------------------------------
                        });
                        // --------------------------------------------------
        
                        // --------------------------------------------------
                        request.catch( function( response ){ console.log( response );
                            var alert = io.get( response, 'data.alert' );
                            if( alert ) $.toast( $.extend( option, alert ));
                        });
                        // --------------------------------------------------
                        request.finally( function(){ vm.status.loading = false });
                        // --------------------------------------------------
                    }
                }
                // ----------------------------------------------------------
            },
            // --------------------------------------------------------------
        }
        // ------------------------------------------------------------------
    });
    // ----------------------------------------------------------------------
}( jQuery, _, document, window ));
// --------------------------------------------------------------------------
@endminify </script>