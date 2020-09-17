<div class="form-controls mx-n2 mx-sm-0 mb-3">
    <div class="row mx-n1">

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


        <!-- Save & export contract section - Start -->
        <div class="{{ $columnClass }}">
            <button type="button" class="{{ $buttonClass }}" :disabled="status.loading" @click="generateReport( 'contract' )">
                <div class="{{ $rowClass }}">
                    <div class="px-1 col-auto">
                        <i v-if="status.report.contract.loading" class="fw-l far fa-cog fa-spin"></i>
                        <i v-else class="fw-l far fa-print"></i>
                    </div>
                    <div class="px-1 col-auto">契約書印刷</div>
                </div>
            </button>
        </div>
        <!-- Save & export contract section - End -->


        <!-- Save & export important-note section - Start -->
        <div class="{{ $columnClass }}">
            <button type="button" class="{{ $buttonClass }}" :disabled="status.loading" @click="generateReport( 'notes' )">
                <div class="{{ $rowClass }}">
                    <div class="px-1 col-auto">
                        <i v-if="status.report.notes.loading" class="fw-l far fa-cog fa-spin"></i>
                        <i v-else class="fw-l far fa-print"></i>
                    </div>
                    <div class="px-1 col-auto">重要事項説明書印刷</div>
                </div>
            </button>
        </div>
        <!-- Save & export important-note section - End -->


        <!-- Save & send approval request - Start -->
        <div class="{{ $columnClass }}">
            <button type="button" class="btn btn-block btn-primary fs-14" :disabled="status.loading" @click="requestInspection">
                <div class="{{ $rowClass }}">
                    <div class="px-1 col-auto fw-l">
                        <i v-if="status.approvalRequest.loading" class="fw-l far fa-cog fa-spin"></i>
                        <i v-else class="fw-l far fa-badge-check"></i>
                    </div>
                    <div class="px-1 col-auto">承認リクエスト</div>
                </div>
            </button>
        </div>
        <!-- Save & send approval request - End -->

    </div>
</div>