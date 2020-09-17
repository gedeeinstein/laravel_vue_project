<script type="text/x-template" id="important-note-use-asbestos">
    <div v-if="building_kind">
        <div class="form-group row">
            <label for="" class="col-3 col-form-label" style="font-weight: normal;">石綿使用調査</label>
            <div class="col-9">
                <div class="sub-label">照会先</div>
                <div class="form-inline">
                    <div class="form-check-inline icheck-cyan">
                        <select v-model="entry.use_asbestos_Reference" :disabled="isDisabled || isCompleted"
                        class="form-control" data-id="A1311-82" name="">
                            <option value="1">売主</option>
                            <option value="2">管理会社</option>
                            <option value="3">施工会社</option>
                            <option value="4">その他</option>
                        </select>
                    </div>
                    <div class="form-check-inline icheck-cyan">
                        <input v-model="entry.use_asbestos_Reference_text"
                        :disabled="isDisabled || isCompleted || entry.use_asbestos_Reference == 1"
                        data-parsley-trigger="keyup" data-parsley-maxlength="128"
                        class="form-control" type="text" value="" data-id="A1311-83">
                    </div>
                </div>
                <div class="sub-label">記録の有無</div>
                <div class="form-check-answer">

                    <div class="form-check icheck-cyan">
                        <input v-model="entry.use_asbestos_record" :disabled="isDisabled || isCompleted"
                        data-id="A1311-84" class="form-check-input" type="radio" name="exampleRadios" id="use_asbestos_record_1" value="1" checked="">
                        <label class="form-check-label" for="use_asbestos_record_1"><span class="answer">有</span>別添資料のとおり、調査の記録が保存されています（別添資料を参照下さい）。</label>
                    </div>
                    <div class="form-check icheck-cyan">
                        <input v-model="entry.use_asbestos_record" :disabled="isDisabled || isCompleted"
                        class="form-check-input" type="radio" name="exampleRadios" id="use_asbestos_record_2" value="2">
                        <label class="form-check-label" for="use_asbestos_record_2"><span class="answer">無</span>現在すでに建築されている多くの建物には、石綿（アスベスト）を含有している建材が一般的に使用されていた時期があり、対象不動産にもアスベスト含有建材が使用されている可能性がありますが、実際は不明です。建材に含まれているアスベストは繊維が固定されているため、日常生活の中では飛散することはなく、通常の使用においては健康に被害を及ぼすものではないと言われています。ただし、増・改築やリフォーム、解体時にはこれらの建材のアスベストを飛散させないよう、「石綿障害予防規則」（平成17年7月1日施行）、その他の関係諸法令に則り、専門業者による適切な施工と産業廃棄物処理が必要となり、そのための費用が必要となります。</label>
                    </div>
                </div>
            </div>
        </div>
        <hr>
    </div>
</script>
