<script> @minify
// --------------------------------------------------------------------------
(function( $, io, document, window, undefined ){
    // ----------------------------------------------------------------------

    // ----------------------------------------------------------------------
    // Contract real estate
    // ----------------------------------------------------------------------
    Vue.component( 'important-note-building-standard-restriction', {
        // ------------------------------------------------------------------
        props: [ 'value', 'master_value', 'residentials', 'roads', 'disabled' ],
        template: '#important-note-building-standard-restriction',
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
                return entry.restricted_law_status == true || false;
            },
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Get residentials and roads use districts
            // --------------------------------------------------------------
            parcel_use_districts: function(){
                let parcel_use_districts = [];

                // get residentials and roads use districts (residential 1:n use district)
                parcel_use_districts.push(io.map(this.residentials, 'use_districts' ));
                parcel_use_districts.push(io.map(this.roads, 'use_districts' ));

                // recursively flattens array and get the value
                let value = io.map(io.flattenDeep(parcel_use_districts), 'value');

                // sort and get unique value, maximum 2
                let unique = io.take(io.sortedUniq(value), 2);

                // get data from master value
                parcel_use_districts = [];
                parcel_use_districts[0] = this.master_value[unique[0]];
                parcel_use_districts[1] = unique[1] ? this.master_value[unique[1]] : null;

                return parcel_use_districts
            },
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Get residentials and roads use districts
            // --------------------------------------------------------------
            parcel_use_districts_key: function(){
                let parcel_use_districts = this.parcel_use_districts;

                // get data from master value
                let key = [];
                key[0] = parcel_use_districts[0] ? parcel_use_districts[0].key : null;
                key[1] = parcel_use_districts[1] ? parcel_use_districts[1].key : null;

                return key
            },
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Get multiplication rate
            // --------------------------------------------------------------
            multiplication_rate: function(){
                // get multiplication rate
                let rate = 6;
                this.parcel_use_districts.forEach((use_district, i) => {
                    if (rate == 6 && use_district)
                        rate = use_district.value.includes("住居") ? 4 : 6
                });

                return rate
            },
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Road width calculation
            // --------------------------------------------------------------
            road_width_calculation: function(){
                // get multiplication rate
                let rate = this.multiplication_rate;

                // calculation
                let result = this.entry.road_width * rate / 10 * 100;

                return parseFloat(result)
            },
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Get residentials and roads build ratio
            // --------------------------------------------------------------
            parcel_build_ratios: function(){
                let parcel_build_ratios = [];
                let value = 0;

                // get residentials and roads build ratio (residential 1:n build ratio)
                parcel_build_ratios.push(io.map(this.residentials, 'build_ratios' ));
                parcel_build_ratios.push(io.map(this.roads, 'build_ratios' ));

                parcel_build_ratios = io.uniqBy(io.flattenDeep(parcel_build_ratios), 'value'); // recursively flattens array and get unique value.
                value = parcel_build_ratios.length == 1 ?
                                parcel_build_ratios[0].value : null; // assign value
                value = value % 1 == 0 ? value : io.floor(value, 2); // set 2 decimal place and round down value

                this.entry.building_coverage_ratio = this.entry.building_coverage_ratio ?? parseFloat(value);

                return parcel_build_ratios
            },
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Get residentials and roads floor ratio
            // --------------------------------------------------------------
            parcel_floor_ratios: function(){
                let parcel_floor_ratios = [];
                let value = 0;

                // get residentials and roads floor ratio (residential 1:n floor ratio)
                parcel_floor_ratios.push(io.map(this.residentials, 'floor_ratios' ));
                parcel_floor_ratios.push(io.map(this.roads, 'floor_ratios' ));

                parcel_floor_ratios = io.uniqBy(io.flattenDeep(parcel_floor_ratios), 'value'); // recursively flattens array and get unique value.
                value = parcel_floor_ratios.length == 1 ?
                                parcel_floor_ratios[0].value : null; // assign value
                value = value % 1 == 0 ? value : io.floor(value, 2); // set 2 decimal place and round down value

                this.entry.floor_area_ratio_text = this.entry.floor_area_ratio_text ?? parseFloat(value);

                return parcel_floor_ratios
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
