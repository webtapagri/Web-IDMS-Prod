<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateVRoadCategoriesAddColumnId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        \DB::unprepared("DROP VIEW IF EXISTS v_road_category");
        DB::unprepared(file_get_contents(__DIR__. '/../sql/v_road_category.sql'));
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        \DB::unprepared("DROP VIEW IF EXISTS v_road_category");
        DB::unprepared(file_get_contents(__DIR__. '/../sql/v_road_category.sql'));
    }
}
