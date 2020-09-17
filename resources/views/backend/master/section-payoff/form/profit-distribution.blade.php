<div class="card mt-2">
    <div class="card-header">利益配分</div>
    <div class="card-body">
        <ul>
            <li>利益配分は計が 100 (%)になるよう入力した後、「配分算出」をクリックし「保存」をクリックしてください。</li>
        </ul>
        <div class="table-responsive">
            <table class="table table-bordered">
                <tbody>
                    <tr>
                        <th v-for="section_payoff in section_payoffs" style="min-width:125px;">
                            @{{ section_payoff.company.kind_in_house_abbreviation }}
                        </th>
                        <th class="bg-light" style="min-width:125px;">計</th>
                    </tr>
                    <tr>
                        <!-- start profit rate -->
                        <td v-for="section_payoff in section_payoffs" class="">
                            <div class="input-group">
                                <template>
                                    <currency-input v-model="section_payoff.profit_rate"
                                        class="form-control input-decimal" data-id="B51-1"
                                        :currency="null" :precision="{ min: 0, max: 2 }"
                                        :allow-negative="false" />
                                </template>
                                <div class="input-group-append">
                                    <div class="input-group-text">%</div>
                                </div>
                            </div>
                        </td>
                        <!-- end profit rate -->

                        <!-- start profit rate total -->
                        <td>
                            <div class="input-group">
                                <template>
                                    <currency-input v-model="profit_rate_total"
                                        class="form-control" data-id="B51-2"
                                        data-parsley-trigger="focusout" data-parsley-number-max="100"
                                        :currency="null" :precision="{ min: 0, max: 2 }"
                                        :allow-negative="false" :disabled="false" :readonly="true"/>
                                </template>
                                <div class="input-group-append">
                                    <div class="input-group-text">%</div>
                                </div>
                            </div>
                            <div class="form-result">

                            </div>
                        </td>
                        <!-- end profit rate total -->
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- start calculation and reset button -->
        <div class="mt-3 buttons text-center">
            <button @click="calculation_adjust(section_payoffs)"
                type="button" class="btn btn-wide btn-info px-4" data-id="B51-3">配分算出
            </button>
            <button @click="reset_adjust(section_payoffs)" type="button"
                class="btn btn-wide btn-info px-4" data-id="B51-4">
                <i v-if="!initial.submited" class="fas fa-save"></i>
                <i v-else class="fas fa-spinner fa-spin"></i>
                リセット
            </button>
        </div>
        <!-- end calculation and reset button -->

    </div>
    <!--card-body-->
</div>
<!--card-->
