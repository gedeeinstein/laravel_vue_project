<script> @minify
// --------------------------------------------------------------------------
(function( $, io, document, window, undefined ){
    // ----------------------------------------------------------------------

    // ----------------------------------------------------------------------
    var template = @json( $new->plan ); // Plan template
    // ----------------------------------------------------------------------

    // ----------------------------------------------------------------------
    // Sale plan section
    // ----------------------------------------------------------------------
    Vue.component( 'sheet-sale', {
        // ------------------------------------------------------------------
        props: [ 'value', 'sheet', 'index', 'disabled' ],
        template: '#sheet-sale',
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Reactive data
        // ------------------------------------------------------------------
        data: function(){
            // --------------------------------------------------------------
            var data = {
                entry: this.value,
                sheetIndex: this.index,
                planKey: 1,
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
                var prefix = 'new-sheet-sale-';
                return entry.id ? 'sheet-sale-' +entry.id+ '-' : prefix;
            },
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Find out if this section is disabled
            // --------------------------------------------------------------
            isDisabled: function(){
                return this.disabled || false;
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
            // --------------------------------------------------------------
            // Reorder sale plan's tab index
            // --------------------------------------------------------------
            reorderPlans: function(){
                // ----------------------------------------------------------
                var vm = this;
                var plans = this.entry.plans;
                // ----------------------------------------------------------

                // ----------------------------------------------------------
                // Reorder the tab index 
                // ----------------------------------------------------------
                if( plans ) $.each( plans, function( index, plan ){
                    Vue.set( plan, 'tab_index', index +1 );
                });
                // ----------------------------------------------------------

                // ----------------------------------------------------------
                // Workaround to keep nested draggable works properly
                // ----------------------------------------------------------
                var clone = io.cloneDeep( plans ); // Keep a clone of the list
                vm.entry.plans = []; // Empty the original list
                // ----------------------------------------------------------
                Vue.nextTick( function(){ 
                    vm.entry.plans = clone; // Assign it back on next tick
                }); 
                // ----------------------------------------------------------
            },
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Create new sale plan
            // --------------------------------------------------------------
            createNewPlan: function(){
                // ----------------------------------------------------------
                var vm = this;
                var plans = vm.entry.plans;
                // ----------------------------------------------------------

                // ----------------------------------------------------------
                // Create new plan object from template
                // ----------------------------------------------------------
                var plan = $.extend( true, {}, template );
                // ----------------------------------------------------------
                plan.active = true;
                plan.created_at = 'pending';
                plan.tab_index = plans.length +1;
                plan.plan_creator = '{{ $user->full_name }}';
                // ----------------------------------------------------------

                // ----------------------------------------------------------
                // Request server time based on server timezone
                // ----------------------------------------------------------
                var url = '/api/server/time';
                var request = axios.get( url ); // Do the request
                // ----------------------------------------------------------
                request.then( function( response ){
                    if( response.data ) plan.created_at = response.data;
                });
                // ----------------------------------------------------------

                // ----------------------------------------------------------
                // Generate duplicate free plan name
                // ----------------------------------------------------------
                var counter = 1;
                var name = 'New Plan ' + counter;
                // ----------------------------------------------------------
                while( true ){
                    var found = io.find( plans, function(o){ return o.plan_name == name });
                    if( found ){ counter++; name = 'New Plan ' + counter }
                    else break;
                }
                // ----------------------------------------------------------
                plan.plan_name = name;
                // ----------------------------------------------------------

                // ----------------------------------------------------------
                // Deactive all other plans
                // ----------------------------------------------------------
                $.each( plans, function( p, item ){ Vue.set( item, 'active', false )});
                // ----------------------------------------------------------

                // ----------------------------------------------------------
                plans.push( plan );
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