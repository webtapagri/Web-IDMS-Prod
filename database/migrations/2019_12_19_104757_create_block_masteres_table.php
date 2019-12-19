<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBlockMasteresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('BLOCK_MASTER', function (Blueprint $table) {
            $table->increments('ID');
            $table->unsignedInteger('UPDATED_BY');
            $table->unsignedInteger('DELETED_BY');
			$table->string('COMPANY_CODE',255);
			$table->string('ESTATE_CODE',255);
			$table->string('AFDELING_CODE',255);
			$table->string('BLOCK_CODE',255);
			$table->string('COMPANY_NAME',255);
			$table->string('ESTATE_NAME',255);
			$table->string('AFDELING_NAME',255);
			$table->string('BLOCK_NAME',255);
			$table->string('PLANT',255);
			$table->foreign('COMPANY_CODE')->references('ID')->on('tm_road_status')->onDelete('cascade');
			$table->timestamps();
			$table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('BLOCK_MASTER');
    }
}
