<ul class="px-4 pt-2 text-bold">
	<li>状況が「保」「確」の場合、背景色がオレンジになります。</li>
</ul>

<div class="expense-caption">
    <strong>D.融資関連費用</strong>
</div>
<div class="form-group">
	<table class="table table-bordered table-small-x table-expense-list mt-0">
		<thead>
			<tr>
				<th class="bg-gray-expense expense_name"></th>
				<th class="bg-gray-expense expense_budget">予算</th>
				<th class="bg-gray-expense expense_decided">決定総額</th>
				<th class="bg-gray-expense expense_period">支払時期</th>
				<th class="bg-gray-expense expense_payee">支払先</th>
				<th class="bg-gray-expense expense_note">備考</th>
				<th class="bg-gray-expense expense_paid">支払額</th>
				<th class="bg-gray-expense expense_date">日付</th>
				<th class="bg-gray-expense expense_bank">出金口座</th>
				<th class="bg-gray-expense expense_tax">非課税</th>
				<th class="bg-gray-expense expense_status">状況</th>
			</tr>
		</thead>
		<tbody>
        <!-- Start - Dynamic Row Input -->
        <template v-for="data in expenses.data">
            <tr v-for="(row, index) in data">
				<template v-if="!row.deleted">
					<td v-if="index == 0" :rowspan="data.length" class="align-text-top">
						<div v-if="!row.other" class="d-flex align-items-center">
							<span class="mr-1">@{{ row.category_index }}. @{{ row.display_name }}</span>
						</div>
						<div v-else class="d-flex align-items-center">
							<span class="mr-1">@{{ row.category_index }}.</span>
							<input v-model="row.display_name" class="form-control w-100" type="text" placeholder="×××××">
						</div>
					</td>
					<td>
						<!-- expense_budget -->
						<span>@{{ index+1 }}.</span>
						<input :value="row.budget | numeralFormat" class="form-control form-control-w-lg" type="text" readonly="readonly">
						<span v-if="initial.editable">
							<i @click="copyExpense(row, 'budget')" class="far fa-copy cur-pointer text-secondary text-primary ml-1"
								data-toggle="tooltip" title="" data-original-title="決定総額へコピー"></i>
						</span>
					</td>
					<td>
						<!-- expense_decided -->
						<currency-input v-model.number="row.decided"
							class="form-control form-control-w-lg input-money"
							:currency="null" :precision="{ min: 0, max: 0 }"
							:allow-negative="false" :disabled="!initial.editable">
						</currency-input>
						<span v-if="initial.editable">
							<i @click="copyExpense(row, 'decided')" class="far fa-copy cur-pointer text-secondary text-primary ml-1"
								data-toggle="tooltip" title="" data-original-title="支払額へコピー"></i>
						</span>
					</td>
					<td>
						<!-- expense_period -->
						<input v-model="row.payperiod" v-mask="'##/##'" class="form-control form-control-w-sm input-date" type="text" placeholder="年/月" :disabled="!initial.editable">
					</td>
					<td>
						<!-- expense_payee -->
						<multiselect v-model="row.payee"
							:options="master.payees" placeholder="サジェスト機能"
							class="expense-suggest"
							:close-on-select="true" select-label="" deselect-label label="name"
							selected-label="" track-by="name"
						>
						</multiselect>
					</td>
					<td>
						<!-- expense_note -->
						<input v-model="row.note" class="form-control form-control-w-xl" type="text" :disabled="!initial.editable">
					</td>
					<td>
						<!-- expense_paid -->
						<currency-input v-model.number="row.paid"
							class="form-control form-control-w-lg input-money"
							:currency="null" :precision="{ min: 0, max: 0 }"
							:allow-negative="false"
							:disabled="!initial.editable"
						/>
					</td>
					<td>
						<!-- expense_date -->
						<date-picker
							v-model="row.date" type="date"
							v-mask="'####/##/##'"
							class="w-100" input-class="form-control form-control-w-lg input-date"
							format="YYYY/MM/DD" value-type="format"
							:disabled="!initial.editable"
						/>
					</td>
					<td>
						<!-- expense_bank -->
						<select v-model="row.bank" class="form-control form-control-w-xl" :disabled="!initial.editable">
							<option value=""></option>
							<option v-for="bank in selected_banks" :value="bank.id">
								@{{ bank.name }}
							</option>
						</select>
					</td>
					<td>
						<!-- expense_tax -->
						<div class="form-check icheck-cyan icheck-sm d-inline">
							<input v-model="row.taxfree" :id="'tax_'+row.display_name+'_'+row.category_index+'_'+index" class="form-check-input" name="taxfree" type="checkbox" value="1">
							<label class="form-check-label" :for="'tax_'+row.display_name+'_'+row.category_index+'_'+index">非</label>
						</div>
					</td>
					<td :class="{'bg-orange-expense': (row.status == 2), 'bg-pink-expense': (row.status == 1 && row.paid || row.status == 1 && row.decided)}" class="position-relative">
						<!-- expense_status -->
						<template>
							<div class="form-check icheck-cyan icheck-sm d-inline">
								<input v-model="row.status" :id="'status_'+row.display_name+'_'+row.category_index+'_'+index+'_1'" class="form-check-input" :name="'status_'+row.display_name+'_'+row.category_index+'_'+index" type="radio" value="1" :disabled="!initial.editable">
								<label class="form-check-label" :for="'status_'+row.display_name+'_'+row.category_index+'_'+index+'_1'">無</label>
							</div>
							<div class="form-check icheck-cyan icheck-sm d-inline">
								<input v-model="row.status" :id="'status_'+row.display_name+'_'+row.category_index+'_'+index+'_2'" class="form-check-input" :name="'status_'+row.display_name+'_'+row.category_index+'_'+index" type="radio" value="2" :disabled="!initial.editable">
								<label class="form-check-label" :for="'status_'+row.display_name+'_'+row.category_index+'_'+index+'_2'">保</label>
							</div>
							<div class="form-check icheck-cyan icheck-sm d-inline">
								<input v-model="row.status" :id="'status_'+row.display_name+'_'+row.category_index+'_'+index+'_3'" class="form-check-input" :name="'status_'+row.display_name+'_'+row.category_index+'_'+index" type="radio" value="3" :disabled="!initial.editable">
								<label class="form-check-label" :for="'status_'+row.display_name+'_'+row.category_index+'_'+index+'_3'">済</label>
							</div>
						</template>
						<template v-if="initial.editable">
							<i v-if="index == 0" @click="addExpenseRow(data)"
								class="btn_overflow fa fa-plus-circle cur-pointer text-primary"
								data-toggle="tooltip" title="" data-original-title="行追加">
							</i>
							<i v-else @click="removeExpenseRow(data, index)"
								class="btn_overflow fa fa-minus-circle cur-pointer text-danger"
								data-toggle="tooltip" title="" data-original-title="行削除">
							</i>
						</template>
					</td>
				</template>
            </tr>
        </template>
		<!-- End - Dynamic Row Input -->
		</tbody>
	</table>
</div>