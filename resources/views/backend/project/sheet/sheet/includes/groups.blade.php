<template v-for="accordion in [ 'sheet-accordion-' +( sheetIndex +1 )]">
    <div :class="'accordion sheet-accordion ' +accordion">
        @php $group = "{$include->sheet}.includes.groups" @endphp

        <!-- Project Checklist - Start -->
        @component("{$components}.sheet.group")
            @slot( 'expanded', true )
            @slot( 'model', 'sheet.checklist' )
            @slot( 'alias', 'checklist' )
            @slot( 'title', __( 'project.sheet.tabs.group.checklist' ))
            @slot( 'id', "'accordion-group-checklist-' +( sheetIndex +1 )" )
            @slot( 'content' ) @include("{$group}.checklist") @endslot
        @endcomponent
        <!-- Project Checklist - End -->


        <!-- Expense Department - Start -->
        @component("{$components}.sheet.group")
            @slot( 'expanded', false )
            @slot( 'model', 'sheet.stock' )
            @slot( 'alias', 'expense' )
            @slot( 'title', __( 'project.sheet.tabs.group.expense' ))
            @slot( 'id', "'accordion-group-expense-' +( sheetIndex +1 )" )
            @slot( 'content' )
                <sheet-expense v-model="expense" :sheet="sheet" :sheet-values="preset.sheetValues" :project="project" :disabled="status.loading"></sheet-expense>
            @endslot
        @endcomponent
        <!-- Expense Department - End -->


        <!-- Sales Department - Start -->
        @component("{$components}.sheet.group")
            @slot( 'expanded', false )
            @slot( 'model', 'sheet.sale' )
            @slot( 'alias', 'sale' )
            @slot( 'title', __( 'project.sheet.tabs.group.sale' ))
            @slot( 'id', "'accordion-group-sale-' +( sheetIndex +1 )" )
            @slot( 'content' )
                <sheet-sale v-model="sale" :sheet="sheet" :index="sheetIndex" :disabled="status.loading"></sheet-sale>
            @endslot
        @endcomponent
        <!-- Sales Department - End -->

    </div>
</template>