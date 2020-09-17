// --------------------------------------------------------------------------
(function( $, io, document, window, undefined ){
    // ----------------------------------------------------------------------
    // Sample filter
    // ----------------------------------------------------------------------
    // Vue.filter('uppercase', function( str ){
    //     return str;
    // });
    // ----------------------------------------------------------------------
    
    // ----------------------------------------------------------------------
    // Convert meter dimension to tsubo based on spec
    // https://bit.ly/2JTSrbD
    // ----------------------------------------------------------------------
    window.tsubo = function( meter, precision, rounding ){
        // ------------------------------------------------------------------
        precision = precision || 2;
        rounding = rounding || 'floor';
        // ------------------------------------------------------------------
        var tsubo = 0.3025;
        var result = meter * tsubo;
        var roundings = [ 'floor', 'round', 'ceil' ];
        // ------------------------------------------------------------------
        if( precision && rounding ){
            // --------------------------------------------------------------
            var multiplier = 1;
            io.times( precision, function(){
                multiplier *= 10;
            });
            // --------------------------------------------------------------
            if( 0 >= roundings.indexOf( rounding )){
                result = Math[ rounding ]( result * multiplier ) / multiplier;
            }
            // --------------------------------------------------------------
        }
        // ------------------------------------------------------------------
        return result;
        // ------------------------------------------------------------------
    }; Vue.filter( 'tsubo', window.tsubo );
    // ----------------------------------------------------------------------


    // ----------------------------------------------------------------------
    // Format number with numeral
    // ----------------------------------------------------------------------
    window.numeralFormat = function( number, precision, format ){
        // ------------------------------------------------------------------
        format = format || '0,0';
        precision = precision || 0;
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        if( precision ){
            format += '.';
            io.times( precision, function(){ format += '0' });
        }
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        return numeral( number ).format( format );
        // ------------------------------------------------------------------
    }; Vue.filter( 'numeralFormat', window.numeralFormat );    
    // ----------------------------------------------------------------------


    // ----------------------------------------------------------------------
    // Parse and format UTC timestamp (server time) to local time
    // ----------------------------------------------------------------------
    window.momentLocal = function( stampUTC, format ){
        // ------------------------------------------------------------------
        format = format || 'YYYY-MM-DD';
        if( stampUTC ) return moment.utc( stampUTC ).local().format( format );
        // ------------------------------------------------------------------
    }; Vue.filter( 'momentLocal', window.momentLocal );    
    // ----------------------------------------------------------------------


    // ----------------------------------------------------------------------
    // Decimal rounding with decimal-point precision
    // ----------------------------------------------------------------------
    window.round = function( x, n ){
        return Math.round( x * Math.pow( 10, n )) / Math.pow( 10, n );
    }; Vue.filter( 'round', window.round );
    // ----------------------------------------------------------------------

    
    // ----------------------------------------------------------------------
    // Decimal round, floor, and ceil rounding with better accuracy
    // https://stackoverflow.com/questions/41259253/how-to-round-down-number-2-decimal-places#comment87737583_41259341
    // http://www.jacklmoore.com/notes/rounding-in-javascript/
    // ----------------------------------------------------------------------
    window.roundDecimal = function( value, decimals ){
        return Number( Math.round( value+'e'+decimals )+'e-'+decimals );
    }; Vue.filter( 'roundDecimal', window.roundDecimal );
    // ----------------------------------------------------------------------
    window.floorDecimal = function( value, decimals ){
        return Number( Math.floor( value+'e'+decimals )+'e-'+decimals );
    }; Vue.filter( 'floorDecimal', window.floorDecimal );
    // ----------------------------------------------------------------------
    window.ceilDecimal = function( value, decimals ){
        return Number( Math.ceil( value+'e'+decimals )+'e-'+decimals );
    }; Vue.filter( 'ceilDecimal', window.ceilDecimal );
    // ----------------------------------------------------------------------

    // ----------------------------------------------------------------------
}( jQuery, _, document, window ));
// --------------------------------------------------------------------------