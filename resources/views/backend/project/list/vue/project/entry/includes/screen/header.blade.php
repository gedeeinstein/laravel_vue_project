<div class="entry-header d-none d-lg-block">
    <div class="row mx-0">
        <div class="px-0" :class="$store.state.print ? 'col-lg-9': 'col-lg-7'">
            <div class="row mx-0">
                <div class="px-0 col-lg-75px column bg-light">
                    <div class="py-2 px-2">
                        <strong>ID</strong>
                    </div>
                </div>
                <div class="px-0 col-lg-120px column bg-light">
                    <div class="py-2 px-2">
                        <strong>Port番号</strong>
                    </div>
                </div>
                <div class="px-0 col column bg-light">
                    <div class="py-2 px-2">
                        <strong>状況/引合日</strong>
                    </div>
                </div>
                <div class="px-0 col column bg-light">
                    <div class="py-2 px-2">
                        <strong>物件種目</strong>
                    </div>
                </div>
                <div class="px-0 col-lg-100px column bg-light">
                    <div class="py-2 px-2">
                        <strong>仕入契約日</strong>
                    </div>
                </div>
            </div>
        </div>
        <div class="px-0" :class="$store.state.print ? 'col-lg-3': 'col-lg-5'">
            <div class="row mx-0">
                <div class="px-0 col-lg-100px column bg-light">
                    <div class="py-2 px-2">
                        <strong>仕入決済日</strong>
                    </div>
                </div>
                <div class="px-0 col column bg-light">
                    <div class="py-2 px-2">
                        <strong>物件名称</strong>
                    </div>
                </div>
                <div v-if="!$store.state.print" class="px-0 col-lg-120px column bg-light">
                    <div class="py-2 px-2">
                        <strong>主事業者</strong>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>