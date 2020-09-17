<div class="row mx-n1">
    <div class="px-1 col-md-3 d-none d-md-block">
        <ul class="list-group h-100 d-flex flex-column">
            <li class="list-group-item flex-grow-1 rounded-0">区画数</li>
            <li class="list-group-item flex-grow-1">1区画平均面積</li>
            <li class="list-group-item flex-grow-1">利益率</li>
            <li class="list-group-item flex-grow-1">販売坪単価</li>
            <li class="list-group-item flex-grow-1 rounded-0">1区画平均価格</li>
        </ul>
    </div>
    
    <!-- Sale calculators - Start -->
    <template v-if="entry.calculators && entry.calculators.length">

        <sale-calculator v-for="( calculator, calculatorIndex ) in entry.calculators" 
            v-model="calculator" :index="calculatorIndex" :sheet="sheet" :disabled="isDisabled">
        </sale-calculator>
        
    </template>
    <!-- Sale calculators - End -->

</div>