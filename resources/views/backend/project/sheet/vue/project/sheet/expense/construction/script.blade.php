<script> @minify
// --------------------------------------------------------------------------
(function( $, io, document, window, undefined ){
    // ----------------------------------------------------------------------

    // ----------------------------------------------------------------------
    // Expense construction
    // ----------------------------------------------------------------------
    Vue.component( 'expense-construction', {
        // ------------------------------------------------------------------
        props: [ 'value', 'sheet', 'sheetValues', 'project', 'expense', 'disabled' ],
        template: '#expense-construction',
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Reactive data
        // ------------------------------------------------------------------
        data: function(){
            // --------------------------------------------------------------
            var data = {
                entry: this.value,
                additionalKey: 0,
                checklist: this.sheet.checklist,
                question: this.project.question,
                values: this.sheetValues,
                rows: io.get( this, 'expense.f.rows' )
            };
            // --------------------------------------------------------------
            // data.culturalFee: entry.cultural_property_research_fee;
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // console.log( data.rows );
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
                var prefix = 'new-expense-construction-';
                return entry.id ? 'expense-construction-' +entry.id+ '-' : prefix;
            },
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Find out if this section is disabled
            // --------------------------------------------------------------
            isDisabled: function(){
                return this.disabled || false;
            },
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Find out if building-demolition is disabled
            // --------------------------------------------------------------
            isBuildingDemolitionDisabled: function(){
                var demolition = io.get( this.checklist, 'building_demolition_work' );
                return !demolition;
            },
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Find out if road-construction is disabled
            // --------------------------------------------------------------
            isRoadConstructionDisabled: function(){
                var road = io.get( this.checklist, 'new_road_type' );
                return 3 === road;
            },
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Find out if commission is disabled
            // --------------------------------------------------------------
            isCommissionDisabled: function(){
                var construction = io.get( this.checklist, 'construction_work' );
                return 3 !== construction;
            },
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Find out if location-fee is disabled
            // --------------------------------------------------------------
            isLocationFeeDisabled: function(){
                // ----------------------------------------------------------
                var disabled = this.isDisabled;
                var roadType = io.get( this.checklist, 'new_road_type' );
                return disabled || 1 !== roadType;
                // ----------------------------------------------------------
            },
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Find out if cultural-property is disabled
            // --------------------------------------------------------------
            isCulturalPropertyDisabled: function(){
                // ----------------------------------------------------------
                var disabled = this.isDisabled;
                var cultural = io.get( this.project, 'question.cultural_property' );
                return disabled || 1 !== cultural;
                // ----------------------------------------------------------
            },
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Find out if wall-construction is disabled
            // --------------------------------------------------------------
            isWallConstructionDisabled: function(){
                // ----------------------------------------------------------
                var disabled = this.isDisabled;
                // ----------------------------------------------------------
                var wall = io.get( this.checklist, 'retaining_wall' );
                var wallLength = io.get( this.checklist, 'retaining_wall_length' );
                var wallHeight = io.get( this.checklist, 'retaining_wall_height' );
                // ----------------------------------------------------------
                return disabled || !( 1 === wall && wallLength && wallHeight <= 6 );
                // ----------------------------------------------------------
            },
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Find out if pole-relocation is hidden
            // --------------------------------------------------------------
            isPoleRelocationHidden: function(){
                var relocation = io.get( this.project, 'question.telegraph_pole_hindrance' );
                return 1 !== relocation;
            },
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Find out if gutter-construction is hidden
            // --------------------------------------------------------------
            isGutterConstructionHidden: function(){
                var gutter = io.get( this.checklist, 'side_groove' );
                return 3 === gutter;
            },
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Find out if embankment/fill-work is hidden
            // --------------------------------------------------------------
            isEmbankmentConstructionHidden: function(){
                var fill = io.get( this.checklist, 'fill' );
                var noFill = io.get( this.checklist, 'no_fill' );
                return !( fill && !noFill );
            },
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Find out if retaining wall demolition is hidden
            // --------------------------------------------------------------
            isWallDemolitionHidden: function(){
                var demolition = io.get( this.checklist, 'demolition_work_of_retaining_wall' );
                return !demolition;
            },
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Tsubo of demolition expense
            // --------------------------------------------------------------
            tsuboDemolition: function(){
                var area = Vue.filter('tsubo')( this.project.overall_area );
                var demolition = this.entry.building_demolition;
                return Math.trunc( demolition / area );
            },
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Tsubo of construction workset
            // --------------------------------------------------------------
            tsuboWorkset: function(){
                var area = Vue.filter('tsubo')( this.project.overall_area );
                var workset = this.entry.construction_work_set;
                return Math.trunc( workset / area );
            },
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Total of building demolition
            // --------------------------------------------------------------
            totalBuildingDemolition: function(){
                // ----------------------------------------------------------
                var total = 0; var rows = this.rows;
                if( rows && rows.length ){
                    var entries = io.filter( rows, { name: '建物解体工事' });
                    if( entries ) io.map( entries, function( entry ){ total += entry.decided });
                } return total;
                // ----------------------------------------------------------
            },
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Total of wall demolition
            // --------------------------------------------------------------
            totalWallDemolition: function(){
                // ----------------------------------------------------------
                var total = 0; var rows = this.rows;
                if( rows && rows.length ){
                    var entries = io.filter( rows, { name: '擁壁解体工事' });
                    if( entries ) io.map( entries, function( entry ){ total += entry.decided });
                } return total;
                // ----------------------------------------------------------
            },
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Total of pole relocation
            // --------------------------------------------------------------
            totalPoleRelocation: function(){
                // ----------------------------------------------------------
                var total = 0; var rows = this.rows;
                if( rows && rows.length ){
                    var entries = io.filter( rows, { name: '電柱移設・撤去' });
                    if( entries ) io.map( entries, function( entry ){ total += entry.decided });
                } return total;
                // ----------------------------------------------------------
            },
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Total of plumbing
            // --------------------------------------------------------------
            totalPlumbing: function(){
                // ----------------------------------------------------------
                var total = 0; var rows = this.rows;
                if( rows && rows.length ){
                    var entries = io.filter( rows, { name: '水道・下水工事' });
                    if( entries ) io.map( entries, function( entry ){ total += entry.decided });
                } return total;
                // ----------------------------------------------------------
            },
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Total of embankment construction
            // --------------------------------------------------------------
            totalEmbankment: function(){
                // ----------------------------------------------------------
                var total = 0; var rows = this.rows;
                if( rows && rows.length ){
                    var entries = io.filter( rows, { name: '盛り土工事' });
                    if( entries ) io.map( entries, function( entry ){ total += entry.decided });
                } return total;
                // ----------------------------------------------------------
            },
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Total of wall construction
            // --------------------------------------------------------------
            totalWallConstruction: function(){
                // ----------------------------------------------------------
                var total = 0; var rows = this.rows;
                if( rows && rows.length ){
                    var entries = io.filter( rows, { name: '擁壁工事' });
                    if( entries ) io.map( entries, function( entry ){ total += entry.decided });
                } return total;
                // ----------------------------------------------------------
            },
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Total of road construction
            // --------------------------------------------------------------
            totalRoadConstruction: function(){
                // ----------------------------------------------------------
                var total = 0; var rows = this.rows;
                if( rows && rows.length ){
                    var entries = io.filter( rows, { name: '道路工事' });
                    if( entries ) io.map( entries, function( entry ){ total += entry.decided });
                } return total;
                // ----------------------------------------------------------
            },
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Total of gutter construction
            // --------------------------------------------------------------
            totalGutterConstruction: function(){
                // ----------------------------------------------------------
                var total = 0; var rows = this.rows;
                if( rows && rows.length ){
                    var entries = io.filter( rows, { name: '側溝工事' });
                    if( entries ) io.map( entries, function( entry ){ total += entry.decided });
                } return total;
                // ----------------------------------------------------------
            },
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Total of construction work rate
            // --------------------------------------------------------------
            totalConstructionWorkset: function(){
                // ----------------------------------------------------------
                var total = 0; var rows = this.rows;
                if( rows && rows.length ){
                    var entries = io.filter( rows, { name: '造成工事' });
                    if( entries ) io.map( entries, function( entry ){ total += entry.decided });
                } return total;
                // ----------------------------------------------------------
            },
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Total of construction work rate
            // --------------------------------------------------------------
            totalLocationFee: function(){
                // ----------------------------------------------------------
                var total = 0; var rows = this.rows;
                if( rows && rows.length ){
                    var entries = io.filter( rows, { name: '位置指定申請費' });
                    if( entries ) io.map( entries, function( entry ){ total += entry.decided });
                } return total;
                // ----------------------------------------------------------
            },
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Total of commission fee
            // --------------------------------------------------------------
            totalCommission: function(){
                // ----------------------------------------------------------
                var total = 0; var rows = this.rows;
                if( rows && rows.length ){
                    var entries = io.filter( rows, { name: '開発委託費' });
                    if( entries ) io.map( entries, function( entry ){ total += entry.decided });
                } return total;
                // ----------------------------------------------------------
            },
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Total of cultural fee
            // --------------------------------------------------------------
            totalCultural: function(){
                // ----------------------------------------------------------
                var total = 0; var rows = this.rows;
                if( rows && rows.length ){
                    var entries = io.filter( rows, { name: '文化財調査費' });
                    if( entries ) io.map( entries, function( entry ){ total += entry.decided });
                } return total;
                // ----------------------------------------------------------
            },
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Total of construuction budget
            // --------------------------------------------------------------
            totalBudget: function(){
                // ----------------------------------------------------------
                var entry = this.entry; var result = 0;
                var checklist = io.get( this, 'sheet.checklist' );
                var question = io.get( this, 'project.question' );
                // ----------------------------------------------------------

                // ----------------------------------------------------------
                result += entry.waterwork_construction;
                result += entry.retaining_wall_construction;
                result += entry.construction_work_set;
                // ----------------------------------------------------------

                // ----------------------------------------------------------
                // Building demolition
                // ----------------------------------------------------------
                if( !this.isBuildingDemolitionDisabled ) result += entry.building_demolition;
                // ----------------------------------------------------------

                // ----------------------------------------------------------
                // Road construction
                // ----------------------------------------------------------
                if( !this.isRoadConstructionDisabled ) result += entry.road_construction;
                // ----------------------------------------------------------

                // ----------------------------------------------------------
                // Commission Fee
                // ----------------------------------------------------------
                if( !this.isCommissionDisabled ) result += entry.development_commissions_fee;
                // ----------------------------------------------------------

                // ----------------------------------------------------------
                // Side groove / Gutter
                // ----------------------------------------------------------
                if( !this.isGutterConstructionHidden ) result += entry.side_groove_construction;
                // ----------------------------------------------------------

                // ----------------------------------------------------------
                // Retaining wall demolition
                // ----------------------------------------------------------
                if( !this.isWallDemolitionHidden ) result += entry.retaining_wall_demolition;
                // ----------------------------------------------------------

                // ----------------------------------------------------------
                // Location fee
                // ----------------------------------------------------------
                if( !this.isLocationFeeDisabled ) result += entry.location_designation_application_fee;
                // ----------------------------------------------------------
                
                // ----------------------------------------------------------
                // Electric pole
                // ----------------------------------------------------------
                if( !this.isPoleRelocationHidden ) result += entry.transfer_electric_pole;
                // ----------------------------------------------------------

                // ----------------------------------------------------------
                // Fill work - Embankment
                // ----------------------------------------------------------
                if( !this.isEmbankmentConstructionHidden ) result += entry.fill_work;
                // ----------------------------------------------------------

                // ----------------------------------------------------------
                // Cultural property
                // ----------------------------------------------------------
                if( !this.isCulturalPropertyDisabled ) result += entry.cultural_property_research_fee;
                // ----------------------------------------------------------

                // ----------------------------------------------------------
                // Add additional costs
                // ----------------------------------------------------------
                var entries = io.get( entry, 'additional.entries' );
                if( entries && entries.length ) $.each( entries, function( a, additional ){
                    if( additional.value ) result += additional.value;
                });
                // ----------------------------------------------------------
                return result;
                // ----------------------------------------------------------
            },
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Total of construuction decided amount
            // --------------------------------------------------------------
            totalAmount: function(){
                // ----------------------------------------------------------
                var entry = this.entry; var result = 0;
                var checklist = io.get( this, 'sheet.checklist' );
                var question = io.get( this, 'project.question' );
                // ----------------------------------------------------------

                // ----------------------------------------------------------
                result += this.totalPlumbing;
                result += this.totalWallConstruction;
                result += this.totalConstructionWorkset;
                // ----------------------------------------------------------

                // ----------------------------------------------------------
                // Building demolition
                // ----------------------------------------------------------
                if( !this.isBuildingDemolitionDisabled ) result += this.totalBuildingDemolition;
                // ----------------------------------------------------------

                // ----------------------------------------------------------
                // Road construction
                // ----------------------------------------------------------
                if( !this.isRoadConstructionDisabled ) result += this.totalRoadConstruction;
                // ----------------------------------------------------------

                // ----------------------------------------------------------
                // Commission Fee
                // ----------------------------------------------------------
                if( !this.isCommissionDisabled ) result += this.totalCommission;
                // ----------------------------------------------------------

                // ----------------------------------------------------------
                // Side groove / Gutter
                // ----------------------------------------------------------
                if( !this.isGutterConstructionHidden ) result += this.totalGutterConstruction;
                // ----------------------------------------------------------

                // ----------------------------------------------------------
                // Retaining wall demolition
                // ----------------------------------------------------------
                if( !this.isWallDemolitionHidden ) result += this.totalWallDemolition;
                // ----------------------------------------------------------

                // ----------------------------------------------------------
                // Location fee
                // ----------------------------------------------------------
                if( !this.isLocationFeeDisabled ) result += this.totalLocationFee;
                // ----------------------------------------------------------
                
                // ----------------------------------------------------------
                // Electric pole
                // ----------------------------------------------------------
                if( !this.isPoleRelocationHidden ) result += this.totalPoleRelocation;
                // ----------------------------------------------------------

                // ----------------------------------------------------------
                // Fill work - Embankment
                // ----------------------------------------------------------
                if( !this.isEmbankmentConstructionHidden ) result += this.totalEmbankment;
                // ----------------------------------------------------------

                // ----------------------------------------------------------
                // Cultural property
                // ----------------------------------------------------------
                if( !this.isCulturalPropertyDisabled ) result += this.totalCultural;
                // ----------------------------------------------------------

                // ----------------------------------------------------------
                // Add additional costs
                // ----------------------------------------------------------
                var entries = io.get( entry, 'additional.entries' );
                if( entries && entries.length ) $.each( entries, function( a, additional ){
                    if( additional.total ) result += additional.total;
                });
                // ----------------------------------------------------------
                // console.log( result );
                return result;
                // ----------------------------------------------------------
            },
            // --------------------------------------------------------------
        },
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Watchers
        // ------------------------------------------------------------------
        watch: {
            // --------------------------------------------------------------
            // Reset building demolition field when disabled
            // --------------------------------------------------------------
            isBuildingDemolitionDisabled: {
                immediate: true, handler: function( disabled ){
                    if( disabled ){
                        this.entry.building_demolition = null;
                        this.entry.building_demolition_memo = null;
                    }
                }
            },
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Reset commission field when disabled
            // --------------------------------------------------------------
            isCommissionDisabled: {
                immediate: true, handler: function( disabled ){
                    if( disabled ){
                        this.entry.development_commissions_fee = null;
                        this.entry.development_commissions_fee_memo = null;
                    }
                }
            },
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Reset cultural property field when disabled
            // --------------------------------------------------------------
            isCulturalPropertyDisabled: {
                immediate: true, handler: function( disabled ){
                    if( disabled ){
                        this.entry.cultural_property_research_fee = null;
                        this.entry.cultural_property_research_fee_memo = null;
                    }
                }
            },
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Reset location fee field when disabled
            // --------------------------------------------------------------
            isLocationFeeDisabled: {
                immediate: true, handler: function( disabled ){
                    if( disabled ){
                        this.entry.location_designation_application_fee = null;
                        this.entry.location_designation_application_fee_memo = null;
                    }
                }
            },
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Reset road construction field when disabled
            // --------------------------------------------------------------
            isRoadConstructionDisabled: {
                immediate: true, handler: function( disabled ){
                    if( disabled ){
                        this.entry.road_construction = null;
                        this.entry.road_construction_memo = null;
                    }
                }
            },
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Reset pole-relocation field when hidden
            // --------------------------------------------------------------
            isPoleRelocationHidden: {
                immediate: true, handler: function( hidden ){
                    if( hidden ){
                        this.entry.transfer_electric_pole = null;
                        this.entry.transfer_electric_pole_memo = null;
                    }
                }
            },
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Reset gutter-relocation field when hidden
            // --------------------------------------------------------------
            isGutterConstructionHidden: {
                immediate: true, handler: function( hidden ){
                    if( hidden ){
                        this.entry.side_groove_construction = null;
                        this.entry.side_groove_construction_memo = null;
                    }
                }
            },
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Reset embankment/fill-work field when hidden
            // --------------------------------------------------------------
            isEmbankmentConstructionHidden: {
                immediate: true, handler: function( hidden ){
                    if( hidden ){
                        this.entry.fill_work = null;
                        this.entry.fill_work_memo = null;
                    }
                }
            },
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Reset retaining wall demoltion field when hidden
            // --------------------------------------------------------------
            isWallDemolitionHidden: {
                immediate: true, handler: function( hidden ){
                    if( hidden ){
                        this.entry.retaining_wall_demolition = null;
                        this.entry.retaining_wall_demolition_memo = null;
                    }
                }
            },
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // When changed, emit total budget back to the parent
            // --------------------------------------------------------------
            totalBudget: { 
                immediate: true, handler: function(){
                    this.$emit( 'totalBudget', this.totalBudget, 'construction' );
                }
            },
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // When changed, emit total amount back to the parent
            // --------------------------------------------------------------
            totalAmount: { 
                immediate: true, handler: function(){
                    this.$emit( 'totalAmount', this.totalAmount, 'construction' );
                }
            },
            // --------------------------------------------------------------
        },
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Component methods
        // ------------------------------------------------------------------
        methods: {
            // --------------------------------------------------------------
            // Auto calculate construction - building demolition
            // --------------------------------------------------------------
            // If checked S2-2(check)、Be calc depend on S4-1(radio).
            // If checked S2-2(uncheck)、The input fields are inactive and not calculated.
            // - S4-1= 1: 木造：set MeterToTsubo([project.building_area])(S1-3) * G41-1
            // - S4-1= 2: 鉄骨：set MeterToTsubo([project.building_area])(S1-3) * G41-2
            // - S4-1= 3: RC：set MeterToTsubo([project.building_area])(S1-3) * G41-3
            // https://bit.ly/395v88E
            // --------------------------------------------------------------
            calculateBuildingDemolition: function(){
                // ----------------------------------------------------------
                var entry = this.entry;
                var project = this.project;
                var result = entry.building_demolition;
                var checklist = io.get( this, 'sheet.checklist' );
                // ----------------------------------------------------------
                if( project && checklist ){
                    // ------------------------------------------------------
                    var multiplier = null;
                    var values = this.values;
                    // ------------------------------------------------------
                    var type = checklist.type_of_building;
                    var demolition = checklist.building_demolition_work;
                    var building = Vue.filter('tsubo')( project.building_area );
                    // ------------------------------------------------------
                    if( demolition ){
                        // --------------------------------------------------
                        if( 1 === type ) multiplier = io.find( values, { serial: 'G41-1' });
                        else if( 2 === type ) multiplier = io.find( values, { serial: 'G41-2' });
                        else if( 3 === type ) multiplier = io.find( values, { serial: 'G41-3' });
                        // --------------------------------------------------
                        if( multiplier ) result = building * multiplier.value;
                        // --------------------------------------------------
                    }
                    // ------------------------------------------------------
                    entry.building_demolition = Math.floor( result );
                    // ------------------------------------------------------
                }
                // ----------------------------------------------------------
            },
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Auto calculate construction - plumbing
            // --------------------------------------------------------------
            // [S6-1] * G41-4
            // https://bit.ly/3beGjgK
            // --------------------------------------------------------------
            calculatePlumbing: function(){
                // ----------------------------------------------------------
                var entry = this.entry;
                var checklist = io.get( this, 'sheet.checklist' );
                var values = this.values;
                // ----------------------------------------------------------
                if( checklist && checklist.water_draw_count ){
                    // ------------------------------------------------------
                    var multiplier = io.find( values, { serial: 'G41-4' });
                    // ------------------------------------------------------
                    if( multiplier ){
                        var result = checklist.water_draw_count * multiplier.value;
                        entry.waterwork_construction = Math.floor( result );
                    }
                    // ------------------------------------------------------
                }
                // ----------------------------------------------------------
            },
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Auto calculate embankment construction
            // --------------------------------------------------------------
            // S6-7 * G41-5
            // https://bit.ly/2UlRTQB
            // --------------------------------------------------------------
            calculateEmbankment: function(){
                // ----------------------------------------------------------
                var entry = this.entry;
                var values = this.values;
                var checklist = io.get( this, 'sheet.checklist' );
                // ----------------------------------------------------------
                if( checklist ){
                    // ------------------------------------------------------
                    var multiplier = io.find( values, { serial: 'G41-5' });
                    // ------------------------------------------------------
                    var fill = checklist.fill;
                    if( fill && multiplier ){
                        entry.fill_work = Math.floor( fill * multiplier.value );
                    }
                    // ------------------------------------------------------
                }
                // ----------------------------------------------------------
            },
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Auto calculate retaining wall construction
            // --------------------------------------------------------------
            // [S6-10]=1: 0.5m：[S6-11]× G41-6 + G41-7
            // [S6-10]=2: 0.75m：[S6-11]× G41-8 + G41-9
            // [S6-10]=3: 1m：[S6-11]× G41-10 + G41-11
            // [S6-10]=4: 1.5m：[S6-11]× G41-12 + G41-13
            // [S6-10]=5: 1.75m：[S6-11]× G41-14 + G41-15
            // [S6-10]=6: 1.95m：[S6-11]× G41-16 + G41-17
            // [S6-10]=7: それ以上：cannot calc.
            // https://bit.ly/3ahRWDX
            // --------------------------------------------------------------
            calculateRetainingWall: function(){
                // ----------------------------------------------------------
                var entry = this.entry;
                var values = this.values;
                var checklist = io.get( this, 'sheet.checklist' );
                var result = entry.retaining_wall_construction;
                // ----------------------------------------------------------
                if( checklist ){
                    // ------------------------------------------------------
                    var wall = checklist.retaining_wall;
                    var wallHeight = checklist.retaining_wall_height;
                    var wallLength = checklist.retaining_wall_length;
                    // ------------------------------------------------------
                    if( 1 === wall && wallLength && wallHeight <= 6 ){
                        var multiplier = null, additional = null;
                        // --------------------------------------------------
                        if( 1 === wallHeight ){ multiplier = 'G41-6'; additional = 'G41-7' }
                        else if( 2 === wallHeight ){ multiplier = 'G41-8'; additional = 'G41-9' }
                        else if( 3 === wallHeight ){ multiplier = 'G41-10'; additional = 'G41-11' }
                        else if( 4 === wallHeight ){ multiplier = 'G41-12'; additional = 'G41-13' }
                        else if( 5 === wallHeight ){ multiplier = 'G41-14'; additional = 'G41-15' }
                        else if( 6 === wallHeight ){ multiplier = 'G41-16'; additional = 'G41-17' }
                        // --------------------------------------------------
                        multiplier = io.find( values, { serial: multiplier });
                        additional = io.find( values, { serial: additional });
                        // --------------------------------------------------
                        result = wallLength * multiplier.value + additional.value;
                        // --------------------------------------------------
                    }
                    // ------------------------------------------------------
                    entry.retaining_wall_construction = Math.floor( result );
                    // ------------------------------------------------------
                }
            },
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Auto calculate road construction
            // --------------------------------------------------------------
            // [S6-3] × [S6-4] × G41-20 + G41-21
            // https://bit.ly/2xV65bK
            // --------------------------------------------------------------
            calculateRoad: function(){
                // ----------------------------------------------------------
                var entry = this.entry;
                var values = this.values;
                var checklist = io.get( this, 'sheet.checklist' );
                // ----------------------------------------------------------
                if( checklist ){
                    // ------------------------------------------------------
                    var type = checklist.new_road_type;
                    var width = checklist.new_road_width;
                    var length = checklist.new_road_length;
                    // ------------------------------------------------------
                    if(( 1 === type || 2 === type ) && width && length ){
                        // --------------------------------------------------
                        var construction = io.find( values, { serial: 'G41-20' });
                        var adjustment = io.find( values, { serial: 'G41-21' });
                        // --------------------------------------------------
                        if( construction && adjustment ){
                            var result = width * length * construction.value + adjustment.value;
                            entry.road_construction = Math.floor( result );
                        }
                        // --------------------------------------------------
                    }
                    // ------------------------------------------------------
                }
            },
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Auto calculate gutter construction
            // --------------------------------------------------------------
            // S6-5 == 1: 片側：[S6-6] × G41-22 + G41-23
            // S6-5 == 2: 両側：[S6-6] × G41-24 + G41-25
            // https://bit.ly/2x8Fc3N
            // --------------------------------------------------------------
            calculateGutter: function(){
                // ----------------------------------------------------------
                var entry = this.entry;
                var values = this.values;
                var checklist = io.get( this, 'sheet.checklist' );
                // ----------------------------------------------------------
                if( checklist ){
                    // ------------------------------------------------------
                    var gutter = checklist.side_groove;
                    var length = checklist.side_groove_length;
                    var result = entry.side_groove_construction;
                    // ------------------------------------------------------
                    var oneSide = io.find( values, { serial: 'G41-22' });
                    var oneSideAdjustment = io.find( values, { serial: 'G41-23' }); 
                    // ------------------------------------------------------
                    var bothSides = io.find( values, { serial: 'G41-24' });
                    var bothSideAdjustment = io.find( values, { serial: 'G41-25' });
                    // ------------------------------------------------------

                    // ------------------------------------------------------
                    if( length ){
                        if( 1 === gutter ) result = length * oneSide.value + oneSideAdjustment.value;
                        else if( 2 === gutter ) result = length * bothSides.value + bothSideAdjustment.value;
                    }
                    // ------------------------------------------------------
                    entry.side_groove_construction = Math.floor( result );
                    // ------------------------------------------------------
                }
            },
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Auto calculate construction set
            // --------------------------------------------------------------
            // if unchecked [S7-2],
            // [S7-1]=1: 平坦：MeterToTsubo([project.overall_area])(S1-1) × G41-26
            // [S7-1]=2: 〜１m：MeterToTsubo([project.overall_area])(S1-1) × G41-27
            // [S7-1]=3: 〜２m：MeterToTsubo([project.overall_area])(S1-1) × G41-28
            // [S7-1]=4: ２m以上：MeterToTsubo([project.overall_area])(S1-1) × G41-29
            // checked [S7-2]
            // [S7-1]=1: 平坦：MeterToTsubo([project.overall_area])(S1-1) × G41-30
            // [S7-1]=2: 〜１m：MeterToTsubo([project.overall_area])(S1-1) × G41-31
            // [S7-1]=3: 〜２m：MeterToTsubo([project.overall_area])(S1-1) × G41-32
            // [S7-1]=4: ２m以上：MeterToTsubo([project.overall_area])(S1-1) × G41-33
            // https://bit.ly/2WIdBkA
            // --------------------------------------------------------------
            calculateWorkset: function(){
                // ----------------------------------------------------------
                var entry = this.entry;
                var values = this.values;
                var project = this.project;
                var checklist = io.get( this, 'sheet.checklist' );
                // ----------------------------------------------------------
                if( project && checklist ){
                    // ------------------------------------------------------
                    var cost = checklist.development_cost;          // S7-1
                    var distant = checklist.main_pipe_is_distant;   // S7-2
                    // ------------------------------------------------------
                    var multiplier = null;
                    var tsubo = Vue.filter('tsubo')( project.overall_area );
                    // ------------------------------------------------------
                    if( !distant ){
                        if( 1 === cost ) multiplier = io.find( values, { serial: 'G41-26' });
                        else if( 2 === cost ) multiplier = io.find( values, { serial: 'G41-27' });
                        else if( 3 === cost ) multiplier = io.find( values, { serial: 'G41-28' });
                        else if( 4 === cost ) multiplier = io.find( values, { serial: 'G41-29' });
                    }
                    // ------------------------------------------------------
                    else {
                        if( 1 === cost ) multiplier = io.find( values, { serial: 'G41-30' });
                        else if( 2 === cost ) multiplier = io.find( values, { serial: 'G41-31' });
                        else if( 3 === cost ) multiplier = io.find( values, { serial: 'G41-32' });
                        else if( 4 === cost ) multiplier = io.find( values, { serial: 'G41-33' });
                    }
                    // ------------------------------------------------------

                    // ------------------------------------------------------
                    if( multiplier ){
                        var result = tsubo * multiplier.value;
                        entry.construction_work_set = Math.floor( result );
                    }
                    // ------------------------------------------------------
                }
            },
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Auto calculate construction location fee
            // --------------------------------------------------------------
            // If option 1 位置指定道路 is selected at S6-2, set G41-35
            // https://bit.ly/2WIgBgQ
            // --------------------------------------------------------------
            calculateLocationFee: function(){
                // ----------------------------------------------------------
                var entry = this.entry;
                var values = this.values;
                var checklist = io.get( this, 'sheet.checklist' );
                // ----------------------------------------------------------
                if( checklist ){
                    // ------------------------------------------------------
                    var result = entry.location_designation_application_fee;
                    var config = io.find( values, { serial: 'G41-35' });
                    // ------------------------------------------------------
                    if( 1 === checklist.new_road_type && config ) result = config.value;
                    // ------------------------------------------------------
                    entry.location_designation_application_fee = Math.floor( result );
                    // ------------------------------------------------------
                }
                // ----------------------------------------------------------
            },
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Auto calculate construction commission fee
            // --------------------------------------------------------------
            // If option 3 造成工事（開発工事）is selected at S2-4,
            // [project.overall_area](S1-1)（㎡）* G41-36
            // when "3: 造成工事（開発工事）" is not selected In S2-4, 
            // it is inactive and the background is gray.
            // https://bit.ly/33NlrL4
            // --------------------------------------------------------------
            calculateCommission: function(){
                // ----------------------------------------------------------
                var entry = this.entry;
                var values = this.values;
                var project = this.project;
                var checklist = io.get( this, 'sheet.checklist' );
                // ----------------------------------------------------------
                if( project && checklist ){
                    // ------------------------------------------------------
                    var area = project.overall_area;
                    var result = entry.development_commissions_fee;
                    var commission = io.find( values, { serial: 'G41-36' });
                    // ------------------------------------------------------
                    if( area && 3 === checklist.construction_work && commission ){
                        result = area * commission.value;
                        entry.development_commissions_fee = Math.floor( result );
                    }
                    // ------------------------------------------------------
                }
                // ----------------------------------------------------------
            },
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Auto calculate construction cultural property research fee
            // --------------------------------------------------------------
            // If option 1 入っている is selected at (S3-2)
            // Set [project.overall_area](㎡) * G41-34
            // Otherwise: set inactive (disabled)
            // https://bit.ly/3bqqF1X
            // --------------------------------------------------------------
            calculateCulturalFee: function(){
                // ----------------------------------------------------------
                var entry = this.entry; 
                var values = this.values;
                var project = this.project;
                var culturalProperty = project.question.cultural_property; // S3-2
                // ----------------------------------------------------------
                if( project ){
                    // ------------------------------------------------------
                    var result = entry.cultural_property_research_fee;
                    var config = io.find( values, { serial: 'G41-34' });
                    // ------------------------------------------------------
                    if( 1 === culturalProperty && config ){
                        var tsuboArea = Vue.filter('tsubo')( project.overall_area );
                        entry.cultural_property_research_fee = Math.floor( tsuboArea * config.value );
                    }
                    // ------------------------------------------------------
                }
                // ----------------------------------------------------------
            },
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Add new additional entry
            // --------------------------------------------------------------
            addAdditional: function(){
                // ----------------------------------------------------------
                var entry = this.entry;
                var entries = io.get( entry, 'additional.entries' );
                if( !entries ) Vue.set( entry.additional, 'entries', []);                
                // ----------------------------------------------------------
                entries = io.get( entry, 'additional.entries' );
                if( io.isArray( entries )){
                    this.additionalKey++; // Change the key to force the vue update
                    entries.push({
                        pj_stock_cost_parent_id: entry.additional.id,
                        name: null, value: null, memo: null
                    });
                }
                // ----------------------------------------------------------
            },
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Delete the additional entry from the list
            // --------------------------------------------------------------
            removeAdditional: function( index ){
                var entries = io.get( this, 'entry.additional.entries' );
                if( entries ){
                    entries.splice( index, 1 ); // This will delete item
                    this.additionalKey++; // Change the key to force the vue update
                }
            }
            // --------------------------------------------------------------
        }
        // ------------------------------------------------------------------
    });
    // ----------------------------------------------------------------------
}( jQuery, _, document, window ));
// --------------------------------------------------------------------------
@endminify </script>