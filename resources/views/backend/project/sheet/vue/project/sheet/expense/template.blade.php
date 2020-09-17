<script type="text/x-template" id="sheet-expense">
    <div class="sheet-expenses">

        <!-- Purchase expense - Start -->
        <template v-if="entry.procurements" v-for="purchase in [ entry.procurements ]">
            <expense-purchase v-model="purchase" :sheet="sheet" :project="project" :expense="expense" :disabled="isDisabled"
                @totalBudget="updateTotalBudget" @totalAmount="updateTotalAmount">
            </expense-purchase>
        </template>
        <!-- Purchase expense  - End -->


        <!-- Purchasing Registration - Start -->
        <template v-if="entry.registers" v-for="registration in [ entry.registers ]">
            <expense-registration v-model="registration" :sheet="sheet" :project="project" :expense="expense" :disabled="isDisabled"
                @totalBudget="updateTotalBudget" @totalAmount="updateTotalAmount">
            </expense-registration>
        </template>
        <!-- Purchasing Registration - End -->


        <!-- Purchasing Finance - Start -->
        <template v-if="entry.finances" v-for="finance in [ entry.finances ]">
            <expense-finance v-model="finance" :sheet="sheet" :project="project" :expense="expense" :disabled="isDisabled"
                @totalBudget="updateTotalBudget" @totalAmount="updateTotalAmount">
            </expense-finance>
        </template>
        <!-- Purchasing Finance - End -->


        <!-- Purchasing Tax - Start -->
        <template v-if="entry.taxes" v-for="tax in [ entry.taxes ]">
            <expense-tax v-model="tax" :sheet="sheet" :project="project" :expense="expense" :disabled="isDisabled"
                @totalBudget="updateTotalBudget" @totalAmount="updateTotalAmount">
            </expense-tax>
        </template>
        <!-- Purchasing Tax - End -->


        <!-- Purchasing Construction - Start -->
        <template v-if="entry.constructions" v-for="construction in [ entry.constructions ]">
            <expense-construction v-model="construction" :sheet="sheet" :sheet-values="sheetValues" :project="project" :expense="expense" 
                :disabled="isDisabled" @totalBudget="updateTotalBudget" @totalAmount="updateTotalAmount">
            </expense-construction>
        </template>
        <!-- Purchasing Construction - End -->


        <!-- Purchasing Survey - Start -->
        <template v-if="entry.surveys" v-for="survey in [ entry.surveys ]">
            <expense-survey v-model="survey" :sheet="sheet" :project="project" :expense="expense" :disabled="isDisabled"
                @totalBudget="updateTotalBudget" @totalAmount="updateTotalAmount">
            </expense-survey>
        </template>
        <!-- Purchasing Survey - End -->


        <!-- Purchasing Other - Start -->
        <template v-if="entry.others" v-for="other in [ entry.others ]">
            <expense-other v-model="other" :sheet="sheet" :project="project" :expense="expense" :disabled="isDisabled"
                @totalBudget="updateTotalBudget" @totalAmount="updateTotalAmount">
            </expense-other>
        </template>
        <!-- Purchasing Other - End -->

        @php 
            $component->expense = 'backend.project.sheet.components.sheet.expense';
            $lang = (object) array(
                'label'   => 'project.sheet.expense.label',
                'option'  => 'project.sheet.expense.option',
                'heading' => 'project.sheet.expense.heading'
            );
        @endphp

        <!-- Purchasing Total - Start -->
        <ul class="list-group">
            <li class="list-group-item p-0">
                <div class="row mx-0">
                    <div class="px-0 col-md-6">

                        <!-- Budget total - Start -->
                        @component( "{$component->expense}.total" )
                            @slot( 'label', __( "{$lang->label}.total.budget" ))
                            @slot( 'total', '{ main: totalBudget, tsubo: totalBudgetTsubo }' )
                        @endcomponent
                        <!-- Budget total - End -->

                    </div>
                    <div class="px-0 col-md-6">

                        <!-- Decided amount total - Start -->
                        @component( "{$component->expense}.total" )
                            @slot( 'label', __( "{$lang->label}.total.amount" ))
                            @slot( 'total', '{ main: totalAmount, tsubo: totalAmountTsubo }' )
                        @endcomponent
                        <!-- Decided amount total - End -->
                        
                    </div>
                </div>
            </li>
        </ul>
        <!-- Purchasing Other - End -->


    </div>
</script>