<div class="entry-controls fs-13">
    <div class="px-2 py-2 column">
        <div class="row mx-n1 text-grey">

            <!-- PJ Sheet - Start -->
            <div class="px-1 col-auto">
                <a target="_blank" :href="sheetURL">PJシート＆チェックリスト</a>
            </div>
            <!-- PJ Sheet - End -->

            <div class="px-1 col-auto">|</div>

            <!-- Assist A - Start -->
            <div class="px-1 col-auto">
                <a target="_blank" :href="sheetURL">マスター設定</a>
            </div>
            <!-- Assist A - End -->
            
            <div class="px-1 col-auto">|</div>

            <!-- Assist B - Start -->
            <div class="px-1 col-auto">
                <a target="_blank" :href="masBasicURL">基本データ</a>
            </div>
            <!-- Assist B - End -->

            <div class="px-1 col-auto">|</div>

            <!-- Purchase Sale - Start -->
            <div class="px-1 col-auto">
                <a target="_blank" :href="expenseURL">支出の部</a>
            </div>
            <!-- Purchase Sale - End -->

            <div class="px-1 col-auto">|</div>

            <!-- Purchase - Start -->
            <div class="px-1 col-auto">
                <a target="_blank" :href="masFinanceURL">融資・入出金</a>
            </div>
            <!-- Purchase - End -->

            <div class="px-1 col-auto">|</div>

            <!-- Purchase Contract - Start -->
            <div class="px-1 col-auto">
                <a target="_blank" href="{{ route('master.section.list.index') }}">事業精算</a>
            </div>
            <!-- Purchase Contract - End -->

        </div>

    </div>
</div>