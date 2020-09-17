<script type="text/x-template" id="project-memo">
    <li class="my-2 memo-entry" :class="{ 'text-black-50': entry.disabled, 'create': entry.create, 'edit': entry.edit }">
        <div class="row mx-n2">
            <div class="px-2 col col-lg-12 d-flex align-items-center">
                <div class="row mx-n1 w-100">

                    <!-- Live edit form - Start -->
                    <template v-if="editMode && ( entry.edit || entry.create )">
                        <div class="px-1 col">
                            <form method="post" @submit.prevent="save()">
                                <div class="input-group mb-2">
                                    <input type="text" class="form-control form-control-sm" v-model="entry.content" :disabled="status.loading" 
                                        @keydown.esc="cancel" />
                                    <div class="input-group-append">
                                        <button type="submit" class="btn btn-sm btn-info px-2" :disabled="status.loading">
                                            <i v-if="status.loading" class="fw-l far fa-cog fa-spin"></i>
                                            <i v-else class="fw-l far fa-save"></i>
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </template>
                    <!-- Live edit form - End -->

                    <template v-else>

                        <!-- Memo content - Start -->
                        <template v-if="entry.content">
                            <div class="px-1 col-auto">
                                <span v-if="entry.disabled"><s>@{{ entry.content }}</s></span>
                                <span v-else>@{{ entry.content }}</span>
                            </div>
                        </template>
                        <!-- Memo content - End -->
    
                        <!-- Memo timestamp - Start -->
                        <template v-if="timestamp">
                            <div class="px-1 col-auto d-flex align-items-center">
                                <span class="fs-12" v-if="entry.disabled"><s>@{{ timestamp }}</s></span>
                                <span class="fs-12" v-else>@{{ timestamp }}</span>
                            </div>
                        </template>
                        <!-- Memo timestamp - End -->
    
                        <!-- Memo creator - Start -->
                        <template v-if="entry.user && entry.user.full_name">
                            <div class="px-1 col-auto">
                                <span class="fs-12" v-if="entry.disabled"><s>@{{ entry.user.full_name }}</s></span>
                                <span class="fs-12" v-else>@{{ entry.user.full_name }}</span>
                            </div>
                        </template>
                        <!-- Memo creator - End -->

                    </template>
                </div>
            </div>
            <template v-if="editMode && !entry.edit && !entry.create && !entry.disabled">
                <div class="px-2 col-auto col-lg-12 mt-0 mt-lg-1">
                    <div class="row mx-n1 d-flex d-lg-none"><!-- SM screen buttons -->
    
                        <!-- Edit memo button  Start -->
                        <div class="px-1 col-6">
                            <button type="button" class="btn btn-block btn-xs btn-default px-2" @click="toggleEdit( $event )">
                                <i class="far fa-pencil"></i>
                            </button>
                        </div>
                        <!-- Edit memo button - End -->
    
                        <!-- Delete memo button - Start -->
                        <div class="px-1 col-6">
                            <button type="button" class="btn btn-block btn-xs btn-danger px-2" @click="remove">
                                <i v-if="status.loading" class="fas fa-cog fa-spin"></i>
                                <i v-else class="far fa-times"></i>
                            </button>
                        </div>
                        <!-- Delete memo button - End -->
    
                    </div>
                    <div class="row mx-n2 d-none d-lg-flex"><!-- LG screen buttons -->
    
                        <!-- Edit memo button - Start -->
                        <div class="px-2 col-auto">
                            <a href="javascript:;" class="row mx-n1 h-100 fs-12 text-info" @click="toggleEdit( $event )">
                                <div class="px-1 col-auto d-flex align-items-center">
                                    <i class="far fa-pencil"></i>
                                </div>
                                <div class="px-1 col-auto">@lang('label.edit')</div>
                            </a>
                        </div>
                        <!-- Edit memo button - End -->

                        <div class="px-1 col-auto text-black-50">|</div>
        
                        <!-- Delete memo button - Start -->
                        <div class="px-2 col-auto">
                            <a href="javascript:;" class="row mx-n1 h-100 fs-12 text-danger" @click="remove">
                                <div class="px-1 col-auto d-flex align-items-center">
                                    <i v-if="status.loading" class="fas fa-cog fa-spin"></i>
                                    <i v-else class="far fa-times"></i>
                                </div>
                                <div class="px-1 col-auto">@lang('label.delete')</div>
                            </a>
                        </div>
                        <!-- Delete memo button - End -->
    
                    </div>
                </div>
            </template>
        </div>
    </li>
</script>