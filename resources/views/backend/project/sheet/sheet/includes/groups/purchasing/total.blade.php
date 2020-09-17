<ul class="list-group">
    <li class="list-group-item p-0">
        <div class="row mx-0">
            <div class="px-0 col-md-6">

                <!-- Budget total - Start -->
                @component("{$purchasing->components}.total")
                    @slot( 'label', __( "{$lang->label}.total.budget" ))
                    @slot( 'total', 'getTotalBudget( sheet, checklist )' )
                @endcomponent
                <!-- Budget total - End -->

            </div>
            <div class="px-0 col-md-6">

                <!-- Decided amount total - Start -->
                @component("{$purchasing->components}.total")
                    @slot( 'label', __( "{$lang->label}.total.amount" ))
                    @slot( 'total', 'getTotalAmount( sheet, checklist )' )
                @endcomponent
                <!-- Decided amount total - End -->
                
            </div>
        </div>
    </li>
</ul>