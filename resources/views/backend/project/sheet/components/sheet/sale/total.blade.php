<div class="row mx-0">
    @if( !empty( $label ))
        <div class="py-2 px-3 col-xl-auto bg-candy d-flex align-items-center">
            <span>{{ $label }}</span>
        </div>
    @endif
    @if( !empty( $total ))
        <div class="py-3 px-3 col-xl">
            <template v-for="total in [ {{ $total }} ]">
                <div class="row mx-n1">

                    <!-- Main total - Start -->
                    <template v-if="total.main">
                        <div class="px-1 col-auto total-main">
                            <span>@{{ total.main | numeralFormat }} 円</span>
                        </div>
                    </template>
                    <!-- Main total - End -->

                    <!-- Additional total - Start -->
                    <template v-if="total.additional">
                        <div class="px-1 col-auto total-additional">
                            <span>(@{{ total.additional | floorDecimal(2) }} %)</span>
                        </div>
                    </template>
                    <!-- Additional - End -->
                </div>
            </template>
        </div>
    @endif
</div>