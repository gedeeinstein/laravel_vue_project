<div class="card bg-light mb-3">
    <div class="card-body py-3 px-3" v-for="group in [ 'filter-' ]">

        <!-- Project properties - Start -->
        <div class="row mx-n2 mx-xl-n3 mb-2">
            <div class="px-2 px-xl-3 col-lg-4 mb-2 mb-lg-0">
                @relativeInclude('queries.id')
            </div>
            <div class="px-2 px-xl-3 col-lg-4 mb-2 mb-lg-0">
                @relativeInclude('queries.title')
            </div>
            <div class="px-2 px-xl-3 col-lg-4 mb-2 mb-lg-0">
                @relativeInclude('queries.organizer')
            </div>
        </div>
        <!-- Project properties - End -->
        
        <!-- Contract payment - Start -->
        <div class="row mx-n2 mx-xl-n3 mb-2">
            <div class="px-2 px-xl-3 col-lg-4 mb-2 mb-lg-0">
                @relativeInclude('queries.payment-date')
            </div>
            <div class="px-2 px-xl-3 col-lg-4 mb-2 mb-lg-0">
                @relativeInclude('queries.payment-period')
            </div>
        </div>
        <!-- Contract payment - End -->

        <!-- Sale contract payment - Start -->
        <div class="row mx-n2 mx-xl-n3 mb-2">
            <div class="px-2 px-xl-3 col-lg-4 mb-2 mb-lg-0">
                @relativeInclude('queries.sale-payment-date')
            </div>
            <div class="px-2 px-xl-3 col-lg-4 mb-2 mb-lg-0">
                @relativeInclude('queries.sale-payment-period')
            </div>
        </div>
        <!-- Sale contract payment - End -->

        <div class="row mx-n2 mx-xl-n3 mb-2">

            <!-- Contract - Start -->
            <div class="px-2 px-xl-3 col-lg-4 mb-2 mb-lg-0">
                @relativeInclude('queries.contract')
            </div>
            <!-- Contract - End -->

            <!-- Sale contract - Start -->
            <div class="px-2 px-xl-3 col-lg-4 mb-2 mb-lg-0">
                @relativeInclude('queries.sale-contract')
            </div>
            <!-- Sale contract - End -->

            <!-- Loan period - Start -->
            <div class="px-2 px-xl-3 col-lg-4 mb-2 mb-lg-0">
                @relativeInclude('queries.loan')
            </div>
            <!-- Loan period - End -->

        </div>

        <div class="row mx-n2 mx-xl-n3 mb-2">

            <!-- Empty contract - Start -->
            <div class="px-2 px-xl-3 col-auto col-lg-4 mb-2 mb-lg-0">
                @relativeInclude('queries.empty-contract')
            </div>
            <!-- Empty contract - End -->

            <!-- Different price - Start -->
            <div class="px-2 px-xl-3 col-auto col-lg-4 mb-2 mb-lg-0">
                @relativeInclude('queries.different-price')
            </div>
            <!-- Different price - End -->

            <!-- Loan status - Start -->
            <div class="px-2 px-xl-3 col-auto col-lg-4 mb-2 mb-lg-0">
                @relativeInclude('queries.loan-status')
            </div>
            <!-- Loan status - End -->
            
        </div>

        <hr/>

        <div class="row mb-2">
            <div class="col-12">
                <div class="row justify-content-center">
                    <div class="mt-2 col-auto col-sm-175px">
    
                        <!-- Filter button - Start -->
                        <button type="submit" class="btn btn-block btn-info" @click="applyFilter( $event, true )">
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