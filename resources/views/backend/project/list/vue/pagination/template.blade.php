<script type="text/x-template" id="pagination-template">
    <nav class="d-flex" :class="align">
        <ul class="pagination">

            <!-- First button - Start -->
            <li v-if="setting.first" class="page-item" :class="{ disabled: 1 === param.page || loading }">
                <template v-if="setting.first.html">
                    <button type="button" :disabled="1 === param.page || loading" class="h-100 page-link" v-html="setting.first.html" @click="navigate('first')"></button>
                </template>
                <template v-else>
                    <button type="button" :disabled="1 === param.page || loading" class="h-100 page-link" @click="navigate('first')">
                        <div class="row mx-n1">
                            <div v-if="setting.first.icon" class="px-1 col-auto fs-10 d-flex align-items-center">
                                <i :class="setting.first.icon"></i>
                            </div>
                            <div v-if="setting.first.label" class="px-1 col-auto">
                                <span>@{{ setting.first.label }}</span>
                            </div>
                        </div>
                    </button>
                </template>
            </li>
            <!-- First button - End -->

            <!-- Previous button - Start -->
            <li v-if="setting.prev" class="page-item" :class="{ disabled: 1 === param.page || loading }">
                <template v-if="setting.prev.html">
                    <button type="button" :disabled="1 === param.page || loading" class="h-100 page-link" v-html="setting.prev.html" @click="navigate('prev')"></button>
                </template>
                <template v-else>
                    <button type="button" :disabled="1 === param.page || loading" class="h-100 page-link" @click="navigate('prev')">
                        <div class="row mx-n1">
                            <div v-if="setting.prev.icon" class="px-1 col-auto fs-10 d-flex align-items-center">
                                <i :class="setting.prev.icon"></i>
                            </div>
                            <div v-if="setting.prev.label" class="px-1 col-auto">
                                <span>@{{ setting.prev.label }}</span>
                            </div>
                        </div>
                    </button>
                </template>
            </li>
            <!-- Previous button - End -->

            <!-- Page buttons - Start -->
            <template v-if="pages && pages.length" v-for="page in pages">
                <li class="page-item" :class="{ active: page === param.page, disabled: loading, 'd-none d-sm-block': page !== param.page }">
                    <button type="button" :disabled="loading" class="h-100 page-link" @click="navigate( page )">
                        <span>@{{ page }}</span>
                    </button>
                </li>
            </template>
            <!-- Page buttons - End -->

            <!-- Next button - Start -->
            <li v-if="setting.next" class="page-item" :class="{ disabled: last === param.page || loading }">
                <template v-if="setting.next.html">
                    <button type="button" :disabled="last === param.page || loading" class="h-100 page-link" v-html="setting.next.html" @click="navigate('next')"></button>
                </template>
                <template v-else>
                    <button type="button" :disabled="last === param.page || loading" class="h-100 page-link" @click="navigate('next')">
                        <div class="row mx-n1">
                            <div v-if="setting.next.label" class="px-1 col-auto">
                                <span>@{{ setting.next.label }}</span>
                            </div>
                            <div v-if="setting.next.icon" class="px-1 col-auto fs-10 d-flex align-items-center">
                                <i :class="setting.next.icon"></i>
                            </div>
                        </div>
                    </button>
                </template>
            </li>
            <!-- Next button - End -->

            <!-- Last button - Start -->
            <li v-if="setting.last" class="page-item" :class="{ disabled: last === param.page || loading }">
                <template v-if="setting.last.html">
                    <button type="button" :disabled="last === param.page || loading" class="h-100 page-link" v-html="setting.last.html" @click="navigate('last')"></button>
                </template>
                <template v-else>
                    <button type="button" :disabled="last === param.page || loading" class="h-100 page-link" @click="navigate('last')">
                        <div class="row mx-n1">
                            <div v-if="setting.last.label" class="px-1 col-auto">
                                <span>@{{ setting.last.label }}</span>
                            </div>
                            <div v-if="setting.last.icon" class="px-1 col-auto fs-10 d-flex align-items-center">
                                <i :class="setting.last.icon"></i>
                            </div>
                        </div>
                    </button>
                </template>
            </li>
            <!-- Last button - End -->

        </ul>
    </nav>
</script>
