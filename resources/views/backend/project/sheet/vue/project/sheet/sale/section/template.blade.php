<script type="text/x-template" id="plan-section">
    <li class="plan-section list-group-item px-3 py-3 py-lg-0 border-top-0 rounded-0" :class="{ 'highlighted': !entry.id }">
        <div class="row mx-n1">
            <div class="px-1 py-0 py-lg-2 col-lg-7 col-xl-8">
                <div class="row mx-n1">
                    <div class="px-1 col-lg-75px mb-2 mb-lg-0">

                        <!-- Divisions number - Start -->
                        <div class="form-group row mb-0 h-100">
                            <div class="col-75px col-sm-100px d-block d-lg-none">区画</div>
                            <div class="col">
                                @component("{$component->preset}.decimal")
                                    @slot( 'disabled', 'isDisabled' )
                                    @slot( 'name', "prefix + 'division-number'" )
                                    @slot( 'model', 'entry.divisions_number' )
                                @endcomponent
                            </div>
                        </div>
                        <!-- Divisions number - End -->

                    </div>
                    <div class="px-1 col-lg-2">
                        
                        <!-- Reference area - Start -->
                        <div class="form-group row mb-2 mb-lg-0">
                            <div class="col-75px col-sm-100px d-block d-lg-none">参考面積</div>
                            <div class="col">
                                @component("{$component->preset}.decimal")
                                    @slot( 'disabled', true )
                                    @slot( 'name', "prefix + 'reference-area'" )
                                    @slot( 'value', 'referenceArea' )
                                @endcomponent
                            </div>
                        </div>
                        <!-- Reference area - End -->

                    </div>
                    <div class="px-1 col-lg">

                        <!-- Planned area - Start -->
                        <div class="form-group row mb-2 mb-lg-0">
                            <div class="col-75px col-sm-100px d-block d-lg-none">予定面積</div>
                            <div class="col">
                                @component("{$component->preset}.decimal")
                                    @slot( 'group', true )
                                    @slot( 'append', [ 
                                        'type' => 'button', 'icon' => 'far fa-copy', 
                                        'click' => 'entry.planned_area = referenceArea'
                                    ])
                                    @slot( 'disabled', 'isDisabled' )
                                    @slot( 'name', "prefix + 'planned-area'" )
                                    @slot( 'model', 'entry.planned_area' )
                                @endcomponent
                            </div>
                        </div>
                        <!-- Planned area - End -->

                    </div>
                    <div class="px-1 col-lg">

                        <!-- Unit selling price - Start -->
                        <div class="form-group row mb-2 mb-lg-0">
                            <div class="col-75px col-sm-100px d-block d-lg-none">販売価格</div>
                            <div class="col">
                                @component("{$component->preset}.money")
                                    @slot( 'disabled', 'isDisabled' )
                                    @slot( 'name', "prefix + 'unit-selling-price'" )
                                    @slot( 'model', 'entry.unit_selling_price' )
                                @endcomponent
                            </div>
                        </div>
                        <!-- Unit selling price - End -->
                        
                    </div>
                    <div class="px-1 col-lg-3">

                        <!-- Unit price - Start -->
                        <div class="form-group row mb-2 mb-lg-0">
                            <div class="col-75px col-sm-100px d-block d-lg-none">坪単価</div>
                            <div class="col">
                                @component("{$component->preset}.money")
                                    @slot( 'disabled', true )
                                    @slot( 'name', "prefix + 'unit-price'" )
                                    @slot( 'value', 'unitPrice' )
                                @endcomponent
                            </div>
                        </div>
                        <!-- Unit price - End -->

                    </div>
                </div>
            </div>
            <div class="px-1 col-lg-5 col-xl-4">
                <div class="row">
                    <div class="col-75px col-sm-100px d-block d-lg-none">仲介手数料</div>
                    <div class="col">
                        <div class="row mx-n1">
                            <div class="px-1 py-0 py-lg-2 col-lg-6">

                                <!-- Brokerage fee - Start -->
                                <div class="form-group mb-2 mb-lg-0">
                                    @component("{$component->preset}.money")
                                        @slot( 'negative', true )
                                        @slot( 'disabled', 'isDisabled' )
                                        @slot( 'name', "prefix + 'brokerage-fee'" )
                                        @slot( 'model', 'entry.brokerage_fee' )
                                        @slot( 'change', 'updateBrokerageFeeSign' )
                                    @endcomponent
                                </div>
                                <!-- Brokerage fee - End -->

                            </div>
                            <div class="px-1 col-lg-6">
                                <div class="row mx-n1">
                                    <div class="px-1 col-auto d-none d-lg-block">
                                        <button type="button" class="btn btn-slim btn-gray" @click="calculateBrokerageFee">
                                            <span>自</span>
                                        </button>
                                    </div>
                                    <div class="px-1 py-0 py-lg-2 col">

                                        <!-- Brokerage fee type - Start -->
                                        <div class="form-group mb-2 mb-lg-0">
                                            @component("{$component->preset}.select")
                                                @slot( 'disabled', 'isDisabled' )
                                                @slot( 'name', "prefix + 'brokerage-fee-type'" )
                                                @slot( 'model', array( 'type' => 'number', 'name' => 'entry.brokerage_fee_type' ))
                                                @slot( 'change', 'updateBrokerageFeeSign')
                                                @slot( 'options')
                                                    <option value="0"></option>
                                                    <option value="1">収</option>
                                                    <option value="2">支</option>
                                                    <option value="3">無</option>
                                                @endslot
                                            @endcomponent
                                        </div>
                                        <!-- Brokerage fee type - End -->

                                    </div>
                                    <div class="px-1 py-0 py-lg-2 col-auto d-none d-lg-block">
                                        <div class="mx-n1">

                                            <!-- Create new button - Start -->
                                            <template v-if="!index">
                                                <button type="button" class="btn btn-icon px-2" :disabled="isDisabled" @click="createNew">
                                                    <i class="fa fa-plus-circle cur-pointer text-primary"></i>
                                                </button>
                                            </template>
                                            <!-- Create new button - End -->

                                            <!-- Remove button - Start -->
                                            <template v-else>
                                                <button type="button" class="btn btn-icon px-2" :disabled="isDisabled" @click="remove">
                                                    <i class="fa fa-times-circle cur-pointer text-danger"></i>
                                                </button>
                                            </template>
                                            <!-- Remove button - End -->

                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="px-1 col-12 d-block d-lg-none">
                                <div class="row justify-content-between">
                                    <div class="col-auto">
                                        
                                        <!-- Create new button - Start -->
                                        <template v-if="!index">
                                            <button type="button" class="btn btn-slim btn-info" :disabled="isDisabled" @click="createNew">
                                                <i class="far fa-plus-circle"></i>
                                            </button>
                                        </template>
                                        <!-- Create new button - End -->

                                        <!-- Remove button - Start -->
                                        <template v-else>
                                            <button type="button" class="btn btn-slim btn-danger" :disabled="isDisabled" @click="remove">
                                                <i class="far fa-trash-alt"></i>
                                            </button>
                                        </template>
                                        <!-- Remove button - End -->

                                    </div>
                                    <div class="col-auto">

                                        <!-- Calculate brokerage fee - Start -->
                                        <button type="button" class="btn btn-slim btn-gray" :disabled="isDisabled" @click="calculateBrokerageFee">
                                            <span>自</span>
                                        </button>
                                        <!-- Calculate brokerage fee - End -->

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </li>
</script>