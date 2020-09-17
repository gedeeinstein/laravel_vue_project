<template v-if="status.loading" v-for="placeholder in config.placeholder">
    <div class="project-entry placeholder mt-4">
        <div class="glimmer">

            <!-- Header placeholder- Start -->
            @include("{$components->includes}.project.placeholder.header")
            <!-- Header placeholder - End -->
            
            <!-- Content placeholder - Start -->
            @include("{$components->includes}.project.placeholder.content")
            <!-- Content placeholder - End -->
            
            <!-- Detail placeholder - Start -->
            @include("{$components->includes}.project.placeholder.detail")
            <!-- Detail placeholder - End -->

            <!-- Detail placeholder - Start -->
            @include("{$components->includes}.project.placeholder.detail")
            <!-- Detail placeholder - End -->
            
        </div>
    </div>
</template>