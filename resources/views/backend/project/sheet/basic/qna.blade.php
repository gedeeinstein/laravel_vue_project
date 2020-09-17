<ul class="list-group">

    <!-- Soil Contamination - Start -->
    @include("{$basic->queries}.soil-contamination")
    <!-- Soil Contamination - End -->

    <!-- Cultural Property - Start -->
    @include("{$basic->queries}.cultural-property")
    <!-- Cultural Property - End -->

    <!-- District Planning - Start -->
    @include("{$basic->queries}.district-planning")
    <!-- District Planning - End -->

    <!-- Elevation - Start -->
    @include("{$basic->queries}.elevation")
    <!-- Elevation - End -->

    <!-- Elevation - Start -->
    @include("{$basic->queries}.retaining-wall")
    <!-- Elevation - End -->

    <!-- Water Service - Start -->
    @include("{$basic->queries}.water")
    <!-- Water Service - End -->

    <!-- Sewage treatment - Start -->
    @include("{$basic->queries}.sewage")
    <!-- Sewage treatment - End -->

    <!-- Extended Water Check - Start -->
    @include("{$basic->queries}.water-extended")
    <!-- Extended Water Check - End -->

    <!-- Obstructive Pole - Start -->
    @include("{$basic->queries}.obstructive-pole")
    <!-- Obstructive Pole - End -->

    <!-- Road Width - Start -->
    @include("{$basic->queries}.road")
    <!-- Road Width - End -->

    <!-- Positive values - Start -->
    @include("{$basic->queries}.positive")
    <!-- Positive values - End -->

    <!-- Negative values - Start -->
    @include("{$basic->queries}.negative")
    <!-- Negative values - End -->

    <!-- Survey - Start -->
    @include("{$basic->queries}.survey")
    <!-- Survey - End -->

    <!-- Block Requirement - Start -->
    @include("{$basic->queries}.requirement")
    <!-- Block Requirement - End -->

</ul>

@relativeInclude('additional')