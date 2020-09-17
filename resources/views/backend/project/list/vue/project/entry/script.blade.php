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
    var confirmRemove = function( text ){
        var deferred = Q.defer(); // Create a promise deferred 
        // ------------------------------------------------------------------
        $.confirm({
            draggable: false, 
            content: text, title: false, 
            type: 'orange', typeAnimated: true,
            animationSpeed: 300, backgroundDismiss: true, 
            buttons: {
                remove: {
                    text: '削除（確定）',
                    btnClass: 'btn-red fw-n', keys: [ 'enter' ],
                    action: function(){ deferred.resolve('remove')}
                },
                cancel: {
                    text: '閉じる', btnClass: 'btn-default fw-n',
                    action: function(){ deferred.reject('cancel')}
                }
            }
        });
        // ------------------------------------------------------------------
        return deferred.promise;
        // ------------------------------------------------------------------
    };
    // ----------------------------------------------------------------------

    // ----------------------------------------------------------------------
    // Project entry component
    // ----------------------------------------------------------------------
    Vue.component( 'project-entry', {
        // ------------------------------------------------------------------
        props: [ 'value' ],
        template: '#project-entry',
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // On component mounted
        // ------------------------------------------------------------------
        mounted: function(){
            // --------------------------------------------------------------
            // Initiate the bootstrap tooltips
            // --------------------------------------------------------------
            $('[data-toggle="tooltip"]').tooltip({ container: 'body' });
            // --------------------------------------------------------------
        },
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Reactive data
        // ------------------------------------------------------------------
        data: function(){
            // --------------------------------------------------------------
            var data = {
                entry: this.value,
                config: { dateFormat: 'YYYY/MM/DD' },
                memo: { edit: false, create: false },
                status: {
                    removal: { loading: false }
                },
            };
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
            // Get purchase sale buyer-staffs fullname in array
            // --------------------------------------------------------------
            buyerStaffs: function(){
                var staffs = [];
                var entries = io.get( this.entry, 'purchase_sale.buyer_staffs' );
                if( entries ) $.each( entries, function( e, entry ){
                    var user = io.get( entry, 'user');
                    if( user ) staffs.push( user );
                });
                return staffs;
            },
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Get project status label
            // --------------------------------------------------------------
            projectStatus: function(){
                var status = io.get( this.entry, 'purchase_sale.project_status' );
                if( status ){
                    if( 1 === status ) return { color: 'text-red', label: '登' };
                    else if( 2 === status ) return { color: 'text-red', label: '決' };
                    else if( 3 === status ) return { color: 'text-skyblue', label: '済' };
                    else if( 4 === status ) return { color: 'text-blue', label: '確' };
                    else if( 5 === status ) return { color: 'text-green', label: '買付' };
                    else if( 6 === status ) return { color: 'text-magenta', label: '情' };
                    else if( 7 === status ) return { color: null, label: '保' };
                    else if( 8 === status ) return { color: null, label: 'OUT' };
                }
            },
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Purchase sale offer date
            // --------------------------------------------------------------
            offerDate: function(){
                var date = io.get( this.entry, 'purchase_sale.offer_date' );
                if( date ) return moment( date, 'YYYY-MM-DD' ).format( 'YYYY/MM/DD' );
            },
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Purchase sale project type
            // --------------------------------------------------------------
            projectType: function(){
                var type = io.get( this.entry, 'purchase_sale.project_type' );
                if( type ){
                    if( 1 === type ) return '土地';
                    else if( 2 === type ) return '建物';
                    else if( 3 === type ) return 'マンション';
                }
            },
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Get purchase sale organizer company abbreviation name
            // --------------------------------------------------------------
            organizerAbbr: function(){
                return io.get( this.entry, 'purchase_sale.organizer.kind_in_house_abbreviation' );
            },
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Get the earliest contract date
            // Reference: https://bit.ly/2VzPOlO
            // --------------------------------------------------------------
            contractDate: function(){
                var result = null;
                var targets = io.get( this.entry, 'purchase.targets' );
                // ----------------------------------------------------------
                if( targets && targets.length ) $.each( targets, function( t, target ){
                    var date = io.get( target, 'contract.contract_date' );
                    if( date ){
                        date = moment( date );
                        if( !result || date.isBefore( result )) result = date;
                    }
                });
                // ----------------------------------------------------------
                if( result ) result = result.format( this.config.dateFormat );
                return result;
                // ----------------------------------------------------------
            },
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Get the earliest contract payment date
            // Reference: https://bit.ly/2VzPOlO
            // --------------------------------------------------------------
            paymentDate: function(){
                var result = null;
                var targets = io.get( this.entry, 'purchase.targets' );
                // ----------------------------------------------------------
                if( targets && targets.length ) $.each( targets, function( t, target ){
                    var date = io.get( target, 'contract.contract_payment_date' );
                    if( date ){
                        date = moment( date );
                        if( !result || date.isBefore( result )) result = date;
                    }
                });
                // ----------------------------------------------------------
                if( result ) result = result.format( this.config.dateFormat );
                return result;
                // ----------------------------------------------------------
            },
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Get water status
            // --------------------------------------------------------------
            statusWater: function(){
                // ----------------------------------------------------------
                var status = []; 
                var result = { result: 3, color: 'text-yellow' };
                var targets = io.get( this.entry, 'purchase.targets' );
                // ----------------------------------------------------------
                if( targets && targets.length ) $.each( targets, function( t, target ){
                    var water = io.get( target, 'doc.heads_up_a' );
                    if( water ) status.push( water );
                });
                // ----------------------------------------------------------
                if( io.indexOf( status, 2 ) >= 0 ) return { result: 2, color: 'text-red' };     // No
                if( io.indexOf( status, 3 ) >= 0 ) return { result: 3, color: 'text-yellow' };  // Unidentified
                if( io.indexOf( status, 1 ) >= 0 ) return { result: 1, color: 'text-grey' };    // Yes
                // ----------------------------------------------------------
                return result;
                // ----------------------------------------------------------
            },
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Get road status
            // --------------------------------------------------------------
            statusRoad: function(){
                // ----------------------------------------------------------
                var status = []; 
                var result = { result: 3, color: 'text-yellow' };
                var targets = io.get( this.entry, 'purchase.targets' );
                // ----------------------------------------------------------
                if( targets && targets.length ) $.each( targets, function( t, target ){
                    var road = io.get( target, 'doc.heads_up_c' );
                    if( road ) status.push( road );
                });
                // ----------------------------------------------------------
                if( io.indexOf( status, 1 ) >= 0 ) return { result: 1, color: 'text-red' };     // Yes
                if( io.indexOf( status, 3 ) >= 0 ) return { result: 3, color: 'text-yellow' };  // Unidentified
                if( io.indexOf( status, 2 ) >= 0 ) return { result: 2, color: 'text-grey' };    // No
                // ----------------------------------------------------------
                return result;
                // ----------------------------------------------------------
            },
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Get cultural-reserve status
            // --------------------------------------------------------------
            statusCultural: function(){
                // ----------------------------------------------------------
                var status = []; 
                var result = { result: 3, color: 'text-yellow' };
                var residentials = io.get( this.entry, 'property.residentials' );
                // ----------------------------------------------------------
                if( residentials && residentials.length ) $.each( residentials, function( r, residential ){
                    var cultural = io.get( residential, 'residential_b.cultural_property_reserves' );
                    if( cultural ) status.push( cultural );
                });
                // ----------------------------------------------------------
                if( io.indexOf( status, 1 ) >= 0 ) return { result: 1, color: 'text-red' };     // Yes
                if( io.indexOf( status, 3 ) >= 0 ) return { result: 3, color: 'text-yellow' };  // Unidentified
                if( io.indexOf( status, 2 ) >= 0 ) return { result: 2, color: 'text-grey' };    // No
                // ----------------------------------------------------------
                return result;
                // ----------------------------------------------------------
            },
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Get district-planning status
            // --------------------------------------------------------------
            statusPlanning: function(){
                // ----------------------------------------------------------
                var status = []; 
                var result = { result: 3, color: 'text-yellow' };
                var residentials = io.get( this.entry, 'property.residentials' );
                // ----------------------------------------------------------
                if( residentials && residentials.length ) $.each( residentials, function( r, residential ){
                    var planning = io.get( residential, 'residential_b.district_planning' );
                    if( planning ) status.push( planning );
                });
                // ----------------------------------------------------------
                if( io.indexOf( status, 1 ) >= 0 ) return { result: 1, color: 'text-red' };     // Yes
                if( io.indexOf( status, 3 ) >= 0 ) return { result: 3, color: 'text-yellow' };  // Unidentified
                if( io.indexOf( status, 2 ) >= 0 ) return { result: 2, color: 'text-grey' };    // No
                // ----------------------------------------------------------
                return result;
                // ----------------------------------------------------------
            },
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Get scenic-area status
            // --------------------------------------------------------------
            statusScenic: function(){
                // ----------------------------------------------------------
                var status = []; 
                var result = { result: 3, color: 'text-yellow' };
                var residentials = io.get( this.entry, 'property.residentials' );
                // ----------------------------------------------------------
                if( residentials && residentials.length ) $.each( residentials, function( r, residential ){
                    var scenic = io.get( residential, 'residential_b.scenic_area' );
                    if( scenic ) status.push( scenic );
                });
                // ----------------------------------------------------------
                if( io.indexOf( status, 1 ) >= 0 ) return { result: 1, color: 'text-red' };     // Yes
                if( io.indexOf( status, 3 ) >= 0 ) return { result: 3, color: 'text-yellow' };  // Unidentified
                if( io.indexOf( status, 2 ) >= 0 ) return { result: 2, color: 'text-grey' };    // No
                // ----------------------------------------------------------
                return result;
                // ----------------------------------------------------------
            },
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Get elevation status
            // --------------------------------------------------------------
            statusElevation: function(){
                // ----------------------------------------------------------
                var status = []; 
                var result = { result: 3, color: 'text-yellow' };
                var targets = io.get( this.entry, 'purchase.targets' );
                // ----------------------------------------------------------
                if( targets && targets.length ) $.each( targets, function( t, target ){
                    var elevation = io.get( target, 'doc.heads_up_d' );
                    if( elevation ) status.push( elevation );
                });
                // ----------------------------------------------------------
                if( io.indexOf( status, 1 ) >= 0 ) return { result: 1, color: 'text-red' };     // Yes
                if( io.indexOf( status, 3 ) >= 0 ) return { result: 3, color: 'text-yellow' };  // Unidentified
                if( io.indexOf( status, 2 ) >= 0 ) return { result: 2, color: 'text-grey' };    // No
                // ----------------------------------------------------------
                return result;
                // ----------------------------------------------------------
            },
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Get landslide status
            // --------------------------------------------------------------
            statusLandslide: function(){
                // ----------------------------------------------------------
                var status = []; 
                var result = { result: 3, color: 'text-yellow' };
                var residentials = io.get( this.entry, 'property.residentials' );
                // ----------------------------------------------------------
                if( residentials && residentials.length ) $.each( residentials, function( r, residential ){
                    var landslide = io.get( residential, 'residential_b.landslide' );
                    if( landslide ) status.push( landslide );
                });
                // ----------------------------------------------------------
                if( io.indexOf( status, 1 ) >= 0 ) return { result: 1, color: 'text-red' };     // Yes
                if( io.indexOf( status, 3 ) >= 0 ) return { result: 3, color: 'text-yellow' };  // Unidentified
                if( io.indexOf( status, 2 ) >= 0 ) return { result: 2, color: 'text-grey' };    // No
                // ----------------------------------------------------------
                return result;
                // ----------------------------------------------------------
            },
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Get soil-contamination status
            // --------------------------------------------------------------
            statusContamination: function(){
                // ----------------------------------------------------------
                var status = []; 
                var result = { result: 3, color: 'text-yellow' };;
                var targets = io.get( this.entry, 'purchase.targets' );
                // ----------------------------------------------------------
                if( targets && targets.length ) $.each( targets, function( t, target ){
                    var contamination = io.get( target, 'doc.heads_up_e' );
                    if( contamination ) status.push( contamination );
                });
                // ----------------------------------------------------------
                if( io.indexOf( status, 1 ) >= 0 ) return { result: 1, color: 'text-red' };     // Yes
                if( io.indexOf( status, 3 ) >= 0 ) return { result: 3, color: 'text-yellow' };  // Unidentified
                if( io.indexOf( status, 2 ) >= 0 ) return { result: 2, color: 'text-grey' };    // No
                // ----------------------------------------------------------
                return result;
                // ----------------------------------------------------------
            },
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Get land development status
            // --------------------------------------------------------------
            statusDevelopment: function(){
                // ----------------------------------------------------------
                var status = []; 
                var result = { result: 3, color: 'text-yellow' };
                var residentials = io.get( this.entry, 'property.residentials' );
                // ----------------------------------------------------------
                if( residentials && residentials.length ) $.each( residentials, function( r, residential ){
                    var development = io.get( residential, 'residential_b.residential_land_development' );
                    if( development ) status.push( development );
                });
                // ----------------------------------------------------------
                if( io.indexOf( status, 1 ) >= 0 ) return { result: 1, color: 'text-red' };     // Yes
                if( io.indexOf( status, 3 ) >= 0 ) return { result: 3, color: 'text-yellow' };  // Unidentified
                if( io.indexOf( status, 2 ) >= 0 ) return { result: 2, color: 'text-grey' };    // No
                // ----------------------------------------------------------
                return result;
                // ----------------------------------------------------------
            },
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Get demolition status
            // --------------------------------------------------------------
            statusDemolition: function(){
                // ----------------------------------------------------------
                var status = []; 
                var result = { result: 1, color: 'text-grey' };
                var targets = io.get( this.entry, 'purchase.targets' );
                // ----------------------------------------------------------
                if( targets && targets.length ) $.each( targets, function( t, target ){
                    var buildings = target.buildings;
                    if( buildings && buildings.length ) $.each( buildings, function( b, building ){
                        status.push( io.parseInt( building.kind ));
                    });
                });
                // ----------------------------------------------------------
                if( io.indexOf( status, 2 ) >= 0 ) return { result: 2, color: 'text-red' };     // Checked
                if( io.indexOf( status, 1 ) >= 0 ) return { result: 1, color: 'text-grey' };    // Unchecked
                // ----------------------------------------------------------
                return result;
                // ----------------------------------------------------------
            },
            // --------------------------------------------------------------
        },
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Component methods
        // ------------------------------------------------------------------
        methods: {
            // --------------------------------------------------------------
            // Append new memo
            // --------------------------------------------------------------
            toggleCreateMemo: function(e){
                // ----------------------------------------------------------
                var vm = this;
                vm.memo.create = !vm.memo.create; // Toggle memo create mode
                // ----------------------------------------------------------
                
                // ----------------------------------------------------------
                // If user toggle the create memo, remove new entries
                // ----------------------------------------------------------
                if( !vm.memo.create ){
                    var found = io.findIndex( vm.entry.memos, { create: true });
                    if( found >= 0 ) vm.entry.memos.splice( found, 1 );
                    return;
                }
                // ----------------------------------------------------------
                
                // ----------------------------------------------------------
                var init = @json( $template->memo );
                init.create = true;
                init.project_id = vm.entry.id;
                // ----------------------------------------------------------
                vm.entry.memos.push( init );
                // ----------------------------------------------------------

                // ----------------------------------------------------------
                var $container = $(e.target).closest('.entry-detail');
                setTimeout( function(){
                    $container.find('.memo-entry.create input').focus();
                })
                // ----------------------------------------------------------
            },
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Toggle memo creation
            // --------------------------------------------------------------
            toggleEditMemo: function(){
                var vm = this;
                vm.memo.edit = !vm.memo.edit;
                // ----------------------------------------------------------

                // ----------------------------------------------------------
                // If user exit the edit memo, remove new entries
                // ----------------------------------------------------------
                if( !vm.memo.edit ){
                    vm.memo.create = false;
                    var found = io.findIndex( vm.entry.memos, { create: true });
                    if( found >= 0 ) vm.entry.memos.splice( found, 1 );
                    return;
                }
                // ----------------------------------------------------------
            },
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Get navigation URL
            // --------------------------------------------------------------
            getURL: function( page ){
                var url = this.entry.url;
                if( page in url ) return url[page];
                else return '';
            },
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Remove project
            // --------------------------------------------------------------
            remove: function(){
                var vm = this;
                var entry = this.entry;
                // ----------------------------------------------------------

                // ----------------------------------------------------------
                // Compose the confirmation dialog text
                // ----------------------------------------------------------
                var text = 'ID: ' +entry.id;
                if( io.trim( entry.title )) text += '「' +entry.title+ '」';
                text += 'に関わる全ての情報が削除されます。\nよろしければ、「削除（確定）」ボタンをクリックしてください。';
                // ----------------------------------------------------------

                // ----------------------------------------------------------
                var promise = confirmRemove( text );
                // ----------------------------------------------------------
                promise.then( function(){
                    // ------------------------------------------------------
                    var url = '{{ route('project.delete') }}/' +entry.id;
                    var request = axios.delete( url);
                    // ------------------------------------------------------
                    vm.status.removal.loading = true;
                    // ------------------------------------------------------

                    // ------------------------------------------------------
                    // On request success
                    // ------------------------------------------------------
                    request.then( function( response ){
                        // --------------------------------------------------
                        var option = io.clone( toast );
                        var alert = io.get( response, 'data.alert' );
                        io.assign( option, alert );
                        // --------------------------------------------------

                        // --------------------------------------------------
                        var status = io.get( response, 'data.status' );
                        // --------------------------------------------------

                        // --------------------------------------------------
                        if( 'success' == status ){
                            vm.$emit( 'removed' );
                        }
                        // --------------------------------------------------

                        // --------------------------------------------------
                        $.toast( option );
                        // --------------------------------------------------
                    });
                    // ------------------------------------------------------


                    // ------------------------------------------------------
                    // When request failed
                    // ------------------------------------------------------
                    request.catch( function( response ){
                        var alert = io.get( response, 'data.alert' );
                        if( alert ){
                            var option = io.clone( toast );
                            io.assign( option, alert );
                            $.toast( option );
                        }
                    });
                    // ------------------------------------------------------
                    request.finally( function(){ vm.status.removal.loading = false });
                    // ------------------------------------------------------
                });
            }
            // --------------------------------------------------------------
        }
        // ------------------------------------------------------------------
    });
    // ----------------------------------------------------------------------
}( jQuery, _, document, window ));
// --------------------------------------------------------------------------
@endminify </script>