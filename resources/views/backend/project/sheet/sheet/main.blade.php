<div class="card project-sheets mt-4">
    <div class="card-header card-header-tabs compact-tabs draggable-tabs">
        <draggable v-model="sheets" group="sheets" class="nav nav-tabs" id="projet-sheet-tabs" role="tablist" @end="reorderSheets"
            tag="ul" handle=".drag-handle" animation="300" easing="cubic-bezier(0.165, 0.84, 0.44, 1)" :disabled="status.loading">

            <!-- Sheet Tabs - Start -->
            @include("{$include->sheet}.tabs")
            <!-- Sheet Tabs - End -->

        </draggable>
    </div>
    <div class="card-body">
        <div class="tab-content" id="project-sheet-boards">
            <template v-for="( sheet, sheetIndex ) in sheets">

                <!-- Sheet Panel - Start -->
                @include("{$include->sheet}.panel")
                <!-- Sheet Panel - End -->
                
            </template>
        </div>
    </div>
</div>
