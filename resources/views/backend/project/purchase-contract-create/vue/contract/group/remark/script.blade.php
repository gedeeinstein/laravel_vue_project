<script> @minify
// --------------------------------------------------------------------------
(function( $, io, document, window, undefined ){
    // ----------------------------------------------------------------------

    // ----------------------------------------------------------------------
    // Contract group remark
    // ----------------------------------------------------------------------
    Vue.component( 'group-remark', {
        // ------------------------------------------------------------------
        props: [ 'value', 'project', 'contract', 'target', 'disabled', 'completed' ],
        template: '#group-remark',
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Reactive data
        // ------------------------------------------------------------------
        data: function(){
            // --------------------------------------------------------------
            var data = {
                entry: this.value,
                project: this.project,
                contract: this.contract,
                response: this.target.response,
                secondContent: false,
            };
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Populate default values
            // https://bit.ly/35I2QkC
            // --------------------------------------------------------------
            var entry = data.entry;
            var contractUpdate = io.get( data.response, 'contract_update' );
            // --------------------------------------------------------------
            var termAB = io.get( data.response, 'desired_contract_terms_ab' );
            var termAF = io.get( data.response, 'desired_contract_terms_af' );
            var termAG = io.get( data.response, 'desired_contract_terms_ag' );
            var termAH = io.get( data.response, 'desired_contract_terms_ah' );
            var termAI = io.get( data.response, 'desired_contract_terms_ai' );
            var termQ = io.get( data.response, 'desired_contract_terms_q' );
            var termO = io.get( data.response, 'desired_contract_terms_o' );
            var termS = io.get( data.response, 'desired_contract_terms_s' );
            var termP = io.get( data.response, 'desired_contract_terms_p' );
            var termR1 = io.get( data.response, 'desired_contract_terms_r_1' );
            var termR2 = io.get( data.response, 'desired_contract_terms_r_2' );
            // --------------------------------------------------------------
            if( io.isNull( entry.front_road_a )) entry.front_road_a = 1 === termAB && 2 === contractUpdate;
            if( io.isNull( entry.front_road_d )) entry.front_road_d = 1 === termAF && 2 === contractUpdate;
            if( io.isNull( entry.front_road_f )) entry.front_road_f = 1 === termAG && 2 === contractUpdate;
            if( io.isNull( entry.front_road_h )) entry.front_road_h = 1 === termAH && 2 === contractUpdate;
            if( io.isNull( entry.front_road_i )) entry.front_road_i = 1 === termAI && 2 === contractUpdate;
            // --------------------------------------------------------------
            if( io.isNull( entry.building_for_merchandise_a )) entry.building_for_merchandise_a = 1 === termQ && 2 === contractUpdate;
            if( io.isNull( entry.building_for_merchandise_b )) entry.building_for_merchandise_b = (1 === termO || 1 === termS) && 2 === contractUpdate;
            if( io.isNull( entry.building_for_merchandise_c )) entry.building_for_merchandise_c = 1 === termP && 2 === contractUpdate;
            // --------------------------------------------------------------
            if( io.isNull( entry.profitable_property_a )) entry.profitable_property_a = 2 === termR1 && 2 === contractUpdate;
            if( io.isNull( entry.profitable_property_b )) entry.profitable_property_b = 2 === termR2 && 2 === contractUpdate;
            // --------------------------------------------------------------
            if( io.isNull( entry.remarks_other )) entry.remarks_other = false;
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Road size contract A
            // Declare them here to resolve the reactivity issue
            // https://bit.ly/35R7XPK
            // --------------------------------------------------------------
            data.roadSizeA = false;
            data.roadSizeElse = false;
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Original content
            // --------------------------------------------------------------
            if( entry.original_contents_text_b ) data.secondContent = true;
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
            prefix: function(){
                var entry = this.entry;
                var prefix = 'new-contract-group-remark-';
                return entry.id ? 'contract-group-remark-' +entry.id+ '-' : prefix;
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


            // ==============================================================
            // Road computed properties
            // ==============================================================

            // --------------------------------------------------------------
            // Road types - https://bit.ly/2SPk1eQ
            // --------------------------------------------------------------
            roadTypeA: function(){ return !!io.get( this, 'entry.road_type_contract_a' )},
            roadTypeB: function(){ return !!io.get( this, 'entry.road_type_contract_b' )},
            roadTypeC: function(){ return !!io.get( this, 'entry.road_type_contract_c' )},
            roadTypeD: function(){ return !!io.get( this, 'entry.road_type_contract_d' )},
            roadTypeE: function(){ return !!io.get( this, 'entry.road_type_contract_e' )},
            roadTypeF: function(){ return !!io.get( this, 'entry.road_type_contract_f' )},
            roadTypeG: function(){ return !!io.get( this, 'entry.road_type_contract_g' )},
            roadTypeH: function(){ return !!io.get( this, 'entry.road_type_contract_h' )},
            roadTypeI: function(){ return !!io.get( this, 'entry.road_type_contract_i' )},
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Road type sub 2 - https://bit.ly/3cjBJPh
            // --------------------------------------------------------------
            roadSub2A: function(){ return !!io.get( this, 'entry.road_type_sub2_contract_a' )},
            roadSub2B: function(){ return !!io.get( this, 'entry.road_type_sub2_contract_b' )},
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Road type sub 1
            // https://bit.ly/3dv0IiQ
            // --------------------------------------------------------------
            roadSub1: function(){ return io.get( this, 'entry.road_type_sub1_contract' )},
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Road type sub 3
            // https://bit.ly/2WzIfeg
            // --------------------------------------------------------------
            roadSub3: function(){ return io.get( this, 'entry.road_type_sub3_contract' )},
            // --------------------------------------------------------------


            // --------------------------------------------------------------
            // Road reference, this holds the raod references for the reactivity
            // --------------------------------------------------------------
            reference: function(){
                var reference = {
                    // ------------------------------------------------------
                    sizeA: this.roadSizeA,
                    public: this.roadSub2A, private: this.roadSub2B,
                    donation: this.roadSub1, shared: this.roadSub3,
                    // ------------------------------------------------------
                    typeA: this.roadTypeA, typeB: this.roadTypeB, typeC: this.roadTypeC,
                    typeD: this.roadTypeD, typeE: this.roadTypeE, typeF: this.roadTypeF,
                    typeG: this.roadTypeG, typeH: this.roadTypeH, typeI: this.roadTypeI
                    // ------------------------------------------------------
                }; return reference;
            },
            // --------------------------------------------------------------


            // --------------------------------------------------------------
            // Road range condition
            // Range A - https://bit.ly/3fyZCVe
            // --------------------------------------------------------------
            conditionA1: function(){ var o = this.reference; return !!( o.typeA && o.sizeA )},
            // --------------------------------------------------------------
            // Range B - https://bit.ly/2Ad0sXz
            // --------------------------------------------------------------
            conditionB1: function(){ var o = this.reference; return !!( o.typeB && o.public && 2 === o.donation )},
            conditionB2: function(){ var o = this.reference; return !!( o.typeB && o.private && 1 === o.shared )},
            conditionB3: function(){ var o = this.reference; return !!( o.typeB && o.private && 2 === o.shared )},
            // --------------------------------------------------------------
            // Range C - https://bit.ly/3fC8pWC
            // --------------------------------------------------------------
            conditionC1: function(){ var o = this.reference; return !!( o.typeC && o.public && o.sizeA )},
            conditionC2: function(){ var o = this.reference; return !!( o.typeC && o.public && !o.sizeA )},
            conditionC3: function(){ var o = this.reference; return !!( o.typeC && o.private && o.sizeA && 1 === o.shared )},
            conditionC4: function(){ var o = this.reference; return !!( o.typeC && o.private && o.sizeA && 2 === o.shared )},
            conditionC5: function(){ var o = this.reference; return !!( o.typeC && o.private && !o.sizeA && 1 === o.shared )},
            conditionC6: function(){ var o = this.reference; return !!( o.typeC && o.private && !o.sizeA && 2 === o.shared )},
            // --------------------------------------------------------------
            // Range D - https://bit.ly/2YJt3xT
            // --------------------------------------------------------------
            conditionD1: function(){ var o = this.reference; return !!( o.typeD && o.public )},
            // --------------------------------------------------------------
            // Range E - https://bit.ly/3fwGcjR
            // --------------------------------------------------------------
            conditionE1: function(){ var o = this.reference; return !!( o.typeE && o.public && o.sizeA )},
            conditionE2: function(){ var o = this.reference; return !!( o.typeE && o.public && !o.sizeA )},
            conditionE3: function(){ var o = this.reference; return !!( o.typeE && o.private && o.sizeA && 1 === o.shared )},
            conditionE4: function(){ var o = this.reference; return !!( o.typeE && o.private && o.sizeA && 2 === o.shared )},
            conditionE5: function(){ var o = this.reference; return !!( o.typeE && o.private && !o.sizeA && 1 === o.shared )},
            conditionE6: function(){ var o = this.reference; return !!( o.typeE && o.private && !o.sizeA && 2 === o.shared )},
            // --------------------------------------------------------------
            // Range F - https://bit.ly/3chXmQbsizeA
            // --------------------------------------------------------------private
            conditionF1: function(){ var o = this.reference; return !!( o.typeF && o.public && o.sizeA )},
            conditionF2: function(){ var o = this.reference; return !!( o.typeF && o.public && !o.sizeA )},
            conditionF3: function(){ var o = this.reference; return !!( o.typeF && o.private && o.sizeA && 1 === o.shared )},
            conditionF4: function(){ var o = this.reference; return !!( o.typeF && o.private && o.sizeA && 2 === o.shared )},
            conditionF5: function(){ var o = this.reference; return !!( o.typeF && o.private && !o.sizeA && 1 === o.shared )},
            conditionF6: function(){ var o = this.reference; return !!( o.typeF && o.private && !o.sizeA && 2 === o.shared )},
            // --------------------------------------------------------------
            // Range G - https://bit.ly/2LeHSjW
            // --------------------------------------------------------------
            conditionG1: function(){ var o = this.reference; return !!( o.typeG && o.public )},
            conditionG2: function(){ var o = this.reference; return !!( o.typeG && o.private && 1 === o.shared )},
            conditionG3: function(){ var o = this.reference; return !!( o.typeG && o.private && 2 === o.shared )},
            // --------------------------------------------------------------
            // Range H - https://bit.ly/2zl3wAo
            // --------------------------------------------------------------
            conditionH1: function(){ var o = this.reference; return !!( o.typeH && o.private && 1 === o.shared )},
            conditionH2: function(){ var o = this.reference; return !!( o.typeH && o.private && 2 === o.shared )},
            // --------------------------------------------------------------
            // Range I - https://bit.ly/3fy9u1p
            // --------------------------------------------------------------
            conditionI1: function(){ var o = this.reference; return !!( o.typeI && o.private && 1 === o.shared )},
            conditionI2: function(){ var o = this.reference; return !!( o.typeI && o.private && 2 === o.shared )},
            // --------------------------------------------------------------


            // --------------------------------------------------------------
            // Road check condition based on colum below
            // https://bit.ly/2WeUEoQ
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Check Front Road A condition
            // --------------------------------------------------------------
            checkRoadA: function(){ // A137-D1
                var show = this.parseConditionString([
                    this.conditionC1, this.conditionC3, this.conditionC4, this.conditionC5, this.conditionC6,
                    this.conditionD1,
                    this.conditionE1, this.conditionE3, this.conditionE4,
                    this.conditionG1, this.conditionG2, this.conditionG3,
                    this.conditionH1, this.conditionH2,
                    this.conditionI1, this.conditionI2
                ]);
                // ----------------------------------------------------------
                if( !show ) Vue.set( this.entry, 'front_road_a', null );
                return show;
                // ----------------------------------------------------------
            },
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Check Front Road B and C condition
            // --------------------------------------------------------------
            checkRoadB: function(){ // A137-D3-1
                var show = this.parseConditionString([
                    this.conditionB2,
                    this.conditionC3, this.conditionC5,
                    this.conditionE3, this.conditionE5,
                    this.conditionF3, this.conditionF5,
                    this.conditionG2, this.conditionH1, this.conditionI1,
                ]);
                // ----------------------------------------------------------
                if( !show ) Vue.set( this.entry, 'front_road_b', null );
                return show;
                // ----------------------------------------------------------
            },
            // --------------------------------------------------------------
            checkRoadC: function(){ // A137-D3-2
                var show = this.checkRoadB;
                if( !show ) Vue.set( this.entry, 'front_road_c', null );
                return show;
            },
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Check Front Road D and E condition
            // --------------------------------------------------------------
            checkRoadD: function(){ // A137-D5-1
                var show = this.parseConditionString([
                    this.conditionF2, this.conditionF5, this.conditionF6
                ]);
                // ----------------------------------------------------------
                if( !show ) Vue.set( this.entry, 'front_road_d', null );
                return show;
                // ----------------------------------------------------------
            },
            // --------------------------------------------------------------
            checkRoadE: function(){ // A137-D5-2
                var show = this.checkRoadD;
                if( !show ) Vue.set( this.entry, 'front_road_e', null );
                return show;
            },
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Check Front Road F and G condition
            // --------------------------------------------------------------
            checkRoadF: function(){ // A137-D6-1
                var show = this.parseConditionString([
                    this.conditionC1, this.conditionC3, this.conditionC4,
                    this.conditionE1, this.conditionE3, this.conditionE4,
                    this.conditionF1, this.conditionF3, this.conditionF4,
                ]);
                // ----------------------------------------------------------
                if( !show ) Vue.set( this.entry, 'front_road_f', null );
                return show;
                // ----------------------------------------------------------
            },
            // --------------------------------------------------------------
            checkRoadG: function(){ // A137-D6-2
                var show = this.checkRoadF;
                if( !show ) Vue.set( this.entry, 'front_road_g', null );
                return show;
            },
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Check Front Road H and I condition
            // --------------------------------------------------------------
            checkRoadH: function(){ // A137-D7-1
                var show = this.parseConditionString([
                    this.conditionG1, this.conditionG2, this.conditionG3
                ]);
                // ----------------------------------------------------------
                if( !show ) Vue.set( this.entry, 'front_road_h', null );
                return show;
                // ----------------------------------------------------------
            },
            // --------------------------------------------------------------
            checkRoadI: function(){ // A137-D7-2
                var show = this.checkRoadH;
                if( !show ) Vue.set( this.entry, 'front_road_i', null );
                return show;
            },
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Check Front Road J (A137-D8) is always visible
            // --------------------------------------------------------------
            checkRoadJ: function(){ return true },
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Check Front Road K and L condition
            // --------------------------------------------------------------
            checkRoadK: function(){ // A137-D10-1
                var show = this.parseConditionString([
                    this.conditionB3,
                    this.conditionC4, this.conditionC6,
                    this.conditionE4, this.conditionE6,
                    this.conditionF4, this.conditionF6,
                    this.conditionG3, this.conditionH2, this.conditionI2,
                ]);
                // ----------------------------------------------------------
                if( !show ) Vue.set( this.entry, 'front_road_k', null );
                return show;
                // ----------------------------------------------------------
            },
            // --------------------------------------------------------------
            checkRoadL: function(){ // A137-D10-2
                var show = this.parseConditionString([
                    this.conditionB3,
                    this.conditionC4, this.conditionC6,
                    this.conditionE4, this.conditionE6,
                    this.conditionF4, this.conditionF6,
                    this.conditionG3, this.conditionH2, this.conditionI2,
                ]);
                if( !show ) Vue.set( this.entry, 'front_road_l', null );
                return show;
            },
            // --------------------------------------------------------------


            // --------------------------------------------------------------
            // Agricultural section
            // https://bit.ly/2AdVUQH
            // --------------------------------------------------------------
            checkAgricultural: function(){
                // ----------------------------------------------------------
                var show = false; var categories = [ '田', '畑' ];
                var residentials = io.get( this.project, 'property.residentials' );
                // ----------------------------------------------------------
                if( residentials && residentials.length ) $.each( residentials, function( r, residential ){
                    var category = io.get( residential, 'land_category.value' );
                    // ------------------------------------------------------
                    if( io.indexOf( categories, category ) >= 0 ){ // If category is in the list
                        show = true; // Set to visible
                        return false; // Break out of the loop
                    }
                    // ------------------------------------------------------
                });
                // ----------------------------------------------------------
                if( !show ){ // Reset values when condition changes
                    Vue.set( this.entry, 'agricultural_section_a', null );
                    Vue.set( this.entry, 'agricultural_section_b', null );
                }
                // ----------------------------------------------------------
                return show;
                // ----------------------------------------------------------
            },
            // --------------------------------------------------------------


            // --------------------------------------------------------------
            // Cross border section
            // https://bit.ly/3bblxOU
            // --------------------------------------------------------------
            checkCrossBorder: function(){
                // ----------------------------------------------------------
                var show = false;
                var buildingKind = io.get( this.contract, 'contract_building_kind' );
                var unregisteredKind = io.get( this.contract, 'contract_building_unregistered_kind' );
                show = 1 === buildingKind || 1 === unregisteredKind;
                // ----------------------------------------------------------
                if( !show ) Vue.set( this.entry, 'cross_border', null );
                return show;
                // ----------------------------------------------------------
            },
            // --------------------------------------------------------------


            // --------------------------------------------------------------
            // Trading other people section
            // https://bit.ly/3cdvZqp
            // --------------------------------------------------------------
            checkTradingOther: function(){
                // ----------------------------------------------------------
                var show = false;
                // ----------------------------------------------------------
                var residentials = io.get( this.project, 'property.residentials' );
                if( residentials && residentials.length ) $.each( residentials, function( r, residential ){
                    var exit = false;
                    // ------------------------------------------------------
                    var contractors = io.get( residential, 'common.contractors' );
                    if( contractors && contractors.length ) $.each( contractors, function( c, contractor ){
                        // --------------------------------------------------
                        if( !contractor.same_to_owner ){
                            show = true; exit = true; // Update show and mark for exit
                            return false; // Breack out and exit loop
                        }
                        // --------------------------------------------------
                    });
                    // ------------------------------------------------------
                    return !exit; // If marked as exit, break out of the loop
                    // ------------------------------------------------------
                });
                // ----------------------------------------------------------
                if( !show ) Vue.set( this.entry, 'trading_other_people', null );
                // ----------------------------------------------------------
                return show;
                // ----------------------------------------------------------
            },
            // --------------------------------------------------------------


            // --------------------------------------------------------------
            // Building for merchandise section.
            // It shares the same conditionals with 'checkCrossBorder' section above
            // https://bit.ly/2zmVqr6
            // --------------------------------------------------------------
            checkBuildingMerchandise: function(){
                var show = this.checkCrossBorder;
                // ----------------------------------------------------------
                if( !show ){
                    Vue.set( this.entry, 'building_for_merchandise_a', null );
                    Vue.set( this.entry, 'building_for_merchandise_b', null );
                    Vue.set( this.entry, 'building_for_merchandise_c', null );
                } return show;
                // ----------------------------------------------------------
            },
            // --------------------------------------------------------------


            // --------------------------------------------------------------
            // Profitable property section
            // https://bit.ly/35INgVY
            // --------------------------------------------------------------
            checkProfitableProperty: function(){
                // ----------------------------------------------------------
                var show = false;
                var kind = io.get( this.entry, 'property_description_kind' );
                if( 2 === kind || 3 === kind ) show = true;
                // ----------------------------------------------------------
                if( !show ){
                    Vue.set( this.entry, 'profitable_property_a', null );
                    Vue.set( this.entry, 'profitable_property_b', null );
                } return show;
                // ----------------------------------------------------------
            }
            // --------------------------------------------------------------
        },
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Watchers
        // ------------------------------------------------------------------
        watch: {
            'entry.road_size_contract_a': {
                immediate: true,
                handler: function( value ){
                    Vue.set( this, 'roadSizeA', !!value );
                    Vue.set( this, 'roadSizeElse', !value );
                }
            }
        },
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Component methods
        // ------------------------------------------------------------------
        methods: {
            // --------------------------------------------------------------
            parseConditionString: function( conditions, log ){
                var entry = this.entry; var result = false;
                if( conditions ) $.each( conditions, function( i, condition ){
                    if( condition ){ result = true; return false }
                });
                return result;
            },
            // --------------------------------------------------------------


            // --------------------------------------------------------------
            // Original content
            // --------------------------------------------------------------
            addContent: function(){ Vue.set( this, 'secondContent', true )},
            // --------------------------------------------------------------
            removeContent: function(){
                // ----------------------------------------------------------
                var confirmed = true;
                var content = io.trim( this.entry.original_contents_text_b );
                // ----------------------------------------------------------
                // Show confirmation dialog
                // ----------------------------------------------------------
                if( content ) confirmed = confirm('@lang('label.confirm_delete')');
                if( !confirmed ) return;
                // ----------------------------------------------------------

                // ----------------------------------------------------------
                // Remove second content and set it null
                // ----------------------------------------------------------
                Vue.set( this, 'secondContent', false );
                Vue.set( this.entry, 'original_contents_text_b', null );
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
