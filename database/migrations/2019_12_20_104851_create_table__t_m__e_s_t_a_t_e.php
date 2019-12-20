<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableTMESTATE extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('TM_ESTATE', function (Blueprint $table) {
            $table->increments('id');
			$table->string('estate_code',100);
			$table->string('estate_name',255);
            $table->unsignedInteger('company_id');
            $table->timestamps();
			$table->softDeletes();
			$table->foreign('company_id')->references('id')->on('TM_COMPANY')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('TM_ESTATE');
    }
}
