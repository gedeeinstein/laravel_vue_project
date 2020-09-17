<script> @minify
// --------------------------------------------------------------------------
(function( $, io, document, window, undefined ){
    // ----------------------------------------------------------------------


    // ----------------------------------------------------------------------
    // List of road types. 
    // Has to be listed literally to keep computed watcher reactive 
    // List of index only (a,b,c...) will not work
    // ----------------------------------------------------------------------
    var roadTypes = [ 
        'road_type_contract_a', 'road_type_contract_b', 'road_type_contract_c',
        'road_type_contract_d', 'road_type_contract_e', 'road_type_contract_f',
        'road_type_contract_g', 'road_type_contract_h', 'road_type_contract_i',
    ];
    // ----------------------------------------------------------------------
    var publicTypes = [ 'road_type_contract_a', 'road_type_contract_d' ];
    var privateTypes = [ 'road_type_contract_h', 'road_type_contract_i' ];
    // ----------------------------------------------------------------------

    // ----------------------------------------------------------------------
    // Contract road
    // ----------------------------------------------------------------------
    Vue.component( 'contract-road', {
        // ------------------------------------------------------------------
        props: [ 'value', 'project', 'contract', 'target', 'disabled' ],
        template: '#contract-road',
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Reactive data
        // ------------------------------------------------------------------
        data: function(){
            // --------------------------------------------------------------
            var data = {
                entry: this.value,
                project: this.project,
                defaults: io.get( this, 'target.doc' )
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
                var prefix = 'new-contract-road-';
                return entry.id ? 'contract-road-' +entry.id+ '-' : prefix;
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
            // Find out if this section is disabled
            // --------------------------------------------------------------
            isBurdenDisabled: function(){
                var entry = this.entry;
                return this.isDisabled || 2 !== entry.road_private_burden_contract;
            },
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Find out if this group is completed
            // --------------------------------------------------------------
            isCompleted: function(){
                return 1 === this.entry.road_private_status;
            },
            // --------------------------------------------------------------


            // --------------------------------------------------------------
            // Find if the road type check is empty
            // --------------------------------------------------------------
            isRoadTypeEmpty: function(){
                var empty = true; var entry = this.entry;
                // ----------------------------------------------------------
                $.each( roadTypes, function( t, type ){
                    var road = io.get( entry, type ) || false;
                    if( road ){ empty = false; return false };
                }); return empty;
                // ----------------------------------------------------------
            },
            // --------------------------------------------------------------


            // --------------------------------------------------------------
            // If public-road status is fixed
            // --------------------------------------------------------------
            fixedPublic: function(){
                // ----------------------------------------------------------
                var fixed = false; var entry = this.entry;
                // ----------------------------------------------------------
                // If at least one of road type in public list is checked, fixed is true
                // ----------------------------------------------------------
                $.each( publicTypes, function( t, type ){
                    var road = io.get( entry, type );
                    if( road ){ fixed = true; return; }
                });
                // ----------------------------------------------------------
                // If other types are also checked, fixed is false
                // ----------------------------------------------------------
                $.each( roadTypes, function( t, type ){
                    var road = io.get( entry, type );
                    if( road && io.indexOf( publicTypes, type ) == -1 ){
                        fixed = false; return;
                    }
                });
                // ----------------------------------------------------------
                return fixed;
                // ----------------------------------------------------------
            },
            // --------------------------------------------------------------


            // --------------------------------------------------------------
            // If private-road status is fixed
            // --------------------------------------------------------------
            fixedPrivate: function(){
                // ----------------------------------------------------------
                var fixed = false; var entry = this.entry;
                // ----------------------------------------------------------
                // If at least one of road type in private list is checked, fixed is true
                // ----------------------------------------------------------
                $.each( privateTypes, function( t, type ){
                    var road = io.get( entry, type );
                    if( road ){ fixed = true; return; }
                });
                // ----------------------------------------------------------
                // If other types are also checked, fixed is false
                // ----------------------------------------------------------
                $.each( roadTypes, function( t, type ){
                    var road = io.get( entry, type );
                    if( road && io.indexOf( privateTypes, type ) == -1 ){
                        fixed = false; return;
                    }
                });
                // ----------------------------------------------------------
                return fixed;
                // ----------------------------------------------------------
            },
            // --------------------------------------------------------------


            // --------------------------------------------------------------
            // Whether to enable or disable the goverment-donation radio
            // https://bit.ly/3fJLpER
            // --------------------------------------------------------------
            govermentDonation: function(){
                var entry = this.entry; var enabled = false;
                if( entry.road_type_contract_b ) enabled = true;
                return enabled;
            },
            // --------------------------------------------------------------


            // --------------------------------------------------------------
            // Find default selection of goverment-donation from purchase doc
            // --------------------------------------------------------------
            defaultGovermentDonation: function(){
                return io.get( this, 'target.doc.road_type_sub1_contract' );
            },
            // --------------------------------------------------------------


            // --------------------------------------------------------------
            // Whether to enable or disable the road-sharing radio
            // https://bit.ly/2WSGk4z
            // --------------------------------------------------------------
            roadSharing: function(){
                // ----------------------------------------------------------
                var entry = this.entry; var enabled = false;
                var public = entry.road_type_sub2_contract_a;
                var private = entry.road_type_sub2_contract_b;
                // ----------------------------------------------------------
                if( public && !private ) enabled = false;
                if( !public && !private ) enabled = true;
                // ----------------------------------------------------------
                entry.road_type_sub3_contract = null;
                return enabled;
                // ----------------------------------------------------------
            },
            // --------------------------------------------------------------


            // --------------------------------------------------------------
            // Find default selection of road-sharing from purchase doc
            // --------------------------------------------------------------
            defaultRoadSharing: function(){
                return io.get( this, 'target.doc.road_type_sub3_contract' );
            },
            // --------------------------------------------------------------


            // --------------------------------------------------------------
            // Public and private road type checker
            // --------------------------------------------------------------
            isPublic: function(){ return this.entry.road_type_sub2_contract_a },
            isOnlyPublic: function(){ return this.isPublic && !this.isPrivate },
            // --------------------------------------------------------------
            isPrivate: function(){ return this.entry.road_type_sub2_contract_b },
            isOnlyPrivate: function(){ return this.isPrivate && !this.isPublic },
            // --------------------------------------------------------------
        },
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Watchers
        // ------------------------------------------------------------------
        watch: {
            // --------------------------------------------------------------
            // Reset the burden-contract values when the selection is switched
            // --------------------------------------------------------------
            'entry.road_private_burden_contract': function( value ){
                if( 2 !== value ){
                    var entry = this;
                    entry.entry.road_private_burden_area_contract = null;
                    entry.entry.road_private_burden_share_denom_contract = null;
                    entry.entry.road_private_burden_share_number_contract = null;
                    entry.entry.road_private_burden_amount_contract = null;
                }
            },
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Reset setback area size when contract changed
            // --------------------------------------------------------------
            'entry.road_setback_area_contract': function( value ){
                if( 1 !== value ) this.entry.road_setback_area_size_contract = null;
            },
            // --------------------------------------------------------------
            

            // --------------------------------------------------------------
            // Reset goverment-donation when unchecked
            // --------------------------------------------------------------
            'entry.road_type_contract_b': function( value ){
                if( !value ) this.entry.road_type_sub1_contract = null;
            },
            // --------------------------------------------------------------


            // --------------------------------------------------------------
            // Reset private-road form when private-road check is unchecked
            // --------------------------------------------------------------
            'entry.road_type_sub2_contract_b': function( value ){
                if( !value ){
                    var entry = this.entry;
                    entry.road_private_burden_contract = null;
                    entry.road_private_burden_area_contract = null;
                    entry.road_private_burden_share_denom_contract = null;
                    entry.road_private_burden_share_number_contract = null;
                    entry.road_private_burden_amount_contract = null;
                    entry.road_setback_area_contract = null;
                    entry.road_setback_area_size_contract = null;
                    entry.road_type_sub3_contract = null;
                }
            },
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Reset road properties when type selection is empty
            // --------------------------------------------------------------
            isRoadTypeEmpty: function( empty ){
                if( empty ){
                    this.entry.road_type_sub2_contract_a = null;
                    this.entry.road_type_sub2_contract_b = null;
                    this.entry.road_type_sub1_contract = null;
                    this.entry.road_type_sub3_contract = null;
                }
            },
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Watch goverment donation
            // --------------------------------------------------------------
            govermentDonation: function( donation ){
                this.entry.road_type_sub1_contract = donation ? this.defaultGovermentDonation : null;
            },
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Watch private road
            // --------------------------------------------------------------
            isPrivate: function( private ){
                this.entry.road_type_sub3_contract = private ? this.defaultRoadSharing : null;
            }
            // --------------------------------------------------------------
        },
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Component methods
        // ------------------------------------------------------------------
        methods: {
            // --------------------------------------------------------------
            // Update road ownership based on road type selection
            // --------------------------------------------------------------
            updateRoadOwnership: function(){
                // ----------------------------------------------------------
                var vm = this; var entry = vm.entry;
                var public = entry.road_type_sub2_contract_a;
                var private = entry.road_type_sub2_contract_b;
                // ----------------------------------------------------------
                setTimeout( function(){
                    // ------------------------------------------------------
                    var empty = vm.isRoadTypeEmpty;
                    if( empty ){ public = false; private = false };
                    // ------------------------------------------------------
                    if( entry.road_type_contract_a || entry.road_type_contract_d ) public = true;
                    else public = false;
                    // ------------------------------------------------------
                    if( entry.road_type_contract_h || entry.road_type_contract_i ) private = true;
                    else private = false;
                    // ------------------------------------------------------

                    // ------------------------------------------------------
                    entry.road_type_sub2_contract_a = public;
                    entry.road_type_sub2_contract_b = private;
                    // ------------------------------------------------------
                });
                // ----------------------------------------------------------
            },
            // --------------------------------------------------------------


            // --------------------------------------------------------------
            // Enable all burden input, focus the one that was clicked
            // --------------------------------------------------------------
            inputBurden: function(e){
                var vm = this; var entry = vm.entry;
                if( 2 === entry.road_private_burden_contract ) return;
                // ----------------------------------------------------------
                entry.road_private_burden_contract = 2; // Activate the selection
                // ----------------------------------------------------------
                setTimeout( function(){
                    var $target = $( e.target );
                    var $input = $target.closest('.row').find('input');
                    $input.trigger('focus');
                });
                // ----------------------------------------------------------
            },
            // --------------------------------------------------------------


            // --------------------------------------------------------------
            // Enable input road-setback
            // --------------------------------------------------------------
            inputSetback: function(){
                // ----------------------------------------------------------
                var vm = this; var entry = vm.entry;
                if( 1 === entry.road_setback_area_contract ) return;
                // ----------------------------------------------------------
                entry.road_setback_area_contract = 1;
                // ----------------------------------------------------------
                setTimeout( function(){
                    var $input = $( vm.$refs.inputSetback ).find('input');
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