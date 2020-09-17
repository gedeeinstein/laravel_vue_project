<script type="text/x-template" id="important-note-infrastructure">
    <div class="card mt-2">
        <div class="card-header" style="font-weight: bold;">インフラ</div>
        <div class="card-body">

            <!-- drinking water -->
            <important-note-drinking-water v-model="entry"></important-note-drinking-water>
            <!-- drinking water -->
            <!-- electrical -->
            <important-note-electrical v-model="entry"></important-note-electrical>
            <!-- electrical -->
            <!-- gas -->
            <important-note-gas v-model="entry"></important-note-gas>
            <!-- gas -->
            <!-- sewage -->
            <important-note-sewage v-model="entry"></important-note-sewage>
            <!-- sewage -->
            <!-- miscellaneous water -->
            <important-note-miscellaneous-water v-model="entry"></important-note-miscellaneous-water>
            <!-- miscellaneous water -->
            <!-- rain water -->
            <important-note-rain-water v-model="entry"></important-note-rain-water>
            <!-- rain water -->
            <!-- drainege remark -->
            <important-note-drainage-remark v-model="entry"></important-note-drainage-remark>
            <!-- drainege remark -->
            <!-- shape structure -->
            <important-note-shape-structure v-model="entry"></important-note-shape-structure>
            <!-- shape structure -->
            <!-- earth and sand -->
            <important-note-earth-and-sand v-model="entry"></important-note-earth-and-sand>
            <!-- earth and sand -->
            <!-- performance evaluation -->
            <important-note-performance-evaluation v-model="entry" :building_kind="building_kind">
            </important-note-performance-evaluation>
            <!-- performance evaluation -->
            <!-- survey status -->
            <important-note-survey-status v-model="entry" :building_kind="building_kind">
            </important-note-survey-status>
            <!-- survey status -->
            <!-- maintenance -->
            <important-note-maintenance v-model="entry" :building_kind="building_kind">
            </important-note-maintenance>
            <!-- maintenance -->
            <!-- use asbestos -->
            <important-note-use-asbestos v-model="entry" :building_kind="building_kind">
            </important-note-use-asbestos>
            <!-- use asbestos -->
            <!-- seismic diagnosis -->
            <important-note-seismic-diagnosis v-model="entry" :building_kind="building_kind">
            </important-note-seismic-diagnosis>
            <!-- seismic diagnosis -->
            <!-- infrastructure remark -->
            <important-note-infrastructure-remark v-model="entry"></important-note-infrastructure-remark>
            <!-- infrastructure remark -->


            <div class="incomplete_memo form-row p-2 bg-light">
                <div class="col-auto form-text">
                    <div class="form-check form-check-inline icheck-cyan">
                        <input v-model="entry.infrastructure_status"
                        class="form-check-input" type="radio" name="" id="infrastructure_status_1" value="1">
                        <label class="form-check-label" for="infrastructure_status_1">完</label>
                    </div>
                    <div class="form-check form-check-inline icheck-cyan">
                        <input v-model="entry.infrastructure_status"
                        class="form-check-input" type="radio" name="" id="infrastructure_status_2" value="2">
                        <label class="form-check-label" for="infrastructure_status_2">未</label>
                    </div>
                </div>
                <div class="col-auto form-text">
                    <span class="">未完メモ：</span>
                </div>
                <div class="col-6">
                    <input v-model="entry.infrastructure_memo" :disabled="isDisabled || isCompleted"
                    data-parsley-trigger="keyup" data-parsley-maxlength="128"
                    class="form-control" name="" type="text" value="" placeholder="未完となっている項目や理由を記入してください">
                </div>
            </div>
        </div>
    </div>
</script>
