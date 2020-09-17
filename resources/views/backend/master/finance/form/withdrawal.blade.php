<div class="row mt-1">
	<div class="col-12">
		<div class="card">
			<div class="card-header text-bold">出戻り入出金</div>
			<div class="card-body">
				<!-- Start - Return Bank Table -->
				<table class="table table-bordered table-small mt-0 table-return w-auto">
					<thead>
						<tr>
							<th class="bg-gray-expense">入出金額</th>
							<th class="bg-gray-expense">出金口座</th>
							<th class="bg-gray-expense">入金口座</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td>
								<!-- amount -->
								<currency-input
									v-model.number="return_bank.amount"
									class="form-control form-control-sm input-money"
									:currency="null" :precision="{ min: 0, max: 0 }"
									:allow-negative="false" :disabled="!initial.editable">
								</currency-input>
							</td>
							<td>
								<!-- withdraw_bank -->
								<div class="form-group">
									<select v-model="return_bank.withdraw_bank" class="form-control form-control-sm">
										<option value=""></option>
										<option v-for="bank in selected_banks" :value="bank.id">
											@{{ bank.name }}
										</option>
									</select>
								</div>
							</td>
							<td>
								<!-- deposit_bank -->
								<div class="form-group">
									<select v-model="return_bank.deposit_bank" class="form-control form-control-sm">
										<option value=""></option>
										<option v-for="bank in selected_banks" :value="bank.id">
											@{{ bank.name }}
										</option>
									</select>
								</div>
							</td>
						</tr>
					</tbody>
				</table>
				<!-- End - Return Bank Table -->
			</div>
		</div>
	</div>
</div>