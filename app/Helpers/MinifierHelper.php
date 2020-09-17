<?php
    use Tholu\Packer\Packer;
    use WyriHaximus\HtmlCompress\Factory as Compress;
    // ----------------------------------------------------------------------
    if( !function_exists( 'minify' )){
        /**
        * Obfuscate js script
        *
        * @param string $content
        * Javascript content
        *
        * @return string obfuscated javascript
        *
        **/
        // ------------------------------------------------------------------
        function minify( $content, $type = 'js' ){
            // --------------------------------------------------------------
            if( 'js' == $type ){
                $packer = new Packer( $content, 'Normal', true, false, true );
                return $packer->pack();
            }
            // --------------------------------------------------------------
        }
        // ------------------------------------------------------------------
    }
    // ----------------------------------------------------------------------
?>
