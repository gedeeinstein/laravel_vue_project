<div class="form-controls mx-n2 mx-sm-0">
    <div class="row mt-3 mx-n1">

        @php
            $columnClass = 'px-1 col-sm-6 col-md-auto my-1';
            $buttonClass = 'btn btn-block btn-primary fs-14';
            $rowClass = 'row mx-n1 justify-content-center'
        @endphp

        <!-- Save button - Start -->
        <div class="{{ $columnClass }}">
            <button type="button" class="{{ $buttonClass }}" :disabled="status.loading" @click="submit">
                <div class="{{ $rowClass }}">
                    <div class="px-1 col-auto">
                        <i v-if="status.loading" class="fw-l far fa-cog fa-spin"></i>
                        <i v-else class="fw-l far fa-save"></i>
                    </div>
                    <div class="px-1 col-auto">保存</div>
                </div>
            </button>
        </div>
        <!-- Save button - End -->


        <!-- Save & export pj-sheet - Start -->
        <div class="{{ $columnClass }}">
            <button type="button" class="{{ $buttonClass }}" :disabled="status.loading"
                @click="generateReport( $event, 'sheet', sheet, project )">
                <div class="{{ $rowClass }}">
                    <div class="px-1 col-auto">
                        <i v-if="status.report.sheet.loading" class="fw-l far fa-cog fa-spin"></i>
                        <i v-else class="fw-l far fa-print"></i>
                    </div>
                    <div class="px-1 col-auto">PJシート印刷</div>
                </div>
            </button>
        </div>
        <!-- Save & export pj-sheet - End -->


        <!-- Save & export sheet checklist - Start -->
        <div class="{{ $columnClass }}">
            <button type="button" class="{{ $buttonClass }}" :disabled="status.loading || null == reflectedSheet"
                @click="generateReport( $event, 'checklist', sheet, project )">
                <div class="{{ $rowClass }}">
                    <div class="px-1 col-auto">
                        <i v-if="status.report.checklist.loading" class="fw-l far fa-cog fa-spin"></i>
                        <i v-else class="fw-l far fa-print"></i>
                    </div>
                    <div class="px-1 col-auto">チェックリスト印刷</div>
                </div>
            </button>
        </div>
        <!-- Save & export sheet checklist - End -->


        <!-- Save & set as "is_reflecting_in_budget" - Start -->
        <div class="{{ $columnClass }}">
            <template v-for="reflected in [ sheet.is_reflecting_in_budget ]">

                <button type="button" class="btn btn-block fs-14" :disabled="status.loading"
                    :class="{ 'btn-primary': !reflected, 'btn-info btn-outline': reflected }"
                    @click="toggleSheetReflected( $event, sheetIndex, !reflected )">

                    <div class="{{ $rowClass }}">
                        <div class="px-1 col-auto">
                            <i class="far fw-l" :class="{ 'fa-toggle-off': !reflected, 'fa-toggle-on': reflected }"></i>
                        </div>
                        <div class="px-1 col-auto">予算に反映</div>
                    </div>
                </button>

            </template>
        </div>
        <!-- Save & set as "is_reflecting_in_budget" - End -->


        <!-- Save & send inspection request - Start -->
        <div class="{{ $columnClass }}">
            <button type="button" class="btn btn-block btn-primary fs-14" :disabled="status.loading" @click="requestInspection( sheet )">
                <div class="{{ $rowClass }}">
                    <div class="px-1 col-auto fw-l">
                        <i v-if="status.approvalRequest.loading" class="fw-l far fa-cog fa-spin"></i>
                        <i v-else class="fw-l far fa-badge-check"></i>
                    </div>
                    <div class="px-1 col-auto">承認リクエスト</div>
                </div>
            </button>
        </div>
        <!-- Save & send inspection request - End -->

    </div>
</div>
