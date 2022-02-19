<?php

/**
 * Création de la table stockant les logs
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
        Schema::create('logs', function (Blueprint $table) {
            $table->id();
            $table->timestamp('created_at')->useCurrent()->index('created_at');
            $table->string('path', 255)->nullable()->index('idx_path');
            $table->string('level', 20)->index('idx_level');
            $table->text('message');
            $table->string('user_agent', 255)->nullable()->index('idx_user_agent');
        });

        // On ne peut pas préciser la taille d'un index avec Laravel ; nous le faisons manuellement
        DB::statement('ALTER TABLE `logs` ADD INDEX idx_message(message(255))');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('logs');
    }
};
