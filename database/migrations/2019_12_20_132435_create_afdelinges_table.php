<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAfdelingesTable extends Migration
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
            $table->unsignedInteger('UPDATED_BY');
            $table->unsignedInteger('DELETED_BY');
			$table->string('AFDELING_CODE',255);
			$table->string('AFDELING_NAME',255);
			$table->string('ESTATE_CODE',255);
			$table->foreign('ESTATE_CODE')->references('ESTATE_CODE')->on('TM_ESTATE')->onDelete('cascade');
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
        Schema::dropIfExists('afdelinges');
    }
}
