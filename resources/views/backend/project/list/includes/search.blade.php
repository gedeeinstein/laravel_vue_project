<transition name="paste-in">
    <template v-if="!$store.state.print">
        <form method="post" action="#">
            <div class="card bg-light mb-3">
                <div class="card-body py-3 px-3">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="row">
    
                                <div class="col-md-6">
    
                                    <div class="form-group border-0 py-0 mb-2">
                                        <label class="mb-1" for="">ID</label>
                                        <div class="row mx-0">

                                            <!-- Minimum ID filter - Start -->
                                            <div class="px-0 col">
                                                <input type="number" class="form-control" min="1" v-model.number="filter.min" @change="applyFilter">
                                            </div>
                                            <!-- Minimum ID filter - End -->

                                            <div class="px-1 col-auto d-flex align-items-center">
                                                <span>-</span>
                                            </div>

                                            <!-- Maximum ID filter - Start -->
                                            <div class="px-0 col">
                                                <input type="number" class="form-control" min="1" v-model.number="filter.max" @change="applyFilter">
                                            </div>
                                            <!-- Maximum ID filter - End -->

                                        </div>
                                    </div>
    
                                </div>
                                <div class="col-md-6">

                                    <!-- Title filter - Start -->
                                    <div class="form-group border-0 py-0 mb-2">
                                        <label class="mb-1" for="">物件名称</label>
                                        <input type="text" class="form-control" v-model="filter.title" @change="applyFilter">
                                    </div>
                                    <!-- Title filter - End -->
    
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row">
                                
                                <div class="col-md-6">
    
                                    <div class="form-group border-0 py-0 mb-2">
                                        <label class="mb-1" for="">仕入決済年月</label>
                                        <div class="row">

                                            <!-- Payment year filter - Start -->
                                            <div class="col">
                                                <select class="form-control" v-model.number="filter.year" @change="applyFilter">
                                                    <option value="all"></option>
                                                    <template v-for="year in preset.filter.years">
                                                        <option :value="year">@{{ year }}年</option>
                                                    </template>
                                                </select>
                                            </div>
                                            <!-- Payment year filter - End -->
                                            
                                            <!-- Payment month filter - Start -->
                                            <div class="col">
                                                <select class="form-control" v-model.number="filter.month" @change="applyFilter">
                                                    <option value="all"></option>
                                                    <template v-for="month in 12">
                                                        <option :value="month">@{{ month }}月</option>
                                                    </template>
                                                </select>
                                            </div>
                                            <!-- Payment month filter - End -->

                                        </div>
                                    </div>
    
                                </div>
                                <div class="col-md-6">

                                    <!-- Period filter - Start -->
                                    <div class="form-group border-0 py-0 mb-2">
                                        <label class="mb-1" for="">仕入決済日年度</label>
                                        <select class="form-control" v-model.number="filter.period" @change="applyFilter">
                                            <option value="all"></option>
                                            <template v-for="period in preset.filter.periods">
                                                <option :value="period.id">@{{ period.label }}</option>
                                            </template>
                                        </select>
                                    </div>
                                    <!-- Period filter - End -->
    
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="row">
                                <div class="col-auto">
                                    
                                    <!-- Empty contract date inclusion check - Start -->
                                    <div class="icheck-cyan">
                                        <input type="checkbox" id="id" name="name" data-parsley-checkmin="1" v-model="filter.empty"
                                            :disabled="status.loading" :true-value="true" :false-value="false" @change="applyFilter" />
                                        <label for="id" class="text-uppercase fs-12 noselect w-100">
                                            <span>仕入契約日未入力</span>
                                        </label>
                                    </div>
                                    <!-- Empty contract date inclusion check - End -->

                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="row justify-content-center">
                                <div class="mt-2 col-auto col-sm-175px">

                                    <!-- Filter button - Start -->
                                    <button type="button" class="btn btn-block btn-info" @change="applyFilter">
                                        <div class="row mx-n1 justify-content-center">
                                            <div class="px-1 col-auto fs-15 d-flex align-items-center">
                                                <i class="fw-l far fa-search"></i>
                                            </div>
                                            <div class="px-1 col-auto">検索</div>
                                        </div>
                                    </button>
                                    <!-- Filter button - End -->

                                </div>
                                <div class="mt-2 col-sm-auto">

                                    <!-- Reset filter button - Start -->
                                    <button type="button" class="btn btn-block btn-secondary" @click="resetFilter">
                                        <div class="row mx-n1 justify-content-center">
                                            <div class="px-1 col-auto fs-13 d-flex align-items-center">
                                                <i class="fw-l far fa-undo"></i>
                                            </div>
                                            <div class="px-1 col-auto">リセット</div>
                                        </div>
                                    </button>
                                    <!-- Reset filter button - End -->

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </template>
</transition>