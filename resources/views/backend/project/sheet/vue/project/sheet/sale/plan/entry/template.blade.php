<script type="text/x-template" id="plan-entry">

    <!-- Plan panel - Start -->
    <div class="tab-pane fade sale-plan" :class="{ 'show active paste-in-up': entry.active }" role="tabpanel">

        <!-- Main sale-plan form - Start -->
        @relativeInclude('includes.plan')
        <!-- Main sale-plan form - End -->

        <!-- Main plan-section form - Start -->
        @relativeInclude('includes.sections')
        <!-- Main plan-section form - End -->

        <!-- Sale plan total - Start -->
        @relativeInclude('includes.total')
        <!-- Sale plan total - End -->

    </div>
    <!-- Plan panel - End -->

</script>