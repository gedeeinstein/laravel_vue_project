<script type="text/x-template" id="plan-tab">

    <!-- Plan tab - Start -->
    <li class="nav-item plan-drag-handle">
        <div class="nav-link cursor-pointer p-0" role="button" :class="{ active: entry.active }">
            <div class="btn-group">

                <!-- Main button - Start -->
                <button type="button" class="btn btn-tab pr-0" @click="activate( entry )">
                    <div class="row mx-n1">
                        <template v-if="!entry.id">
                            <div class="px-1 col-auto d-flex align-items-center">
                                <i class="fas fa-circle fs-8 text-green"></i>
                            </div>
                        </template>
                        <div class="px-1 col-auto">
                            <span>@{{ entry.plan_name }}</span>
                        </div>
                    </div>
                </button>
                <!-- Main button - End -->
                
                <!-- Option dropdown - Start -->
                <div class="btn-group dropdown" role="group">

                    <!-- Option button - Start -->
                    <button type="button" class="btn btn-tab dropdown-toggle dropdown-toggle-split position-static pl-2" data-toggle="dropdown">
                        <span class="sr-only">Plan Options</span>
                    </button>
                    <!-- Option button - End -->

                    <div class="dropdown-menu fade-in">

                        <!-- Duplicate plan - Start -->
                        <a class="dropdown-item" href="javascript:;" @click="duplicate">
                            <div class="row mx-n1">
                                <div class="px-1 col-auto fs-13 d-flex align-items-center">
                                    <i class="far fa-copy"></i>
                                </div>
                                <div class="px-1 col">
                                    <span>@lang('project.sheet.tabs.options.duplicate')</span>
                                </div>
                            </div>
                        </a>
                        <!-- Duplicate plan - End -->

                        <!-- Delete plan - Start -->
                        <a class="dropdown-item" href="javascript:;" @click="remove">
                            <div class="row mx-n1">
                                <div class="px-1 col-auto fs-13 fs-13 d-flex align-items-center">
                                    <i class="far fa-trash-alt"></i>
                                </div>
                                <div class="px-1 col">
                                    <span>@lang('project.sheet.tabs.options.delete')</span>
                                </div>
                            </div>
                        </a>
                        <!-- Delete plan - End -->

                    </div>
                </div>
                <!-- Option dropdown - End -->

            </div>
        </div>
    </li>
    <!-- Plan tab - End -->

</script>