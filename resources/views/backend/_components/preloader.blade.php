@php
    // ----------------------------------------------------------------------
    // Vue conditional
    // ----------------------------------------------------------------------
    $condition = '!status.mounted';
    if( !empty( $if )) $condition = $if;
    // ----------------------------------------------------------------------

    // ----------------------------------------------------------------------
    // Default class list
    // ----------------------------------------------------------------------
    $classes = array( 'preloader', 'd-flex', 'justify-content-center', 'align-items-center' );
    if( !empty( $fullscreen ) && $fullscreen ) $classes[] ='preloader-fullscreen';
    // ----------------------------------------------------------------------
@endphp

<!-- Preloader component - Start -->
<transition name="preloader">
    <div class="{{ join(' ', $classes )}}" v-if="{{ $condition }}">
        <div class="sk-folding-cube">
            <div class="sk-cube1 sk-cube"></div>
            <div class="sk-cube2 sk-cube"></div>
            <div class="sk-cube4 sk-cube"></div>
            <div class="sk-cube3 sk-cube"></div>
        </div>
    </div>
</transition>
<!-- Preloader component - End -->