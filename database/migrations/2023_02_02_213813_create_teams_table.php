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
        Schema::create('teams', function (Blueprint $table) {
            $table->id();
            $table->foreignID('poule_id')->constrained('poules')->onDelete('cascade');
            $table->foreignID('club_id')->nullable()->default(null)->constrained('clubs');
            $table->smallInteger('team_nr');
            $table->string('team_name')->nullable();
            $table->smallInteger('played')->default(0);
            $table->smallInteger('points')->default(0);
            $table->smallInteger('win')->default(0);
            $table->smallInteger('draw')->default(0);
            $table->smallInteger('loss')->default(0);
            $table->smallInteger('goalagainst')->default(0);
            $table->smallInteger('goal')->default(0);
            $table->smallInteger('goaldifference')->default(0);
            $table->smallInteger('team_ranking')->nullable();
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
        Schema::dropIfExists('teams');
    }
};
