<div class="row mx-0 h-100">
    <div class="px-0 col-lg">
        <div class="row mx-0 flex-row h-100">

            <!-- Section ID - Start -->
            <div class="px-0 column-xl col-12 col-sm-6 col-lg-12 col-xl-75px">
                <div class="row mx-0 h-100">
                    <div class="px-0 column-xl col-80px col-lg-75px bg-light d-block d-xl-none border-top-0">
                        <div class="d-flex align-items-center w-100 h-100 py-1 px-2">
                            <span>ID</span>
                        </div>
                    </div>
                    <div class="px-0 col">
                        <div class="d-flex align-items-center w-100 h-100 py-1 px-2">
                            <span>@{{ project.id }}</span>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Section ID - End -->

            <!-- Port contract number - Start -->
            <div class="px-0 column-xl col-12 col-sm-6 col-lg-12 col-xl">
                <div class="row mx-0 h-100">
                    <div class="px-0 column-xl col-80px col-lg-75px bg-light d-block d-xl-none border-top-0">
                        <div class="d-flex align-items-center w-100 h-100 py-1 px-2">
                            <span>区画番号</span>
                        </div>
                    </div>
                    <div class="px-0 col">
                        <div class="d-flex align-items-center w-100 h-100 py-1 px-2">
                            <span>@{{ project.port_contract_number || '-' }}</span>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Port contract number - End -->

        </div>
    </div>

    <div class="px-0 col-lg-200px col-xl-200px">
        <div class="row mx-0 h-100">

            <!-- Loan days - Start -->
            <div class="px-0 column-xl col-12 col-sm-6 col-lg-12 col-xl-100px">
                <div class="row mx-0 h-100">
                    <div class="px-0 column-xl col-80px col-lg-75px bg-light d-block d-xl-none border-top-0">
                        <div class="d-flex align-items-center w-100 h-100 py-1 px-2">
                            <span>融資日数</span>
                        </div>
                    </div>
                    <div class="px-0 col">
                        <div class="d-flex align-items-center w-100 h-100 py-1 px-2">
                            <span v-if="null === loanDays" class="text-red">未融資</span>
                            <span v-else>@{{ loanDays }}日</span>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Loan days - End -->

            <!-- Tsubo section-sale area - Start -->
            <div class="px-0 column-xl col-12 col-sm-6 col-lg-12 col-xl-100px">
                <div class="row mx-0 h-100">
                    <div class="px-0 column-xl col-80px col-lg-75px bg-light d-block d-xl-none border-top-0">
                        <div class="d-flex align-items-center w-100 h-100 py-1 px-2">
                            <span>面積</span>
                        </div>
                    </div>
                    <div class="px-0 col">
                        <div class="d-flex align-items-center w-100 h-100 py-1 px-2">
                            <span v-if="null === tsuboArea">-</span>
                            <span v-else>@{{ tsuboArea | numeralFormat(2) }}坪</span>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Tsubo section-sale area - End -->

        </div>
    </div>

    <div class="px-0 col-lg-200px col-xl-300px">
        <div class="row mx-0 h-100">

            <!-- Section book price - Start -->
            <div class="px-0 column-xl col-12 col-sm-4 col-lg-12 col-xl-100px">
                <div class="row mx-0 h-100">
                    <div class="px-0 column-xl col-80px col-lg-75px bg-light d-block d-xl-none border-top-0">
                        <div class="d-flex align-items-center w-100 h-100 py-1 px-2">
                            <span>支出簿価</span>
                        </div>
                    </div>
                    <div class="px-0 col">
                        <div class="d-flex align-items-center w-100 h-100 py-1 px-2">
                            <span v-if="!bookPrice">-</span>
                            <span v-else>@{{ bookPrice | numeralFormat(0) }}千円</span>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Section book price - End -->

            <!-- Sale contract price - Start -->
            <div class="px-0 column-xl col-12 col-sm-4 col-lg-12 col-xl-100px">
                <div class="row mx-0 h-100">
                    <div class="px-0 column-xl col-80px col-lg-75px bg-light d-block d-xl-none border-top-0">
                        <div class="d-flex align-items-center w-100 h-100 py-1 px-2">
                            <span>販売価格</span>
                        </div>
                    </div>
                    <div class="px-0 col">
                        <div class="d-flex align-items-center w-100 h-100 py-1 px-2">
                            <span v-if="!contractPrice">-</span>
                            <span v-else>@{{ contractPrice | numeralFormat(0) }}千円</span>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Sale contract price - End -->

            <!-- Profit - Start -->
            <div class="px-0 column-xl col-12 col-sm-4 col-lg-12 col-xl-100px">
                <div class="row mx-0 h-100">
                    <div class="px-0 column-xl col-80px col-lg-75px bg-light d-block d-xl-none border-top-0">
                        <div class="d-flex align-items-center w-100 h-100 py-1 px-2">
                            <span>利益</span>
                        </div>
                    </div>
                    <div class="px-0 col">
                        <div class="d-flex align-items-center w-100 h-100 py-1 px-2">
                            <span v-if="null === profit"></span>
                            <span v-else>@{{ profit | floorDecimal(0) | numeralFormat(0) }}千円</span>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Profit - End -->

        </div>
    </div>

    <div class="px-0 col-lg-200px col-xl-300px">
        <div class="row mx-0 h-100">

            <!-- Loan balance - Start -->
            <div class="px-0 column-xl col-12 col-sm-4 col-lg-12 col-xl-100px">
                <div class="row mx-0 h-100">
                    <div class="px-0 column-xl col-80px col-lg-75px bg-light d-block d-xl-none border-top-0">
                        <div class="d-flex align-items-center w-100 h-100 py-1 px-2">
                            <span>融資残高</span>
                        </div>
                    </div>
                    <div class="px-0 col">
                        <div class="d-flex align-items-center w-100 h-100 py-1 px-2">
                            <span v-if="null === loanBalance">-</span>
                            <span v-else>@{{ loanBalance | floorDecimal(0) | numeralFormat(0) }}千円</span>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Loan balance - End -->

            <!-- Financing (pre) - Start -->
            <div class="px-0 column-xl col-12 col-sm-4 col-lg-12 col-xl-100px">
                <div class="row mx-0 h-100">
                    <div class="px-0 column-xl col-80px col-lg-75px bg-light d-block d-xl-none border-top-0">
                        <div class="d-flex align-items-center w-100 h-100 py-1 px-2">
                            <span>融資(予)</span>
                        </div>
                    </div>
                    <div class="px-0 col">
                        <div class="d-flex align-items-center w-100 h-100 py-1 px-2">
                            <span v-if="null === financing">-</span>
                            <span v-else>@{{ financing | floorDecimal(0) | numeralFormat(0) }}千円</span>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Financing (pre) - End -->

            <!-- Repayment (Preliminary) - Start -->
            <div class="px-0 column-xl col-12 col-sm-4 col-lg-12 col-xl-100px">
                <div class="row mx-0 h-100">
                    <div class="px-0 column-xl col-80px col-lg-75px bg-light d-block d-xl-none border-top-0">
                        <div class="d-flex align-items-center w-100 h-100 py-1 px-2">
                            <span>融資(済)</span>
                        </div>
                    </div>
                    <div class="px-0 col">
                        <div class="d-flex align-items-center w-100 h-100 py-1 px-2">
                            <span v-if="null === repayment">-</span>
                            <span v-else>@{{ repayment | floorDecimal(0) | numeralFormat(0) }}千円</span>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Repayment (Preliminary) - End -->

        </div>
    </div>
</div>