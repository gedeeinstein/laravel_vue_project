<?php
    use Illuminate\Support\Collection;

    // ----------------------------------------------------------------------
    if( !function_exists( 'to_tsubo' )){
        /**
        * Convert metric unit dimension into tsubo unit
        *
        * @param string $dimension
        * Dimension in metric to be converted to tsubo
        *
        * @param string $precision
        * Dimension in metric to be converted to tsubo
        *
        * @param string $unit
        * Origin metric unit - milli, deci, centi, meter, deka, hecto, kilo, mega 
        * Defaults to meter
        *
        * @param string $rounding
        * Rounding mode either floor or ceil
        *
        * @return string dimension in tsubo
        *
        **/
        // ------------------------------------------------------------------
        function to_tsubo( $dimension, $precision = 2, $rounding = 'floor', $unit = 'meter' ){
            // --------------------------------------------------------------
            $factor = 1;
            if( 'milli' == $unit ) $factor = 0.001;
            elseif( 'deci' == $unit ) $factor = 0.01;
            elseif( 'centi' == $unit ) $factor = 0.1;
            elseif( 'deka' == $unit ) $factor = 10;
            elseif( 'hecto' == $unit ) $factor = 100;
            elseif( 'kilo' == $unit ) $factor = 1000;
            elseif( 'mega' == $unit ) $factor = 10000;
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Meter to Tsubo calculation based on reference spec
            // https://bit.ly/2JTSrbD
            // --------------------------------------------------------------
            $tsubo = 0.3025; // 1 tsubo unit in meter
            $precision = (int) $precision;
            $result = $dimension * $factor * $tsubo;
            $roundings = collect([ 'floor', 'round', 'ceil' ]);
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            if( $roundings->contains( $rounding )){
                // ----------------------------------------------------------
                if( 'round' == $rounding ) $result = round( $result, $precision );
                else {
                    // ------------------------------------------------------
                    if( $precision ){
                        $multiplier = pow( 10, $precision );
                        $result = $rounding( $result * $multiplier ) / $multiplier;
                    } 
                    // ------------------------------------------------------
                    else $result = $rounding( $result ); // 0 precision
                    // ------------------------------------------------------
                }
                // ----------------------------------------------------------
            }
            // --------------------------------------------------------------
            
            // --------------------------------------------------------------
            return $result;
            // --------------------------------------------------------------
        }
        // ------------------------------------------------------------------
    }
    // ----------------------------------------------------------------------
?>
