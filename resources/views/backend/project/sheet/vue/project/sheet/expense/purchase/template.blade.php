<script type="text/x-template" id="expense-purchase">
    <ul class="expense-purchase list-group mb-3 mb-md-4">

        @php 
            $component->expense = 'backend.project.sheet.components.sheet.expense';
            $lang = (object) array(
                'label'   => 'project.sheet.expense.label',
                'option'  => 'project.sheet.expense.option',
                'heading' => 'project.sheet.expense.heading'
            );
        @endphp

        <!-- Group heading - Start -->
        @component("{$component->expense}.heading") @endcomponent
        <!-- Group heading - End -->
    
        <!-- Purchase Price - Start -->
        @relativeInclude('includes.price')
        <!-- Purchase Price - End -->
    
        <!-- Purchase Broker Commission - Start -->
        @relativeInclude('includes.commission')
        <!-- Purchase Broker Commission - End -->
    
    </ul>
</script>