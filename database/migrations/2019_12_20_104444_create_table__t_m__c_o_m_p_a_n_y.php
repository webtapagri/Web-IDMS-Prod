<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableTMCOMPANY extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('TM_COMPANY', function (Blueprint $table) {
            $table->increments('id');
			$table->string('company_code',100);
			$table->string('company_name',255);
			$table->dateTime('valid_from')->nullable();
			$table->dateTime('valid_to')->nullable();
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
        Schema::dropIfExists('TM_COMPANY');
    }
}
