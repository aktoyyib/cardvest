<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDeletedColumnToBanksAndCardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('banks', function (Blueprint $table) {
            // I added this because the migration has been run for banks already
            if (!Schema::hasColumn('banks', 'deleted'))
                $table->boolean('deleted')->default(false);
        });
        Schema::table('cards', function (Blueprint $table) {
            $table->boolean('deleted')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('banks', function (Blueprint $table) {
            $table->dropColumn(['deleted']);
        });
        Schema::table('cards', function (Blueprint $table) {
            $table->dropColumn(['deleted']);
        });
    }
}
