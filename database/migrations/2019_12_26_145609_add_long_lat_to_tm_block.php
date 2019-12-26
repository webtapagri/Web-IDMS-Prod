<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLongLatToTmBlock extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('TM_BLOCK', function (Blueprint $table) {
            $table->string('region_code',50)->nullable();
            $table->string('company_code',50)->nullable();
            $table->string('estate_code',50)->nullable();
            $table->string('werks',50)->nullable();
            $table->string('werks_afd_block_code',50)->nullable();
            $table->string('latitude_block',100)->nullable();
            $table->string('longitude_block',100)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('TM_BLOCK', function (Blueprint $table) {
			$table->dropColumn('region_code');
			$table->dropColumn('company_code');
			$table->dropColumn('werks');
			$table->dropColumn('werks_afd_code');
			$table->dropColumn('latitude_block');
			$table->dropColumn('longitude_block');
		});
    }
}
