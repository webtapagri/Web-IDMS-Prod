<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRoadCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tm_road_category', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('status_id');
            $table->unsignedInteger('updated_by');
			$table->string('category_name',255);
			$table->integer('category_code');
			$table->string('category_initial',255);
			$table->foreign('status_id')->references('id')->on('tm_road_status')->onDelete('cascade');
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
        Schema::dropIfExists('tm_road_category');
    }
}
