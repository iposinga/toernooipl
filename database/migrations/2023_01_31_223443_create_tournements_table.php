<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tournements', function (Blueprint $table) {
            $table->id();
            $table->string('tournement_name');
            $table->timestamp('tournement_date');
            $table->smallInteger('teams_nmbr');
            $table->smallInteger('pitches_nmbr');
            $table->smallInteger('poules_nmbr');
            $table->smallInteger('game_duration');
            $table->smallInteger('change_duration')->default(0);
            $table->boolean('is_entire_comp')->default(0);
            $table->boolean('is_public')->default(0);
            $table->smallInteger('is_clubcompetition')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tournements');
    }
};
