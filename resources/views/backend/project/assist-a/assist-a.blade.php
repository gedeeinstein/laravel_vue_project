@extends('backend._base.content_project')

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
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">@lang('project.base.tabs.assist.a')</a></li>
    <li class="breadcrumb-item active">{{ $page_title }}</li>
</ol>
@endsection

@section('content')
<form id="assist_a_form" method="POST" data-parsley class="parsley-minimal" v-cloak>
    @csrf
    <!-- Start - Land Information Card -->
    <div class="card card-project">
        <div class="card-header">
            物件情報
        </div>
        <div class="card-body">
                @include('backend.project.assist-a.form.land')
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
                @include('backend.project.assist-a.form.residential')
            </div>
            <!-- End - Residential Table -->

            <!-- Start - Road Table -->
            <div id="form-residential">
                @include('backend.project.assist-a.form.road')
            </div>
            <!-- End - Road Table -->

            <!-- Start - Building Table -->
            <div id="form-residential">
                @include('backend.project.assist-a.form.building')
            </div>
            <!-- End - Building Table -->
        </div>
    </div>

    <!-- Start - Status Check Form -->
    <div class="incomplete_memo form-row p-2 bg-light">
        @include('backend.project.assist-a.form.status')
    </div>
    <!-- End - Status Check Form -->

    <div class="bottom mt-2 mb-5 text-center">
        @if ($editable)
            <button type="submit" class="btn btn-wide btn-info px-4">
                <i v-if="!initial.submited" class="fas fa-save"></i>
                <i v-else class="fas fa-spinner fa-spin"></i>
                保存
            </button>
        @endif
    </div>
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
            // Basic Data Assist A Init
            // --------------------------------------------------------------
            let initial = {
                submited: false,
                loading: true,
                editable: @json( $editable ),
                update_url: '{{ $update_url }}',
                delete_url: {
                    property_owners: '{{ $delete_url->property_owners }}',
                    residential: '{{ $delete_url->residential }}',
                    road: '{{ $delete_url->road }}',
                    building: '{{ $delete_url->building }}',
                    section: '{{ $delete_url->section }}'
                }
            };
            // --------------------------------------------------------------
            let master = {
                values: @json( $master_values ),
                regions: @json( $master_regions ),
            }
            // --------------------------------------------------------------
            let project = {
                id: null, title: null
            };
            // --------------------------------------------------------------
            let property = {
                id: null, school_primary: null,
                school_primary_distance: null, school_juniorhigh: null,
                school_juniorhigh_distance: null, registry_size: 0,
                registry_size_status: null, survey_size: 0,
                survey_size_status: null, fixed_asset_tax_route_value: null
            };
            // --------------------------------------------------------------
            let property_owners = [
                { id: 1, name: null }
            ];
            // --------------------------------------------------------------
            let residentials = {
                active: false,
                default: {
                    id: null, exists_land_residential: 1, parcel_city: null, parcel_city_extra: null,
                    parcel_town: null, parcel_number_first: null, parcel_number_second: null,
                    parcel_land_category: null, parcel_size: null, parcel_size_survey: null,
                    pj_lot_residential_b_id: null,
                    use_districts: [{
                        id: null, value: ''
                    }],
                    build_ratios: [{
                        id: null, value: ''
                    }],
                    floor_ratios: [{
                        id: null, value: ''
                    }],
                    residential_owners: [{
                        id: null, share_denom: '', share_number: '',
                        other: 'その他', other_denom: 0, other_number: 0,
                        pj_property_owners_id: '', pj_lot_residential_a_id: '',
                    }],
                },
                data: []
            };
            // --------------------------------------------------------------
            let roads = {
                active: false,
                default: {
                    id: null, exists_road_residential: 1, parcel_city: null, parcel_city_extra: null,
                    parcel_town: null, parcel_number_first: null, parcel_number_second: null,
                    parcel_land_category: null, parcel_size: null, parcel_size_survey: null,
                    pj_lot_road_b_id: null,
                    use_districts: [{
                        id: null, value: ''
                    }],
                    build_ratios: [{
                        id: null, value: ''
                    }],
                    floor_ratios: [{
                        id: null, value: ''
                    }],
                    road_owners: [{
                        id: null, share_denom: '', share_number: '',
                        other: 'その他', other_denom: 0, other_number: 0,
                        pj_property_owners_id: '', pj_lot_road_a_id: '',
                    }],
                },
                data: []
            };
            // --------------------------------------------------------------
            let buildings = {
                active: false,
                default: {
                    id: null, exists_building_residential: 1,
                    parcel_city : null, parcel_city_extra : null, parcel_town : null,
                    parcel_number_first : null, parcel_number_second : null,
                    building_number_first : null, building_number_second : null, building_number_third : null,
                    building_usetype : null, building_attached : 0, building_attached_select : 0,
                    building_date_nengou : null, building_date_western : null, building_date_year : null,
                    building_date_month : null, building_date_day : null, building_structure : null,
                    building_floor_count : 1, building_roof : null,
                    building_owners: [{
                        id: null, share_denom: '', share_number: '',
                        other: 'その他', other_denom: 0, other_number: 0,
                        pj_property_owners_id: '', pj_lot_road_a_id: '',
                    }],
                    building_floors: [{
                        id: null, floor_size: null
                    }]
                },
                data: []
            };
            // --------------------------------------------------------------
            let stat_check = {
                project_id: null, screen: 'pj_assist_a',
                status: 2, memo: '',
            };
            // --------------------------------------------------------------

            // Data from db to json
            // --------------------------------------------------------------
            let db_project = @json( $project );
            let db_property = @json( $property );
            let db_residential = @json( $residentials );
            let db_road = @json( $roads );
            let db_building = @json( $buildings );
            let db_stat_check = @json( $stat_check );
            // --------------------------------------------------------------

            // Assign value from db
            // --------------------------------------------------------------
            if (db_project != null){
                Object.assign(project, db_project);
                property.fixed_asset_tax_route_value = project.fixed_asset_tax_route_value;
            }
            // --------------------------------------------------------------
            if (db_property != null) {
                // assign property value
                Object.assign(property, db_property);

                // assign owner value
                if (db_property.owners.length != 0) {
                    Object.assign(property_owners, db_property.owners);
                    delete property.owners;
                }
            }
            // --------------------------------------------------------------
            if (db_residential.length != 0) {
                Object.assign(residentials.data, db_residential);
                residentials.active = initial.editable ? true : false;
            }else{
                residentials.data.push(_.cloneDeep(residentials.default));
            }
            // --------------------------------------------------------------
            if (db_road.length != 0) {
                Object.assign(roads.data, db_road);
                roads.active = initial.editable ? true : false;
            }else{
                roads.data.push(_.cloneDeep(roads.default));
            }
            // --------------------------------------------------------------
            if (db_building.length != 0) {
                Object.assign(buildings.data, db_building);
                buildings.active = initial.editable ? true : false;
            }else{
                buildings.data.push(_.cloneDeep(buildings.default));
            }
            // --------------------------------------------------------------
            if (db_stat_check != null){
                Object.assign(stat_check, db_stat_check);
            }

            // Return compiled data
            // --------------------------------------------------------------
            return {
                initial: initial,
                master: master,
                project: project,
                property: property,
                property_owners: property_owners,
                residentials: residentials,
                roads: roads,
                buildings: buildings,
                stat_check: stat_check
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

            // convert from western date to nengou
            this.buildings.data = this.convertNengouToWestern(this.buildings.data, true);

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
            // --------------------------------------------------------------
            // Calaculated Value
            // --------------------------------------------------------------
            calculated_registry_size: function () {
                let total = 0;
                let residential_registry_size = _.sumBy(this.residentials.data, function(data) {
                    return Number(data.parcel_size);
                });
                let road_parcel_size = _.sumBy(this.roads.data, function(data) {
                    return Number(data.parcel_size);
                });
                total = residential_registry_size + road_parcel_size;
                return total;
            },
            calculated_survey_size: function () {
                let total = 0;
                let residential_survey_size = _.sumBy(this.residentials.data, function(data) {
                    return Number(data.parcel_size_survey);
                });
                let road_survey_size = _.sumBy(this.roads.data, function(data) {
                    return Number(data.parcel_size_survey);
                });
                total = residential_survey_size + road_survey_size;
                return total;
            },
            // --------------------------------------------------------------
            residential_calculated_total: function () {
                let total = this.residentials.data.length;
                return total;
            },
            residential_calculated_registration: function () {
                let total = 0;
                let residentials = this.residentials.data;
                parcel_size = _.sumBy(residentials, function(data) {
                    return Number(data.parcel_size);
                });
                total = parcel_size;
                return total;
            },
            residential_calculated_actual: function () {
                let total = 0;
                let residentials = this.residentials.data;
                parcel_size_survey = _.sumBy(residentials, function(data) {
                    return Number(data.parcel_size_survey);
                });
                total = parcel_size_survey;
                return total;
            },
            // --------------------------------------------------------------
            road_calculated_total: function () {
                let total = this.roads.data.length;
                return total;
            },
            road_calculated_registration: function () {
                let total = 0;
                let roads = this.roads.data;
                parcel_size = _.sumBy(roads, function(data) {
                    return Number(data.parcel_size);
                });
                total = parcel_size;
                return total;
            },
            road_calculated_actual: function () {
                let total = 0;
                let roads = this.roads.data;
                parcel_size_survey = _.sumBy(roads, function(data) {
                    return Number(data.parcel_size_survey);
                });
                total = parcel_size_survey;
                return total;
            },
        },

        /*
        ## ------------------------------------------------------------------
        ## VUE WATCH
        ## vue reactive data watch
        ## ------------------------------------------------------------------
        */
        watch: {
            // --------------------------------------------------------------
            // watch calaculation
            // --------------------------------------------------------------
            calculated_registry_size: {
                deep: true,
                handler: function( registry_size ){
                    this.property.registry_size = registry_size;
                }
            },
            calculated_survey_size: {
                deep: true,
                handler: function( survey_size ){
                    this.property.survey_size = survey_size;
                }
            },
            // --------------------------------------------------------------
            // watch data
            // --------------------------------------------------------------
            property_owners: {
                deep: true,
                handler: function( property_owners ){
                    // ------------------------------------------------------
                    if(property_owners.length == 0){
                        this.property_owners.push({ id: 1, name: null });
                    }
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
            // Common Function
            // --------------------------------------------------------------
            // Switch Active or Inactive Section
            // --------------------------------------------------------------
            switchSection: function(section, field, event){
                let checked = event.target.checked;
                if(!checked){
                    let baseData = _.cloneDeep(section.default);
                    let defaultData = [baseData];
                    let data = section.data;
                    let is_equal = _.isEqual(defaultData, data);

                    resetParsley();

                    // if data not changes
                    if(is_equal){
                        section.data[0][field] = 0;
                    }else{
                        let property_id = this.property.id;
                        let confirmed = confirm('チェックを外すと入力済みの情報が消去されます。よろしいですか？');
                        // on confirmed send http request delete section
                        if (confirmed) {
                            // ---------------------------------------------
                            // handle delete request
                            // ---------------------------------------------
                            let delete_section = axios.delete(this.initial.delete_url.section, {
                                data: {
                                    section : field,
                                    property_id : property_id
                                }
                            });
                            // ---------------------------------------------
                            // handle success response
                            // ---------------------------------------------
                            delete_section.then(function (response) {
                                if (response.data.status == 'success') {
                                    section.active = false;
                                    section.data.splice(0, section.data.length);
                                    Vue.nextTick(function () {
                                        section.data.push(baseData);
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
                        }else{
                            event.preventDefault();
                        }
                    }
                }else{
                    section.data[0][field] = 1;
                }
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
                let section_data, last_value;
                if (section == 'residential') section_data = this.residentials.data;
                else section_data = this.roads.data;
                // ---------------------------------------------------------
                if(index == null || index == 0){
                    let prev_data;
                    if(row != 0){
                        let prev_row = section_data[row-1];
                        prev_data = prev_row[field];
                    }else{
                        let last_row = this.residentials.data.length-1;
                        prev_data = this.residentials.data[last_row][field];
                    }
                    // -----------------------------------------------------
                    if(index == null){
                        last_value = prev_data;
                        if (last_value) section_data[row][field] = last_value;
                    }else{
                        last_value = prev_data[prev_data.length-1].value;
                        if (last_value) section_data[row][field][index].value = last_value
                    }
                }
                // ---------------------------------------------------------
                else{
                    let field_data = section_data[row][field];
                    last_value = field_data[index-1].value;
                    if (last_value) section_data[row][field][index].value = last_value;
                }
            },
            // --------------------------------------------------------------
            copyArea: function(row, section){
                let copy_row, parcel_city, parcel_city_extra, parcel_town;
                // ---------------------------------------------------------
                switch (section) {
                    case 'residential':
                        // -------------------------------------------------
                        copy_row = this.residentials.data[row-1];
                        parcel_city = copy_row.parcel_city;
                        parcel_city_extra = copy_row.parcel_city_extra;
                        parcel_town = copy_row.parcel_town;
                        // -------------------------------------------------
                        if (parcel_city || parcel_city_extra || parcel_town) {
                            this.residentials.data[row].parcel_city = parcel_city;
                            this.residentials.data[row].parcel_city_extra = parcel_city_extra;
                            this.residentials.data[row].parcel_town = parcel_town;
                        }
                    break;
                    case 'road':
                        // -------------------------------------------------
                        if(row == 0) {
                            let last_index = this.residentials.data.length-1;
                            copy_row = this.residentials.data[last_index];
                        }else{
                            copy_row = this.roads.data[row-1];
                        }
                        // -------------------------------------------------
                        parcel_city = copy_row.parcel_city;
                        parcel_city_extra = copy_row.parcel_city_extra;
                        parcel_town = copy_row.parcel_town;
                        // -------------------------------------------------
                        if (parcel_city || parcel_city_extra || parcel_town) {
                            this.roads.data[row].parcel_city = parcel_city;
                            this.roads.data[row].parcel_city_extra = parcel_city_extra;
                            this.roads.data[row].parcel_town = parcel_town;
                        }
                    break;
                    case 'building':
                        // -------------------------------------------------
                        if(row == 0) {
                            let last_index = this.residentials.data.length-1;
                            copy_row = this.residentials.data[last_index];
                        }else{
                            copy_row = this.buildings.data[row-1];
                        }
                        // -------------------------------------------------
                        parcel_city = copy_row.parcel_city;
                        parcel_city_extra = copy_row.parcel_city_extra;
                        parcel_town = copy_row.parcel_town;
                        // -------------------------------------------------
                        if (parcel_city || parcel_city_extra || parcel_town) {
                            this.buildings.data[row].parcel_city = parcel_city;
                            this.buildings.data[row].parcel_city_extra = parcel_city_extra;
                            this.buildings.data[row].parcel_town = parcel_town;
                        }
                    break;
                    default: break;
                }

            },

            // --------------------------------------------------------------
            //  Calculate each lot total share
            // --------------------------------------------------------------
            calculateTotalShare: function(owners){
                // create a mathjs instance with fraction
                const config = {
                    number: 'Fraction'
                }
                const math = mathjs(mathAll, config)

                // filter deleted owners data
                let owners_data = _.filter(owners, function(owner){
                    return !owner.deleted;
                });

                // greatest common divisor
                let gcd = function(a, b) {
                    if (b < 0.0000001) return a;
                    return gcd(b, Math.floor(a % b));
                };

                // init first fraction
                let result = 0;
                let first_denom = owners_data[0].share_denom ? owners_data[0].share_denom : 0;
                let first_number = owners_data[0].share_number ? owners_data[0].share_number : 0;
                // start chaining fraction calculations
                let total_share = math.chain(math.fraction(first_number,first_denom));

                // loop all share number data calculate total share
                owners_data.forEach(function(owner, index) {
                    let denom = owner.share_denom;
                    let number = owner.share_number;
                    if (index != 0 && denom && number) {
                        total_share = total_share.add(math.fraction(number,denom));
                    }
                });

                // total share
                // done math chaining method return fraction result
                result = total_share.done();

                // remain share
                // calculate remain fraction
                let remain_share = math.subtract(math.fraction(1), result);

                // console.log(remain_share);
                // loop and add remain share data
                owners_data.forEach(function(owner) {
                    if (isNaN(remain_share.d) || isNaN(remain_share.n)) {
                        owner.other_denom = 0;
                        owner.other_number = 0;
                    }
                    else if(remain_share.n == 0) {
                        owner.other_denom = remain_share.d;
                        owner.other_number = remain_share.d;
                    }
                    else if(remain_share.s == -1) {
                        owner.other_denom = -Math.abs(remain_share.d);
                        owner.other_number = -Math.abs(remain_share.n);
                    }
                    else {
                        owner.other_denom = remain_share.d;
                        owner.other_number = remain_share.n;
                    }
                });

            },


            // --------------------------------------------------------------
            //  Convert nengou year to western year or reverse
            // --------------------------------------------------------------
            convertNengouToWestern: function(buildings, reverse = false){
                buildings.forEach( function(building) {
                    if (building.building_date_nengou && building.building_date_year) {
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
                        let index = building.building_date_year;
                        let selected = nengou[building.building_date_nengou];

                        // get western year from nengou
                        let western_year = selected.range[index-1];
                        if(typeof western_year === 'undefined'){
                            western_year = building.building_date_year;
                        }
                        building.building_date_western = western_year;

                        // get nengou from year
                        if (reverse) {
                            let nengou_year = selected.range.indexOf(western_year);
                            building.building_date_year = nengou_year+1;
                        }
                    }
                    else{
                        building.building_date_western = null;
                    }
                });
                return buildings;
            },

            // --------------------------------------------------------------
            // Add and Remove Property Owners
            // --------------------------------------------------------------
            addPropertyOwners: function(){
                let id = Math.max.apply(Math, this.property_owners.map(function(owner) { return owner.id; }));
                this.property_owners.push({id: id+1, name : null});
                // ----------------------------------------------------------
                refreshParsley();
                refreshTooltip();
            },
            removePropertyOwners: function(index){
                let name = this.property_owners[index].name;
                let confirmed = false;
                let vm = this;

                refreshTooltip();

                if (name) confirmed = confirm('@lang('label.confirm_delete')');
                else vm.property_owners.splice(index, 1);

                if (confirmed) {
                    // find nested owner id
                    // ------------------------------------------------------
                    let findOwner = function(data, type, owner_id) {
                        return data.map(function(e) {
                            return e[type].some(function(owner) {
                                return owner.pj_property_owners_id === owner_id;
                            })
                        })
                    }
                    // ------------------------------------------------------
                    let data = vm.property_owners[index];
                    let residential = findOwner(vm.residentials.data, 'residential_owners', data.id);
                    let road = findOwner(vm.roads.data, 'road_owners', data.id);
                    let building = findOwner(vm.buildings.data, 'building_owners', data.id);
                    // check if owners already selected
                    // ------------------------------------------------------
                    if (residential.includes(true) || road.includes(true) || building.includes(true)) {
                        return $.toast({
                            heading: false, icon: 'warning',
                            position: 'top-right', stack: false, hideAfter: 3000,
                            text: '対象の所有者は既に地番情報に利用されているため削除できません。',
                            position: { right: 18, top: 68 }
                        });
                    }
                    // ------------------------------------------------------

                    // handle delete request
                    // ------------------------------------------------------
                    let delete_onwer = axios.delete(vm.initial.delete_url.property_owners, {
                        data: data
                    });
                    // ------------------------------------------------------

                    // handle success response
                    // ------------------------------------------------------
                    delete_onwer.then(function (response) {
                        if (response.data.status == 'success') {
                            vm.property_owners.splice(index, 1);
                            $( "#assist_a_form" ).submit();
                        }
                    })
                    // ------------------------------------------------------

                    // handle error response
                    // ------------------------------------------------------
                    delete_onwer.catch(function (error) {
                        $.toast({
                            heading: '失敗', icon: 'error',
                            position: 'top-right', stack: false, hideAfter: 3000,
                            text: [error.response.data.message, error.response.data.error],
                            position: { right: 18, top: 68 }
                        });
                    });
                    // ------------------------------------------------------
                }
            },

            // --------------------------------------------------------------
            // RESIDENTIAL - Add and Remove Residential Table Row
            // --------------------------------------------------------------
            addResidentialRow: function(){
                let defaultData = _.cloneDeep(this.residentials.default);
                this.residentials.data.push(defaultData);
                refreshTooltip();
            },
            removeResidentialRow: function(row){
                let defaultData = JSON.stringify(this.residentials.default);
                let data = JSON.stringify(this.residentials.data[row]);
                let confirmed = false;
                let vm = this;

                refreshTooltip();
                refreshParsley();

                if (defaultData !== data) confirmed = confirm('@lang('label.confirm_delete')');
                else vm.residentials.data.splice(row, 1);

                let residential = vm.residentials.data[row];
                if (confirmed && residential.id === null){
                    vm.residentials.data.splice(row, 1);
                    return;
                }

                if (confirmed) {
                    // handle delete request
                    // ------------------------------------------------------
                    let delete_residential = axios.delete(vm.initial.delete_url.residential, {
                        data: vm.residentials.data[row]
                    });
                    // ------------------------------------------------------

                    // handle success response
                    // ------------------------------------------------------
                    delete_residential.then(function (response) {
                        if (response.data.status == 'success') {
                            vm.residentials.data.splice(row, 1);
                            $.toast({
                                heading: '成功', icon: 'success',
                                position: 'top-right', stack: false, hideAfter: 3000,
                                text: response.data.message,
                                position: { right: 18, top: 68 }
                            });
                        }
                    })
                    // ------------------------------------------------------

                    // handle error response
                    // ------------------------------------------------------
                    delete_residential.catch(function (error) {
                        $.toast({
                            heading: '失敗', icon: 'error',
                            position: 'top-right', stack: false, hideAfter: 3000,
                            text: [error.response.data.message, error.response.data.error],
                            position: { right: 18, top: 68 }
                        });
                    });
                    // ------------------------------------------------------
                }
            },
            // --------------------------------------------------------------
            // RESIDENTIAL - Add and Remove Residential Owners
            // --------------------------------------------------------------
            addResidentialOwners: function(row){
                let defaultData = _.cloneDeep(this.residentials.default.residential_owners[0]);
                this.residentials.data[row].residential_owners.push(defaultData);
                refreshParsley();
                refreshTooltip();
            },
            removeResidentialOwners: function(row, index){
                let data = this.residentials.data[row].residential_owners[index];
                if (data.id === null) {
                    this.residentials.data[row].residential_owners.splice(index, 1);
                }else{
                    Vue.set( data, 'deleted', true );
                }
                refreshTooltip();
                this.calculateTotalShare(this.residentials.data[row].residential_owners);
            },

            // --------------------------------------------------------------
            // ROAD - Add and Remove Road Table Row
            // --------------------------------------------------------------
            addRoadRow: function(){
                let defaultData = _.cloneDeep(this.roads.default);
                this.roads.data.push(defaultData);
                refreshTooltip();
            },
            removeRoadRow: function(row){
                let defaultData = JSON.stringify(this.roads.default);
                let data = JSON.stringify(this.roads.data[row]);
                let confirmed = false;
                let vm = this;

                refreshTooltip();

                if (defaultData !== data) confirmed = confirm('@lang('label.confirm_delete')');
                else vm.roads.data.splice(row, 1);

                let road = vm.roads.data[row];
                if (confirmed && road.id === null){
                    vm.roads.data.splice(row, 1);
                    return;
                }

                if (confirmed) {
                    // handle delete request
                    // ------------------------------------------------------
                    let delete_road = axios.delete(vm.initial.delete_url.road, {
                        data: vm.roads.data[row]
                    });
                    // ------------------------------------------------------

                    // handle success response
                    // ------------------------------------------------------
                    delete_road.then(function (response) {
                        if (response.data.status == 'success') {
                            vm.roads.data.splice(row, 1);
                            $.toast({
                                heading: '成功', icon: 'success',
                                position: 'top-right', stack: false, hideAfter: 3000,
                                text: response.data.message,
                                position: { right: 18, top: 68 }
                            });
                        }
                    })
                    // ------------------------------------------------------

                    // handle error response
                    // ------------------------------------------------------
                    delete_road.catch(function (error) {
                        $.toast({
                            heading: '失敗', icon: 'error',
                            position: 'top-right', stack: false, hideAfter: 3000,
                            text: [error.response.data.message, error.response.data.error],
                            position: { right: 18, top: 68 }
                        });
                    });
                    // ------------------------------------------------------
                }
            },
            // --------------------------------------------------------------
            // ROAD - Add and Remove Road Owners
            // --------------------------------------------------------------
            addRoadOwners: function(row){
                let defaultData = _.cloneDeep(this.roads.default.road_owners[0]);
                this.roads.data[row].road_owners.push(defaultData);
                refreshParsley();
                refreshTooltip();
            },
            removeRoadOwners: function(row, index){
                let data = this.roads.data[row].road_owners[index];
                if (data.id === null) {
                    this.roads.data[row].road_owners.splice(index, 1);
                }else{
                    Vue.set( data, 'deleted', true );
                }
                refreshTooltip();
                this.calculateTotalShare(this.roads.data[row].road_owners);
            },

            // --------------------------------------------------------------
            // BUILDING - Add and Remove Building Table Row
            // --------------------------------------------------------------
            addBuildingRow: function(){
                let defaultData = _.cloneDeep(this.buildings.default);
                this.buildings.data.push(defaultData);
                refreshTooltip();
            },
            removeBuildingRow: function(row){
                let defaultData = JSON.stringify(this.buildings.default);
                let data = JSON.stringify(this.buildings.data[row]);
                let confirmed = false;
                let vm = this;

                refreshTooltip();

                if (defaultData !== data) confirmed = confirm('@lang('label.confirm_delete')');
                else vm.buildings.data.splice(row, 1);

                let building = vm.buildings.data[row];
                if (confirmed && building.id === null){
                    vm.buildings.data.splice(row, 1);
                    return;
                }

                if (confirmed) {
                    // handle delete request
                    // ------------------------------------------------------
                    let delete_building = axios.delete(vm.initial.delete_url.building, {
                        data: vm.buildings.data[row]
                    });
                    // ------------------------------------------------------

                    // handle success response
                    // ------------------------------------------------------
                    delete_building.then(function (response) {
                        if (response.data.status == 'success') {
                            vm.buildings.data.splice(row, 1);
                            $.toast({
                                heading: '成功', icon: 'success',
                                position: 'top-right', stack: false, hideAfter: 3000,
                                text: response.data.message,
                                position: { right: 18, top: 68 }
                            });
                        }
                    })
                    // ------------------------------------------------------

                    // handle error response
                    // ------------------------------------------------------
                    delete_building.catch(function (error) {
                        $.toast({
                            heading: '失敗', icon: 'error',
                            position: 'top-right', stack: false, hideAfter: 3000,
                            text: [error.response.data.message, error.response.data.error],
                            position: { right: 18, top: 68 }
                        });
                    });
                    // ------------------------------------------------------
                }
            },
            // --------------------------------------------------------------
            // BUILDING - Add and Remove Building Owners
            // --------------------------------------------------------------
            addBuildingOwners: function(row){
                let defaultData = _.cloneDeep(this.buildings.default.building_owners[0]);
                this.buildings.data[row].building_owners.push(defaultData);
                refreshParsley();
                refreshTooltip();
            },
            removeBuildingOwners: function(row, index){
                let data = this.buildings.data[row].building_owners[index];
                if (data.id === null) {
                    this.buildings.data[row].building_owners.splice(index, 1);
                }else{
                    Vue.set( data, 'deleted', true );
                }
                refreshTooltip();
                this.calculateTotalShare(this.buildings.data[row].building_owners);
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
                            floor_data = _.cloneDeep(this.buildings.default.building_floors[0]);
                            field.building_floors.push(floor_data);
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
            // BUILDING - Validate to required input date
            // --------------------------------------------------------------
            dateCheck: function(field){
                let validate = false;
                
                if (field.building_date_nengou !=null || field.building_date_year != null|| field.building_date_month !=null || field.building_date_day !=null ) { 
                    validate = true;
                }
                // console.log("date", field.building_date_nengou, "year :", field.building_date_year, "month: ", field.building_date_month, "day :", field.building_date_day);
                return validate;
            },
            // --------------------------------------------------------------

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
                // filter null row
                // ----------------------------------------------------------
                let residential_data = _.filter(vm.residentials.data, function(residential){
                    let defaultData = JSON.stringify(vm.residentials.default);
                    let data = JSON.stringify(residential);
                    return data!==defaultData;
                });
                let road_data = _.filter(vm.roads.data, function(road){
                    let defaultData = JSON.stringify(vm.roads.default);
                    let data = JSON.stringify(road);
                    return data!==defaultData;
                });
                let building_data = _.filter(vm.buildings.data, function(building){
                    let defaultData = JSON.stringify(vm.buildings.default);
                    let data = JSON.stringify(building);
                    return data!==defaultData;
                });

                // convert nengou to western date
                building_data = vm.convertNengouToWestern(building_data);

                // compile post data
                // ----------------------------------------------------------
                let data = {
                    project: vm.project,
                    property: vm.property,
                    property_owners: vm.property_owners,
                    residentials: residential_data,
                    roads: road_data,
                    buildings: building_data,
                    stat_check: vm.stat_check
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
                    console.log(response)
                    if (response.data.status == 'success') {
                        let data = response.data.data;

                        // update data to response data
                        vm.property = data.property;
                        vm.property_owners = data.property_owners;
                        if(data.residentials.length != 0){
                            vm.residentials.data = data.residentials;
                        }
                        if (data.roads.length != 0) {
                            vm.roads.data = data.roads;
                        }
                        if (data.buildings.length != 0) {
                            // convert western date to nengou
                            let buildings = vm.convertNengouToWestern(data.buildings, true);
                            vm.buildings.data = buildings;
                        }
                        vm.stat_check = data.stat_check;

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
    var revalidateParsley = function(){
        Vue.nextTick(function () {
            var $form = $('form[data-parsley]');
            if (!$form.parsley().isValid()) {
                $form.parsley().validate();
            }
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