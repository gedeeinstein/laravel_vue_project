<template v-if="additional && additional.length">
    <ul class="list-group mt-2 mt-md-3">
        <template v-for="category in additional">
        
            <!-- Additional Q&A Categories - Start -->
            <template v-if="category && category.questions && category.questions.length">
                <li class="list-group-item">
                    
                    <template v-if="category.name">
                        <label>@{{ category.name }}</label>
                    </template>
            
                    <template v-for="( question, questionIndex ) in category.questions">

                        <!-- Additional question entry - Start -->
                        <additional-question v-model="question" :index="questionIndex" :disabled="status.loading"></additional-question>
                        <!-- Additional question entry - End -->

                    </template>
                </li>
            </template>
            <!-- Additional Q&A Categories - End -->
            
        </template>
    </ul>
</template>