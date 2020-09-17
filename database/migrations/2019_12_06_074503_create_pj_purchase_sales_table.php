<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePjPurchaseSalesTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('pj_purchase_sales', function (Blueprint $table) {
			$table->bigIncrements('id');
			// ---------------------------------------------------------------------- \\
			$table->unsignedBigInteger('project_id')->nullable();
			// ---------------------------------------------------------------------- \\
			$table->smallInteger('company_id_organizer')->default(0)->nullable();
			$table->smallInteger('organizer_realestate_explainer')->default(0);
			$table->string('project_address',128)->nullable();
			$table->string('project_address_extra',128)->nullable();
			// ---------------------------------------------------------------------- \\
			$table->integer('offer_route');
			$table->date('offer_date');
			// ---------------------------------------------------------------------- \\
			$table->smallInteger('project_type')->default(0);
			// ---------------------------------------------------------------------- \\
			$table->decimal('registry_size',12,4)->nullable();
			$table->smallInteger('registry_size_status')->nullable();
			$table->decimal('survey_size',12,4)->nullable();
			// ---------------------------------------------------------------------- \\
			$table->smallInteger('survey_size_status')->nullable();
			// ---------------------------------------------------------------------- \\
			$table->decimal('project_size', 12,4)->nullable();
			$table->smallInteger('project_size_status')->nullable();
			// ---------------------------------------------------------------------- \\
			$table->string('purchase_price',128)->nullable();
			// ---------------------------------------------------------------------- \\
			$table->smallInteger('project_status')->default(6)->nullable();
			$table->smallInteger('project_urbanization_area')->default(0)->nullable();
			$table->smallInteger('project_urbanization_area_status')->nullable();
			$table->smallInteger('project_urbanization_area_sub')->default(0)->nullable();
			$table->date('project_urbanization_area_date')->nullable();
			// ---------------------------------------------------------------------- \\
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('pj_purchase_sales');
	}
}
