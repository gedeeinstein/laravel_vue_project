<div v-if="!$store.state.print" class="entry-detail">
    <div class="row mx-0">
        <div class="px-0 col-md-7 column">
            <div class="row mx-0">
                <div class="px-0 col">
                    <div class="px-2 py-2">

                        <!-- Project memos - Start -->
                        <ul class="project-memo fs-14 mb-0 pl-4 my-n2">
                            <template v-if="entry.memos && entry.memos.length" v-for="( memoEntry, memoIndex ) in entry.memos">

                                <!-- Project memo - Start -->
                                <project-memo :edit="memo.edit" v-model="memoEntry" @created="created"></project-memo>
                                <!-- Project memo - End -->

                            </template>
                        </ul>
                        <!-- Project memos - End -->

                    </div>
                </div>
                <div class="px-0 col-md-auto column border-right-0">
                    <div class="px-2 py-2">
                        <div class="row mx-n1">

                            <!-- Add memo button - Start -->
                            <div v-if="memo.edit" class="px-1 col-sm col-lg-auto mb-2 mb-sm-0" @click="toggleCreateMemo">
                                <button type="button" class="btn btn-block btn-sm btn-success px-2">
                                    <i class="fw-l far fa-plus"></i>
                                </button>
                            </div>
                            <!-- Add memo button - End -->

                            <!-- Toggle edit memo button - Start -->
                            <div class="px-1 col-sm col-lg-auto" @click="toggleEditMemo">
                                <button type="button" class="btn btn-block btn-sm px-2" :class="memo.edit ? 'btn-outline-info': 'btn-default'">
                                    <span>メモ</span>
                                </button>
                            </div>
                            <!-- Toggle edit memo button - End -->

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="px-0 col-md-5 column d-flex align-items-center justify-content-center">
            <div class="px-2 py-2">
                <div class="row mx-n1 fs-28">

                    <!-- Water and sewage - Start -->
                    <div class="px-1 col-auto">
                        <a target="_blank" :href="getURL('purchaseCreate')" class="cursor-pointer" 
                            data-toggle="tooltip" data-placement="top" title="上下水道" :class="statusWater.color">
                            <i class="icon icon-water"></i>
                        </a>
                    </div>
                    <!-- Water and sewage - End -->

                    <!-- Road - Start -->
                    <div class="px-1 col-auto">
                        <a target="_blank" :href="getURL('purchaseCreate')" class="cursor-pointer" 
                            data-toggle="tooltip" data-placement="top" title="都計道路" :class="statusRoad.color">
                            <i class="icon icon-road"></i>
                        </a>
                    </div>
                    <!-- Road - End -->

                    <!-- Cultural reserve - Start -->
                    <div class="px-1 col-auto">
                        <a target="_blank" :href="getURL('assistB')" class="cursor-pointer" 
                            data-toggle="tooltip" data-placement="top" title="文化埋蔵" :class="statusCultural.color">
                            <i class="icon icon-cultural"></i>
                        </a>
                    </div>
                    <!-- Cultural reserve - End -->
                    
                    <!-- District planning - Start -->
                    <div class="px-1 col-auto">
                        <a target="_blank" :href="getURL('assistB')" class="cursor-pointer" 
                            data-toggle="tooltip" data-placement="top" title="地区計画" :class="statusPlanning.color">
                            <i class="icon icon-blueprint"></i>
                        </a>
                    </div>
                    <!-- District planning - End -->

                    <!-- Scenic area - Start -->
                    <div class="px-1 col-auto">
                        <a target="_blank" :href="getURL('assistB')" class="cursor-pointer" 
                            data-toggle="tooltip" data-placement="top" title="風致地区" :class="statusScenic.color">
                            <i class="icon icon-landscape"></i>
                        </a>
                    </div>
                    <!-- Scenic area - End -->

                    <!-- Elevation - Start -->
                    <div class="px-1 col-auto">
                        <a target="_blank" :href="getURL('purchaseCreate')" class="cursor-pointer" 
                            data-toggle="tooltip" data-placement="top" title="高低差" :class="statusElevation.color">
                            <i class="icon icon-elevation"></i>
                        </a>
                    </div>
                    <!-- Elevation - End -->

                    <!-- Landslide - Start -->
                    <div class="px-1 col-auto">
                        <a target="_blank" :href="getURL('assistB')" class="cursor-pointer" 
                            data-toggle="tooltip" data-placement="top" title="地滑り" :class="statusLandslide.color">
                            <i class="icon icon-landslide"></i>
                        </a>
                    </div>
                    <!-- Landslide - End -->

                    <!-- Soil contamination - Start -->
                    <div class="px-1 col-auto">
                        <a target="_blank" :href="getURL('purchaseCreate')" class="cursor-pointer" 
                            data-toggle="tooltip" data-placement="top" title="土壌汚染" :class="statusContamination.color">
                            <i class="icon icon-contamination"></i>
                        </a>
                    </div>
                    <!-- Soil contamination - End -->

                    <!-- Development Law - Start -->
                    <div class="px-1 col-auto">
                        <a target="_blank" :href="getURL('assistB')" class="cursor-pointer" 
                            data-toggle="tooltip" data-placement="top" title="宅地造成区域法" :class="statusDevelopment.color">
                            <i class="icon icon-law"></i>
                        </a>
                    </div>
                    <!-- Development Law - End -->
                    
                    <!-- Demolition - Start -->
                    <div class="px-1 col-auto">
                        <a target="_blank" :href="getURL('purchase')" class="cursor-pointer" 
                            data-toggle="tooltip" data-placement="top" title="解体予定" :class="statusDemolition.color">
                            <i class="icon icon-demolition"></i>
                        </a>
                    </div>
                    <!-- Demolition - End -->
                    
                </div>
            </div>
        </div>
    </div>
</div>