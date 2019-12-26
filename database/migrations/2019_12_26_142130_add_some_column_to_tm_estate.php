<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSomeColumnToTmEstate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('TM_ESTATE', function (Blueprint $table) {
            $table->string('werks',50)->nullable();
            $table->string('city',50)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('TM_ESTATE', function (Blueprint $table) {
            $table->dropColumn('werks');
            $table->dropColumn('city');
        });
    }
}
