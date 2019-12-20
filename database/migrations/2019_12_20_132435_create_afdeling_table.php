<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAfdelingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('TM_AFDELING', function (Blueprint $table) {
            $table->increments('id');
			$table->string('afdeling_code',100);
			$table->string('afdeling_name',255);
            $table->unsignedInteger('estate_id');
            $table->unsignedInteger('updated_by');
            $table->unsignedInteger('deleted_by');
			$table->foreign('estate_id')->references('id')->on('TM_ESTATE')->onDelete('cascade');
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
        Schema::dropIfExists('TM_AFDELING');
    }
}
