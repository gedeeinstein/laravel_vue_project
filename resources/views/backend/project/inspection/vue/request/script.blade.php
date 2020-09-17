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
    // Request entry component
    // ----------------------------------------------------------------------
    Vue.component( 'request-entry', {
        // ------------------------------------------------------------------
        props: [ 'value', 'index' ],
        template: '#request-entry',
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Reactive data
        // ------------------------------------------------------------------
        data: function(){
            // --------------------------------------------------------------
            var data = {
                entry: this.value,
                purchases: @json( $purchases ),
                status: { loading: false },
                project: this.value.project,
                config: { dateFormat: 'YYYY/MM/DD HH:mm' },
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
            // Disabled state
            // --------------------------------------------------------------
            isDisabled: function(){ return this.status.loading },
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Find if request is disabled by the active state
            // --------------------------------------------------------------
            isRequestDisabled: function(){
                var active = io.get( this, 'entry.active' );
                return this.isDisabled || !active;
            },
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Find out if it is odd or even index
            // Reminder: index starts with 0 (even)
            // --------------------------------------------------------------
            isOdd: function(){ return this.index % 2 },
            isEven: function(){ return !this.isOdd },
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Get formatted request-datetime
            // --------------------------------------------------------------
            timestamp: function(){
                var time = io.get( this, 'entry.request_datetime' );
                if( time ) return moment( time ).format( this.config.dateFormat );
            },
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Get port serial-number based on the port number type
            // --------------------------------------------------------------
            portNumber: function(){
                // ----------------------------------------------------------
                var type = io.get( this, 'entry.port_number' );
                var infoNumber = io.get( this, 'project.port_pj_info_number' );
                var contractNumber = io.get( this, 'project.port_contract_number' );
                var sectionNumber = 'TBD';
                // ----------------------------------------------------------
                if( 1 === type && infoNumber ) return infoNumber;
                else if( 2 === type && contractNumber ) return contractNumber;
                else if( 3 === type && sectionNumber ) return sectionNumber;
                // ----------------------------------------------------------
            },
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Reference to pj sheet
            // --------------------------------------------------------------
            requestSheet: function(){ return io.get( this, 'entry.sheet' ) || null },
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Get project-sheet URL
            // --------------------------------------------------------------
            sheetURL: function(){ 
                var url = io.get( this, 'project.url.sheet' );
                if( this.requestSheet ) url += '/' +this.requestSheet.id;
                return url;
            },
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Get entry URL
            // --------------------------------------------------------------
            entryURL: function(){
                // ----------------------------------------------------------
                var result = null;
                var entry = this.entry;
                var purchases = this.purchases;
                var url = '{{ route('index') }}/';
                var project = io.get( this, 'project' );
                var type = io.parseInt( io.get( this, 'entry.type.key' ));
                // ----------------------------------------------------------
                if( project && type ){
                    // ------------------------------------------------------
                    // PJ Sheet
                    // ------------------------------------------------------
                    if( 1 === type ) result = this.sheetURL;
                    // ------------------------------------------------------
                    // Purchase create
                    // ------------------------------------------------------
                    else if( 2 === type ){
                        var target = 1;
                        if( entry.context ) target = io.parseInt( entry.context );
                        result = url+ 'project/' +project.id+ '/' +target+ '/purchase-create';
                    }
                    // ------------------------------------------------------
                    // Purchase contract create
                    // ------------------------------------------------------
                    else if( 3 === type ){
                        var target = 1;
                        if( entry.context ) target = io.parseInt( entry.context );
                        result = url+ 'project/' +project.id+ '/purchase/target/' +target+ '/contract-create';
                    }
                    // ------------------------------------------------------
                    // Sale contract
                    // ------------------------------------------------------
                    else if( 4 === type ){
                        var target  = 1;
                        var section = 1;

                        if( entry.context ) purchase_id = io.parseInt( entry.context );

                        let purchase = io.filter(purchases, function(purchase) {
                            return purchase.id == purchase_id;
                        });

                        section = purchase[0].contract.section.id;
                        result = url+ 'sale/' +project.id+ '/section/' +section+ '/contract/tab/' + purchase_id;

                    }
                    // ------------------------------------------------------
                    // Sale contract create
                    // ------------------------------------------------------
                    else if( 5 === type ){
                        var target = 1;
                        if( entry.context ) target = io.parseInt( entry.context );
                        result = url+ 'project/' +project.id+ '/purchase/target/' +target+ '/contract-create';
                    }
                    // ------------------------------------------------------
                    return result;
                    // ------------------------------------------------------
                }
                // ----------------------------------------------------------
            }
            // --------------------------------------------------------------
        },
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Component methods
        // ------------------------------------------------------------------
        methods: {
            // --------------------------------------------------------------
            // Update inspection status
            // --------------------------------------------------------------
            update: function(){
                // ----------------------------------------------------------
                var vm = this;
                var entry = this.entry;
                // ----------------------------------------------------------

                // ----------------------------------------------------------
                var url = '{{ route('project.inspection.update') }}/' +entry.id;
                var request = axios.post( url, { updates: entry });
                // ----------------------------------------------------------
                vm.status.loading = true;
                // ----------------------------------------------------------

                // ----------------------------------------------------------
                // On request success
                // ----------------------------------------------------------
                request.then( function( response ){
                    console.log( response );
                    // ------------------------------------------------------
                    var option = io.clone( toast );
                    var alert = io.get( response, 'data.alert' );
                    io.assign( option, alert );
                    // ------------------------------------------------------

                    // ------------------------------------------------------
                    var status = io.get( response, 'data.status' );
                    // ------------------------------------------------------

                    // ------------------------------------------------------
                    if( 'success' == status ) vm.$emit( 'updated' ); // Emit an update event
                    // ------------------------------------------------------

                    // ------------------------------------------------------
                    $.toast( option ); // Display the notification
                    // ------------------------------------------------------
                });
                // ----------------------------------------------------------


                // ----------------------------------------------------------
                // When request failed
                // ----------------------------------------------------------
                request.catch( function( response ){
                    var alert = io.get( response, 'data.alert' );
                    if( alert ){
                        var option = io.clone( toast );
                        io.assign( option, alert );
                        $.toast( option );
                    }
                });
                // ----------------------------------------------------------
                request.finally( function(){ vm.status.loading = false });
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
