{{-- We need checklist reference --}}
<template v-for="checklist in [ sheet.checklist ]">
    @php 
        // ------------------------------------------------------------------
        $baseComponent = "backend.project.sheet.components";
        $section = "{$include->sheet}.fields.purchasing";
        // ------------------------------------------------------------------
        $sheetIndex = "'sheet-' + ( sheetIndex +1 )";
        $prefix = (object) array(
            'input' => "{$sheetIndex} + '-stock-'", // Input name prefix
            'additional' => "{$sheetIndex} + '-stock-additional-' +additionalIndex+ '-'" // Additional input prefix
        );
        // ------------------------------------------------------------------
        $purchasing = (object) array(
            // --------------------------------------------------------------
            'groups'       => "{$include->sheet}.includes.groups.purchasing",
            // --------------------------------------------------------------
            'common'       => "{$baseComponent}.common",
            'components'   => "{$baseComponent}.sheet.purchasing",
            // --------------------------------------------------------------
            'label'       => "{$baseComponent}.sheet.purchasing.label",
            'column'       => "{$baseComponent}.sheet.purchasing.column",
            'button'       => "{$baseComponent}.sheet.purchasing.button",
            // --------------------------------------------------------------
            'row'          => "{$baseComponent}.sheet.purchasing.row",
            'total'        => "{$baseComponent}.sheet.purchasing.total",
            'heading'      => "{$baseComponent}.sheet.purchasing.heading",
            // --------------------------------------------------------------
            'tax'          => "{$section}.tax",
            'other'        => "{$section}.other",
            'total'        => "{$section}.total",
            'survey'       => "{$section}.survey",
            'finance'      => "{$section}.finance",
            'purchase'     => "{$section}.purchase",
            'construction' => "{$section}.construction",
            'registration' => "{$section}.registration"
            // --------------------------------------------------------------
        );
        // ------------------------------------------------------------------
        $lang = (object) array(
            'label'   => 'project.sheet.purchasing.label',
            'option'  => 'project.sheet.purchasing.option',
            'heading' => 'project.sheet.purchasing.heading'
        );
        // ------------------------------------------------------------------
    @endphp

    <!-- Purchasing Procurement / Purchase - Start -->
    <template v-if="stock.procurements" v-for="purchase in [ stock.procurements ]">
        @include("{$purchasing->groups}.purchase")
    </template>
    <!-- Purchasing Procurement / Purchase - Start -->


    <!-- Purchasing Registration - Start -->
    <template v-if="stock.registers" v-for="registration in [ stock.registers ]">
        @include("{$purchasing->groups}.registration")
    </template>
    <!-- Purchasing Registration - Start -->


    <!-- Purchasing Finance - Start -->
    <template v-if="stock.finances" v-for="finance in [ stock.finances ]">
        @include("{$purchasing->groups}.finance")
    </template>
    <!-- Purchasing Finance - Start -->


    <!-- Purchasing Tax - Start -->
    <template v-if="stock.taxes" v-for="tax in [ stock.taxes ]">
        @include("{$purchasing->groups}.tax")
    </template>
    <!-- Purchasing Tax - Start -->


    <!-- Purchasing Construction - Start -->
    <template v-if="stock.constructions" v-for="construction in [ stock.constructions ]">
        @include("{$purchasing->groups}.construction")
    </template>
    <!-- Purchasing Construction - Start -->


    <!-- Purchasing Survey - Start -->
    <template v-if="stock.surveys" v-for="survey in [ stock.surveys ]">
        @include("{$purchasing->groups}.survey")
    </template>
    <!-- Purchasing Survey - Start -->


    <!-- Purchasing Other - Start -->
    <template v-if="stock.others" v-for="other in [ stock.others ]">
        @include("{$purchasing->groups}.other")
    </template>
    <!-- Purchasing Other - Start -->


    <!-- Purchasing Total - Start -->
    @include("{$purchasing->groups}.total")
    <!-- Purchasing Other - Start -->


</template>