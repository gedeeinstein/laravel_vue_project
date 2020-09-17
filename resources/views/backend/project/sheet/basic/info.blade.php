<div class="row mx-n3">
    
    <!-- Total/Overall Area - Start -->
    <div class="px-3 col-md-6 col-lg-3">
        @include("{$basic->fields}.overall-area")
    </div>
    <!-- Total/Overall Area - End -->

    <!-- Asset Tax - Start -->
    <div class="px-3 col-md-6 col-lg-3">
        @include("{$basic->fields}.asset-tax")
    </div>
    <!-- Asset Tax - End -->

    <!-- Building Area - Start -->
    <div class="px-3 col-md-6 col-lg-3">
        @include("{$basic->fields}.building-area")
    </div>
    <!-- Building Area - End -->

    <!-- District / Area Type - Start -->
    <div class="px-3 col-md-6 col-lg-3">
        @include("{$basic->fields}.area-type")
    </div>
    <!-- District / Area Type - End -->

</div>

<div class="row mx-n3">
    
    <!-- Building Coverage Ratio - Start -->
    <div class="px-3 col-md-6 col-lg-3">
        @include("{$basic->fields}.building-ratio")
    </div>
    <!-- Building Coverage Ratio - End -->

    <!-- Floor Area Ratio - Start -->
    <div class="px-3 col-md-6 col-lg-3">
        @include("{$basic->fields}.floor-ratio")
    </div>
    <!-- Floor Area Ratio - End -->

    <!-- Estimated Closing Date - Start -->
    <div class="px-3 col-md-6 col-lg-4">
        @include("{$basic->fields}.closing-date")
    </div>
    <!-- Estimated Closing Date - End -->
    
</div>