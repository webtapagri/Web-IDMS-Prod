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
        Schema::create('TM_BLOCK_MASTER', function (Blueprint $table) {
            $table->increments('ID');
            $table->unsignedInteger('UPDATED_BY');
            $table->unsignedInteger('DELETED_BY');
			$table->string('AFDELING_CODE',255);
			$table->string('BLOCK_CODE',255);
			$table->string('BLOCK_NAME',255);
			$table->foreign('AFDELING_CODE')->references('AFDELING_CODE')->on('TM_AFDELING')->onDelete('cascade');
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
        Schema::dropIfExists('TM_BLOCK_MASTER');
    }
}
