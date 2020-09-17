<div class="entry-content">
    @php $columns = 'backend.project.list.vue.project.entry.columns' @endphp
    <div class="row mx-0">
        <div class="px-0" :class="$store.state.print ? 'col-lg-9': 'col-lg-7'">
            <div class="row mx-0">

                <!-- Project ID - Start -->
                <div class="px-0 col-lg-75px column">
                    <div class="row h-100 mx-0">
                        <div class="px-0 col-100px col-sm-150px d-block d-lg-none">
                            <div class="px-2 py-2">
                                <strong>ID</strong>
                            </div>
                        </div>
                        <div class="px-0 col">
                            @include("{$columns}.project-id")
                        </div>
                    </div>
                </div>
                <!-- Project ID - End -->

                <!-- Port number - Start -->
                <div class="px-0 col-lg-120px column">
                    <div class="row mx-0 h-100">
                        <div class="px-0 col-100px col-sm-150px d-block d-lg-none">
                            <div class="px-2 py-2">
                                <strong>Port番号</strong>
                            </div>
                        </div>
                        <div class="px-0 col d-flex align-items-center">
                            @include("{$columns}.port-number")
                        </div>
                    </div>
                </div>
                <!-- Port number - End -->

                <!-- Project status & offder date - Start -->
                <div class="px-0 col-lg column">
                    <div class="row mx-0 h-100">
                        <div class="px-0 col-100px col-sm-150px d-block d-lg-none">
                            <div class="px-2 py-2">
                                <strong>状況/引合日</strong>
                            </div>
                        </div>
                        <div class="px-0 col">
                            @include("{$columns}.project-status")
                        </div>
                    </div>
                </div>
                <!-- Project status & offder date - End -->

                <!-- Project type - Start -->
                <div class="px-0 col-lg column">
                    <div class="row mx-0 h-100">
                        <div class="px-0 col-100px col-sm-150px d-block d-lg-none">
                            <div class="px-2 py-2">
                                <strong>物件種目</strong>
                            </div>
                        </div>
                        <div class="px-0 col d-flex align-items-center">
                            @include("{$columns}.project-type")
                        </div>
                    </div>
                </div>
                <!-- Project type - End -->

                <!-- Contract date - Start -->
                <div class="px-0 col-lg-100px column">
                    
                    <div class="row mx-0 h-100">
                        <div class="px-0 col-100px col-sm-150px d-block d-lg-none">
                            <div class="px-2 py-2">
                                <strong>仕入契約日</strong>
                            </div>
                        </div>
                        <div class="px-0 col d-flex align-items-center justify-content-lg-center">
                            @include("{$columns}.contract-date")
                        </div>
                    </div>
                </div>
                <!-- Contract date - End -->

            </div>
        </div>
        <div class="px-0" :class="$store.state.print ? 'col-lg-3': 'col-lg-5'">
            <div class="row mx-0 h-100">

                <!-- Contract payment date - Start -->
                <div class="px-0 col-lg-100px column">
                    <div class="row mx-0 h-100">
                        <div class="px-0 col-100px col-sm-150px d-block d-lg-none">
                            <div class="px-2 py-2">
                                <strong>仕入決済日</strong>
                            </div>
                        </div>
                        <div class="px-0 col d-flex align-items-center justify-content-lg-center">
                            @include("{$columns}.contract-payment-date")
                        </div>
                    </div>
                </div>
                <!-- Contract payment date - End -->

                <!-- Project title - Start -->
                <div class="px-0 col-lg column">
                    <div class="row mx-0 h-100">
                        <div class="px-0 col-100px col-sm-150px d-block d-lg-none">
                            <div class="px-2 py-2">
                                <strong>物件名称</strong>
                            </div>
                        </div>
                        <div class="px-0 col d-flex align-items-center">
                            @include("{$columns}.project-title")
                        </div>
                    </div>
                </div>
                <!-- Project title - End -->

                <!-- Organizer company - Start -->
                <div v-if="!$store.state.print" class="px-0 col-lg-120px column">
                    <div class="row mx-0 h-100">
                        <div class="px-0 col-100px col-sm-150px d-block d-lg-none">
                            <div class="px-2 py-2">
                                <strong>主事業者</strong>
                            </div>
                        </div>
                        <div class="px-0 col d-flex align-items-center justify-content-lg-center">
                            @include("{$columns}.organizer")
                        </div>
                    </div>
                </div>
                <!-- Organizer company - End -->

            </div>
        </div>
    </div>
</div>