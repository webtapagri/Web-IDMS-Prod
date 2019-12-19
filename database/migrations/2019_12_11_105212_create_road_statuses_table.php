<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRoadStatusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('TM_ROAD_STATUS', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('updated_by');
			$table->string('status_name',255);
			$table->integer('status_code');
            $table->timestamps();
			$table->softDeletes();
			// $table->foreign('updated_by')->references('id')->on('tbm_user')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('TM_ROAD_STATUS');
    }
}
