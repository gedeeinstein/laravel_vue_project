<div class="qna-group">
    @php $label = 'project.sheet.checklist.group.construction.non_development' @endphp
    <div class="fw-b mb-3 mb-md-2">
        <span>Q&A</span>
        <strong>@lang("{$label}.heading")</strong>
        <span class="fw-n">@lang("{$label}.subtitle")</span>
    </div>
    
    <!-- Water access - Start -->
    <div class="row my-2">
        @include("{$checklist->construction}.water-access")
    </div>
    <!-- Water access - End -->

    <!-- Road Type - Start -->
    <div class="row my-2">
        @include("{$checklist->construction}.road-type")
    </div>
    <!-- Road Type - End -->

    <!-- Road Dimension - Start -->
    <transition name="fade-in">
        <div class="row my-2" v-if="1 === checklist.new_road_type || 2 === checklist.new_road_type">
            @include("{$checklist->construction}.road-dimension")
        </div>
    </transition>
    <!-- Road Dimension - End -->



    <!-- Road Gutter - Start -->
    <div class="row my-2">
        @include("{$checklist->construction}.gutter")
    </div>
    <!-- Road Gutter - End -->

    <!-- Road Gutter Length - Start -->
    <transition name="fade-in">
        <div class="row my-2" v-if="1 === checklist.side_groove || 2 === checklist.side_groove">
            @include("{$checklist->construction}.gutter-length")
        </div>
    </transition>
    <!-- Road Gutter Length - End -->

    <!-- Road Embankment - Start -->
    <transition name="fade-in">
        <div class="row my-2" v-if="1 === checklist.side_groove || 2 === checklist.side_groove">
            @include("{$checklist->construction}.embankment")
        </div>
    </transition>
    <!-- Road Embankment - End -->



    <!-- Retaining Wall - Start -->
    <div class="row my-2">
        @include("{$checklist->construction}.retaining-wall")
    </div>
    <!-- Retaining Wall - End -->

    <!-- Retaining Wall Height - Start -->
    <transition name="fade-in">
        <div class="row my-2" v-if="1 === checklist.retaining_wall">
            @include("{$checklist->construction}.retaining-wall-height")
        </div>
    </transition>
    <!-- Retaining Wall Height - End -->

    <!-- Retaining Wall Length - Start -->
    <transition name="fade-in">
        <div class="row my-2" v-if="1 === checklist.retaining_wall">
            @include("{$checklist->construction}.retaining-wall-length")
        </div>
    </transition>
    <!-- Retaining Wall Length - End -->

</div>

