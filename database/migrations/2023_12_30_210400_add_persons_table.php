<?php

/**
 * Ajoute les tables pour les contributeurs aux films
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
        Schema::create('persons', function (Blueprint $table) {
            $table->id();
            $table->string('first_name', 100)->nullable(false)->index('idx_first_name');
            $table->string('last_name', 100)->nullable(false)->index('idx_last_name');
            $table->string('full_name', 200)->nullable(false)->fulltext('idx_full_name');
            $table->dateTime('birthdate')->nullable(true);
        });

        Schema::create('movie_contributors', function (Blueprint $table) {
            $table->id();

            $table->unsignedMediumInteger('movie_id')->nullable(false)->index('fk_movie_id');
            $table->foreign('movie_id')->references('id')->on('movies')->cascadeOnUpdate();

            $table->unsignedBigInteger('person_id')->nullable(false)->index('fk_person_id');
            $table->foreign('person_id')->references('id')->on('persons')->cascadeOnUpdate();

            $table->string('contributor_type', 100)->nullable(false);

            $table->unique([
                'movie_id',
                'person_id',
                'contributor_type',
            ], 'idx_movie_id_person_id_contributor_type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('movie_contributors', function (Blueprint $table) {
            $table->dropForeign([ 'movie_id' ]);
            $table->dropForeign([ 'person_id' ]);
        });

        Schema::drop('persons');
        Schema::drop('movie_contributors');
    }
};
