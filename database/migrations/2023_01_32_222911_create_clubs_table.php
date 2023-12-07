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
        Schema::create('clubs', function (Blueprint $table) {
            $table->id();
            $table->foreignID('tournement_id')->constrained('tournements')->onDelete('cascade');
            $table->smallInteger('club_nr');
            $table->string('club_name');
            $table->smallInteger('club_played')->default(0);
            $table->smallInteger('club_points')->default(0);
            $table->smallInteger('club_win')->default(0);
            $table->smallInteger('club_draw')->default(0);
            $table->smallInteger('club_loss')->default(0);
            $table->smallInteger('club_goalagainst')->default(0);
            $table->smallInteger('club_goal')->default(0);
            $table->smallInteger('club_goaldifference')->default(0);
            $table->smallInteger('club_ranking')->nullable();
            $table->float('club_average')->nullable();
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
        Schema::dropIfExists('clubs');
    }
};
