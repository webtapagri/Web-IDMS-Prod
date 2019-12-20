<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBlockTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('TM_BLOCK', function (Blueprint $table) {
            $table->increments('id');
			$table->string('block_code',255);
			$table->string('block_name',255);
            $table->unsignedInteger('updated_by');
            $table->unsignedInteger('deleted_by');
            $table->unsignedInteger('afdeling_id');
			$table->foreign('afdeling_id')->references('id')->on('TM_AFDELING')->onDelete('cascade');
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
        Schema::dropIfExists('TM_BLOCK');
    }
}
