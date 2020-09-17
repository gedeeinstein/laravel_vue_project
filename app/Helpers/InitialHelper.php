<?php
// --------------------------------------------------------------------------
namespace App\Helpers;
// --------------------------------------------------------------------------

// --------------------------------------------------------------------------
// Generate Perfect Initials
// By Chris Blackwell
// Taken from https://chrisblackwell.me/generate-perfect-initials-using-php/
// --------------------------------------------------------------------------
class InitialHelper {
    // ----------------------------------------------------------------------
    /**
     * Generate initials from a name
     *
     * @param string $name
     * @return string
     */
    // ----------------------------------------------------------------------
    public static function generate( $name ){
        $words = explode( ' ', $name );
        if( count( $words ) >= 2 ){
            return strtoupper( substr( $words[0], 0, 1 ) . substr( end( $words ), 0, 1 ));
        } return self::makeInitials( $name );
    }
    // ----------------------------------------------------------------------

    // ----------------------------------------------------------------------
    /**
     * Make initials from a word with no spaces
     *
     * @param string $name
     * @return string
     */
    // ----------------------------------------------------------------------
    protected static function makeInitials( $name ){
        preg_match_all( '#([A-Z]+)#', $name, $capitals );
        if( count( $capitals[1] ) >= 2 ){
            return substr( implode( '', $capitals[1]), 0, 2 );
        } return strtoupper( substr( $name, 0, 2 ));
    }
    // ----------------------------------------------------------------------
}
// --------------------------------------------------------------------------
