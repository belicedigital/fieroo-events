<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventsExhibitorsStandsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('exhibitors_data', function(Blueprint $table) {
            $table->dropForeign(['stand_type_id']);
            $table->dropColumn(['stand_type_id','n_modules']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('exhibitors_data', function(Blueprint $table) {
            $table->unsignedBigInteger('stand_type_id')->after('exhibitor_id');
            $table->foreign('stand_type_id')->references('id')->on('stands_types')->onDelete('cascade');
            $table->integer('n_modules')->after('exhibitor_id');
        });
    }
}
