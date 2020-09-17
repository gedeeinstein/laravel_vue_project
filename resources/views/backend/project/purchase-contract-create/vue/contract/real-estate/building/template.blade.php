<script type="text/x-template" id="contract-building">
    <div class="product-building">

        <!-- Heading - Start -->
        <div class="heading rounded bg-grey p-2 mb-2 mb-md-3">
            <div class="row mx-n1 fw-b" :class="{ 'text-grey': isCompleted }">
                <div class="px-1 col-auto">商品用建物：</div>
                <div class="px-1 col-auto" v-if="numberLabel">
                    <span>@{{ numberLabel }}</span>
                </div>
            </div>
        </div>
        <!-- Heading - End -->

        <div class="px-md-2">

            <!-- Building number - Start -->
            <template v-for="name in [ prefix+ 'building-number' ]">
                <div class="form-group row mb-2 mb-md-3">
                    <label :for="name" class="col-md-3 col-form-label">
                        <span class="fw-n" :class="{ 'text-grey': isCompleted }">建築確認番号</span>
                    </label>
                    <div class="col-md">
                        <div class="row">
                            <div class="col-lg-8">

                                @component("{$component->preset}.text")
                                    @slot( 'disabled', 'isDisabled' )
                                    @slot( 'model', 'entry.building_number' )
                                @endcomponent

                            </div>
                        </div>
                    </div>
                </div>
            </template>
            <!-- Building number - End -->

            <!-- House display - Start -->
            <template v-for="name in [ prefix+ 'house-display' ]">
                <div class="form-group row mb-2 mb-md-3">
                    <label :for="name" class="col-md-3 col-form-label">
                        <div class="row">
                            <div class="col-auto">
                                <span class="fw-n" :class="{ 'text-grey': isCompleted }">住居表示</span>
                            </div>
                            <div class="col-auto d-block d-md-none">
                                <label :for="name" class="m-0">
                                    <span class="fs-12 fw-n" :class="{ 'text-grey': isCompleted }">
                                        <span>@{{ cityLabel }}</span>
                                    </span>
                                </label>
                            </div>
                        </div>
                    </label>
                    <div class="col-md">
                        <div class="row">
                            <div class="col-lg-8">
                                <div class="row mx-n2">
                                    <label :for="name" class="px-2 col-auto col-form-label d-none d-md-block">
                                        <span class="fs-12 fw-n" :class="{ 'text-grey': isCompleted }">
                                            <span>@{{ cityLabel }}</span>
                                        </span>
                                    </label>
                                    <div class="col">

                                        @component("{$component->preset}.text")
                                            @slot( 'disabled', 'isDisabled' )
                                            @slot( 'model', 'entry.building_address' )
                                        @endcomponent

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </template>
            <!-- House display - End -->
            
        </div>
    </div>
</script>
