<script> @minify
// --------------------------------------------------------------------------
(function( $, io, document, window, undefined ){
    // ----------------------------------------------------------------------

    // ----------------------------------------------------------------------
    var toast = {
        heading: 'error', icon: 'error',
        stack: false, hideAfter: 3000,
        position: { right: 18, top: 68 }
    };
    // ----------------------------------------------------------------------

    // ----------------------------------------------------------------------
    // Project memo component
    // ----------------------------------------------------------------------
    Vue.component( 'project-memo', {
        // ------------------------------------------------------------------
        props: [ 'value', 'edit' ],
        template: '#project-memo',
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Reactive data
        // ------------------------------------------------------------------
        data: function(){
            // --------------------------------------------------------------
            var data = {
                status: { loading: false },
                entry: this.value
            };
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            if( io.isUndefined( data.entry.edit )) Vue.set( data.entry, 'edit', false );
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // console.log( data );
            return data;
            // --------------------------------------------------------------
        },
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Computed properties
        // ------------------------------------------------------------------
        computed: {
            // --------------------------------------------------------------
            // Get the parent edit-mode
            // --------------------------------------------------------------
            editMode: function(){ return this.edit },
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Get the memo timestamp
            // --------------------------------------------------------------
            timestamp: function(){
                var entry = this.entry;
                if( entry.updated_at ){
                    var stamp = moment( entry.updated_at );
                    return stamp.format('YYYY/MM/DD');
                }
            }
            // --------------------------------------------------------------
        },
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Component methods
        // ------------------------------------------------------------------
        methods: {
            // --------------------------------------------------------------
            // Toggle the live edit state
            // --------------------------------------------------------------
            toggleEdit: function(e){
                // ----------------------------------------------------------
                var vm = this;
                vm.entry.edit = !vm.entry.edit;
                // ----------------------------------------------------------
                var $memo = $(e.target).closest('.memo-entry');
                if( vm.entry.edit ) setTimeout( function(){
                    $memo.find('input').focus();
                });
                // ----------------------------------------------------------
            },
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Cancel the edit or create form
            // --------------------------------------------------------------
            cancel: function(){
                var vm = this;
                var entry = vm.entry;
                if( entry.edit ) entry.edit = false;
                else {
                    vm.$emit('cancel');
                    vm.$parent.toggleCreateMemo();
                }
            },
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Create new memo or save the updates
            // --------------------------------------------------------------
            save: function(){
                // ----------------------------------------------------------
                var vm = this;
                if( !io.trim( vm.entry.content )) return;
                // ----------------------------------------------------------

                // ----------------------------------------------------------
                vm.status.loading = true;
                var url = '/master/sale-list/memo';
                // ----------------------------------------------------------
                var method = vm.entry.id ? 'post' : 'put'; // Save or update
                var request = axios[method]( url, { entry: vm.entry });
                // ----------------------------------------------------------

                // ----------------------------------------------------------
                request.then( function( response ){
                    // ------------------------------------------------------
                    var data = response.data;
                    var status = io.get( response, 'data.status' );
                    var entry = io.get( response, 'data.entry' );
                    // ------------------------------------------------------
                    if( data ){
                        var option = $.extend( {}, toast, data );
                        if( 'success' == status ){
                            option.icon = 'success';
                            if( entry ){
                                vm.$emit( 'created', entry ); // Emit the object back to parent
                                $.extend( vm.value, entry ); // Merge object with the update
                                vm.value.create = false; // Hide the create input
                            }
                        }
                        $.toast( option );
                    }
                    // ------------------------------------------------------
                });
                // ----------------------------------------------------------

                // ----------------------------------------------------------
                request.catch( function( response ){
                    if( response.data ){
                        var option = $.extend({}, toast, response.data );
                        $.toast( option );
                    }
                });
                // ----------------------------------------------------------
                request.finally( function(){ 
                    vm.entry.edit = false;
                    vm.status.loading = false;
                });
                // ----------------------------------------------------------
            },
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Request removal
            // --------------------------------------------------------------
            remove: function(){
                // ----------------------------------------------------------
                var vm = this;
                // ----------------------------------------------------------
                
                // ----------------------------------------------------------
                var url = '/master/sale-list/memo/' +vm.entry.id;
                var confirmed = confirm( '@lang('label.confirm_delete')' );
                if( confirmed ){
                    // ------------------------------------------------------
                    vm.status.loading = true;
                    var request = axios.delete( url );
                    request.then( function( response ){
                        // --------------------------------------------------
                        var data = response.data;
                        var status = io.get( response, 'data.status' );
                        // --------------------------------------------------
                        if( data ){
                            var option = $.extend( {}, toast, data );
                            if( 'success' == status ){
                                option.icon = 'success';
                                vm.entry.is_deleted = true;
                            }
                            $.toast( option );
                        }
                        // --------------------------------------------------
                    });
                    // ------------------------------------------------------
    
                    // ------------------------------------------------------
                    request.catch( function( response ){
                        if( response.data ){
                            var option = $.extend({}, toast, response.data );
                            $.toast( option );
                        }
                    });
                    // ------------------------------------------------------
                    request.finally( function(){ vm.status.loading = false });
                    // ------------------------------------------------------
                }
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