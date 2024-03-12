<?php

/**
 * Change les champs de type TIMESTAMP en type DATETIME
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\{
    DB, Schema
};

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('logs', function (Blueprint $table) {
            $table->dateTime('created_at')->useCurrent()->nullable(false)->change();
        });

        Schema::table('movies', function (Blueprint $table) {
            $table->dropColumn('production_date');
            $table->dateTime('produced_at')->nullable(true)->default(null)->index('idx_produced_at')->after('name');
        });

        Schema::table('password_resets', function(Blueprint $table) {
            $table->dateTime('created_at')->useCurrent()->nullable(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('logs', function (Blueprint $table) {
            $table->timestamp('created_at')->useCurrent()->nullable(false)->change();
        });

        Schema::table('movies', function (Blueprint $table) {
            $table->dropColumn('produced_at');

            $table->dateTime('production_date')->index('idx_production_date')->after('name');
        });

        Schema::table('password_resets', function(Blueprint $table) {
            $table->timestamp('created_at')->useCurrent()->nullable(false)->change();
        });
    }
};
