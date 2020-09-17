@php 
    $checklist = (object) array(
        'fields'       => "{$include->sheet}.fields.checklist",
        'option'       => "{$include->sheet}.fields.checklist.option",
        'group'        => "{$include->sheet}.fields.checklist.qna.group",
        'question'     => "{$include->sheet}.fields.checklist.qna.question",
        'division'     => "{$include->sheet}.fields.checklist.qna.question.division",
        'driveway'     => "{$include->sheet}.fields.checklist.qna.question.driveway",
        'demolition'   => "{$include->sheet}.fields.checklist.qna.question.demolition",
        'construction' => "{$include->sheet}.fields.checklist.qna.question.construction"
    );
@endphp
<template v-for="checklist in [ sheet.checklist ]">
    <div class="row mx-n3">
    
        <!-- Breakthrough Rate - Start -->
        <div class="px-3 col-md-4">
            @include("{$checklist->fields}.breakthrough")
        </div>
        <!-- Breakthrough Rate - Start -->
    
        <!-- Effective area - Start -->
        <div class="px-3 col-md-8">
            @include("{$checklist->fields}.effective-area")
        </div>
        <!-- Effective area - End -->
    
    </div>
    <div class="row mx-n3">

        <!-- Loan amount - Start -->
        <div class="px-3 col-md-4">
            @include("{$checklist->fields}.loan")
        </div>
        <!-- Loan amount - Start -->

        <!-- Brokerage Fee - Start -->
        <div class="px-3 col-md-4">
            @include("{$checklist->fields}.brokerage-fee")
        </div>
        <!-- Brokerage Fee - End -->

        <!-- Resale Brokerage Fee - Start -->
        <div class="px-3 col-md-4">
            @include("{$checklist->fields}.resale-fee")
        </div>
        <!-- Resale Brokerage Fee - End -->

    </div>

    <!-- Checklist Options - Start -->
    <ul class="list-group mt-2 mx-n4">

        <!-- Sales Area - Start -->
        <li class="list-group-item">
            @include("{$checklist->option}.sales-area")
        </li>
        <!-- Sales Area - End -->

        <!-- Building Demolition - Start -->
        <transition name="paste-in">
            <li class="list-group-item" v-if="checklist.sales_area">
                @include("{$checklist->option}.building-demolition")
            </li>
        </transition>
        <!-- Building Demolition - End -->

        <!-- Retaining Wall Demolition - Start -->
        <transition name="paste-in">
            <li class="list-group-item" v-if="checklist.sales_area">
                @include("{$checklist->option}.retaining-wall-demolition")
            </li>
        </transition>
        <!-- Retaining Wall Demolition - End -->

        <!-- Construction Work - Start -->
        <transition name="paste-in">
            <li class="list-group-item" v-if="checklist.sales_area">
                @include("{$checklist->option}.construction-work")
            </li>
        </transition>
        <!-- Construction Work - End -->

        <!-- Driveway / Private Road - Start -->
        <transition name="paste-in">
            <li class="list-group-item" v-if="checklist.sales_area">
                @include("{$checklist->option}.driveway")
            </li>
        </transition>
        <!-- Driveway / Private Road - End -->

    </ul>
    <!-- Checklist Options - End -->


    <!-- Checklist Q&A - Start -->
    <transition name="paste-in">
        <template v-if="checklist.sales_area">

            <!-- Checklist Q&A - Start -->
            <ul class="list-group mt-3 mx-n2 mt-md-4 mx-md-0">
                @php $column = (object) array(
                    'left' => 'col-md-6',
                    'right' => 'col-md-6'
                ) @endphp

                <!-- Division Group - Start -->
                <li class="list-group-item">
                    @include("{$checklist->group}.division")
                </li>
                <!-- Division Group - End -->

                <!-- Demolition Group - Start -->
                <transition name="paste-in">
                    <li class="list-group-item" v-if="checklist.building_demolition_work">
                        @include("{$checklist->group}.demolition")
                    </li>
                </transition>
                <!-- Demolition Group - End -->

                <!-- Construction Non Development - Start -->
                <transition name="paste-in">
                    <li class="list-group-item" v-if="2 === checklist.construction_work || ( 3 === checklist.construction_work && project.overall_area < 1000 )">
                        @include("{$checklist->group}.construction-non-dev")
                    </li>
                </transition>
                <!-- Construction Non Development - Start -->

                <!-- Construction Development - Start -->
                <li class="list-group-item">
                    @include("{$checklist->group}.construction-dev")
                </li>
                <!-- Construction Development - Start -->

                <!-- Driveway - Start -->
                <transition name="paste-in">
                    <li class="list-group-item" v-if="1 === checklist.driveway">
                        @include("{$checklist->group}.driveway")
                    </li>
                </transition>
                <!-- Driveway - Start -->

            </ul>
            <!-- Checklist Q&A - End -->

        </template>
    </transition>
    <!-- Checklist options - End -->

</template>




