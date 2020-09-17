<template v-if="!status.loading">

    <!-- Request entry header - Start -->
    @relativeInclude('header')
    <!-- Request entry header - End -->

    <!-- Request entries - Start -->
    <template v-if="result.data && result.data.length" v-for="( request, requestIndex ) in result.data">
        <request-entry v-model="request" :index="requestIndex"></request-entry>
    </template>
    <!-- Request entries - End -->

    <!-- Empty placeholder - Start -->
    <template v-if="result.data && !result.data.length">
        <div class="project-entry request-entry mt-4 border rounded">
            <div class="p-3 text-center text-muted">
                <span>対象のレコードはありません。</span>
            </div>
        </div>
    </template>
    <!-- Empty placeholder - End -->

</template>