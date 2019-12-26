<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRegionCodeToTmCompany extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('TM_COMPANY', function (Blueprint $table) {
            $table->string('region_code',50)->nullable();
            $table->string('address',255)->nullable();
            $table->string('national',100)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('TM_COMPANY', function (Blueprint $table) {
			$table->dropColumn('region_code');
			$table->dropColumn('address');
			$table->dropColumn('national');
		});
    }
}
