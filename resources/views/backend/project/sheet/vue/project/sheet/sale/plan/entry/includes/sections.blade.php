<ul class="list-group mx-n4 mx-md-0">

    <!-- Section header - Start -->
    <li class="list-group-item list-group-item-secondary py-1 py-lg-2">
        <div class="row d-none d-lg-flex">
            <div class="col-lg-7">
                <div class="row">
                    <div class="text-center col-lg-75px">区画</div>
                    <div class="text-center col-lg-2">参考面積</div>
                    <div class="text-center col-lg">予定面積</div>
                    <div class="text-center col-lg">販売価格</div>
                    <div class="text-center col-lg-2">坪単価</div>
                </div>
            </div>
            <div class="text-center col-lg-5">
                <span>仲介手数料</span>
            </div>
        </div>
    </li>
    <!-- Section header - End -->

    <!-- Sale sections - Start -->
    <template v-if="entry.sections && entry.sections.length">

        <!-- Section entries - Start -->
        <div v-for="( section, sectionIndex ) in entry.sections" :key="sectionKey">
            <plan-section v-model="section" :index="sectionIndex" :plan="entry" :sheet="sheet" :disabled="isDisabled"
                @removed="removeSection( sectionIndex )">
            </plan-section>
        </div>
        <!-- Section entries - End -->


        <!-- Section total - Start -->
        <li class="section-total list-group-item px-3 py-3 py-lg-0 border-top-0">
            <div class="row mx-n1">
                <div class="px-1 py-0 py-lg-2 col-lg-7 col-xl-8">
                    <div class="row mx-n1">
                        <div class="px-1 col-lg-75px mb-2 mb-lg-0">

                            <!-- Divisions number - Start -->
                            <div class="form-group row mb-0 h-100">
                                <div class="col-75px col-sm-100px d-block d-lg-none">区画</div>
                                <div class="col d-flex align-items-center">計</div>
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
                                        @slot( 'name', "prefix + 'reference-area-total'" )
                                        @slot( 'value', 'referenceAreaTotal' )
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
                                        @slot( 'disabled', true )
                                        @slot( 'name', "prefix + 'planned-area-total'" )
                                        @slot( 'value', 'plannedAreaTotal' )
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
                                        @slot( 'disabled', true )
                                        @slot( 'name', "prefix + 'unit-selling-price-total'" )
                                        @slot( 'model', 'unitSellingPriceTotal' )
                                    @endcomponent
                                </div>
                            </div>
                            <!-- Unit selling price - End -->
                            
                        </div>
                        <div class="px-1 col-lg-3"></div>
                    </div>
                </div>
            </div>
        </li>
        <!-- Section total - End -->

    </template>
    <!-- Sale sections - End -->
</ul>