<!-- Project status filter - Start -->
<transition name="paste-in">
    <template v-if="!$store.state.print">
        <template v-if="!$store.state.print && preset.tabs && preset.tabs.length">

            <div class="card project-filter mt-4">
                <div class="card-header card-header-tabs compact-tabs bg-light">
                    <ul class="nav nav-tabs border-bottom-0" id="projet-filter-tabs" role="tablist">

                        <template v-for="tab in preset.tabs">
                            <li class="nav-item">
                                <div class="nav-link cursor-pointer p-0" role="button" :class="{ active: tab.id == filter.status || ( 'all' == tab.id && !filter.status )}">
                                    <button type="button" class="btn btn-tab" :class="tab.color ? 'text-' + tab.color: ''" @click="filterStatus( tab )">
                                        <span v-if="tab.label">@{{ tab.label }}</span>
                                        <span v-if="tab.total">(@{{ tab.total }})</span>
                                    </button>
                                </div>
                            </li>
                        </template>
                        
                    </ul>
                </div>
            </div>
            
        </template>
    </template>
</transition>
<!-- Project status filter - End -->