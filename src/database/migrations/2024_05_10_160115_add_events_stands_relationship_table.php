<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddEventsStandsRelationshipTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('events_stands', function (Blueprint $table) {
            $table->unsignedBigInteger('event_id');
            $table->foreign('event_id')->references('id')->on('events')->onDelete('cascade');
            $table->unsignedBigInteger('stand_type_id');
            $table->foreign('stand_type_id')->references('id')->on('stands_types')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('events_stands', function (Blueprint $table) {
            $table->dropForeign(['event_id', 'stand_type_id']);
        });

        Schema::dropIfExists('events_stands');
    }
}
