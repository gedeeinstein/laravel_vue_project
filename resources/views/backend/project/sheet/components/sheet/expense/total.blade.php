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
                        <div class="px-1 col-auto total">
                            <span>@{{ total.main | numeralFormat }}</span>
                            <span>円</span>
                        </div>
                    </template>
                    <!-- Main total - End -->

                    <!-- Tsubo total - Start -->
                    <template v-if="total.tsubo">
                        <div class="px-1 col-auto total-tsubo">
                            <span>(</span><span>@lang( 'project.sheet.expense.label.total.tsubo' )</span>
                            <span>@{{ total.tsubo | numeralFormat }}</span>
                            <span>円)</span>
                        </div>
                    </template>
                    <!-- Tsubo total - End -->
                </div>
            </template>
        </div>
    @endif
</div>