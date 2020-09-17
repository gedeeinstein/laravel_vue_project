<?php

if( !function_exists( 'format_date' )){
    /**
     * Format given value date
     * $value = string date value to format
     * $format = string date format, default = Y/m/d
     *
     * @param string $value
     * @param string $format
     *
     * @return string $date
     */
    function format_date($value = null, $format = 'Y/m/d'){
        $date = '';

        // check if empty given value
        if (!isset($value) || is_null($value)) {
            return $date;
        }

        // parse given date using carbon
        $parsed = Illuminate\Support\Carbon::parse($value);

        // check if date is parsed
        if ($parsed) {
            $date = $parsed->format($format);
        }

        return $date;
    }
}

?>
