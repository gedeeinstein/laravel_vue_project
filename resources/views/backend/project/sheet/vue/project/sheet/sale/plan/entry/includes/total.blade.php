<ul class="list-group mt-3 mt-md-4">
    <li class="list-group-item p-0">
        <div class="row mx-0">
            <div class="px-0 col-md-6">

                <!-- Gross profit plan - Start -->
                @component("{$component->sheet}.sale.total")
                    @slot( 'label', '予定粗利' )
                    @slot( 'total', 'grossProfitPlan' )
                @endcomponent
                <!-- Gross profit plan - End -->

            </div>
            <div class="px-0 col-md-6">

                <!-- Gross profit total plan - Start -->
                @component("{$component->sheet}.sale.total")
                    @slot( 'label', '予定総粗利' )
                    @slot( 'total', 'grossProfitTotal' )
                @endcomponent
                <!-- Gross profit total plan - End -->
                
            </div>
        </div>
    </li>
</ul>