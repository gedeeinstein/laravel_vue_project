<div class="row mx-0 bg-light">

    <div class="px-0 col-12 col-lg">
        <div class="row mx-0 h-100">
            
            <!-- Project title and rank - Start -->
            <div class="px-0 col-md-12 col-lg">
                <div class="row mx-0 h-100">
        
                    <!-- Organizer company abbreviation - Start -->
                    <div class="px-0 column col-6 col-sm-auto col-lg-auto d-flex align-items-center">
                        <div class="w-100 py-1 px-2" title="主事業者">
                            <span>@{{ organizerAbbr || '-' }}</span>
                        </div>
                    </div>
                    <!-- Organizer company abbreviation - End -->
                    
                    <!-- Project rank - Start -->
                    <div class="px-0 column col-6 col-sm-auto col-lg-auto d-flex align-items-center">
                        <div class="w-100 py-1 px-2">
                            <span>精完</span>
                        </div>
                    </div>
                    <!-- Project rank - End -->
        
                    <!-- Project title - Start -->
                    <div class="px-0 column col-12 col-sm col-lg d-flex align-items-center">
                        <a class="d-flex align-items-center w-100 h-100 py-1 px-2 text-dark" :href="sheetURL" title="物件名称">
                            <span>@{{ projectTitle || '-' }}</span>
                        </a>
                    </div>
                    <!-- Project title - End -->
        
                </div>
            </div>
            <!-- Project title and rank - End -->

            <!-- Border separator - Start -->
            <div class="px-0 col-md-12 d-none d-lg-block d-xl-none border-top"></div>
            <!-- Border separator - End -->
        
            <!-- Purchaser and sales person - Start -->
            <div class="px-0 column col-md-12 col-lg col-xl-auto d-flex align-items-center">
                <div class="w-100 py-2 py-md-1 px-2">
                    <div class="row mx-n1 flex-nowrap">
                        <div class="px-1 col-auto d-flex align-items-center">
                            <span class="badge badge-lg badge-info fw-n">担当</span>
                        </div>
                        <div class="px-1 col d-flex align-items-center fs-13">
                            <div class="row mx-n1 h-100 align-items-center">
                                
                                <!-- Sales person - Start -->
                                <div v-if="buyerStaffs" class="px-1 col-auto col-lg-12 col-xl-auto">
                                    <span>仕:</span>
                                    <span v-if="!buyerStaffs.length">-</span>
                                    <template v-else v-for="( staff, index ) in buyerStaffs">
                                        <span v-if="index">, </span>
                                        <span class="cursor-pointer" data-toggle="tooltip" data-placement="top" :title="staff.full_name">
                                            <span>@{{ staff.last_name }}</span>
                                        </span>
                                    </template>
                                </div>
                                <!-- Sales person - End -->
        
                                <!-- Separator - Start -->
                                <div v-if="buyerStaffs" class="px-1 col-auto d-none">/</div>
                                <!-- Separator - End -->
        
                                <!-- Section sales person - Start -->
                                <div v-if="sectionStaffs" class="px-1 col-auto col-lg-12 col-xl-auto">
                                    <span>販:</span>
                                    <span v-if="!sectionStaffs.length">-</span>
                                    <template v-for="( staff, index ) in sectionStaffs">
                                        <span v-if="index">, </span>
                                        <span class="cursor-pointer" data-toggle="tooltip" data-placement="top" :title="staff.full_name">
                                            <span>@{{ staff.last_name }}</span>
                                        </span>
                                    </template>
                                </div>
                                <!-- Section sales person - End -->
        
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Purchaser and sales person - End -->

        </div>
    </div>

    <!-- Project selects - Start -->
    <div class="px-0 column col-md col-lg-auto d-flex align-items-center">
        <div class="w-100 py-2 px-2 px-lg-3">
            <div class="row mx-n1 justify-content-center flex-lg-nowrap">

                <!-- Section sale NET - Start -->
                <div class="px-1 col-6 col-sm-4 col-md col-lg-90px mb-2 mb-md-0">
                    <div class="input-group input-group-sm" v-for="id in [ 'section-' +entry.id+ '-sale-net' ]">
                        <div class="input-group-prepend">
                            <span class="input-group-text border-0 fs-12 bg-info">NET</span>
                        </div>
                        <select :name="id" :id="id" class="form-control p-1" disabled v-model.number="saleNet">
                            <option value="0">未選択</option>
                            <option value="1">〇</option>
                            <option value="2">×</option>
                        </select>
                    </div>
                </div>
                <!-- Section sale NET - End -->

                <div class="px-1 col-6 col-sm-4 col-md col-lg-90px mb-2 mb-md-0">
                    <div class="input-group input-group-sm" v-for="id in [ 'section-' +entry.id+ '-sale-mediation' ]">
                        <div class="input-group-prepend">
                            <span class="input-group-text border-0 fs-12 bg-info">仲介</span>
                        </div>
                        <select :name="id" :id="id" class="form-control p-1" disabled v-model.number="saleMediation">
                            <option value="0">未選択</option>
                            <option value="1">〇</option>
                            <option value="2">×</option>
                        </select>
                    </div>
                </div>

                <div class="px-1 col-6 col-sm-4 col-md col-lg-90px mb-2 mb-md-0">
                    <div class="input-group input-group-sm" v-for="id in [ 'section-' +entry.id+ '-sale-condition' ]">
                        <div class="input-group-prepend">
                            <span class="input-group-text border-0 fs-12 bg-info">条件</span>
                        </div>
                        <select :name="id" :id="id" class="form-control p-1" disabled v-model.number="saleCondition">
                            <option value="0">未選択</option>
                            <option value="1">杜</option>
                            <option value="2">有</option>
                            <option value="3">無</option>
                        </select>
                    </div>
                </div>

                <div class="px-1 col-6 col-sm-4 col-md col-lg-90px mb-2 mb-md-0">
                    <div class="input-group input-group-sm" v-for="id in [ 'section-' +entry.id+ '-sale-signboard' ]">
                        <div class="input-group-prepend">
                            <span class="input-group-text border-0 fs-12 bg-info">看板</span>
                        </div>
                        <select :name="id" :id="id" class="form-control p-1" disabled v-model.number="saleSignboard">
                            <option value="0">未選択</option>
                            <option value="1">済</option>
                            <option value="2">予</option>
                            <option value="3">無</option>
                        </select>
                    </div>
                </div>

                <div class="px-1 col-6 col-sm-4 col-md col-lg-90px mb-2 mb-md-0">
                    <div class="input-group input-group-sm" v-for="id in [ 'section-' +entry.id+ '-sale-rank' ]">
                        <div class="input-group-prepend">
                            <span class="input-group-text border-0 fs-12 bg-info">Rank</span>
                        </div>
                        <select :name="id" :id="id" class="form-control p-1" disabled v-model.number="saleRank">
                            <option value="1"></option>
                            <option value="2">優</option>
                            <option value="3">可</option>
                            <option value="4">劣</option>
                            <option value="5">危</option>
                        </select>
                    </div>
                </div>

                <div class="px-1 col-6 col-sm-4 col-md col-lg-90px mb-2 mb-md-0 d-md-none">
                    <a :href="sectionPayoffURL" type="button" class="btn btn-sm btn-block btn-primary" target="_blank">
                        <span>区画精算</span>
                    </a>
                </div>

            </div>
        </div>
    </div>
    <!-- Project selects - End -->

    <!-- Lot settlement - Start -->
    <div class="px-0 column col-md-auto d-none d-md-flex align-items-center">
        <div class="w-100 py-2 px-2">
            <a :href="sectionPayoffURL" type="button" class="btn btn-sm btn-primary" target="_blank">
                <span>区画精算</span>
            </a>
        </div>
    </div>
    <!-- Lot settlement - End -->

</div>