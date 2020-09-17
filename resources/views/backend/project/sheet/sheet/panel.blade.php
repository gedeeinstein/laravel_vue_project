<!-- Sheet panel - Start -->
<div class="tab-pane fade" :class="{ 'show active paste-in-up': sheet.active }" role="tabpanel">
    
    <!-- Base sheet properties - Start -->
    @include("{$include->sheet}.includes.base")
    <!-- Base sheet properties - End -->

    <!-- Project sheet group - Start -->
    @include("{$include->sheet}.includes.groups")
    <!-- Project sheet group - End -->

    <!-- Project control buttons - Start -->
    @include("{$include->sheet}.includes.controls")
    <!-- Project control buttons - End -->

</div>
<!-- Sheet panel - End -->