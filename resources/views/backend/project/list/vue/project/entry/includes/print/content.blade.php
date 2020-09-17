<div class="entry-content">
    @php $columns = 'backend.project.list.vue.project.entry.columns' @endphp
    <div class="row mx-0">
        <div class="px-0" :class="$store.state.print ? 'col-md-9': 'col-md-7'">
            <div class="row mx-0">

                <!-- Project ID - Start -->
                <div class="px-0 col-md-75px column">
                    <div class="row h-100 mx-0">
                        <div class="px-0 col">
                            @include("{$columns}.project-id")
                        </div>
                    </div>
                </div>
                <!-- Project ID - End -->

                <!-- Port number - Start -->
                <div class="px-0 col-md-120px column">
                    <div class="row mx-0 h-100">
                        <div class="px-0 col d-flex align-items-center">
                            @include("{$columns}.port-number")
                        </div>
                    </div>
                </div>
                <!-- Port number - End -->

                <!-- Project status & offder date - Start -->
                <div class="px-0 col-md column">
                    <div class="row mx-0 h-100">
                        <div class="px-0 col">
                            @include("{$columns}.project-status")
                        </div>
                    </div>
                </div>
                <!-- Project status & offder date - End -->

                <!-- Project type - Start -->
                <div class="px-0 col-md column">
                    <div class="row mx-0 h-100">
                        <div class="px-0 col d-flex align-items-center">
                            @include("{$columns}.project-type")
                        </div>
                    </div>
                </div>
                <!-- Project type - End -->

                <!-- Contract date - Start -->
                <div class="px-0 col-md-100px column">
                    
                    <div class="row mx-0 h-100">
                        <div class="px-0 col d-flex align-items-center justify-content-lg-center">
                            @include("{$columns}.contract-date")
                        </div>
                    </div>
                </div>
                <!-- Contract date - End -->

            </div>
        </div>
        <div class="px-0" :class="$store.state.print ? 'col-md-3': 'col-md-5'">
            <div class="row mx-0 h-100">

                <!-- Contract payment date - Start -->
                <div class="px-0 col-md-100px column">
                    <div class="row mx-0 h-100">
                        <div class="px-0 col d-flex align-items-center justify-content-lg-center">
                            @include("{$columns}.contract-payment-date")
                        </div>
                    </div>
                </div>
                <!-- Contract payment date - End -->

                <!-- Project title - Start -->
                <div class="px-0 col-md column">
                    <div class="row mx-0 h-100">
                        <div class="px-0 col d-flex align-items-center">
                            @include("{$columns}.project-title")
                        </div>
                    </div>
                </div>
                <!-- Project title - End -->

                <!-- Organizer company - Start -->
                <div v-if="!$store.state.print" class="px-0 col-md-120px column">
                    <div class="row mx-0 h-100">
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