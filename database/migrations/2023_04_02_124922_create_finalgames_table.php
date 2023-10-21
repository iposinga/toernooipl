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
        Schema::create('finalgames', function (Blueprint $table) {

            $table->id();
            $table->foreignId('tournement_id')->constrained('tournements')->onDelete('cascade');
            $table->integer('round_id');
            $table->integer('pitch_id');
            $table->mediumInteger('type_id');
            $table->string('name')->nullable();
            $table->integer('homepoule_id')->nullable();
            $table->mediumInteger('homepoule_teamsnmbr')->nullable();
            $table->integer('awaypoule_id')->nullable();
            $table->mediumInteger('awaypoule_teamsnmbr')->nullable();
            $table->mediumInteger('home_ranking')->nullable();
            $table->mediumInteger('away_ranking')->nullable();
            $table->integer('hometeam_id')->nullable();
            $table->integer('awayteam_id')->nullable();
            $table->mediumInteger('home_score')->nullable();
            $table->mediumInteger('away_score')->nullable();
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
        Schema::dropIfExists('finalgames');
    }
};
