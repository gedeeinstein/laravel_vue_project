<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProjectsTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(){
        Schema::create('projects', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title', 64);
            $table->decimal('overall_area', 12, 4)->default(0);
            $table->decimal('fixed_asset_tax_route_value', 12, 0)->default(0)->nullable();
            $table->decimal('building_area', 12, 4)->default(0)->nullable();
            $table->unsignedSmallInteger('usage_area')->default(0)->nullable();
            $table->decimal('building_coverage_ratio', 12, 4)->default(0)->nullable();
            $table->decimal('floor_area_ratio', 12, 4)->default(0)->nullable();
            $table->timestamp('estimated_closing_date')->nullable();
            $table->string('port_pj_info_number', 64)->default(null)->nullable();
            $table->string('port_contract_number', 64)->default(null)->nullable();
            $table->unsignedSmallInteger('approval_request')->default(0)->nullable();
            // $table->string('request_author', 128)->nullable();
            // $table->timestamp('request_time')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(){
        Schema::dropIfExists('projects');
    }
}
