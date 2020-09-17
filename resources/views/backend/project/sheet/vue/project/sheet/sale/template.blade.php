<script type="text/x-template" id="sheet-sale">
    <div class="sheet-sale-est">
    
        <div class="row fs-14">
            <div class="col-auto"><strong>参考値</strong></div>
            <div class="col-auto">プラン策定用の数字を簡易計算</div>
        </div>

        <!-- Sale calculator - Start -->
        <div class="row mt-4">
            <div class="col-lg-10 col-xl-8">
                @relativeInclude('includes.calculator')
            </div>
        </div>
        <!-- Sale calculator - End -->

        <!-- Sale plans - Start -->
        @relativeInclude('includes.plans')
        <!-- Sale plans - End -->

    </div>
</script>