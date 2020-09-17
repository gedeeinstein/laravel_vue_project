<script type="text/x-template" id="sale-calculator">
    <div class="sale-calculator px-1 col-md">
        <ul class="list-group">
            <li class="list-group-item rounded-0 p-2" :class="{ 'bg-light': !entry.primary }">

                <!-- Sales division - Start -->
                <div class="row mx-n2">
                    <div class="px-2 col-75px col-sm-125px d-flex d-md-none align-items-center">
                        <span>区画数</span>
                    </div>
                    <div class="px-2 col">
                        @component("{$component->preset}.integer")
                            @slot( 'disabled', 'isDisabled' )
                            @slot( 'model', 'entry.sales_divisions' )
                            @slot( 'name', "prefix + 'sales-division'" )
                        @endcomponent
                    </div>
                </div>
                <!-- Sales division - End -->

            </li>
            <li class="list-group-item p-2" :class="{ 'bg-light': !entry.primary }">

                <!-- Unit average area - Start -->
                <div class="row mx-n2">
                    <div class="px-2 col-75px col-sm-125px d-flex d-md-none align-items-center">
                        <span>1区画平均面積</span>
                    </div>
                    <div class="px-2 col">
                        @component("{$component->preset}.decimal")
                            @slot( 'disabled', true )
                            @slot( 'value', 'averageArea' )
                            @slot( 'name', "prefix + 'unit-average-area'" )
                        @endcomponent
                    </div>
                </div>
                <!-- Unit average area - End -->

            </li>
            <li class="list-group-item p-2" :class="{ 'bg-light': !entry.primary }">

                <!-- Rate of return - Start -->
                <div class="row mx-n2">
                    <div class="px-2 col-75px col-sm-125px d-flex d-md-none align-items-center">
                        <span>利益率</span>
                    </div>
                    <div class="px-2 col">
                        @component("{$component->preset}.decimal")
                            @slot( 'disabled', 'isDisabled' )
                            @slot( 'model', 'entry.rate_of_return' )
                            @slot( 'name', "prefix + 'rate-of-return'" )
                        @endcomponent
                    </div>
                </div>
                <!-- Rate of return - End -->

            </li>
            <li class="list-group-item p-2" :class="{ 'bg-light': !entry.primary }">

                <!-- Sales unit price - Start -->
                <div class="row mx-n2">
                    <div class="px-2 col-75px col-sm-125px d-flex d-md-none align-items-center">
                        <span>販売坪単価</span>
                    </div>
                    <div class="px-2 col">
                        @component("{$component->preset}.money")
                            @slot( 'disabled', true )
                            @slot( 'value', 'unitPrice' )
                            @slot( 'name', "prefix + 'sales-unit-price'" )
                        @endcomponent
                    </div>
                </div>
                <!-- Sales unit price - End -->

            </li>
            <li class="list-group-item p-2" :class="{ 'bg-light': !entry.primary }">

                <!-- Unit average area - Start -->
                <div class="row mx-n2">
                    <div class="px-2 col-75px col-sm-125px d-flex d-md-none align-items-center">
                        <span>1区画平均価格</span>
                    </div>
                    <div class="px-2 col">
                        @component("{$component->preset}.decimal")
                            @slot( 'disabled', true )
                            @slot( 'value', 'unitAveragePrice' )
                            @slot( 'name', "prefix + 'unit-average-price'" )
                        @endcomponent
                    </div>
                </div>
                <!-- Unit average area - End -->

            </li>
        </ul>
    </div>
</script>