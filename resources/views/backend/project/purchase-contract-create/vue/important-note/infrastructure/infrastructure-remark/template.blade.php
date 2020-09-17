<script type="text/x-template" id="important-note-infrastructure-remark">
    <div class="form-group row">
        <label for="" class="col-3 col-form-label" style="font-weight: normal;">備考</label>
        <div class="col-9">
            <textarea v-model="entry.infrastructure_remarks" :disabled="isDisabled || isCompleted"
            data-parsley-trigger="keyup" data-parsley-maxlength="4096"
             class="form-control" data-id="A1311-90">津波災害警戒区域・津波災害特別警戒区域については法施工後間もない制度である事から宮城県内は現時点では未指定の状況ですが、本物件に係る区域については、今後、宮城県から当該区域として指定される可能性があります。</textarea>
        </div>
    </div>
</script>
