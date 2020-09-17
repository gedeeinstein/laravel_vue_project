<div class="tab-pane tab-contract fade show active pt-3" id="tab-contract" role="tabpanel" aria-labelledby="home-tab">

    <!-- Real estate form group - Start -->
    <contract-real-estate v-model="entry" :project="project" :contract="contract" :target="target" :buildings="buildings"
        :disabled="status.loading">
    </contract-real-estate>
    <!-- Real estate form group - End -->

    <!-- Property form group - Start -->
    <contract-property v-model="entry" :project="project" :contract="contract" :target="target" :buildings="buildings"
        :disabled="status.loading">
    </contract-property>
    <!-- Property form group - End -->

    <!-- Road form group - Start -->
    <contract-road v-model="entry" :project="project" :contract="contract" :target="target" 
        :disabled="status.loading">
    </contract-road>
    <!-- Road form group - End -->

    <!-- Contract group - Start -->
    <contract-group v-model="entry" :project="project" :contract="contract" :target="target" 
        :disabled="status.loading">
    </contract-group>
    <!-- Contract group - End -->

</div>