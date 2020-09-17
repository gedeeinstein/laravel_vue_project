<div class="table-responsive w-50">
    <table class="table table-bordered table-small buypurchase_table mt-0 mb-0">
        <thead>
            <tr>
                <th class="bg-light-gray">買付日</th>
                <th class="bg-light-gray">契約日</th>
                <th class="bg-light-gray">決済日</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <!-- start - sale_contract.purchase_date -->
                <td>
                    <date-picker v-model="sale_contract.purchase_date"
                        type="date" class="form-control-w-xl"
                        input-class="form-control form-control-w-xl input-date"
                        format="YYYY/MM/DD" value-type="format" data-id="C23-12"
                        :disabled="!initial.editable">
                    </date-picker>
                </td>
                <!-- end - sale_contract.purchase_date -->

                <!-- start - sale_contract.contract_date -->
                <td>
                    <date-picker v-model="sale_contract.contract_date"
                        type="date" class="form-control-w-xl"
                        input-class="form-control form-control-w-xl input-date"
                        format="YYYY/MM/DD" value-type="format" data-id="C23-13"
                        :disabled="!initial.editable">
                    </date-picker>
                </td>
                <!-- end - sale_contract.contract_date -->

                <!-- start - sale_contract.payment_date -->
                <td>
                    <date-picker v-model="sale_contract.payment_date"
                        type="date" class="form-control-w-xl"
                        input-class="form-control form-control-w-xl input-date"
                        format="YYYY/MM/DD" value-type="format" data-id="C23-14"
                        :disabled="!initial.editable" :required="payment_date_required">
                    </date-picker>
                </td>
                <!-- end - sale_contract.payment_date -->
            </tr>
        </tbody>
    </table>
</div>
