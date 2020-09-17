<div class="card mt-2">
    <div class="card-header">区画精算</div>
    <div class="card-body">
        <ul>
            <li>支出は赤字、収入は青字となっています。</li>
            <li>支出は支出簿価です。</li>
            <li>収入は 販売の部 の各収入「入金済」と 仕入営業 の仲介収入「入金済」を集計しています。</li>
        </ul>

        <!-- start port section number -->
        <div class="card-subheader01" data-id="B52-1">@{{ section.port_section_number }}</div>
        <!-- end port section number -->

        <div class="table-responsive">
            <table class="table table-bordered">
                <tbody>
                    <tr>
                        <th class="bg-light" style="min-width:125px;"></th>
                        <th v-for="section_payoff in section_payoffs"
                            class="bg-light" style="min-width:125px;">
                            @{{ section_payoff.company.kind_in_house_abbreviation }}
                        </th>
                        <th class="bg-light" style="min-width:125px;">計</th>
                    </tr>

                    <!-- start expense book value -->
                    <tr>
                        <td class="bg-light">
                            <div data-id="B52-2" class="text-right">支出簿価</div>
                        </td>
                        <td v-for="section_payoff in section_payoffs" class="text-red text-right">
                            @{{ parseInt(section_payoff.expense_book_distribution).toLocaleString() }}
                        </td>
                        <td class="text-red text-right">
                            @{{ parseInt(outcome.book).toLocaleString() }}
                        </td>
                    </tr>
                    <!-- end expense book value -->

                    <!-- start income -->
                    <tr>
                        <td class="bg-light">
                            <div data-id="B52-3" class="text-right">
                                <button type="button" @click="setVisibility" class="badge badge-info px-1 py-2 mr-1">内訳</button>
                                収入
                            </div>
                        </td>
                        <td v-for="section_payoff in section_payoffs" class="text-blue text-right">
                            @{{ parseInt(section_payoff.total_income_distribution).toLocaleString() }}
                        </td>
                        <td class="text-blue text-right">
                            @{{ parseInt(income.total).toLocaleString() }}
                        </td>
                    </tr>
                    <!-- end income -->

                    <!-- start sales income -->
                    <tr v-if="initial.is_visible">
                        <td class="bg-light">
                            <div data-id="B52-4" class="text-right">販売収入</div>
                        </td>
                        <td v-for="section_payoff in section_payoffs" class="text-blue text-right">
                            @{{ parseInt(section_payoff.sales_income_distribution).toLocaleString() }}
                        </td>
                        <td class="text-blue text-right">
                            @{{ parseInt(income.sales).toLocaleString() }}
                        </td>
                    </tr>
                    <!-- end sales income -->

                    <!-- start sales brokarage income -->
                    <tr v-if="initial.is_visible">
                        <td class="bg-light">
                            <div data-id="B52-5" class="text-right">販売仲介収入</div>
                        </td>
                        <td v-for="section_payoff in section_payoffs" class="text-blue text-right">
                            @{{ parseInt(section_payoff.brokerage_income_distribution).toLocaleString() }}
                        </td>
                        <td class="text-blue text-right">
                            @{{ parseInt(income.brokerage).toLocaleString() }}
                        </td>
                    </tr>
                    <!-- end sales brokarage income -->

                    <!-- start sales fee income -->
                    <tr v-if="initial.is_visible">
                        <td class="bg-light">
                            <div data-id="B52-6" class="text-right">販売フィー・他収入</div>
                        </td>
                        <td v-for="section_payoff in section_payoffs" class="text-blue text-right">
                            @{{ parseInt(section_payoff.fee_income_distribution).toLocaleString() }}
                        </td>
                        <td class="text-blue text-right">
                            @{{ parseInt(income.fee).toLocaleString() }}
                        </td>
                    </tr>
                    <!-- end sales fee income -->

                    <!-- start brokarage income -->
                    <tr v-if="initial.is_visible">
                        <td class="bg-light">
                            <div data-id="B52-7" class="text-right">仕入時仲介収入</div>
                        </td>
                        <td v-for="section_payoff in section_payoffs" class="text-blue text-right">
                            @{{ parseInt(section_payoff.purchase_income_distribution).toLocaleString() }}
                        </td>
                        <td class="text-blue text-right">
                            @{{ parseInt(income.purchase).toLocaleString() }}
                        </td>
                    </tr>
                    <!-- end brokarage income -->

                    <!-- start property tax income -->
                    <tr v-if="initial.is_visible">
                        <td class="bg-light">
                            <div data-id="B52-8" class="text-right">固定資産税収入</div>
                        </td>
                        <td v-for="section_payoff in section_payoffs" class="text-blue text-right">
                            @{{ parseInt(section_payoff.tax_income_distribution).toLocaleString() }}
                        </td>
                        <td class="text-blue text-right">
                            @{{ parseInt(income.tax).toLocaleString() }}
                        </td>
                    </tr>
                    <!-- end property tax income -->

                    <!-- start net profit -->
                    <tr>
                        <td class="bg-light">
                            <div data-id="B52-9" class="text-right">差引利益</div>
                        </td>
                        <td v-for="section_payoff in section_payoffs">
                            <input :value="parseInt(section_payoff.profit).toLocaleString()"
                            class="form-control" style="text-align: right;"
                            type="text" readonly="readonly">
                        </td>
                        <td>
                            <input :value="parseInt(total.profit).toLocaleString()"
                            class="form-control" style="text-align: right;"
                            type="text" readonly="readonly">
                        </td>
                    </tr>
                    <!-- end net profit -->

                    <!-- start distribution adjustment -->
                    <tr>
                        <td class="bg-light">
                            <div data-id="B52-10" class="text-right">配分調整</div>
                        </td>
                        <td v-for="section_payoff in section_payoffs">
                            <input :value="parseInt(section_payoff.adjust).toLocaleString()"
                            class="form-control" style="text-align: right;"
                            type="text" readonly="readonly">
                        </td>
                        <td>
                            <input :value="parseInt(total.adjust).toLocaleString()"
                            class="form-control" style="text-align: right;"
                            type="text" readonly="readonly">
                        </td>
                    </tr>
                    <!-- end distribution adjustment -->

                    <!-- start profit after liquidation -->
                    <tr>
                        <td class="bg-light">
                            <div data-id="B52-11" class="text-right">区画清算後利益</div>
                        </td>
                        <td v-for="section_payoff in section_payoffs">
                            <input :value="parseInt(section_payoff.adjusted).toLocaleString()"
                            class="form-control" style="text-align: right;"
                            type="text" readonly="readonly">
                        </td>
                        <td>
                            <input :value="parseInt(total.adjusted).toLocaleString()"
                            class="form-control" style="text-align: right;"
                            type="text" readonly="readonly">
                        </td>
                    </tr>
                    <!-- end profit after liquidation -->

                </tbody>
            </table>
        </div>

    </div>
    <!--card-body-->
</div>
<!--card-->
