<?php

/**
 * Migration pour la création de la table movies
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

final class CreateMoviesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('movies', function (Blueprint $table) {
            $table->unsignedMediumInteger('id', true);

            $table->string('public_id', 100);
            $table->unique('public_id', 'idx_public_id');

            $table->string('name', 100);
            $table->unique('name', 'idx_name');

            $table->dateTime('production_date');
            $table->index([ 'production_date' ], 'idx_production_date');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('movies');
    }
}
