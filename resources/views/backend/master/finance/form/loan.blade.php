<div class="row mt-1">
	<div class="col-12">
		<div class="card">
			<div class="card-header text-bold">融資情報</div>
			<template v-for="(loan, page) in loans.data">
				<div class="card-body">
					<!-- Start - Borrower Information -->
					<div class="row form-group">
						<div class="col-2">
							<label class="font-weight-normal">借入先</label>
						</div>
						<div class="col-10">
							<div class="row">
								<!-- loan_lender -->
								<select v-model="loan.loan_lender" class="form-control col-4"
										data-parsley-trigger="change"
										:data-parsley-required="checkRequired(loans.data, page, 'unit')">
									<option value=""></option>
									<option v-for="borrower in master.borrowers" :value="borrower.id">
										@{{ borrower.bank_name }}
									</option>
								</select>
								<label class="col-form-label font-weight-normal ml-4">抵当権設定</label>
								<!-- loan_mortgage 1 -->
								<div class="form-check form-check-inline icheck-cyan ml-2">
									<input v-model="loan.loan_mortgage" class="form-check-input" type="radio" name="mortgage-setting" :id="'mortgage-setting1-'+page" value="1">
									<label class="form-check-label" :for="'mortgage-setting1-'+page">有</label>
								</div>
								<!-- loan_mortgage 2 -->
								<div class="form-check form-check-inline icheck-cyan">
									<input v-model="loan.loan_mortgage" class="form-check-input" type="radio" name="mortgage-setting" :id="'mortgage-setting2-'+page" value="2">
									<label class="form-check-label" :for="'mortgage-setting2-'+page">無</label>
								</div>
							</div>
							<div class="form-result"></div>
						</div>
					</div>
					<!-- End - Borrower Information -->

					<!-- Start - Deposit Account -->
					<div class="row form-group">
						<div class="col-2">
							<label class="font-weight-normal">入金口座</label>
						</div>
						<div class="col-10">
							<div class="row">
								<!-- loan_account -->
								<select v-model="loan.loan_account" class="form-control col-4"
										data-parsley-trigger="change"
										:data-parsley-required="checkRequired(loans.data, page, 'unit')">
									<option value=""></option>
									<option v-for="bank in selected_banks" :value="bank.id">
										@{{ bank.name }}
									</option>
								</select>
							</div>
							<div class="form-result"></div>
						</div>
					</div>
					<!-- End - Deposit Account -->

					<!-- Start - Loan Amount Money -->
					<div class="row form-group">
						<div class="col-2">
							<label class="font-weight-normal">融資額/融資日</label>
						</div>
						<div class="col-10">
							<template v-for="(money, index) in loan.moneys">
								<div v-if="!money.deleted" class="row mb-2">
									<!-- loan_money -->
									<div class="col-2 pl-0">
										<currency-input
										v-model.number="money.loan_money"
										class="form-control input-money" placeholder="融資額"
										:currency="null" :precision="{ min: 0, max: 0 }"
										data-parsley-trigger="change"
										:data-parsley-required="checkRequired(loans.data, page, 'money')"
										:allow-negative="false" :disabled="!initial.editable">
									</currency-input>
									</div>
									<!-- loan_date -->
									<date-picker
										v-model="money.loan_date" type="date" class="col-2"
										format="YYYY/MM/DD" value-type="format"
										@change="datepickerChange"
										:disabled="!initial.editable">
										<template #input>
											<input v-model="money.loan_date" placeholder="融資日" name="date" type="text" autocomplete="off"
												   class="form-control input-date"
												   data-parsley-trigger="change"
												   :data-parsley-required="checkRequired(loans.data, page, 'money')">
										</template>
									</date-picker>
									<!-- loan_note -->
									<input v-model="money.loan_note" class="form-control col-3 ml-1"
										type="text" placeholder="備考">
									<!-- loan_status -->
									<fieldset>
										<div class="form-check form-check-inline icheck-cyan ml-2">
											<input v-model="money.loan_status" class="form-check-input" type="radio" :name="'loan-information'+page+'-'+index" :id="'loan-money-status1-'+page+'-'+index" value="1">
											<label class="form-check-label" :for="'loan-money-status1-'+page+'-'+index">未</label>
										</div>
										<div class="form-check form-check-inline icheck-cyan">
											<input v-model="money.loan_status" class="form-check-input" type="radio" :name="'loan-information'+page+'-'+index" :id="'loan-money-status2-'+page+'-'+index" value="2">
											<label class="form-check-label" :for="'loan-money-status2-'+page+'-'+index">予</label>
										</div>
										<div class="form-check form-check-inline icheck-cyan">
											<input v-model="money.loan_status" class="form-check-input" type="radio" :name="'loan-information'+page+'-'+index" :id="'loan-money-status3-'+page+'-'+index" value="3">
											<label class="form-check-label" :for="'loan-money-status3-'+page+'-'+index">確</label>
										</div>
										<div class="form-check form-check-inline icheck-cyan">
											<input v-model="money.loan_status" class="form-check-input" type="radio" :name="'loan-information'+page+'-'+index" :id="'loan-money-status4-'+page+'-'+index" value="4" :disabled="!loan.loan_account">
											<label class="form-check-label" :for="'loan-money-status4-'+page+'-'+index">済</label>
										</div>
										<div class="form-check form-check-inline icheck-cyan">
											<input v-model="money.loan_status" class="form-check-input" type="radio" :name="'loan-information'+page+'-'+index" :id="'loan-money-status5-'+page+'-'+index" value="5" :disabled="!loan.loan_account">
											<label class="form-check-label" :for="'loan-money-status5-'+page+'-'+index">完</label>
										</div>
									</fieldset>
									<!-- add / remove button -->
									<span v-if="index == 0" class="pt-2">
										<i @click="addLoanMoney(loan.moneys)" class="fa fa-plus-circle cur-pointer text-primary ml-2" data-toggle="tooltip" title="" data-original-title="行を追加"></i>
									</span>
									<span v-else class="pt-2">
										<i @click="removeLoanMoney(loan.moneys, index)" class="fa fa-minus-circle cur-pointer text-danger ml-2" data-toggle="tooltip" title="" data-original-title="行を削除"></i>
									</span>
								</div>
							</template>
						</div>
					</div>
					<!-- End - Loan Amount Money -->

					<!-- Start - Loan Amount Total -->
					<div class="row form-group">
						<div class="col-2">
							<label class="font-weight-normal">融資額計(予/済)</label>
						</div>
						<div class="col-10">
							<div class="row">
								<input :value="calculated[page].loan_total_budget | numeralFormat" class="form-control col-2" type="text" readonly="readonly">
								<input :value="calculated[page].loan_total | numeralFormat" class="form-control col-2 ml-1" type="text" readonly="readonly">
							</div>
						</div>
					</div>
					<!-- End - Loan Amount Total -->

					<!-- Start - Repayments -->
					<div class="card-subheader02">返済</div>
					<table class="table table-bordered table-small table-repayment-list mt-2">
						<thead>
							<tr>
								<th class="bg-gray-expense repayment_section">No.</th>
								<th class="bg-gray-expense repayment_money">返済額</th>
								<th class="bg-gray-expense repayment_date">日付</th>
								<th class="bg-gray-expense repayment_account">出金口座</th>
								<th class="bg-gray-expense repayment_note">備考</th>
								<th class="bg-gray-expense repayment_status">状況</th>
								<th class="bg-gray-expense repayment_loan_bookprice">融資簿価</th>
								<th class="bg-gray-expense repayment_loan_bookprice_reference">融資簿価参考値</th>
							</tr>
						</thead>
						<tbody>
							<!-- Start - Repayment Sales -->
							<template v-for="(sales, index) in loan.repayment_sales">
								<tr>
									<td>
										<!-- repayment section_number -->
										<div>@{{ sales.section_number }}</div>
									</td>
									<td>
										<!-- repayment money -->
										<div class="form-group">
											<currency-input
												v-model.number="sales.money"
												class="form-control form-control-sm input-money"
												:currency="null" :precision="{ min: 0, max: 0 }"
												data-parsley-trigger="change"
												:data-parsley-required="sales.money || sales.date"
												:allow-negative="false"
												:disabled="!initial.editable || sales.disabled">
											</currency-input>
										</div>
									</td>
									<td>
										<!-- repayment date -->
										<div class="form-group">
											<date-picker
												v-model="sales.date" type="date" class="w-100"
												format="YYYY/MM/DD" value-type="format"
												@change="datepickerChange"
												:disabled="!initial.editable || sales.disabled">
												<template #input>
													<input v-model="sales.date" placeholder="" name="date" type="text" autocomplete="off"
														   class="form-control form-control-sm input-date"
														   data-parsley-trigger="change"
														   :data-parsley-required="sales.money || sales.date"
														   :disabled="!initial.editable || sales.disabled">
												</template>
											</date-picker>
										</div>
									</td>
									<td>
										<!-- repayment account -->
										<div class="form-group">
											<select v-model="sales.account" class="form-control form-control-sm"
													:disabled="!initial.editable || sales.disabled">
												<option value=""></option>
												<option v-for="bank in selected_banks" :value="bank.id">
													@{{ bank.name }}
												</option>
											</select>
										</div>
									</td>
									<td>
										<!-- repayment note -->
										<div class="form-group">
											<input v-model="sales.note" class="form-control form-control-sm w-100" type="text"
												   :disabled="!initial.editable || sales.disabled">
										</div>
									</td>
									<td>
										<!-- repayment status -->
										<div class="form-check form-check-inline icheck-cyan">
											<input v-model="sales.status" :id="'sales-status1_'+page+'_'+index" class="form-check-input" type="radio" :name="'sales-status_'+page+'_'+index" value="1" :disabled="!initial.editable || sales.disabled">
											<label class="form-check-label" :for="'sales-status1_'+page+'_'+index">予</label>
										</div>
										<div class="form-check form-check-inline icheck-cyan">
											<input v-model="sales.status" :id="'sales-status2_'+page+'_'+index" class="form-check-input" type="radio" :name="'sales-status_'+page+'_'+index" value="2" :disabled="!initial.editable || sales.disabled">
											<label class="form-check-label" :for="'sales-status2_'+page+'_'+index">確</label>
										</div>
										<div class="form-check form-check-inline icheck-cyan">
											<input v-model="sales.status" :id="'sales-status3_'+page+'_'+index" class="form-check-input" type="radio" :name="'sales-status_'+page+'_'+index" value="3" :disabled="!initial.editable || sales.disabled">
											<label class="form-check-label" :for="'sales-status3_'+page+'_'+index">済</label>
										</div>
									</td>
									<td>
										<!-- repayment book_price -->
										<div class="form-group">
											<currency-input
												v-model.number="sales.book_price" type="text"
												class="form-control form-control-1btn form-control-sm input-money"
												:currency="null" :precision="{ min: 0, max: 0 }"
												:allow-negative="false"
												:disabled="!initial.editable || sales.disabled">
											</currency-input>
											<template v-if="!sales.disabled">
												<i @click="copyReference(sales)" class="far fa-copy cur-pointer text-secondary text-primary ml-1" data-toggle="tooltip" title="" data-original-title="参考値コピー"></i>
											</template>
										</div>
									</td>
									<td>
										<!-- repayment book_price_reference -->
										<div class="form-group">
											<input :value="sales.book_price_reference | numeralFormat" class="form-control form-control-sm" type="text" readonly="readonly">
										</div>
									</td>
								</tr>
							</template>
							<!-- End - Repayment Sales -->

							<!-- Start - Repayment Others -->
							<template v-for="(other, index) in loan.repayment_others">
								<tr v-if="!other.deleted">
									<td>@{{ other.section_number }}</td>
									<td>
										<!-- repayment money -->
										<div class="form-group">
											<currency-input
												v-model.number="other.money"
												class="form-control form-control-sm input-money"
												:currency="null" :precision="{ min: 0, max: 0 }"
												:allow-negative="false" :disabled="!initial.editable">
											</currency-input>
										</div>
									</td>
									<td>
										<!-- repayment date -->
										<div class="form-group">
											<date-picker
												v-model="other.date" type="date"
												v-mask="'####/##/##'" class="w-100"
												input-class="form-control form-control-sm input-date"
												format="YYYY/MM/DD" value-type="format"
												:disabled="!initial.editable">
											</date-picker>
									</td>
									<td>
										<!-- repayment account -->
										<div class="form-group">
											<select v-model="other.account" class="form-control form-control-sm">
												<option value=""></option>
												<option v-for="bank in selected_banks" :value="bank.id">
													@{{ bank.name }}
												</option>
											</select>
										</div>
									</td>
									<td>
										<!-- repayment note -->
										<div class="form-group">
											<input v-model="other.note" class="form-control form-control-sm w-100" type="text">
										</div>
									</td>
									<td>
										<!-- repayment status -->
										<div class="form-check form-check-inline icheck-cyan">
											<input v-model="other.status" :id="'other-status1_'+page+'_'+index" class="form-check-input" type="radio" :name="'other-status_'+page+'_'+index" value="1">
											<label class="form-check-label" :for="'other-status1_'+page+'_'+index">予</label>
										</div>
										<div class="form-check form-check-inline icheck-cyan">
											<input v-model="other.status" :id="'other-status2_'+page+'_'+index" class="form-check-input" type="radio" :name="'other-status_'+page+'_'+index" value="2">
											<label class="form-check-label" :for="'other-status2_'+page+'_'+index">確</label>
										</div>
										<div class="form-check form-check-inline icheck-cyan">
											<input v-model="other.status" :id="'other-status3_'+page+'_'+index" class="form-check-input" type="radio" :name="'other-status_'+page+'_'+index" value="3">
											<label class="form-check-label" :for="'other-status3_'+page+'_'+index">済</label>
										</div>
									</td>
									<template>
										<!-- add/remove repayment other -->
										<td v-if="index == 0" class="position-relative" colspan="2">
											<i @click="addOtherRepayment(loan)" class="btn-finance fa fa-plus-circle cur-pointer text-primary" data-toggle="tooltip" title="" data-original-title="行を追加"></i>
										</td>
										<td v-else class="position-relative" colspan="2">
											<i @click="removeOtherRepayment(loan.repayment_others, index)" class="btn-finance fa fa-minus-circle cur-pointer text-danger" data-toggle="tooltip" title="" data-original-title="行を削除"></i>
										</td>
									</template>
								</tr>
							</template>
							<!-- End - Repayment Others -->

							<!-- Start - Repayment Total -->
							<tr>
								<td>計</td>
								<td>
									<div class="form-group">
										<input :value="calculated[page].money_total | numeralFormat" class="form-control form-control-sm" type="text" readonly="readonly">
									</div>
								</td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td>
									<div class="form-group">
										<input :value="calculated[page].book_price_total | numeralFormat" class="form-control form-control-sm" type="text" readonly="readonly">
									</div>
								</td>
								<td>
									<div class="form-group">
										<input :value="calculated[page].book_price_reference_total | numeralFormat" class="form-control form-control-sm" type="text" readonly="readonly">
									</div>
								</td>
							</tr>
							<!-- End - Repayment Total -->
						</tbody>
					</table>
					<!-- End - Repayments -->

					<!-- Start - Calculated Loan Balance -->
					<div class="row form-group mt-3">
						<div class="col-2">
							<label class="font-weight-normal">融資残高(予/済)</label>
						</div>
						<div class="col-10">
							<div class="row">
								<!-- loan_balance_money_budget -->
								<input :value="calculated[page].loan_balance_money_budget | numeralFormat" class="form-control col-2" type="text" readonly="readonly">
								<!-- loan_balance_money -->
								<input :value="calculated[page].loan_balance_money | numeralFormat" class="form-control col-2 ml-1" type="text" readonly="readonly">
							</div>
						</div>
					</div>
					<!-- End - Calculated Loan Balance -->

					<!-- Start - Loan Ratio -->
					<div class="row form-group">
						<div class="col-2">
							<label class="font-weight-normal">年利</label>
						</div>
						<div class="col-10">
							<div class="row input-group input-decimal">
								<!-- loan_ratio -->
								<currency-input
									v-model.number="loan.loan_ratio"
									class="form-control input-group col-1"
									:currency="null" :precision="{ min: 0, max: 4 }"
									:allow-negative="false" :disabled="!initial.editable">
								</currency-input>
								<div class="input-group-append">
									<div class="input-group-text">%</div>
								</div>
							</div>
						</div>
					</div>
					<!-- End - Loan Ratio -->

					<!-- Start - Loan Date -->
					<div class="row form-group">
						<div class="col-2">
							<label class="col-form-label font-weight-normal">返済期限</label>
						</div>
						<div class="col-10">
							<div class="row">
								<!-- loan_period_date -->
								<date-picker
									v-model="loan.loan_period_date" type="date"
									v-mask="'####/##/##'" class="col-2 pl-0"
									input-class="form-control input-date"
									format="YYYY/MM/DD" value-type="format"
									:disabled="!initial.editable">
								</date-picker>
								<!-- loan_type -->
								<div class="form-check form-check-inline icheck-cyan ml-2">
									<input v-model="loan.loan_type" class="form-check-input" type="radio" :name="'repayment_date_'+page" :id="'repayment_date1_'+page" value="1">
									<label class="form-check-label" :for="'repayment_date1_'+page">通常</label>
								</div>
								<!-- loan_type -->
								<div class="form-check form-check-inline icheck-cyan">
									<input v-model="loan.loan_type" class="form-check-input" type="radio" :name="'repayment_date_'+page" :id="'repayment_date2_'+page" value="2">
									<label class="form-check-label" :for="'repayment_date2_'+page">書換有</label>
								</div>
								<!-- loan_type_date -->
								<template v-if="loan.loan_type == 2">
									<input v-model="loan.loan_type_date" class="form-control col-2 mr-2" type="text" placeholder="融資書換日">
								</template>
								<!-- loan_type -->
								<div class="form-check form-check-inline icheck-cyan">
									<input v-model="loan.loan_type" class="form-check-input" type="radio" :name="'repayment_date_'+page" :id="'repayment_date3_'+page" value="3">
									<label class="form-check-label" :for="'repayment_date3_'+page">異常</label>
								</div>
							</div>
						</div>
					</div>
					<!-- End - Loan Date -->

					<!-- Start - Add/Remove Loans -->
					<div v-if="page != 0" class="text-right">
						<button v-on:click.prevent="removeLoan(page)" class="btn btn-danger"><i class="fa fa-trash"></i> 融資削除</button>
					</div>
					<hr>
					<div v-if="page == loans.data.length-1" class="text-right">
						<button v-on:click.prevent="addLoan" class="btn btn-info"><i class="fa fa-plus"></i> 融資書換</button>
					</div>
					<!-- End - Add/Remove Loans -->
				</div>
			</template>
		</div>
		<!--card-->
	</div>
</div>