<template v-if="!status.loading">

    <!-- Project entries - Start -->
    <template v-if="result.data && result.data.length" v-for="( project, projectIndex ) in result.data">
        <project-entry v-model="project" @removed="onProjectRemoved"></project-entry>
    </template>
    <!-- Project entries - End -->

    <!-- Empty placeholder - Start -->
    <template v-if="result.data && !result.data.length">
        <div class="project-entry mt-4 border rounded">
            <div class="p-3 text-center text-muted">
                <span>対象のレコードはありません。</span>
            </div>
        </div>
    </template>
    <!-- Empty placeholder - End -->

</template>