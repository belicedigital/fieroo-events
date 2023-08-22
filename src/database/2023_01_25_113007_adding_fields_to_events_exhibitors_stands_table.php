<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddingFieldsToEventsExhibitorsStandsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('events_exhibitors_stands', function (Blueprint $table) {
            $table->boolean('is_furnished')->default(0)->after('n_modules');
            $table->boolean('catalog_is_completed')->default(0)->after('n_modules');
        });

        Schema::table('exhibitors_data', function(Blueprint $table) {
            $table->dropColumn([
                'close_catalog',
                'close_furnishings',
                'catalog',
                'furnishings',
                'invoice_tot_sent',
                'invoice_sent',
                'balance_received',
                'deposit_received',
                'is_admitted'
            ]);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('events_exhibitors_stands', function (Blueprint $table) {
            $table->dropColumn(['is_furnished', 'catalog_is_completed']);
        });

        Schema::table('exhibitors_data', function(Blueprint $table) {
            $table->boolean('close_catalog')->default(0)->after('locale');
            $table->boolean('close_furnishings')->default(0)->after('locale');
            $table->boolean('catalog')->default(0)->after('locale');
            $table->boolean('furnishings')->default(0)->after('locale');
            $table->boolean('invoice_tot_sent')->default(0)->after('locale');
            $table->boolean('invoice_sent')->default(0)->after('locale');
            $table->boolean('balance_received')->default(0)->after('locale');
            $table->boolean('deposit_received')->default(0)->after('locale');
            $table->boolean('is_admitted')->default(0)->after('locale');
        });
    }
}
