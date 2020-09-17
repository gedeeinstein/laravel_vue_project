<div class="entry-controls fs-13">
    <div class="px-2 py-2 column">
        <div class="row mx-n1 text-grey">

            <!-- PJ Sheet - Start -->
            <div class="px-1 col-auto">
                <a target="_blank" :href="getURL('sheet')">PJシート＆チェックリスト</a>
            </div>
            <!-- PJ Sheet - End -->

            <div class="px-1 col-auto">|</div>

            <!-- Assist A - Start -->
            <div class="px-1 col-auto">
                <a target="_blank" :href="getURL('assistA')">アシストA</a>
            </div>
            <!-- Assist A - End -->
            
            <div class="px-1 col-auto">|</div>

            <!-- Assist B - Start -->
            <div class="px-1 col-auto">
                <a target="_blank" :href="getURL('assistB')">アシストB</a>
            </div>
            <!-- Assist B - End -->

            <div class="px-1 col-auto">|</div>

            <!-- Purchase Sale - Start -->
            <div class="px-1 col-auto">
                <a target="_blank" :href="getURL('purchaseSale')">仕入営業</a>
            </div>
            <!-- Purchase Sale - End -->

            <div class="px-1 col-auto">|</div>

            <!-- Purchase - Start -->
            <div class="px-1 col-auto">
                <a target="_blank" :href="getURL('purchase')">仕入買付</a>
            </div>
            <!-- Purchase - End -->

            <div class="px-1 col-auto">|</div>

            <!-- Purchase Contract - Start -->
            <div class="px-1 col-auto">
                <a target="_blank" :href="getURL('purchaseContract')">仕入契約</a>
            </div>
            <!-- Purchase Contract - End -->

            <div class="px-1 col-auto">|</div>

            <!-- Expense - Start -->
            <div class="px-1 col-auto">
                <a target="_blank" :href="getURL('expense')">支出の部</a>
            </div>
            <!-- Expense - End -->

            <div class="px-1 col-auto">|</div>

            <!-- Transaction Ledger - Start -->
            <div class="px-1 col-auto">
                <a target="_blank" :href="getURL('ledger')">取引台帳</a>
            </div>
            <!-- Transaction Ledger - End -->

            <div class="px-1 col-auto">|</div>

            <div class="px-1 col-auto">
                <a href="javascript:;" class="text-red" @click="remove">
                    <div class="row mx-n1">
                        <span class="px-1 col-auto" v-if="status.removal.loading">
                            <i class="fas fa-spinner fa-spin"></i>
                        </span>
                        <span class="px-1 col-auto">削除</span>
                    </div>
                </a>
            </div>
        </div>

    </div>
</div>