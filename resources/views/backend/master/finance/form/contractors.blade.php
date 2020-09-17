<div class="row">
	<div class="col-12">
		<div class="card">
			<div class="card-header text-bold">仕入契約者情報</div>
			<div class="card-body">
				<table class="table table-bordered table-small table-contractor-list-loan mt-0">
					<thead>
						<tr>
							<th class="bg-gray-expense contractor">契約者</th>
							<th class="bg-gray-expense contractor_address">住所</th>
							<th class="bg-gray-expense contractor_identification">本人確認</th>
							<th class="bg-gray-expense contractor_building_guarantee">建物保証書</th>
						</tr>
					</thead>
					<tbody>
						<template v-for="(contractor, index) in contractors">
							<!-- Start - Enabled Contractors -->
							<tr v-if="!contractor.disabled">
								<td>
									<!-- Purchaser Contractor Name -->
									<div class="form-group">
										<input :value="contractor.purchaser.name" class="form-control form-control-sm" type="text" readonly="readonly">
									</div>
								</td>
								<td>
									<!-- Contractor Address -->
									<div class="form-group">
										<input v-model="contractor.address" class="form-control form-control-sm w-100" type="text">
									</div>
								</td>
								<td>
									<!-- Contractor Identification -->
									<div class="form-group">
										<select v-model="contractor.identification" class="form-control form-control-sm" data-id="B21-3">
											<option value="0"></option>
											<option value="1">運</option>
											<option value="2">パ</option>
											<option value="3">健</option>
											<option value="4">年</option>
											<option value="5">謄</option>
											<option value="6">不</option>
										</select>
										<!-- Contractor Identification Attach -->
										<div class="form-check form-check-inline icheck-cyan ml-1">
											<input v-model="contractor.identification_attach"
													:id="'identification_attach_'+index" class="form-check-input" type="checkbox"
													name="attachment-check" value="1">
											<label class="form-check-label" :for="'identification_attach_'+index">添付</label>
										</div>
									</div>
								</td>
								<td></td>
							</tr>
							<!-- End - Enabled Contractors -->

							<!-- Start - Disabled Contractors -->
							<tr v-else>
								<td>
									<div class="form-group">
										<input class="form-control form-control-sm" type="text" disabled="disabled">
									</div>
								</td>
								<td>
									<div class="form-group">
										<input class="form-control form-control-sm w-100" type="text" disabled="disabled">
									</div>
								</td>
								<td>
									<div class="form-group">
										<select class="form-control form-control-sm" disabled="disabled">
										</select>
										<div class="form-check form-check-inline icheck-cyan ml-1">
											<input class="form-check-input" type="checkbox" name="attachment-check" id="attachment" value="1" disabled="disabled">
											<label class="form-check-label" for="attachment">添付</label>
										</div>
									</div>
								</td>
								<td></td>
							</tr>
							<!-- End - Disabled Contractors -->
						</template>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>