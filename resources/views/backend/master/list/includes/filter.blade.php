<!-- Request type filter - Start -->
<template v-if="preset.tabs && preset.tabs.length">
    <div class="card project-filter mt-4">
        <div class="card-header card-header-tabs compact-tabs bg-light">
            <ul class="nav nav-tabs border-bottom-0" id="projet-filter-tabs" role="tablist">

                <template v-for="tab in preset.tabs">
                    <li class="nav-item">
                        <div class="nav-link cursor-pointer p-0" role="button" :class="{ active: tab.id == filter.rank || ( 'all' == tab.id && !filter.rank )}">
                            <button type="button" class="btn btn-tab" @click="filterRank( tab )">
                                <span class="text-info" v-if="tab.label">@{{ tab.label }}</span>
                            </button>
                        </div>
                    </li>
                </template>
                
            </ul>
        </div>
    </div>
</template>
<!-- Request type filter - End -->