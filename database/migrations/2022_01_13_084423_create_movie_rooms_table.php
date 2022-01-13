<?php

/**
 * Création de la table des salles de cinéma
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

final class CreateMovieRoomsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('movie_rooms', function(Blueprint $table) {
            $table->unsignedTinyInteger('id', true);

            $table->string('public_id', 25);
            $table->unique('public_id', 'idx_public_id');

            $table->string('name', 25);
            $table->unique('name', 'idx_name');

            $table->smallInteger('floor');
            
            $table->unsignedSmallInteger('nb_places');
            $table->unsignedSmallInteger('nb_handicap_places');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('movie_rooms');
    }
}
