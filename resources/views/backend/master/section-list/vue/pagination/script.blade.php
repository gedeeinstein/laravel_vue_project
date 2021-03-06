<script> @minify
// --------------------------------------------------------------------------
(function( $, io, document, window, undefined ){
    // ----------------------------------------------------------------------

    // ----------------------------------------------------------------------
    // Pagination component
    // ----------------------------------------------------------------------
    Vue.component( 'pagination', {
        // ------------------------------------------------------------------
        props: [ 'meta', 'config', 'action', 'loading' ],
        template: '#pagination-template',
        // ------------------------------------------------------------------
        data: function(){
            // --------------------------------------------------------------
            var data = {
                // ----------------------------------------------------------
                status: { loading: false },
                // ----------------------------------------------------------
                default: {
                    meta: { page: 1, perpage: 10, total: 50 },
                    config: {
                        // --------------------------------------------------
                        first: {
                            icon: 'far fa-chevron-double-left',
                            label: false, html: false
                        },
                        // --------------------------------------------------
                        last: {
                            icon: 'far fa-chevron-double-right',
                            label: false, html: false
                        },
                        // --------------------------------------------------
                        prev: {
                            icon: 'far fa-chevron-left',
                            label: false, html: false
                        },
                        // --------------------------------------------------
                        next: {
                            icon: 'far fa-chevron-right',
                            label: false, html: false
                        },
                        // --------------------------------------------------
                        label: 'Search result pages',
                        size: 'md', length: 5, align: 'center'
                        // --------------------------------------------------
                    }
                },
                // ----------------------------------------------------------
            };
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            return data;
            // --------------------------------------------------------------
        },
        // ------------------------------------------------------------------
        computed: {
            // --------------------------------------------------------------
            first: function(){ return 1 }, // First page
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Get the last page 
            // --------------------------------------------------------------
            last: function(){
                var last = 1;
                var total = this.param.total;
                var perpage = this.param.perpage;
                if( total && perpage ) last = Math.ceil( total / perpage );
                return last;
            },
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Build the pagination button
            // --------------------------------------------------------------
            pages: function(){
                // ----------------------------------------------------------
                var pages = [];
                var last = this.last;
                var current = this.param.page;
                // ----------------------------------------------------------
                if( last ) io.times( last, function( index ){
                    pages.push( index +1 );
                });
                // ----------------------------------------------------------

                // ----------------------------------------------------------
                // Limit pages to the maximum length
                // ----------------------------------------------------------
                if( this.setting.length && pages.length > this.setting.length ){
                    // ------------------------------------------------------
                    var length = this.setting.length;
                    var travel = Math.floor( length / 2 );
                    var index = pages.indexOf( current );
                    // ------------------------------------------------------
                    if( index <= travel ) return io.take( pages, length );
                    if( index >= last - travel -1 ) return io.takeRight( pages, length );
                    // ------------------------------------------------------
                    var start = index - travel; 
                    var end = start + length;
                    return io.slice( pages, start, end );
                    // ------------------------------------------------------
                }
                // ----------------------------------------------------------

                // ----------------------------------------------------------
                return pages;
                // ----------------------------------------------------------
            },
            // --------------------------------------------------------------


            // --------------------------------------------------------------
            // Compute param object whenever meta property is changed
            // --------------------------------------------------------------
            param: function(){
                return $.extend( true, {}, this.default.meta, this.meta );
            },
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Compute setting object whenever config property is changed
            // --------------------------------------------------------------
            setting: function(){
                // ----------------------------------------------------------
                var config = $.extend( true, {}, this.default.config, this.config );
                // ----------------------------------------------------------
                if( io.isString( config.first )) config.first = { label: config.first };
                if( io.isString( config.prev )) config.prev = { label: config.prev };
                if( io.isString( config.next )) config.next = { label: config.next };
                if( io.isString( config.last )) config.last = { label: config.last };
                // ----------------------------------------------------------
                if( config.length && config.length % 2 == 0 ) config.length += 1;
                // ----------------------------------------------------------
                return config;
                // ----------------------------------------------------------
            },
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // HTML helpers
            // --------------------------------------------------------------
            align: function(){
                // ----------------------------------------------------------
                var align = false;
                var prefix = 'justify-content-';
                // ----------------------------------------------------------
                if( 'left' == this.setting.align ) align = prefix+ 'start';
                else if( 'center' == this.setting.align ) align = prefix+ 'center';
                else if( 'right' == this.setting.align ) align = prefix+ 'end';
                // ----------------------------------------------------------
                return align;
                // ----------------------------------------------------------
            }
            // --------------------------------------------------------------
        },
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Call to action methods
        // ------------------------------------------------------------------
        methods: {
            // --------------------------------------------------------------
            // Page navigation
            // --------------------------------------------------------------
            navigate: function( page ){
                if( this.action && io.isFunction( this.action )){
                    // ------------------------------------------------------
                    if( 'first' == page ) page = 1;
                    else if( 'prev' == page ) page = this.param.page - 1 || 1;
                    else if( 'next' == page ){
                        page = this.param.page + 1;
                        if( page > this.last ) page = this.last;
                    }
                    else if( 'last' == page ) page = this.last;
                    // ------------------------------------------------------
                    if( io.isInteger( page )) this.action( page );
                    this.meta.page = page;
                    // console.log( page );
                    // ------------------------------------------------------
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