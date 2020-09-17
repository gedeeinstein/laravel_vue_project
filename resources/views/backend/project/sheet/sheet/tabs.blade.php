<template v-for="( sheet, sheetIndex ) in sheets">
    <template v-for="reflected in [ sheet.is_reflecting_in_budget ]">

        <li class="nav-item drag-handle">
            <div class="nav-link cursor-pointer p-0" role="button" :class="{ active: sheet.active, highlight: reflected }">
                <div class="btn-group">
                    
                    <!-- Main button - Start -->
                    <button type="button" class="btn btn-tab pr-0" @click="activateSheet( sheet )">
                        <div class="row mx-n1">
                            <template v-if="!sheet.id">
                                <div class="px-1 col-auto d-flex align-items-center">
                                    <i class="fas fa-circle fs-8 text-green"></i>
                                </div>
                            </template>
                            <div class="px-1 col-auto">
                                <span>@{{ sheet.name }}</span>
                            </div>
                        </div>
                    </button>
                    <!-- Main button - End -->

                    <!-- Option dropdown - Start -->
                    <div class="btn-group dropdown" role="group">

                        <!-- Option button - Start -->
                        <button type="button" class="btn btn-tab dropdown-toggle dropdown-toggle-split position-static pl-2" data-toggle="dropdown">
                            <span class="sr-only">Sheet Options</span>
                        </button>
                        <!-- Option button - End -->

                        <!-- Dropdown menu - Start -->
                        <div class="dropdown-menu fade-in">

                            <!-- Duplicate sheet - Start -->
                            <a class="dropdown-item" href="javascript:;" @click="duplicateSheet( sheetIndex )">
                                <div class="row mx-n1">
                                    <div class="px-1 col-auto fs-13 d-flex align-items-center">
                                        <i class="far fa-copy"></i>
                                    </div>
                                    <div class="px-1 col">
                                        <span>@lang('project.sheet.tabs.options.duplicate')</span>
                                    </div>
                                </div>
                            </a>
                            <!-- Duplicate sheet - End -->

                            <!-- Delete sheet - Start -->
                            <a class="dropdown-item" href="javascript:;" @click="removeSheet( sheetIndex, project )">
                                <div class="row mx-n1">
                                    <div class="px-1 col-auto fs-13 fs-13 d-flex align-items-center">
                                        <i class="far fa-trash-alt"></i>
                                    </div>
                                    <div class="px-1 col">
                                        <span>@lang('project.sheet.tabs.options.delete')</span>
                                    </div>
                                </div>
                            </a>
                            <!-- Delete sheet - End -->

                        </div>
                        <!-- Dropdown menu - End -->

                    </div>
                    <!-- Option dropdown - End -->

                </div>
            </div>
        </li>
    </template>
</template>

<!-- Create Sheet Button - Start -->
<li class="nav-item">
    <div class="nav-link cursor-pointer p-0" role="button">

        <!-- Create button - Start -->
        <button type="button" class="btn btn-tab btn-block" @click="createNewSheet()">
            <div class="row mx-n1 justify-content-center">
                <div class="px-1 col-auto fs-14 d-flex align-items-center">
                    <i class="fas fa-plus-circle"></i>
                </div>
                <div class="px-1 col-auto">
                    <span>@lang('project.sheet.tabs.options.new')</span>
                </div>
            </div>
        </button>
        <!-- Create button - End -->

    </div>
</li>
<!-- Create Sheet Button - End -->