<template v-if="!status.loading">

    <!-- Section entries - Start -->
    <template v-if="result.data && result.data.length" v-for="( section, sectionIndex ) in result.data">
        <section-entry v-model="section" :index="sectionIndex"></section-entry>
    </template>
    <!-- Section entries - End -->

    <!-- Empty placeholder - Start -->
    <template v-if="result.data && !result.data.length">
        <div class="project-entry section-entry mt-4 border rounded">
            <div class="p-3 text-center text-muted">
                <span>対象のレコードはありません。</span>
            </div>
        </div>
    </template>
    <!-- Empty placeholder - End -->

</template>