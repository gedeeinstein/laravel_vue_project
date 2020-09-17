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
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">@lang('project.base.tabs.assist.b')</a></li>
    <li class="breadcrumb-item active">{{ $page_title }}</li>
</ol>
@endsection

@section('content')
<form id="assist_b_form" method="POST" data-parsley class="parsley-minimal" v-cloak>
    @csrf
    <!-- Start - Land Information Card -->
    <div class="card card-project">
        <div class="card-header">
            物件情報
        </div>
        <div class="card-body">
                @include('backend.project.assist-b.form.land')
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
                @include('backend.project.assist-b.form.residential')
            </div>
            <!-- End - Residential Table -->

            <!-- Start - Road Table -->
            <div id="form-residential">
                @include('backend.project.assist-b.form.road')
            </div>
            <!-- End - Road Table -->

            <!-- Start - Building Table -->
            <div id="form-residential">
                @include('backend.project.assist-b.form.building')
            </div>
            <!-- End - Building Table -->
        </div>
    </div>

    <!-- Start - Status Check Form -->
    <div class="incomplete_memo form-row p-2 bg-light">
        @include('backend.project.assist-b.form.status')
    </div>
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
                editable: @json( $editable ),
                update_url: '{{ $update_url }}'
            };
            // --------------------------------------------------------------
            let master = {
                values: @json( $master_values ),
                regions: @json( $master_regions ),
            }
            // --------------------------------------------------------------
            let project = @json( $project );
            // --------------------------------------------------------------
            let property = @json( $property )
            // --------------------------------------------------------------
            let property_restrictions = [
                { id: null, restriction_id: null }
            ];
            // --------------------------------------------------------------
            let residentials = {
                active: false, disable_input: true,
                data: []
            }
            // --------------------------------------------------------------
            let roads = {
                active: false, disable_input: true,
                data: []
            }
            // --------------------------------------------------------------
            let buildings = {
                active: false, disable_input: true,
                data: []
            }
            // --------------------------------------------------------------
            let stat_check = {
                project_id: null, screen: 'pj_assist_b',
                status: 2, memo: '',
            };
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Assign data from database
            // --------------------------------------------------------------
            let db_residentials = @json( $residentials );
            let db_roads = @json( $roads );
            let db_buildings = @json( $buildings );
            let db_stat_check = @json( $stat_check );
            // --------------------------------------------------------------
            if(property.restrictions.length != 0){
                Object.assign(property_restrictions, property.restrictions);
            }
            if (db_stat_check != null){
                Object.assign(stat_check, db_stat_check);
            }
            if(db_residentials.length != 0){
                residentials.active = true;
                Object.assign(residentials.data, db_residentials);
            }
            if(db_roads.length != 0){
                roads.active = true;
                Object.assign(roads.data, db_roads);
            }
            if(db_buildings.length != 0){
                buildings.active = true;
                Object.assign(buildings.data, db_buildings);
            }
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Return compiled data
            // --------------------------------------------------------------
            return {
                initial: initial,
                master: master,
                project: project,
                property: property,
                property_restrictions: property_restrictions,
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
            master_property_owners: function () {
                return this.property.owners;
            },
            // --------------------------------------------------------------
            // Calaculated Value
            // --------------------------------------------------------------
            transportation_time_calc: function () {
                let total = 0;
                let const_time = 80;
                let distance = this.property.transportation_time;
                total = Math.floor(distance/const_time);
                return total;
            }
        },

        /*
        ## ------------------------------------------------------------------
        ## VUE WATCH
        ## vue reactive data watch
        ## ------------------------------------------------------------------
        */
        watch: {
            property: {
                deep: true,
                handler: function( property ){
                    this.sameAsBasicResidential();
                    this.sameAsBasicRoad();
                }
            },
            property_restrictions: {
                deep: true,
                handler: function( restriction ){
                    if(restriction.length == 0){
                        this.property_restrictions.push({ id: null, restriction_id: null });
                    }
                }
            },
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
                this.property_restrictions.push({id: null, restriction_id : null});
                // ----------------------------------------------------------
                refreshParsley();
                refreshTooltip();
            },
            removePropertyRestrictions: function(index){
                let restriction = this.property_restrictions[index];
                let confirmed = false;

                refreshTooltip();

                if (restriction.restriction_id) confirmed = confirm('@lang('label.confirm_delete')');
                else this.property_restrictions.splice(index, 1);

                if (confirmed) {
                    if(restriction.id) Vue.set( restriction, 'deleted', true );
                    else this.property_restrictions.splice(index, 1);
                }
            },

            // --------------------------------------------------------------
            // Set residential value same as property basic
            // --------------------------------------------------------------
            sameAsBasicResidential: function(checked = 1){
                if(checked == 0) return;
                // ----------------------------------------------------------
                let data_residentials = this.residentials.data;
                let data_property = this.property;
                // ----------------------------------------------------------
                data_residentials.forEach(function(item){
                    let residential_b = item.residential_b;
                    // ------------------------------------------------------
                    if(residential_b.fire_protection_same == 1){
                        residential_b.fire_protection = data_property.basic_fire_protection;
                    }
                    // ------------------------------------------------------
                    if(residential_b.cultural_property_reserves_same == 1){
                        residential_b.cultural_property_reserves = data_property.basic_cultural_property_reserves;
                        residential_b.cultural_property_reserves_name = data_property.basic_cultural_property_reserves_name;
                    }
                    // ------------------------------------------------------
                    if(residential_b.district_planning_same == 1){
                        residential_b.district_planning = data_property.basic_district_planning;
                        residential_b.district_planning_name = data_property.basic_district_planning_name;
                    }
                    // ------------------------------------------------------
                    if(residential_b.scenic_area_same == 1){
                        residential_b.scenic_area = data_property.basic_scenic_area;
                    }
                    // ------------------------------------------------------
                    if(residential_b.landslide_same == 1){
                        residential_b.landslide = data_property.basic_landslide;
                    }
                    // ------------------------------------------------------
                    if(residential_b.residential_land_development_same == 1){
                        residential_b.residential_land_development = data_property.basic_residential_land_development;
                    }
                });
            },

            // --------------------------------------------------------------
            // Set road value same as property basic
            // --------------------------------------------------------------
            sameAsBasicRoad: function(checked = 1){
                if(checked == 0) return;
                // ----------------------------------------------------------
                let data_roads = this.roads.data;
                let data_property = this.property;
                // ----------------------------------------------------------
                data_roads.forEach(function(item){
                    let road_b = item.road_b;
                    // ------------------------------------------------------
                    if(road_b.fire_protection_same == 1){
                        road_b.fire_protection = data_property.basic_fire_protection;
                    }
                    // ------------------------------------------------------
                    if(road_b.cultural_property_reserves_same == 1){
                        road_b.cultural_property_reserves = data_property.basic_cultural_property_reserves;
                        road_b.cultural_property_reserves_name = data_property.basic_cultural_property_reserves_name;
                    }
                    // ------------------------------------------------------
                    if(road_b.district_planning_same == 1){
                        road_b.district_planning = data_property.basic_district_planning;
                        road_b.district_planning_name = data_property.basic_district_planning_name;
                    }
                    // ------------------------------------------------------
                    if(road_b.scenic_area_same == 1){
                        road_b.scenic_area = data_property.basic_scenic_area;
                    }
                    // ------------------------------------------------------
                    if(road_b.landslide_same == 1){
                        road_b.landslide = data_property.basic_landslide;
                    }
                    // ------------------------------------------------------
                    if(road_b.residential_land_development_same == 1){
                        road_b.residential_land_development = data_property.basic_residential_land_development;
                    }
                });
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
                let data_property = vm.property;
                delete data_property.restrictions;
                // ----------------------------------------------------------
                let data_residential = vm.residentials.data.map(function(residential){
                    return residential.residential_b;
                });
                let data_road = vm.roads.data.map(function(road){
                    return road.road_b;
                });

                // compile post data
                // ----------------------------------------------------------
                let data = {
                    project: vm.project,
                    property: data_property,
                    property_restrictions: vm.property_restrictions,
                    residentials: data_residential,
                    roads: data_road,
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
                        vm.property_restrictions = data.property.restrictions;
                        // vm.residentials.data = data.residentials;
                        // vm.roads.data = data.roads;
                        // vm.buildings.data = data.buildings;
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