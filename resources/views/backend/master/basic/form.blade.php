@extends('backend._base.content_master')

@section('preloader')
    <transition name="preloader">
        <div class="preloader preloader-fullscreen d-flex justify-content-center align-items-center" v-if="initial.loading">
            <div class="sk-folding-cube">
                <div class="sk-cube1 sk-cube"></div>
                <div class="sk-cube2 sk-cube"></div>
                <div class="sk-cube4 sk-cube"></div>
                <div class="sk-cube3 sk-cube"></div>
            </div>
        </div>
    </transition>
@endsection


@section('breadcrumbs')
<ol class="breadcrumb float-sm-right">
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="fas fa-tachometer-alt mr-1"></i> @lang('label.dashboard')</a></li>
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">仕入</a></li>
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">基本データ</a></li>
    <li class="breadcrumb-item active">{{ $page_title }}</li>
</ol>
@endsection

@section('content')
<form method="POST" data-parsley class="parsley-minimal" v-cloak>
    @csrf
    <!-- Start - Land Information Card -->
    <div class="card card-project">
        <div class="card-header">
            物件情報
        </div>
        <div class="card-body">
                @include('backend.master.basic.form.basic-information')
        </div>
    </div>
    <!-- End - Land Information Card -->

    <div class="card card-project">
        <div class="card-header">
            仕入地番ごと情報
        </div>
        <div class="card-body">
            <!-- Start - Residential Table -->
            <div id="form-residential">
                @include('backend.master.basic.form.residential')
            </div>
            <!-- End - Residential Table -->

            <!-- Start - Road Table -->
            <div id="form-residential">
                @include('backend.master.basic.form.road')
            </div>
            <!-- End - Road Table -->

            <!-- Start - Building Table -->
            <div id="form-residential">
                @include('backend.master.basic.form.building')
            </div>
            <!-- End - Building Table -->
        </div>
    </div>

    <!-- Start - Status Check Form -->
        @include('backend.master.basic.form.status')
    <!-- End - Status Check Form -->

    @if ($editable)
        <div class="bottom mt-2 mb-5 text-center">
            <button type="submit" class="btn btn-wide btn-info px-4">
                <i v-if="!initial.submited" class="fas fa-save"></i>
                <i v-else class="fas fa-spinner fa-spin"></i>
                保存
            </button>
        </div>
    @endif

</form>
@endsection

@push('vue-scripts')
<script>
    mixin = {
        /*
        ## ------------------------------------------------------------------
        ## VUE DATA
        ## vue data binding, difine a properties
        ## ------------------------------------------------------------------
        */
        data: function(){
            // --------------------------------------------------------------
            // Basic Data Assist B Init
            // --------------------------------------------------------------
            let initial = {
                submited: false,
                loading: true,
                is_modified: false,
                editable: @json( $editable ),
                update_url: '{{ $update_url }}',
                delete_section: '{{ $delete_section }}'
            };
            // --------------------------------------------------------------
            let master = {
                values: @json( $master_values ),
                regions: @json( $master_regions ),
            }
            // --------------------------------------------------------------
            let project = @json( $project );
            // --------------------------------------------------------------
            let mas_basic = @json( $mas_basic )
            // --------------------------------------------------------------
            let residentials = {
                active: false,
                data: []
            }
            // --------------------------------------------------------------
            let roads = {
                active: false,
                data: []
            }
            // --------------------------------------------------------------
            let buildings = {
                active: false,
                data: []
            }
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Assign data from database
            // --------------------------------------------------------------
            let db_residentials = @json( $residentials );
            let db_roads = @json( $roads );
            let db_buildings = @json( $buildings );
            // --------------------------------------------------------------
            if(db_residentials.length != 0){
                Object.assign(residentials.data, db_residentials);
                // assign mas residential relation with mas lot residential property
                residentials.data.forEach((residential, i) => {
                    if (!residential.mas_residential) {
                        residential.mas_residential = residential.mas_lot_residential
                        delete residential.mas_lot_residential
                        residentials.active = true;
                    }else {
                        residentials.active = true;
                    }
                });
            }
            if(db_roads.length != 0){
                Object.assign(roads.data, db_roads);
                // assign mas road relation with mas lot road property
                roads.data.forEach((road, i) => {
                    if (!road.mas_road) {
                        road.mas_road = road.mas_lot_road
                        delete road.mas_lot_road
                        roads.active = true;
                    }else {
                        roads.active = true;
                    }
                });
            }
            if(db_buildings.length != 0){
                Object.assign(buildings.data, db_buildings);
                // assign mas building with mas lot building property
                buildings.data.forEach((building, i) => {
                    if (!building.mas_building) {
                        building.mas_building = building.mas_lot_building
                        delete building.mas_lot_building
                        buildings.active = true;
                    }else {
                        buildings.active = true;
                    }
                });
            }
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Return compiled data
            // --------------------------------------------------------------
            return {
                initial: initial,
                master: master,
                project: project,
                mas_basic: mas_basic,
                residentials: residentials,
                roads: roads,
                buildings: buildings,
            };
            // --------------------------------------------------------------
        },

        /*
        ## ------------------------------------------------------------------
        ## VUE MOUNTED
        ## vue on mounted state
        ## ------------------------------------------------------------------
        */
        mounted: function(){
            // refresh parsley form validation
            refreshParsley();

            // switch loading state
            this.initial.loading = false;

            // triger event on loaded
            $(document).trigger( 'vue-loaded', this );
        },

        /*
        ## ------------------------------------------------------------------
        ## VUE COMPUTED
        ## define a property with custom logic
        ## ------------------------------------------------------------------
        */
        computed: {
            // --------------------------------------------------------------
            // Master Data
            // --------------------------------------------------------------
            // define parcel_cities from master data
            master_parcel_cities: function () {
                return this.master.regions;
            },
            // define land_categories from master data
            master_land_categories: function () {
                let master_values = this.master.values;
                let data_lands = master_values.filter(function (data) {
                    return data.type == 'land_category';
                });
                return data_lands;
            },
            // define use_districts from master data
            master_use_districts: function () {
                let master_values = this.master.values;
                let data_district = master_values.filter(function (data) {
                    return data.type == 'usedistrict';
                });
                return data_district;
            },
            // define use_types from master data
            master_use_types: function () {
                let master_values = this.master.values;
                let data_types = master_values.filter(function (data) {
                    return data.type == 'building_usetype';
                });
                return data_types;
            },
            // define building_structures from master data
            master_building_structures: function () {
                let master_values = this.master.values;
                let building_structures = master_values.filter(function (data) {
                    return data.type == 'building_structure';
                });
                return building_structures;
            },
            // define building_roofs from master data
            master_building_roofs: function () {
                let master_values = this.master.values;
                let building_roofs = master_values.filter(function (data) {
                    return data.type == 'building_roof';
                });
                return building_roofs;
            },
            // property onwers selected on assist a
            // master_property_owners: function () {
            //     return this.property.owners;
            // },
            // --------------------------------------------------------------
            // Calaculated Value
            // --------------------------------------------------------------
            transportation_time_calc: function () {
                let total = 0;
                let const_time = 80;
                let distance = this.mas_basic.transportation_time;
                total = Math.floor(distance/const_time);
                return total;
            },

            // if modified button clicked
            // -----------------------------------------------------------------
            is_modified: function(){
                return this.initial.is_modified
            },
            // -----------------------------------------------------------------
        },

        /*
        ## ------------------------------------------------------------------
        ## VUE WATCH
        ## vue reactive data watch
        ## ------------------------------------------------------------------
        */
        watch: {
            mas_basic: {
                deep: true,
                handler: function( mas_basic ){
                    this.sameAsBasicResidential();
                    this.sameAsBasicRoad();

                    if(mas_basic.restrictions.length == 0){
                        this.mas_basic.restrictions.push({ id: null, restriction_id: null, restriction_note: null });
                    }
                }
            },
            // --------------------------------------------------------------
            buildings: {
                deep: true,
                handler: function( buildings ){
                    // ------------------------------------------------------
                    this.convertNengouToWestern(buildings, true);
                    // ------------------------------------------------------
                }
            },
            // --------------------------------------------------------------
        },

        /*
        ## ------------------------------------------------------------------
        ## VUE METHOD
        ## function associated with the vue instance
        ## ------------------------------------------------------------------
        */
        methods: {
            // --------------------------------------------------------------
            // Add and Remove Property Owners
            // --------------------------------------------------------------
            addPropertyRestrictions: function(){
                this.mas_basic.restrictions.push({id: null, restriction_id: null, restriction_note: null});
                // ----------------------------------------------------------
                refreshParsley();
                refreshTooltip();
            },
            removePropertyRestrictions: function(index){
                let restriction = this.mas_basic.restrictions[index];
                let confirmed = false;

                refreshTooltip();

                if (restriction.restriction_id) confirmed = confirm('@lang('label.confirm_delete')');
                else this.mas_basic.restrictions.splice(index, 1);

                if (confirmed) {
                    if(restriction.id) Vue.set( restriction, 'deleted', true );
                    else this.mas_basic.restrictions.splice(index, 1);
                }
            },

            // --------------------------------------------------------------
            // Switch Active or Inactive Section
            // --------------------------------------------------------------
            switchSection: function(section, field, event){
                let checked = event.target.checked;

                if (!checked) {
                    let confirmed = confirm('チェックを外すと入力済みの情報が消去されます。よろしいですか？');
                    if (confirmed) {
                        let mas_lot_relation = null;
                        if (field == 'exists_land_residential') mas_lot_relation = 'mas_residential'
                        if (field == 'exists_road_residential') mas_lot_relation = 'mas_road'
                        if (field == 'exists_building_residential') mas_lot_relation = 'mas_building'

                        let mas_lot = _.map(section.data, mas_lot_relation)
                        if (mas_lot[0].id) {
                            // ---------------------------------------------
                            // handle delete request
                            // ---------------------------------------------
                            let delete_section = axios.delete(this.initial.delete_section, {
                                data: {
                                    field: field,
                                    mas_lot: mas_lot,
                                }
                            });
                            let vm = this
                            // ---------------------------------------------
                            // handle success response
                            // ---------------------------------------------
                            delete_section.then(function (response) {
                                if (response.data.status == 'success') {
                                    section.active = false;

                                    let data = response.data.data;

                                    // update data
                                    if (data.section_type == "residential") vm.residentials.data = data.residentials;
                                    else if (data.section_type == "road") vm.roads.data = data.roads;
                                    else if (data.section_type == "building") vm.buildings.data = data.buildings;

                                    Vue.nextTick(function () {
                                        $.toast({
                                            heading: '成功', icon: 'success',
                                            position: 'top-right', stack: false, hideAfter: 3000,
                                            text: response.data.message,
                                            position: { right: 18, top: 68 }
                                        });
                                    });
                                }
                            })
                            // ---------------------------------------------
                            // handle error response
                            // ---------------------------------------------
                            delete_section.catch(function (error) {
                                $.toast({
                                    heading: '失敗', icon: 'error',
                                    position: 'top-right', stack: false, hideAfter: 3000,
                                    text: [error.response.data.message, error.response.data.error],
                                    position: { right: 18, top: 68 }
                                });
                            });
                            // ---------------------------------------------
                        }else {
                            section.data = []
                        }
                    }else {
                        event.preventDefault()
                    }
                }
            },

            // --------------------------------------------------------------
            // Set residential value same as master basic
            // --------------------------------------------------------------
            sameAsBasicResidential: function(checked = 1){
                if(checked == 0) return;
                // ----------------------------------------------------------
                let data_residentials = this.residentials.data;
                let data_property = this.mas_basic;
                // ----------------------------------------------------------
                data_residentials.forEach(function(item){
                    let residential = item.mas_residential;

                    // assist b
                    // ------------------------------------------------------
                    if(residential.fire_protection_same == 1){
                        residential.fire_protection = data_property.basic_fire_protection;
                    }
                    // ------------------------------------------------------
                    if(residential.cultural_property_reserves_same == 1){
                        residential.cultural_property_reserves = data_property.basic_cultural_property_reserves;
                        residential.cultural_property_reserves_name = data_property.basic_cultural_property_reserves_name;
                    }
                    // ------------------------------------------------------
                    if(residential.district_planning_same == 1){
                        residential.district_planning = data_property.basic_district_planning;
                        residential.district_planning_name = data_property.basic_district_planning_name;
                    }
                    // ------------------------------------------------------
                    if(residential.scenic_area_same == 1){
                        residential.scenic_area = data_property.basic_scenic_area;
                    }
                    // ------------------------------------------------------
                    if(residential.landslide_same == 1){
                        residential.landslide = data_property.basic_landslide;
                    }
                    // ------------------------------------------------------
                    if(residential.residential_land_development_same == 1){
                        residential.residential_land_development = data_property.basic_residential_land_development;
                    }
                    // ---------------------------------------------------------

                    // purchase sale
                    // ---------------------------------------------------------
                    if (residential.urbanization_area_same == true) {
                        residential.urbanization_area = data_property.project_urbanization_area
                        if (residential.urbanization_area == 3) {
                            residential.urbanization_area_sub = data_property.project_urbanization_area_sub

                            // if (data_property.project_urbanization_area_status == 1)
                            //     residential.urbanization_area_number = '計画有'
                            // else if (data_property.project_urbanization_area_status == 2)
                            //     residential.urbanization_area_number = '施行中'

                            residential.urbanization_area_date = data_property.project_urbanization_area_date
                        }
                    }
                    // ---------------------------------------------------------

                    // project
                    // ---------------------------------------------------------
                    if(residential.project_current_situation_same_to_basic == 1){
                        residential.project_current_situation = data_property.project_current_situation;
                        residential.project_current_situation_other = data_property.project_current_situation_other;
                    }
                    // ------------------------------------------------------
                    if(residential.project_set_back_same_to_basic == 1){
                        residential.project_set_back = data_property.project_set_back;
                    }
                    // ------------------------------------------------------
                    if(residential.project_private_road_same_to_basic == 1){
                        residential.project_private_road = data_property.project_private_road;
                    }
                    // ---------------------------------------------------------

                });
            },

            // --------------------------------------------------------------
            // Set road value same as property basic
            // --------------------------------------------------------------
            sameAsBasicRoad: function(checked = 1){
                if(checked == 0) return;
                // ----------------------------------------------------------
                let data_roads = this.roads.data;
                let data_property = this.mas_basic;
                // ----------------------------------------------------------
                data_roads.forEach(function(item){
                    let road = item.mas_road;

                    //  assist b
                    // ------------------------------------------------------
                    if(road.fire_protection_same == 1){
                        road.fire_protection = data_property.basic_fire_protection;
                    }
                    // ------------------------------------------------------
                    if(road.cultural_property_reserves_same == 1){
                        road.cultural_property_reserves = data_property.basic_cultural_property_reserves;
                        road.cultural_property_reserves_name = data_property.basic_cultural_property_reserves_name;
                    }
                    // ------------------------------------------------------
                    if(road.district_planning_same == 1){
                        road.district_planning = data_property.basic_district_planning;
                        road.district_planning_name = data_property.basic_district_planning_name;
                    }
                    // ------------------------------------------------------
                    if(road.scenic_area_same == 1){
                        road.scenic_area = data_property.basic_scenic_area;
                    }
                    // ------------------------------------------------------
                    if(road.landslide_same == 1){
                        road.landslide = data_property.basic_landslide;
                    }
                    // ------------------------------------------------------
                    if(road.residential_land_development_same == 1){
                        road.residential_land_development = data_property.basic_residential_land_development;
                    }

                    // purchase sale
                    // ---------------------------------------------------------
                    if (road.urbanization_area_same == true) {
                        road.urbanization_area = data_property.project_urbanization_area
                        if (road.urbanization_area == 3) {
                            road.urbanization_area_sub = data_property.project_urbanization_area_sub

                            // if (data_property.project_urbanization_area_status == 1)
                            //     road.urbanization_area_number = '計画有'
                            // else if (data_property.project_urbanization_area_status == 2)
                            //     road.urbanization_area_number = '施行中'

                            road.urbanization_area_date = data_property.project_urbanization_area_date
                        }
                    }
                    // ---------------------------------------------------------

                    // project
                    // ---------------------------------------------------------
                    if(road.project_current_situation_same_to_basic == 1){
                        road.project_current_situation = data_property.project_current_situation;
                        road.project_current_situation_other = data_property.project_current_situation_other;
                    }
                    // ------------------------------------------------------
                    if(road.project_set_back_same_to_basic == 1){
                        road.project_set_back = data_property.project_set_back;
                    }
                    // ------------------------------------------------------
                    if(road.project_private_road_same_to_basic == 1){
                        road.project_private_road = data_property.project_private_road;
                    }
                    // ---------------------------------------------------------

                });
            },

            // --------------------------------------------------------------
            // Add and Remove Addable Input on Table
            // --------------------------------------------------------------
            addTableInput: function(field){
                let data = field.filter( function( value ) {
                    return !value.deleted;
                });
                let number = data.length;
                if( number < 2) field.push({ id: '', value: '' });
                refreshTooltip();
            },
            // --------------------------------------------------------------
            removeTableInput: function(field, index){
                data = field[index];
                if (data.id === null || data.id === '') {
                    field.splice(index, 1);
                }else{
                    Vue.set( data, 'deleted', true );
                }
                refreshTooltip();
            },

            // --------------------------------------------------------------
            // Copy From Previous Inputed field
            // --------------------------------------------------------------
            copyText: function(field, index, row, section){
                let section_data, last_value, mas_lot;
                if (section == 'residential') {
                    section_data = this.residentials.data;
                    mas_lot = 'mas_residential';
                }
                else {
                    section_data = this.roads.data;
                    mas_lot = 'mas_road';
                }
                // ---------------------------------------------------------
                if(index == null || index == 0){
                    let prev_data;
                    if(row != 0){
                        let prev_row = section_data[row-1];
                        prev_data = prev_row[mas_lot][field];
                    }else{
                        let last_row = this.residentials.data.length-1;
                        prev_data = this.residentials.data[last_row].mas_residential[field];
                    }
                    // -----------------------------------------------------
                    if(index == null){
                        last_value = prev_data;
                        if (last_value) section_data[row][mas_lot][field] = last_value;
                    }else{
                        last_value = prev_data[prev_data.length-1].value;
                        if (last_value) section_data[row][mas_lot][field][index].value = last_value
                    }
                }
                // ---------------------------------------------------------
                else{
                    let field_data = section_data[row][mas_lot][field];
                    last_value = field_data[index-1].value;
                    if (last_value) section_data[row][mas_lot][field][index].value = last_value;
                }
            },

            // --------------------------------------------------------------
            // BUILDING - Count floor and add floor size input
            // --------------------------------------------------------------
            calculateFloorCount: _.debounce(function(field){
                let floor_count, displayed_floor = 0
                let building_floors = [];

                // existed data
                let existed_data = _.filter(field.building_floors, function(floor){
                    return !floor.deleted;
                });

                // calculate floor count based reduce displayed input
                floor_count = field.building_floor_count;
                displayed_floor = existed_data.length;
                floor_count = floor_count-displayed_floor;

                // on added floor count
                if (floor_count > 0) {
                    for (let i = 0; i < floor_count; i++) {
                        // restore deleted flag data if any
                        let deleted_data = _.filter(field.building_floors, function(floor){
                            return floor.deleted;
                        });
                        if (deleted_data.length != 0) {
                            let data = deleted_data[0];
                            Vue.delete(data, 'deleted');
                        }else{
                            field.building_floors.push({id: null, floor_size: null});
                        }
                    }
                }
                //  on reduce floor count
                else{
                    // if data is already saved on db, set flag deleted
                    let last_index = field.building_floors.length;
                    for (let i = 0; i > floor_count; i--) {
                        last_index--;
                        let data = field.building_floors[last_index];
                        if (data.id != null) {
                            Vue.set( data, 'deleted', true );
                        }else {
                            field.building_floors.splice(last_index, 1);
                        }
                    }
                }
            }, 500 ),
            // --------------------------------------------------------------
            // --------------------------------------------------------------
            //  Convert nengou year to western year or reverse
            // --------------------------------------------------------------
            convertNengouToWestern: function(buildings, reverse = false){
                buildings.data.forEach( function(building) {
                    if (building.mas_building.building_date_nengou && building.mas_building.building_date_year) {
                        // create array from range
                        let showa = _.range(1926, 1990);
                        let heisei = _.range(1989, 2020);
                        let reiwa = _.range(2019, 2050);
                        let nengou = {
                            1: { range: showa },
                            2: { range: heisei },
                            3: { range: reiwa }
                        };

                        // get selected date nengou
                        let index = building.mas_building.building_date_year;
                        let selected = nengou[building.mas_building.building_date_nengou];

                        // get western year from nengou
                        let western_year = selected.range[index-1];
                        if(typeof western_year === 'undefined'){
                            western_year = building.mas_building.building_date_year;
                        }
                        building.mas_building.building_date_western = western_year;

                        // get nengou from year
                        if (reverse) {
                            let nengou_year = selected.range.indexOf(western_year);
                            building.mas_building.building_date_year = nengou_year+1;
                        }
                    }
                    else{
                        building.mas_building.building_date_western = null;
                    }
                });
            },
            // --------------------------------------------------------------
            // --------------------------------------------------------------
            // BUILDING - Validate to required input date
            // --------------------------------------------------------------
            dateCheck: function(field){
                let validate = false;
                if (field.building_date_nengou != 0 || field.building_date_year || field.building_date_month || field.building_date_day) {
                    validate = true;
                }
                return validate;
            },
            // --------------------------------------------------------------

            //
            is_modified: function(){
                this.initial.is_modified = true
            },
        }
    }


    /*
    ## ----------------------------------------------------------------------
    ## VUE LOADED EVENT
    ## Handle submit data and form validation
    ## ----------------------------------------------------------------------
    */
    $(document).on('vue-loaded', function( event, vm ){
        // init parsley form validation
        // ------------------------------------------------------------------
        let $form = $('form[data-parsley]');
        let form = $form.parsley();
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // On form submit
        // ------------------------------------------------------------------
        form.on( 'form:validated', function(){

            // on form not valid
            // --------------------------------------------------------------
            let valid = form.isValid();
            if( !valid ) setTimeout( function(){
                var $errors = $('.parsley-errors-list.filled').first();
                animateScroll( $errors );
            });
            // --------------------------------------------------------------

            // on valid form
            // --------------------------------------------------------------
            else {
                // map data before send to controller
                // ----------------------------------------------------------
                let basic_restrictions = vm.mas_basic.restrictions
                delete vm.mas_basic.restrictions;
                // ----------------------------------------------------------
                let data_residential = vm.residentials.data.map(function(residential){
                    return residential.mas_residential;
                });
                let data_road = vm.roads.data.map(function(road){
                    return road.mas_road;
                });
                let data_buildings = vm.buildings.data.map(function(building){
                    return building.mas_building;
                });

                // compile post data
                // ----------------------------------------------------------
                let data = {
                    project: vm.project,
                    mas_basic: vm.mas_basic,
                    basic_restrictions: basic_restrictions,
                    residentials: data_residential,
                    roads: data_road,
                    buildings: data_buildings,
                };
                // ----------------------------------------------------------

                // set loading state
                // ----------------------------------------------------------
                vm.initial.submited = true;

                // handle update request
                // ----------------------------------------------------------
                let url = vm.initial.update_url;
                let req_update = axios.post(url, data);
                // ----------------------------------------------------------

                // handle success response
                // ----------------------------------------------------------
                req_update.then(function (response) {
                    if (response.data.status == 'success') {
                        let data = response.data.data;
                        console.log(data, 'response');

                        // update data to response data
                        vm.mas_basic = data.mas_basic
                        vm.residentials.data = data.residentials;
                        vm.roads.data = data.roads;
                        vm.buildings.data = data.buildings;
                        vm.initial.is_modified = false;

                        Vue.nextTick(function () {
                            // show toast success message
                            $.toast({
                                heading: '成功', icon: 'success',
                                position: 'top-right', stack: false, hideAfter: 4000,
                                text: response.data.message,
                                position: { right: 18, top: 68 }
                            });
                        });
                    }else if(response.data.status == 'warning'){
                        // show toast warning message
                        $.toast({
                            heading: false, icon: 'warning',
                            position: 'top-right', stack: false, hideAfter: 4000,
                            text: response.data.message,
                            position: { right: 18, top: 68 }
                        });
                    }
                })
                // ----------------------------------------------------------

                // handle error response
                // ----------------------------------------------------------
                req_update.catch(function (error) {
                    console.log(error.response)
                    $.toast({
                        heading: '失敗', icon: 'error',
                        position: 'top-right', stack: false, hideAfter: 4000,
                        text: [error.response.data.message, error.response.data.error],
                        position: { right: 18, top: 68 }
                    });
                });
                // ----------------------------------------------------------

                // always execute function
                // ----------------------------------------------------------
                req_update.finally(function () {
                    vm.initial.submited = false;
                });
                // ----------------------------------------------------------
            }
            // --------------------------------------------------------------
        }).on('form:submit', function(){ return false });
    });

    // ----------------------------------------------------------------------
    // Custom function refresh validator
    // ----------------------------------------------------------------------
    var refreshParsley = function(){
        Vue.nextTick(function () {
            var $form = $('form[data-parsley]');
            $form.parsley().refresh();
        });
    }
    var resetParsley = function(){
        Vue.nextTick(function () {
            var $form = $('form[data-parsley]');
            $form.parsley().reset();
        });
    }
    var refreshTooltip = function(){
        $('[data-toggle="tooltip"]').tooltip('hide');
        Vue.nextTick(function () {
            $('[data-toggle="tooltip"]').tooltip()
        });
    }
    // ----------------------------------------------------------------------
    var animateScroll = function( scroll, duration ){
        duration = duration || 800;
        var offset = 160;
        // ------------------------------------------------------------------
        if( !_.isInteger( scroll )){
            var $target = $( scroll );
            if( $target.length ) scroll = $target.offset().top;
        }
        // ------------------------------------------------------------------
        var $html = $('html');
        var scrolltop = scroll - offset;
        if( scrolltop <= 0 ) scrolltop = 0;
        // ------------------------------------------------------------------
        anime({
            targets: $html.get()[0], scrollTop: scrolltop,
            duration: duration, easing: 'linear'
        });
        // ------------------------------------------------------------------
    }
    // ----------------------------------------------------------------------

    @push('extend-parsley')
        // ------------------------------------------------------------------
        options.successClass = false;
        // ------------------------------------------------------------------
        // Exluded elements
        // ------------------------------------------------------------------
        options.excluded = 'input[type=button], input[type=submit], input[type=reset], '+
            'input[type=hidden], input.parsley-excluded, [data-parsley-excluded]';
        // ------------------------------------------------------------------
        // Finding error container
        // ------------------------------------------------------------------
        options.errorsContainer = function( field ){
            // --------------------------------------------------------------
            var formResult = '.form-result';
            var $element = $( field.$element );
            var $result = $element.siblings( formResult );
            // --------------------------------------------------------------
            if( $result.length ) return $result;
            else {
                // ----------------------------------------------------------
                var $parent = $element.parent();
                if( $parent.is('.input-group')){
                    $result = $parent.siblings( formResult );
                    if( $result.length ) return $result;
                }
                // ----------------------------------------------------------
                var $row = $element.closest('.row');
                $result = $row.siblings('.form-result');
                // ----------------------------------------------------------
                if( $result.length ) return $result;
                else {
                    // ------------------------------------------------------
                    var $group = $element.closest('.form-group');
                    $result = $group.next( formResult );
                    // ------------------------------------------------------
                    if( $result.length ) return $result;
                    // ------------------------------------------------------
                }
                // ----------------------------------------------------------
            }
            // --------------------------------------------------------------
        }
        // ------------------------------------------------------------------
    @endpush
</script>
@endpush
