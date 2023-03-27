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
        Schema::create('games', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tournement_id')->constrained('tournements')->onDelete('cascade');
            $table->integer('round_id')->nullable();
            $table->integer('pitch_id')->nullable();
            $table->integer('hometeam_id');
            $table->integer('awayteam_id');
            $table->mediumInteger('home_score')->nullable();
            $table->tinyInteger('home_points')->nullable();
            $table->tinyInteger('home_win')->nullable();
            $table->tinyInteger('home_draw')->nullable();
            $table->tinyInteger('home_loss')->nullable();
            $table->mediumInteger('away_score')->nullable();
            $table->tinyInteger('away_points')->nullable();
            $table->tinyInteger('away_win')->nullable();
            $table->tinyInteger('away_draw')->nullable();
            $table->tinyInteger('away_loss')->nullable();
            $table->tinyInteger('poule_round')->nullable();
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
        Schema::dropIfExists('games');
    }
};
